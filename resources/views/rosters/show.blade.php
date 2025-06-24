@extends('layouts.app')

@section('content')
@php
    // Calculate total rows for the NAMES column spanning all subunits across all units
    $totalRows = $roster->discipline->units->sum(function ($unit) {
        return $unit->subunits->count();
    });

    // Collect all student names from assignments and join with newline
    $studentNames = $assignments->pluck('student_name')->unique()->implode("\n");
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
        @php
            $currentDate = \Carbon\Carbon::parse($roster->start_date);
        @endphp
        @foreach($roster->discipline->units as $unitIndex => $unit)
          @foreach($unit->subunits as $loopIndex => $sub)
            <tr>
              {{-- Render NAMES column only on the very first row of the table --}}
              @if($unitIndex === 0 && $loopIndex === 0)
                <td class="border p-1 align-top" rowspan="{{ $totalRows }}">
                  {{ $studentNames }}
                </td>
              @endif

              {{-- Render other columns only on the first subunit of each unit --}}
              @if($loopIndex === 0)
                {{-- DURATION: a single cell listing every subunit’s duration --}}
                <td class="border p-1 align-top" rowspan="{{ $unit->subunits->count() }}">
                  <div class="h-6"></div>
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
                <td class="border p-1" rowspan="{{ $unit->subunits->count() }}">
                  <table class="w-full border-collapse">
                    <tbody>
                      @foreach($unit->subunits as $s)
                        <tr class="h-6">
                          <td class="text-gray-800 align-middle">
                            {{ $currentDate->format('d/m/Y') }}
                          </td>
                        </tr>
                        @php
                            $currentDate->addWeeks($s->duration_weeks);
                        @endphp
                      @endforeach
                    </tbody>
                  </table>
                </td>

                {{-- END DATE --}}
                <td class="border p-1" rowspan="{{ $unit->subunits->count() }}">
                  <table class="w-full border-collapse">
                    <tbody>
                      @php
                          $endDate = \Carbon\Carbon::parse($roster->start_date);
                      @endphp
                      @foreach($unit->subunits as $s)
                        <tr class="h-6">
                          <td class="text-gray-800 align-middle">
                            {{ $endDate->copy()->addWeeks($s->duration_weeks - 1)->format('d/m/Y') }}
                          </td>
                        </tr>
                        @php
                            $endDate->addWeeks($s->duration_weeks);
                        @endphp
                      @endforeach
                    </tbody>
                  </table>
                </td>

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
          @php
              // Reset currentDate for the next unit, starting from the last end date of the previous unit
              $currentDate = \Carbon\Carbon::parse($roster->start_date)->addWeeks(
                  $unit->subunits->sum('duration_weeks')
              );
          @endphp
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
  /* Ensure text starts at the top of the NAMES cell */
  td.align-top {
    vertical-align: top;
    white-space: pre-wrap; /* Preserve newlines and wrap text */
  }
</style>
@endpush