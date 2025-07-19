<?php

namespace App\Http\Controllers;

use App\Models\Roster;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $rosters = Roster::with(['discipline', 'assignments', 'createdBy'])->get();

        $activeStudents = $rosters->pluck('assignments')->flatten()->pluck('student_name')->unique()->count();

        Log::debug('Dashboard data', [
            'roster_count' => $rosters->count(),
            'active_students' => $activeStudents,
            'sample_creators' => $rosters->take(5)->pluck('createdBy.name')->toArray()
        ]);

        return view('dashboard', compact('rosters', 'activeStudents'));
    }
}