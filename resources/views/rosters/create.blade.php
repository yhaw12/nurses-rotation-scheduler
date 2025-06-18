@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Create Roster</h1>
    <form action="{{ route('rosters.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="discipline_id" class="block text-sm font-medium text-gray-700">Discipline</label>
            <select id="discipline_id" name="discipline_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @foreach ($disciplines as $discipline)
                    <option value="{{ $discipline->id }}">{{ $discipline->name }}</option>
                @endforeach
            </select>
            @error('discipline_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" id="start_date" name="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            @error('start_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="nurses" class="block text-sm font-medium text-gray-700">Nurse Names (one per line)</label>
            <textarea id="nurses" name="nurses" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Enter nurse names, one per line" required></textarea>
            @error('nurses') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-center">
            <input type="checkbox" id="reshuffle" name="reshuffle" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
            <label for="reshuffle" class="ml-2 text-sm text-gray-600">Reshuffle Units</label>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Create Roster</button>
    </form>
</div>
@endsection