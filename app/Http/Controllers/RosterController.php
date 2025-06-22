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
            'discipline'         => $discipline,
            'displayDiscipline'  => $display,
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

        // Normalize to the DB name
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
                    if ($cursor > $data['end_date']) break 3;
                }
            }
        }

        return redirect()->route('rosters.show', $roster);
    }

    public function show(Roster $roster)
    {
        $assignments = $roster->assignments()->with('unit','subunit')->get();
        return view('rosters.show', compact('roster','assignments'));
    }
}
