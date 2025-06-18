@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Create RGN Schedule</h2>
    <form method="POST" action="{{ route('schedules.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block font-medium">Discipline</label>
            <select name="discipline_id" class="mt-1 block w-full border-gray-300 rounded">
                @foreach($disciplines as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-medium">Start Date</label>
            <input type="date" name="start_date" class="mt-1 block w-full border-gray-300 rounded" required>
        </div>
        <div class="mb-4">
            <label class="block font-medium">Nurse Names (one per line)</label>
            <textarea name="nurses[]" rows="5" class="mt-1 block w-full border-gray-300 rounded" placeholder="Enter one name per line"></textarea>
        </div>
        <div class="mb-4 flex items-center">
            <input type="checkbox" name="reshuffle" value="1" class="mr-2">
            <label class="font-medium">Reshuffle Starting Unit</label>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Generate Schedule</button>
    </form>
</div>
@endsection