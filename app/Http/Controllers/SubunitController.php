<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Unit;
use App\Models\Subunit;
use Illuminate\Http\Request;

class SubunitController extends Controller
{
    public function edit(Discipline $discipline, Unit $unit)
    {
        return view('subunits.edit', [
            'discipline' => $discipline,
            'unit' => $unit,
            'subunits' => $unit->subunits
        ]);
    }

    public function update(Request $request, Discipline $discipline, Unit $unit)
    {
        $request->validate([
            'subunits' => 'required|array',
            'subunits.*.id' => 'nullable|exists:subunits,id',
            'subunits.*.name' => 'required|string|max:255',
            'subunits.*.duration_weeks' => 'required|integer|min:1',
        ]);

        foreach ($request->subunits as $subunitData) {
            if (isset($subunitData['id'])) {
                // Update existing subunit
                $subunit = Subunit::find($subunitData['id']);
                if ($subunit && $subunit->unit_id === $unit->id) {
                    $subunit->update([
                        'name' => $subunitData['name'],
                        'duration_weeks' => $subunitData['duration_weeks'],
                    ]);
                }
            } else {
                // Create new subunit
                $unit->subunits()->create([
                    'name' => $subunitData['name'],
                    'duration_weeks' => $subunitData['duration_weeks'],
                ]);
            }
        }

        return redirect()->route('disciplines.units.index', $discipline)->with('success', 'Subunits updated successfully.');
    }

    public function destroy(Discipline $discipline, Unit $unit, Subunit $subunit)
    {
        if ($subunit->unit_id === $unit->id) {
            $subunit->delete();
        }
        return redirect()->route('disciplines.units.index', $discipline)->with('success', 'Subunit deleted successfully.');
    }
}