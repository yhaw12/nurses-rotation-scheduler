@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">All Rosters</h1>
    <ul class="space-y-2">
        @foreach($rosters as $roster)
            <li>
                <a href="{{ route('rosters.show', $roster) }}" class="text-blue-600 hover:underline">
                    Roster for {{ $roster->discipline->name }} ({{ $roster->start_date }} to {{ $roster->end_date }})
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection