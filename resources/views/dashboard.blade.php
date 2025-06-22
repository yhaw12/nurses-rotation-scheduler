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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 mb-4" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 000 6.5 4.5 0 4L12 20.36417.682-7.682a4.5 4.5 0 00-6.364-6.364-6.364L12 7.636-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
</svg>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Midwives</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 text-center">Create roster for Midwives for Midwives</p>
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
</div>
@endsection