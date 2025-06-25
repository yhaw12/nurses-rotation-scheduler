<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Roster;
use App\Models\RosterAssignment;
use Illuminate\Http\Request;

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

                    // Stop this student if we passed overall end_date
                    if ($cursor > $data['end_date']) {
                        break 2;
                    }
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
        $discipline = $roster->discipline()->with('units.subunits')->first();

        // Load assignments with related data
        $assignments = $roster->assignments()
            ->with(['unit', 'subunit'])
            ->get();

        return view('rosters.show', compact('roster', 'discipline', 'assignments'));
    }

    /**
     * Shuffle the unit order for all students in the roster.
     *
     * @param \App\Models\Roster $roster
     * @return \Illuminate\Http\JsonResponse
     */
    public function shuffle(Roster $roster)
    {
        // Load discipline with units and subunits
        $discipline = $roster->discipline()->with('units.subunits')->first();

        // Get unique student names
        $students = $roster->assignments()->pluck('student_name')->unique()->values();

        // Randomize unit order
        $units = $discipline->units->shuffle();

        // Delete existing assignments
        $roster->assignments()->delete();

        // Reassign each student to subunits in the new unit order
        foreach ($students as $student) {
            $cursor = $roster->start_date;

            foreach ($units as $unit) {
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

                    // Stop if we exceed the roster's end date
                    if ($cursor > $roster->end_date) {
                        break 2;
                    }
                }
            }
        }
        return response()->json(['success' => true]);
    }
}
