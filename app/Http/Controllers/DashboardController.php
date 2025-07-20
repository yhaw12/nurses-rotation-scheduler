<?php

namespace App\Http\Controllers;

use App\Models\Roster;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch rosters with related data
        $rosters = Roster::with(['discipline', 'assignments', 'createdBy'])->get();

        // Calculate active students
        $activeStudents = $rosters->pluck('assignments')->flatten()->pluck('student_name')->unique()->count();

        // Prepare allRows data for JavaScript
        try {
            $allRows = $rosters->map(function ($roster) {
                return [
                    'id' => $roster->id,
                    'discipline_name' => $roster->discipline ? $roster->discipline->name : 'Unknown',
                    // 'start_date' => $roster->start_date ? $roster->start_date->toDateString() : '',
                    // 'end_date' => $roster->end_date ? $roster->end_date->toDateString() : '',
                    'created_by_name' => $roster->createdBy ? ($roster->createdBy->name ?? 'Unknown') : 'Unknown',
                    'student_names' => $roster->assignments ? $roster->assignments->pluck('student_name')->unique()->values()->filter()->toArray() : []
                ];
            })->toArray();
        } catch (\Exception $e) {
            // Log any errors and set allRows to an empty array as a fallback
            Log::error('Error preparing allRows', ['error' => $e->getMessage()]);
            $allRows = [];
        }

        // Log data for debugging
        Log::debug('Dashboard data', [
            'roster_count' => $rosters->count(),
            'active_students' => $activeStudents,
            'allRows_sample' => array_slice($allRows, 0, 2)
        ]);

        // Pass data to the view
        return view('dashboard', compact('rosters', 'activeStudents', 'allRows'));
    }
}