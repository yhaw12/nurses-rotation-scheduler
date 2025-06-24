@extends('layouts.app')

@section('content')
@php
    // Calculate total rows for the NAMES column spanning all subunits across all units
    $totalRows = $roster->discipline->units->sum(function ($unit) {
        return $unit->subunits->count();
    });
@endphp

<div class="container mx-auto px-2 py-6">
  <!-- Header -->
  <div class="text-center mb-4">
    <h2 class="text-xl font-bold">
      ROSTER FOR {{ strtoupper($roster->discipline->name) }}
    </h2>
    <div class="text-sm">
      (FIFTY-TWO WEEKS) FROM
      {{ \Carbon\Carbon::parse($roster->start_date)->format('d/m/Y') }}
      TO
      {{ \Carbon\Carbon::parse($roster->end_date)->format('d/m/Y') }}
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full border-collapse text-sm table-fixed">
      <thead>
        <tr class="bg-gray-100">
          <th class="border p-1 w-[15%]">NAMES</th>
          <th class="border p-1 w-[10%]">DURATION</th>
          <th class="border p-1 w-[15%]">START DATE</th>
          <th class="border p-1 w-[15%]">END DATE</th>
          <th class="border p-1 w-[30%]">UNITS & SUBUNITS</th>
          <th class="border p-1 w-[15%]">SIGN</th>
        </tr>
      </thead>
      <tbody>
        @foreach($roster->discipline->units as $unitIndex => $unit)
          @foreach($unit->subunits as $loopIndex => $sub)
            <tr>
              {{-- Render NAMES column only on the very first row of the table --}}
              @if($unitIndex === 0 && $loopIndex === 0)
                <td class="border p-1" rowspan="{{ $totalRows }}"> </td>
              @endif

              {{-- Render other columns only on the first subunit of each unit --}}
              @if($loopIndex === 0)
                {{-- DURATION: a single cell listing every subunit’s duration --}}
                <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                  <div class="h-6"></div> <!-- Spacer to align durations below unit name -->
                  <table class="w-full border-collapse">
                    <tbody>
                      @foreach($unit->subunits as $s)
                        <tr class="h-6">
                          <td class="text-gray-800 align-middle">
                            {{ $s->duration_weeks }} WEEKS
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </td>

                {{-- START DATE --}}
                <td class="border p-1" rowspan="{{ $unit->subunits->count() }}"> </td>
                {{-- END DATE --}}
                <td class="border p-1" rowspan="{{ $unit->subunits->count() }}"> </td>

                {{-- UNITS & SUBUNITS: a single cell listing every subunit’s name --}}
                <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                  <strong class="block h-6 leading-6 text-gray-800">
                    {{ strtoupper($unit->name) }}
                  </strong>
                  <table class="w-full border-collapse">
                    <tbody>
                      @foreach($unit->subunits as $s)
                        <tr class="h-6">
                          <td class="text-gray-800 align-middle">{{ $s->name }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </td>

                {{-- SIGN --}}
                <td class="border p-1" rowspan="{{ $unit->subunits->count() }}"> </td>
              @endif
            </tr>
          @endforeach
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('styles')
<style>
  @media print {
    @page { size: A4 landscape; margin: 1cm; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #000 !important; padding: 2px !important; }
  }
  table.table-fixed td {
    vertical-align: top;
  }
  table.table-fixed tr {
    height: 1.5rem; /* Consistent row height */
  }
  .h-6 {
    height: 1.5rem; /* Matches tr height for alignment */
  }
  .leading-6 {
    line-height: 1.5rem; /* Ensures unit name aligns with spacer */
  }
</style>
@endpush