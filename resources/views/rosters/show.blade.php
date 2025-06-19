@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-6 text-center">Roster for {{ $roster->discipline->name }}</h1>
        
        <p class="text-center mb-4">Period: {{ $roster->start_date }} to {{ $roster->end_date }}</p>
        
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3">Student Name</th>
                    <th class="p-3">Unit</th>
                    <th class="p-3">Subunit</th>
                    <th class="p-3">Start Date</th>
                    <th class="p-3">End Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $assignment)
                    <tr class="border-b">
                        <td class="p-3">{{ $assignment->student_name }}</td>
                        <td class="p-3">{{ $assignment->unit->name }}</td>
                        <td class="p-3">{{ $assignment->subunit->name }}</td>
                        <td class="p-3">{{ $assignment->start_date }}</td>
                        <td class="p-3">{{ $assignment->end_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Reshuffle Button -->
        <div class="mt-6 text-center">
            <form action="{{ route('rosters.reshuffle', $roster) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition duration-200 font-semibold">
                    Reshuffle Units
                </button>
            </form>
        </div>

        <!-- Print Button -->
        <div class="mt-4 text-center">
            <button onclick="window.print()" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200 font-semibold">
                Print Roster
            </button>
        </div>
    </div>
</div>
@endsection