@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">{{ $roster->discipline->name }} Roster</h1>
    <p class="mb-4">From {{ $roster->start_date->format('d M Y') }} to {{ $roster->end_date->format('d M Y') }}</p>
    <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 mb-4">Print</button>
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">Nurse</th>
                    @foreach ($roster->rosterUnits->groupBy('unit_id')->first() as $unit)
                        <th class="border px-4 py-2">{{ $unit->unit->name }}<br>{{ $unit->start_date->format('d M') }} - {{ $unit->end_date->format('d M Y') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($roster->nurses as $nurse)
                    <tr>
                        <td class="border px-4 py-2">{{ $nurse->name }}</td>
                        @foreach ($roster->rosterUnits->where('nurse_id', $nurse->id) as $rotation)
                            <td class="border px-4 py-2 text-center">{{ $rotation->unit->name }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<style>
    @media print {
        @page { size: landscape; }
        .container { padding: 0; }
        button { display: none; }
    }
</style>
@endsection