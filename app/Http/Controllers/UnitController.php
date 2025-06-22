<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Discipline $discipline)
    {
        $units = $discipline->units()->with('subunits')->get();
        return view('units.index', ['discipline' => $discipline, 'units' => $units]);
    }

    public function create(Discipline $discipline)
    {
        return view('units.create', ['discipline' => $discipline]);
    }

    public function store(Request $request, Discipline $discipline)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration_weeks' => 'required|integer|min:1',
            'sort_order' => 'required|integer|min:0',
        ]);
        $discipline->units()->create($request->only(['name', 'duration_weeks', 'sort_order']));
        return redirect()->route('disciplines.units.index', $discipline);
    }

    // Add edit, update, destroy methods as needed
    public function edit(Discipline $discipline, Unit $unit)
    {
        return view('units.edit', ['discipline' => $discipline, 'unit' => $unit]);
    }

    public function update(Request $request, Discipline $discipline, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration_weeks' => 'required|integer|min:1',
            'sort_order' => 'required|integer|min:0',
        ]);
        $unit->update($request->only(['name', 'duration_weeks', 'sort_order']));
        return redirect()->route('disciplines.units.index', $discipline);
    }

    public function destroy(Discipline $discipline, Unit $unit)
    {
        $unit->delete();
        return redirect()->route('disciplines.units.index', $discipline);
    }
}