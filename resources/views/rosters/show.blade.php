@extends('layouts.app')

@section('content')
@php
    // Safely calculate total rows, default to 0 if data is missing
    $totalRows = $roster && $roster->discipline && $roster->discipline->units
        ? $roster->discipline->units->sum(function ($unit) {
            return $unit->subunits ? $unit->subunits->count() : 0;
        })
        : 0;
@endphp

<div class="container mx-auto px-2 py-6">
    <!-- Header outside print-content -->
    <div class="text-center mb-4 print-hidden">
        <h2 class="text-xl font-bold">
            ROSTER FOR {{ $roster && $roster->discipline ? strtoupper($roster->discipline->name) : 'N/A' }}
        </h2>
        <div class="text-sm">
            (FIFTY-TWO WEEKS) FROM
            {{ $roster && $roster->start_date ? \Carbon\Carbon::parse($roster->start_date)->format('d/m/Y') : 'N/A' }}
            TO
            {{ $roster && $roster->end_date ? \Carbon\Carbon::parse($roster->end_date)->format('d/m/Y') : 'N/A' }}
        </div>
    </div>

    <!-- Wrap only the table in print-content -->
    <div class="print-content">
        <table class="w-full border-collapse text-sm table-fixed">
            <colgroup>
                <col style="width: 20%;">
                <col style="width: 11%;">
                <col style="width: 12%;">
                <col style="width: 12%;">
                <col style="width: 20%;">
                <col style="width: 10%;">
                <col style="width: 15%;">
            </colgroup>
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-1">NAMES</th>
                    <th class="border p-1">DURATION</th>
                    <th class="border p-1">START DATE</th>
                    <th class="border p-1">END DATE</th>
                    <th class="border p-1">UNITS</th>
                    <th class="border p-1">SIGN</th>
                    <th class="border p-1">REMARKS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentDate = $roster && $roster->start_date ? \Carbon\Carbon::parse($roster->start_date) : now();
                @endphp
                @if($roster && $roster->discipline && $roster->discipline->units)
                    @foreach($roster->discipline->units as $unitIndex => $unit)
                        @if($unit->subunits)
                            @foreach($unit->subunits as $loopIndex => $sub)
                                <tr class="h-6">
                                    @if($unitIndex === 0 && $loopIndex === 0)
                                        <td class="border p-1 align-top names-cell" rowspan="{{ $totalRows }}">
                                            @if(isset($assignments) && $assignments && $assignments->isNotEmpty())
                                                @foreach($assignments->pluck('student_name')->unique() as $name)
                                                    <div class="mb-2 text-lg leading-snug uppercase">{{ $name }}</div>
                                                @endforeach
                                            @else
                                                <div class="mb-2 text-lg leading-snug uppercase">No Names</div>
                                            @endif
                                        </td>
                                    @endif

                                    @if($loopIndex === 0)
                                        <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                                            <table class="w-full border-collapse">
                                                <tbody>
                                                    <tr class="h-6"><td class="text-gray-800 align-middle text-center"></td></tr>
                                                    @foreach($unit->subunits as $s)
                                                        <tr class="h-6">
                                                            <td class="text-gray-800 align-middle text-center">
                                                                {{ $s->duration_weeks ?? 0 }} WEEKS
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>

                                        <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                                            <table class="w-full border-collapse">
                                                <tbody>
                                                    <tr class="h-6"><td class="text-gray-800 align-middle text-center"></td></tr>
                                                    @php
                                                        $subunitStartDate = $currentDate->copy();
                                                    @endphp
                                                    @foreach($unit->subunits as $s)
                                                        <tr class="h-6">
                                                            <td class="text-gray-800 align-middle text-center">
                                                                {{ $subunitStartDate->format('d/m/Y') }}
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $subunitStartDate->addWeeks($s->duration_weeks ?? 0);
                                                        @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>

                                        <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                                            <table class="w-full border-collapse">
                                                <tbody>
                                                    <tr class="h-6"><td class="text-gray-800 align-middle text-center"></td></tr>
                                                    @php
                                                        $subunitEndDate = $currentDate->copy();
                                                    @endphp
                                                    @foreach($unit->subunits as $s)
                                                        <tr class="h-6">
                                                            <td class="text-gray-800 align-middle text-center">
                                                                {{ $subunitEndDate->copy()->addWeeks($s->duration_weeks ?? 0)->subDay()->format('d/m/Y') }}
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $subunitEndDate->addWeeks($s->duration_weeks ?? 0);
                                                        @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>

                                        <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                                            <strong class="block h-6 leading-6 text-gray-800 text-center">
                                                {{ strtoupper($unit->name ?? 'N/A') }}
                                            </strong>
                                            <table class="w-full border-collapse">
                                                <tbody>
                                                    @foreach($unit->subunits as $s)
                                                        <tr class="h-6">
                                                            <td class="text-gray-800 align-middle">{{ $s->name ?? 'N/A' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>

                                        <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}"></td>
                                        <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}"></td>
                                    @endif
                                </tr>
                                @php
                                    $currentDate->addWeeks($sub->duration_weeks ?? 0);
                                @endphp
                            @endforeach
                        @endif
                    @endforeach
                @else
                    <tr><td colspan="7" class="border p-1">No roster data available</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="flex justify-center mt-6 space-x-4 print-hidden">
        <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Print
        </button>
        <button onclick="shuffleRoster()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
            Shuffle
        </button>
    </div>
</div>

@push('scripts')
<script>
    function shuffleRoster() {
        fetch('/rosters/{{ $roster->id ?? 0 }}/shuffle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error shuffling roster: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to shuffle roster. Please try again.');
            });
    }
</script>
@endpush

@endsection

@push('styles')
<style>
    /* Screen styles */
    table.table-fixed { table-layout: fixed; }
    table.table-fixed td { vertical-align: top; }
    table.table-fixed tr { height: 1.5rem; }
    .h-6 { height: 1.5rem; }
    .leading-6 { line-height: 1.5rem; }
    td.align-top { vertical-align: top; white-space: pre-wrap; }
    td.text-center { text-align: center; }
    .names-cell div { line-height: 1.25rem; }

    /* Print styles */
    @media print {
        /* Page setup */
        @page { size: A4 landscape; margin: 0.5cm; }
        /* Hide everything by default */
        html, body, * { display: none !important; overflow: hidden !important; }
        /* Show only print-content and its table */
        .print-content { display: block !important; width: 100% !important; margin: 0 !important; padding: 0 !important; overflow: hidden !important; }
        .print-content table { display: table !important; width: 100% !important; border-collapse: collapse !important; page-break-inside: avoid !important; overflow: hidden !important; }
        .print-content thead { display: table-header-group !important; }
        .print-content tbody { display: table-row-group !important; }
        .print-content tr { display: table-row !important; }
        .print-content th, .print-content td { display: table-cell !important; border: 1px solid #000 !important; padding: 4px !important; font-size: 10pt !important; text-align: left !important; }
        .print-content .text-center { text-align: center !important; }
        /* Explicitly hide all UI elements */
        header, nav, .sidebar, #sidebar-overlay, #mobile-menu-button, #theme-toggle, .print-hidden, .container, main, .flex, .flex-1, .flex-col, .relative, .h-screen, .text-center, .text-xl, .text-sm, aside, footer { display: none !important; }
        /* Remove any potential scrollbars */
        body { -webkit-print-color-adjust: exact; overflow: hidden !important; }
        * { overflow: hidden !important; }
    }
</style>
@endpush