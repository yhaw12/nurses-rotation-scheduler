<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Roster;
use App\Models\RosterAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RosterController extends Controller
{
    public function create($discipline)
    {
        $display = match ($discipline) {
            'rgn'                   => 'Registered General Nurses (RGN)',
            'midwives'             => 'Midwives',
            'public-health-nurses' => 'Public Health Nurses',
            default                => ucwords(str_replace('-', ' ', $discipline)),
        };

        return view('rosters.create', [
            'discipline'        => $discipline,
            'displayDiscipline' => $display,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'discipline'    => 'required|string',
            'student_names' => 'required|string',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
        ]);

        $map = [
            'rgn'                   => 'Registered General Nurses (RGN)',
            'midwives'             => 'Midwives',
            'public-health-nurses' => 'Public Health Nurses',
        ];
        $name = $map[$data['discipline']] ?? ucwords(str_replace('-', ' ', $data['discipline']));

        $discipline = Discipline::where('name', $name)
            ->with('units.subunits')
            ->firstOrFail();

        $students = collect(explode("\n", $data['student_names']))
            ->map(fn($n) => trim($n))
            ->filter()
            ->values();

        $roster = Roster::create([
            'discipline_id' => $discipline->id,
            'start_date'    => $data['start_date'],
            'end_date'      => $data['end_date'],
            'created_by'    => auth()->id(),
        ]);

        $dateSequence = [];
        $cursor = Carbon::parse($data['start_date']);
        foreach ($discipline->units as $unit) {
            foreach ($unit->subunits as $sub) {
                $end = $cursor->copy()->addWeeks($sub->duration_weeks)->subDay()->format('Y-m-d');
                $dateSequence[] = [
                    'start_date' => $cursor->format('Y-m-d'),
                    'end_date'   => $end,
                    'duration_weeks' => $sub->duration_weeks,
                ];
                $cursor->addWeeks($sub->duration_weeks);
            }
        }

        $groupCount = count($students);
        $unitSequences = [];
        for ($i = 0; $i < $groupCount; $i++) {
            $unitSequences[] = $discipline->units->shuffle()->pluck('id')->toArray();
        }

        $batch = [];
        foreach ($students as $index => $student) {
            $unitIds = $unitSequences[$index % $groupCount];
            $subunitIndex = 0;

            foreach ($unitIds as $unitIndex => $unitId) {
                $unit = $discipline->units->find($unitId);
                foreach ($unit->subunits as $sub) {
                    if ($subunitIndex >= count($dateSequence)) {
                        break 2;
                    }
                    $dates = $dateSequence[$subunitIndex];
                    try {
                        $batch[] = [
                            'roster_id'    => $roster->id,
                            'student_name' => encrypt($student),
                            'unit_id'      => $unit->id,
                            'subunit_id'   => $sub->id,
                            'start_date'   => $dates['start_date'],
                            'end_date'     => $dates['end_date'],
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ];
                    } catch (\Exception $e) {
                        Log::error('Failed to encrypt student name in store: ' . $student, ['error' => $e->getMessage()]);
                    }
                    $subunitIndex++;
                }
                if ($index === 0) {
                    $unit->update(['sort_order' => $unitIndex + 1]);
                }
            }
        }

        // Batch insert for efficiency
        DB::table('roster_assignments')->insert($batch);

        return redirect()->route('rosters.show', $roster);
    }

    public function show(Roster $roster)
    {
        if (auth()->id() !== $roster->created_by && !auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $discipline = $roster->discipline()->with('units.subunits')->first();
        $assignments = $roster->assignments()->with(['unit', 'subunit'])->get();

        // Rely on model accessor for decryption
        $studentNames = $assignments->pluck('student_name')->unique()->toArray();

        return view('rosters.show', compact('roster', 'discipline', 'assignments'));
    }

    public function shuffle(Roster $roster)
    {
        try {
            DB::beginTransaction();

            if (auth()->id() !== $roster->created_by && !auth()->user()->is_admin) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $discipline = $roster->discipline()->with('units.subunits')->first();
            $units = $discipline->units;

            $students = $roster->assignments()->pluck('student_name')->unique()->values();
            if ($students->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No students found in roster'], 400);
            }

            $dateSequence = [];
            $cursor = Carbon::parse($roster->start_date);
            foreach ($units as $unit) {
                foreach ($unit->subunits as $sub) {
                    $end = $cursor->copy()->addWeeks($sub->duration_weeks)->subDay()->format('Y-m-d');
                    $dateSequence[] = [
                        'start_date' => $cursor->format('Y-m-d'),
                        'end_date'   => $end,
                        'duration_weeks' => $sub->duration_weeks,
                    ];
                    $cursor->addWeeks($sub->duration_weeks);
                }
            }

            // Soft delete existing assignments
            $roster->assignments()->delete();

            $groupCount = count($students);
            $unitSequences = [];
            for ($i = 0; $i < $groupCount; $i++) {
                $unitSequences[] = $units->shuffle()->pluck('id')->toArray();
            }

            $assignments = [];
            foreach ($students as $index => $student) {
                $unitIds = $unitSequences[$index % $groupCount];
                $subunitIndex = 0;

                foreach ($unitIds as $unitIndex => $unitId) {
                    $unit = $units->find($unitId);
                    foreach ($unit->subunits as $sub) {
                        if ($subunitIndex >= count($dateSequence)) {
                            break 2;
                        }
                        $dates = $dateSequence[$subunitIndex];
                        $assignments[] = [
                            'roster_id'    => $roster->id,
                            'student_name' => $student, // Let model cast handle encryption
                            'unit_id'      => $unit->id,
                            'subunit_id'   => $sub->id,
                            'start_date'   => $dates['start_date'],
                            'end_date'     => $dates['end_date'],
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ];
                        $subunitIndex++;
                    }
                    if ($index === 0) {
                        $unit->update(['sort_order' => $unitIndex + 1]);
                    }
                }
            }
            // 🔒 Encrypt every student_name before we insert
        foreach ($assignments as &$row) {
            $row['student_name'] = encrypt($row['student_name']);
        }
        unset($row);
            RosterAssignment::insert($assignments);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Roster shuffled successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to shuffle roster: ' . $e->getMessage()], 500);
        }
    }
}