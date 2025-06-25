@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-12 bg-gray-100 dark:bg-[#0a0a23] dark:text-white">
    <h1 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-100">Roster System Dashboard</h1>
    
    <!-- Nurse Discipline Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- RGN Card -->
        <a href="{{ route('rosters.create', ['discipline' => 'rgn']) }}" class="block bg-white/60 dark:bg-gray-800/60 p-6 rounded-lg shadow-lg backdrop-blur-sm hover:bg-blue-50 dark:hover:bg-gray-700 transition duration-200">
            <div class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">RGN</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 text-center">Create roster for Registered General Nurses</p>
            </div>
        </a>

        <!-- Midwives Card -->
        <a href="{{ route('rosters.create', ['discipline' => 'midwives']) }}" class="block bg-white/60 dark:bg-gray-800/60 p-6 rounded-lg shadow-lg backdrop-blur-sm hover:bg-blue-50 dark:hover:bg-gray-700 transition duration-200">
            <div class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Midwives</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 text-center">Create roster for Midwives</p>
            </div>
        </a>

        <!-- Public Health Nurses Card -->
        <a href="{{ route('rosters.create', ['discipline' => 'public-health-nurses']) }}" class="block bg-white/60 dark:bg-gray-800/60 p-6 rounded-lg shadow-lg backdrop-blur-sm hover:bg-blue-50 dark:hover:bg-gray-700 transition duration-200">
            <div class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Public Health Nurses</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 text-center">Create roster for Public Health Nurses</p>
            </div>
        </a>
    </div>

    <!-- Existing Rosters Section -->
    <div class="bg-white/60 dark:bg-gray-800/60 p-6 rounded-lg shadow-lg backdrop-blur-sm">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Existing Rosters</h2>
        @if(empty($rosters) || $rosters->isEmpty())
            <p class="text-gray-600 dark:text-gray-300 text-center">No rosters found. Create a new roster to get started.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="border p-2 text-left">ID</th>
                            <th class="border p-2 text-left">Discipline</th>
                            <th class="border p-2 text-left">Date Range</th>
                            <th class="border p-2 text-left">Created By</th>
                            <th class="border p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rosters as $roster)
                            <tr class="hover:bg-blue-50 dark:hover:bg-gray-600 transition duration-200">
                                <td class="border p-2">{{ $roster->id }}</td>
                                <td class="border p-2">{{ $roster->discipline->name }}</td>
                                <td class="border p-2">
                                    {{ \Carbon\Carbon::parse($roster->start_date)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($roster->end_date)->format('d/m/Y') }}
                                </td>
                                <td class="border p-2">{{ $roster->createdBy->name ?? 'Unknown' }}</td>
                                <td class="border p-2">
                                    <a href="{{ route('rosters.show', $roster) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection