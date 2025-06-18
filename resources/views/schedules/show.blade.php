@extends('layouts.app')
@section('content')
<div class="p-6 bg-white rounded shadow max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Schedule for {{ $schedule->discipline->name }}<br>
            {{ $schedule->start_date }} to {{ $schedule->end_date }}
        </h2>
        <button onclick="window.print()" class="px-4 py-2 bg-green-600 text-white rounded">Print</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse table-auto" id="schedule-table">
            <thead>
                <tr>
                    <th class="border p-2">Unit</th>
                    @foreach($schedule->nurses as $nurse)
                        <th class="border p-2">{{ $nurse->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($schedule->nurses->first()->rotations as $index => $rotation)
                    <tr>
                        <td class="border p-2 whitespace-nowrap">{{ $rotation->unit->name }}<br>
                            <span class="text-sm text-gray-600">
                                {{ $rotation->start_date }} - {{ $rotation->end_date }}
                            </span>
                        </td>
                        @foreach($schedule->nurses as $nurse)
                            @php
                                $slot = $nurse->rotations[$index];
                            @endphp
                            <td class="border p-2 text-center">
                                {{-- Could highlight current date slot --}}
                                {{ $slot->start_date }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
