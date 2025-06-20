<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Roster;
use App\Models\RosterAssignment;
use Illuminate\Http\Request;

class RosterController extends Controller
{
    public function index()
    {
        $rosters = Roster::with('discipline')->get();
        return view('rosters.index', compact('rosters'));
    }

    public function create($discipline)
    {
        return view('rosters.create', ['discipline' => $discipline]);
    }

    public function store(Request $request)
{
    // Validate the input
    $validated = $request->validate([
        'discipline' => 'required|string',
        'student_names' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    // Find the discipline
    $discipline = Discipline::where('name', ucwords(str_replace('-', ' ', $validated['discipline'])))->firstOrFail();
    $units = $discipline->units()->with('subunits')->get();

    // Process student names
    $studentNames = array_filter(array_map('trim', explode("\n", $validated['student_names'])));
    if (empty($studentNames)) {
        return back()->withErrors(['student_names' => 'At least one student name is required.']);
    }

    // Create a single roster for all students
    $roster = Roster::create([
        'discipline_id' => $discipline->id,
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
        'created_by' => auth()->id(),
    ]);

    // Shuffle units to assign students randomly
    $shuffledUnits = $units->shuffle();
    $numUnits = $units->count();

    // Assign each student to a unit and its subunits
    foreach ($studentNames as $index => $student) {
        $unit = $shuffledUnits[$index % $numUnits]; // Cycle through units if more students than units
        $subunits = $unit->subunits;

        $currentDate = $validated['start_date'];
        foreach ($subunits as $subunit) {
            $endDate = date('Y-m-d', strtotime($currentDate . " + {$subunit->duration_weeks} weeks - 1 day"));
            RosterAssignment::create([
                'roster_id' => $roster->id,
                'student_name' => $student,
                'unit_id' => $unit->id,
                'subunit_id' => $subunit->id,
                'start_date' => $currentDate,
                'end_date' => $endDate,
            ]);
            $currentDate = date('Y-m-d', strtotime($endDate . ' + 1 day'));
        }
    }

    // Redirect to the show page for the newly created roster
    return redirect()->route('rosters.show', $roster)->with('status', 'Roster created successfully.');
}
    public function show(Roster $roster)
    {
        $assignments = $roster->assignments()->with('unit', 'subunit')->get();
        return view('rosters.show', compact('roster', 'assignments'));
    }

    public function reshuffle(Roster $roster)
    {
        $units = $roster->discipline->units()->with('subunits')->get();
        $studentNames = $roster->assignments()->pluck('student_name')->unique()->toArray();
        $numStudents = count($studentNames);
        $numUnits = $units->count();

        if ($numStudents > $numUnits) {
            return back()->withErrors(['message' => 'Number of students cannot exceed the number of units (' . $numUnits . ').']);
        }

        $roster->assignments()->delete();
        $shuffledUnits = $units->shuffle();

        foreach ($studentNames as $index => $student) {
            $unit = $shuffledUnits[$index % $numUnits];
            $subunits = $unit->subunits;

            $currentDate = $roster->start_date;
            foreach ($subunits as $subunit) {
                $endDate = date('Y-m-d', strtotime($currentDate . " + {$subunit->duration_weeks} weeks"));
                RosterAssignment::create([
                    'roster_id' => $roster->id,
                    'student_name' => $student,
                    'unit_id' => $unit->id,
                    'subunit_id' => $subunit->id,
                    'start_date' => $currentDate,
                    'end_date' => $endDate,
                ]);
                $currentDate = $endDate;
            }
        }

        return redirect()->route('rosters.show', $roster)->with('status', 'Roster reshuffled successfully.');
    }
}