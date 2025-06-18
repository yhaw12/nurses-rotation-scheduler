// app/Http/Controllers/ScheduleController.php
<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Schedule;
use App\Models\Nurse;
use App\Models\Unit;
use App\Models\NurseUnitRotation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ScheduleController extends Controller
{
    public function create()
    {
        $disciplines = Discipline::all();
        return view('schedules.create', compact('disciplines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'discipline_id' => 'required|exists:disciplines,id',
            'start_date'    => 'required|date',
            'nurses'        => 'required|array|min:1',
            'nurses.*'      => 'required|string',
            'reshuffle'     => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($request) {
            // Create Schedule
            $schedule = Schedule::create([
                'discipline_id'   => $request->discipline_id,
                'start_date'      => $request->start_date,
                'reshuffle_index' => $request->reshuffle ? 1 : 0,
                'end_date'        => null,
            ]);

            // Create Nurses
            foreach ($request->nurses as $name) {
                Nurse::create([
                    'schedule_id' => $schedule->id,
                    'name'        => $name,
                ]);
            }

            // Calculate Rotations
            $this->generateRotations($schedule);

            return redirect()->route('schedules.show', $schedule);
        });
    }

    protected function generateRotations(Schedule $schedule)
    {
        $units = Unit::where('discipline_id', $schedule->discipline_id)
                    ->orderBy('sort_order')
                    ->get()
                    ->toArray();

        // Apply reshuffle index
        if ($schedule->reshuffle_index) {
            $units = array_merge(
                array_slice($units, $schedule->reshuffle_index),
                array_slice($units, 0, $schedule->reshuffle_index)
            );
        }

        $startDate = Carbon::parse($schedule->start_date);
        $rotations = [];

        // Build date ranges
        foreach ($units as $unit) {
            $endDate = (clone $startDate)->addWeeks($unit['duration_weeks'])->subDay();
            $rotations[] = [
                'unit_id'    => $unit['id'],
                'start_date'=> $startDate->toDateString(),
                'end_date'  => $endDate->toDateString(),
            ];
            $startDate = $endDate->addDay();
        }

        // Fetch nurses
        $nurses = $schedule->nurses;

        // Create unit rotations for each nurse
        foreach ($nurses as $nurse) {
            foreach ($rotations as $slot) {
                NurseUnitRotation::create([
                    'nurse_id'   => $nurse->id,
                    'unit_id'    => $slot['unit_id'],
                    'start_date' => $slot['start_date'],
                    'end_date'   => $slot['end_date'],
                ]);
            }
        }

        // Optionally update schedule end_date
        $last = end($rotations);
        $schedule->update(['end_date' => $last['end_date']]);
    }

    public function show(Schedule $schedule)
{
    $schedule->load('nurses.rotations.unit');
    return view('schedules.show', compact('schedule'));
}

}
