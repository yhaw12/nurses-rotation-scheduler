<?php

namespace App\Http\Controllers;

use App\Models\Roster;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the roster system dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all rosters with their associated disciplines
        $rosters = Roster::with('discipline')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('rosters'));
    }
}