@extends('layouts.app')

@section('content')
@php
    $totalRows = $roster && $roster->discipline && $roster->discipline->units
        ? $roster->discipline->units->sum(fn($unit) => $unit->subunits?->count() ?? 0)
        : 0;
    $studentGroups = $assignments->pluck('student_name')->unique()->filter()->values()->toArray();
    $dateSequence = [];
    $cursor = \Carbon\Carbon::parse($roster->start_date);
    foreach ($roster->discipline->units()->orderBy('sort_order')->get() as $unit) {
        foreach ($unit->subunits as $sub) {
            $dateSequence[] = [
                'start_date' => $cursor->copy()->format('d/m/Y'),
                'end_date' => $cursor->copy()->addWeeks($sub->duration_weeks)->subDay()->format('d/m/Y'),
                'duration_weeks' => $sub->duration_weeks,
            ];
            $cursor->addWeeks($sub->duration_weeks);
        }
    }
    $dateIndex = 0;
@endphp
<style>
</style>
<div class="a4-container">
    <div class="print-content">
        <div class="text-center mb-1 font-bold underline">
        <h2 class="text-xl text-gray-800 dark:text-white">
            ROSTER FOR {{ strtoupper($roster->discipline->name ?? 'N/A') }} —  
            (FIFTY‑TWO WEEKS) STARTING FROM
            {{ \Carbon\Carbon::parse($roster->start_date)->format('d/m/Y') ?? 'N/A' }}
                TO
                {{ \Carbon\Carbon::parse($roster->end_date)->format('d/m/Y') ?? 'N/A' }},
                KASOA POLYCLINIC
            </h2>
       </div>

        <table class="w-full border-collapse text-sm" style="table-layout: fixed;">
            <colgroup>
                <col style="width: 22%;">
                <col style="width: 10%;">
                <col style="width: 12%;">
                <col style="width: 12%;">
                <col style="width: 24%;">
                <col style="width: 10%;">
                <col style="width: 10%;">
            </colgroup>
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700 h-5">
                    <th class="border border-gray-300 dark:border-gray-600 p-1 text-gray-800 dark:text-gray-200">NAMES</th>
                    <th class="border border-gray-300 dark:border-gray-600 p-1 text-gray-800 dark:text-gray-200">DURATION</th>
                    <th class="border border-gray-300 dark:border-gray-600 p-1 text-gray-800 dark:text-gray-200">START DATE</th>
                    <th class="border border-gray-300 dark:border-gray-600 p-1 text-gray-800 dark:text-gray-200">END DATE</th>
                    <th class="border border-gray-300 dark:border-gray-600 p-1 text-gray-800 dark:text-gray-200">UNITS</th>
                    <th class="border border-gray-300 dark:border-gray-600 p-1 text-gray-800 dark:text-gray-200">SIGN</th>
                    <th class="border border-gray-300 dark:border-gray-600 p-1 text-gray-800 dark:text-gray-200">REMARKS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roster->discipline->units()->orderBy('sort_order')->get() as $unitIndex => $unit)
                    @foreach($unit->subunits as $loopIndex => $sub)
                        <tr class="h-6 unit-group">
                            @if($unitIndex === 0 && $loopIndex === 0)
                                <td class="border border-gray-300 dark:border-gray-600 p-1 align-top wrap-name" rowspan="{{ $totalRows }}">
                                    @foreach($studentGroups as $name)
                                        <div class="uppercase mb-2 px-2 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $name }}</div>
                                    @endforeach
                                </td>
                            @endif
                            @if($loopIndex === 0)
                                <td class="border border-gray-300 dark:border-gray-600 p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                                    <table class="w-full border-collapse">
                                        <tbody>
                                            <tr class="h-6"><td></td></tr>
                                            @foreach($unit->subunits as $s)
                                                @php $dates = $dateSequence[$dateIndex++] ?? ['duration_weeks' => 0]; @endphp
                                                <tr class="h-6">
                                                    <td class="text-center text-gray-900 dark:text-gray-100">
                                                        {{ strtoupper($dates['duration_weeks'] . ' ' . ($dates['duration_weeks'] == 1 ? 'WEEK' : 'WEEKS')) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                                    <table class="w-full border-collapse">
                                        <tbody>
                                            <tr class="h-6"><td></td></tr>
                                            @php $tempIndex = $dateIndex - $unit->subunits->count(); @endphp
                                            @foreach($unit->subunits as $s)
                                                <tr class="h-6">
                                                    <td class="text-center text-gray-900 dark:text-gray-100">{{ $dateSequence[$tempIndex]['start_date'] ?? '-' }}</td>
                                                </tr>
                                                @php $tempIndex++; @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                                    <table class="w-full border-collapse">
                                        <tbody>
                                            <tr class="h-6"><td></td></tr>
                                            @php $tempIndex = $dateIndex - $unit->subunits->count(); @endphp
                                            @foreach($unit->subunits as $s)
                                                <tr class="h-6">
                                                    <td class="text-center text-gray-900 dark:text-gray-100">{{ $dateSequence[$tempIndex]['end_date'] ?? '-' }}</td>
                                                </tr>
                                                @php $tempIndex++; @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                                    <table class="w-full border-collapse">
                                        <tbody>
                                            <tr class="h-6 underline"><td class="text-center font-bold text-gray-900 dark:text-gray-100">{{ strtoupper($unit->name) }}</td></tr>
                                            @foreach($unit->subunits as $s)
                                                <tr class="h-6"><td class="text-center text-gray-900 dark:text-gray-100">{{ strtoupper($s->name) }}</td></tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-1 align-top" rowspan="{{ $unit->subunits->count() }}"></td>
                                <td class="border border-gray-300 dark:border-gray-600 p-1 align-top" rowspan="{{ $unit->subunits->count() }}"></td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="flex justify-center mt-6 space-x-4 print-hidden">
        <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Print</button>
        <button onclick="shuffleRoster()" class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Shuffle</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function shuffleRoster() {
        const loadingSpinner = document.getElementById('loading-spinner');
        if (loadingSpinner) loadingSpinner.classList.remove('hidden');

        fetch('/rosters/{{ $roster->id ?? 0 }}/shuffle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Shuffle failed: ' + error.message);
        })
        .finally(() => {
            if (loadingSpinner) loadingSpinner.classList.add('hidden');
        });
    }
</script>
@endpush
