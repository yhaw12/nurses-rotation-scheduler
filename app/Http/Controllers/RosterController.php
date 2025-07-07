<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Roster;
use App\Models\RosterAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RosterController extends Controller
{
    /**
     * Show the form for creating a new roster.
     *
     * @param string $discipline
     * @return \Illuminate\View\View
     */
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

    /**
     * Store a newly created roster in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'discipline'    => 'required|string',
            'student_names' => 'required|string',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
        ]);

        // Map slug → DB name, with fallback
        $map = [
            'rgn'                   => 'Registered General Nurses (RGN)',
            'midwives'             => 'Midwives',
            'public-health-nurses' => 'Public Health Nurses',
        ];
        $name = $map[$data['discipline']] ?? ucwords(str_replace('-', ' ', $data['discipline']));

        $discipline = Discipline::where('name', $name)
            ->with('units.subunits')
            ->firstOrFail();

        // Parse students
        $students = collect(explode("\n", $data['student_names']))
            ->map(fn($n) => trim($n))
            ->filter()
            ->values();

        // Create roster record
        $roster = Roster::create([
            'discipline_id' => $discipline->id,
            'start_date'    => $data['start_date'],
            'end_date'      => $data['end_date'],
            'created_by'    => auth()->id(),
        ]);

        // Generate fixed date sequence based on original unit order
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

        // Assign each student a shuffled unit sequence with fixed dates
        $groupCount = count($students);
        $unitSequences = [];
        for ($i = 0; $i < $groupCount; $i++) {
            $unitSequences[] = $discipline->units->shuffle()->pluck('id')->toArray();
        }

        foreach ($students as $index => $student) {
            $unitIds = $unitSequences[$index % $groupCount];
            $subunitIndex = 0;

            foreach ($unitIds as $unitIndex => $unitId) {
                $unit = $discipline->units->find($unitId);
                foreach ($unit->subunits as $sub) {
                    if ($subunitIndex >= count($dateSequence)) {
                        break 2; // Stop if we run out of date slots
                    }
                    $dates = $dateSequence[$subunitIndex];
                    RosterAssignment::create([
                        'roster_id'    => $roster->id,
                        'student_name' => $student,
                        'unit_id'      => $unit->id,
                        'subunit_id'   => $sub->id,
                        'start_date'   => $dates['start_date'],
                        'end_date'     => $dates['end_date'],
                    ]);
                    $subunitIndex++;
                }
                // Update sort_order for first group's sequence
                if ($index === 0) {
                    $unit->update(['sort_order' => $unitIndex + 1]);
                }
            }
        }

        return redirect()->route('rosters.show', $roster);
    }

    /**
     * Display the specified roster.
     *
     * @param \App\Models\Roster $roster
     * @return \Illuminate\View\View
     */
    public function show(Roster $roster)
    {
        if (auth()->id() !== $roster->created_by && !auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $discipline = $roster->discipline()->with('units.subunits')->first();
        $assignments = $roster->assignments()->with(['unit', 'subunit'])->get();

        return view('rosters.show', compact('roster', 'discipline', 'assignments'));
    }

    /**
     * Shuffle the unit order for all students in the roster, keeping dates fixed.
     *
     * @param \App\Models\Roster $roster
     * @return \Illuminate\Http\JsonResponse
     */
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
