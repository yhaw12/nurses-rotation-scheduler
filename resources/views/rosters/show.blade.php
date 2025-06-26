@extends('layouts.app')

@section('content')
@php
    $totalRows = $roster && $roster->discipline && $roster->discipline->units
        ? $roster->discipline->units->sum(fn($unit) => $unit->subunits?->count() ?? 0)
        : 0;
@endphp
<div class="container mx-auto px-1 py-2">
    <div class="print-content">
        <div class="text-center mb-1">
            <h2 class="text-xl font-bold">
                ROSTER FOR {{ strtoupper($roster->discipline->name ?? 'N/A') }}
            </h2>
            <div class="text-sm">
                (FIFTY-TWO WEEKS) FROM
                {{ \Carbon\Carbon::parse($roster->start_date)->format('d/m/Y') ?? 'N/A' }}
                TO
                {{ \Carbon\Carbon::parse($roster->end_date)->format('d/m/Y') ?? 'N/A' }}
            </div>
        </div>
        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="bg-gray-100 h-6">
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
                @php $currentDate = \Carbon\Carbon::parse($roster->start_date); @endphp
                @foreach($roster->discipline->units as $unitIndex => $unit)
                    @foreach($unit->subunits as $loopIndex => $sub)
                        <tr class="h-6">
                            @if($unitIndex === 0 && $loopIndex === 0)
                                <td class="border p-1 align-top" rowspan="{{ $totalRows }}">
                                    @foreach($assignments->pluck('student_name')->unique() as $name)
                                        <div class="uppercase mb-1">{{ $name }}</div>
                                    @endforeach
                                </td>
                            @endif

                            @if($loopIndex === 0)
                                <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                                    <table class="w-full border-collapse">
                                        <tbody>
                                            <tr class="h-6"><td></td></tr>
                                            @foreach($unit->subunits as $s)
                                                <tr class="h-6">
                                                    <td class="text-center">{{ $s->duration_weeks }} weeks</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                                    <table class="w-full border-collapse">
                                        <tbody>
                                            <tr class="h-6"><td></td></tr>
                                            @php $sd = $currentDate->copy(); @endphp
                                            @foreach($unit->subunits as $s)
                                                <tr class="h-6"><td class="text-center">{{ $sd->format('d/m/Y') }}</td></tr>
                                                @php $sd->addWeeks($s->duration_weeks); @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                                    <table class="w-full border-collapse">
                                        <tbody>
                                            <tr class="h-6"><td></td></tr>
                                            @php $ed = $currentDate->copy(); @endphp
                                            @foreach($unit->subunits as $s)
                                                <tr class="h-6"><td class="text-center">{{ $ed->copy()->addWeeks($s->duration_weeks)->subDay()->format('d/m/Y') }}</td></tr>
                                                @php $ed->addWeeks($s->duration_weeks); @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                                    <div class="text-center font-bold">{{ strtoupper($unit->name) }}</div>
                                    <table class="w-full border-collapse">
                                        <tbody>
                                            @foreach($unit->subunits as $s)
                                                <tr class="h-6"><td>{{ $s->name }}</td></tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}"></td>
                                <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}"></td>
                            @endif
                        </tr>
                        @php $currentDate->addWeeks($sub->duration_weeks); @endphp
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="flex justify-center mt-6 space-x-4 print-hidden">
        <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Print</button>
        <button onclick="shuffleRoster()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Shuffle</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function shuffleRoster() {
        fetch('/rosters/{{ $roster->id ?? 0 }}/shuffle', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json' }
        })
        .then(r=>r.json()).then(d=>d.success?location.reload():alert('Error:'+d.message)).catch(()=>alert('Shuffle failed'));
    }
</script>
@endpush

@push('styles')
<style>
    @media print {
        @page { size: A4 landscape; margin: 0.2cm; }
        html, body { margin:0; padding:0; width:100%; height:100%; overflow:hidden; }
        .print-hidden, header, aside, nav, .sidebar, #sidebar-overlay, #mobile-menu-button, #theme-toggle { display:none!important; }
        .print-content {
            transform: scale(0.75); /* adjust scale as needed */
            transform-origin: top left;
            width:100%;
        }
        /* Ensure fixed layout for main and nested tables */
        .print-content table {
            width:100%!important;
            border-collapse:collapse!important;
            table-layout:fixed!important;
            page-break-inside:avoid!important;
        }
        .print-content table table {
            width:100%!important;
            table-layout:fixed!important;
        }
        th, td {
            border:1px solid #000!important;
            padding:1px!important;
            font-size:6pt!important;
            vertical-align:top!important;
            word-wrap:break-word;
        }
        h2 { font-size:12pt!important; margin:0!important; padding:0!important; }
    }
</style>
@endpush
