<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Roster;
use App\Models\RosterAssignment;
use Illuminate\Http\Request;

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

        // Map slug → DB name, with fallback
        $map = [
            'rgn'                   => 'Registered General Nurses (RGN)',
            'midwives'             => 'Midwives',
            'public-health-nurses' => 'Public Health Nurses',
        ];
        $name = $map[$data['discipline']]
              ?? ucwords(str_replace('-', ' ', $data['discipline']));

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

        // Assign each student into each subunit in sequence
        foreach ($students as $student) {
            $cursor = $data['start_date'];

            foreach ($discipline->units as $unit) {
                foreach ($unit->subunits as $sub) {
                    $end = date('Y-m-d', strtotime("$cursor +{$sub->duration_weeks} weeks -1 day"));

                    RosterAssignment::create([
                        'roster_id'    => $roster->id,
                        'student_name' => $student,
                        'unit_id'      => $unit->id,
                        'subunit_id'   => $sub->id,
                        'start_date'   => $cursor,
                        'end_date'     => $end,
                    ]);

                    $cursor = date('Y-m-d', strtotime("$end +1 day"));

                    // stop this student if we passed overall end_date
                    if ($cursor > $data['end_date']) {
                        break 2;
                    }
                }
            }
        }

        return redirect()->route('rosters.show', $roster);
    }

 public function show(Roster $roster)
{
    $discipline = $roster->discipline()->with('units.subunits')->first();

    // flatten for row count
    $lines = [];
    foreach ($discipline->units as $unit) {
        foreach ($unit->subunits as $sub) {
            $lines[] = ['unit' => strtoupper($unit->name)];
        }
    }

    // also load assignments just for row‐count (names blank)
    $assignments = $roster->assignments()->get();

    return view('rosters.show', compact('roster','discipline','lines','assignments'));
}


}
