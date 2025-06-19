<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Discipline $discipline)
    {
        return view('units.index', ['discipline' => $discipline, 'units' => $discipline->units]);
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
    // Add edit, update, destroy as needed
}