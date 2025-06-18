<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Nurse;
use App\Models\Roster;
use App\Models\RosterUnit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class RosterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $disciplines = Discipline::with('units')->get();
        return view('rosters.create', compact('disciplines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'discipline_id' => 'required|exists:disciplines,id',
            'start_date'    => 'required|date',
            'nurses'        => 'required|string', // Textarea input
            'reshuffle'     => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($request) {
            // Parse nurse names from textarea (one per line)
            $nurseNames = array_filter(array_map('trim', explode("\n", $request->nurses)));

            // Create Roster
            $roster = Roster::create([
                'discipline_id'   => $request->discipline_id,
                'start_date'      => $request->start_date,
                'reshuffle_index' => $request->reshuffle ? 1 : 0,
            ]);

            // Find or create nurses and collect their IDs
            $nurseIds = [];
            foreach ($nurseNames as $name) {
                $nurse = Nurse::firstOrCreate([
                    'discipline_id' => $request->discipline_id,
                    'name'          => $name,
                ]);
                $nurseIds[] = $nurse->id;
            }

            // Attach nurses to roster
            $roster->nurses()->attach($nurseIds);

            // Generate unit rotations
            $units = Discipline::findOrFail($request->discipline_id)
                ->units
                ->toArray();

            if ($roster->reshuffle_index) {
                $units = array_merge(
                    array_slice($units, $roster->reshuffle_index),
                    array_slice($units, 0, $roster->reshuffle_index)
                );
            }

            $current = Carbon::parse($roster->start_date);
            $rotations = [];
            foreach ($units as $unit) {
                $end = (clone $current)->addWeeks($unit['duration_weeks'])->subDay();
                $rotations[] = [
                    'unit_id'    => $unit['id'],
                    'start_date' => $current->toDateString(),
                    'end_date'   => $end->toDateString(),
                ];
                $current = $end->addDay();
            }

            // Create RosterUnit entries for each nurse
            foreach ($nurseIds as $nid) {
                foreach ($rotations as $slot) {
                    RosterUnit::create([
                        'roster_id'  => $roster->id,
                        'nurse_id'   => $nid,
                        'unit_id'    => $slot['unit_id'],
                        'start_date' => $slot['start_date'],
                        'end_date'   => $slot['end_date'],
                    ]);
                }
            }

            // Update roster end_date
            $roster->update(['end_date' => end($rotations)['end_date']]);

            return redirect()->route('rosters.show', $roster);
        });
    }

    public function show(Roster $roster)
    {
        $roster->load('discipline', 'nurses', 'rosterUnits.unit');
        return view('rosters.show', compact('roster'));
    }
}