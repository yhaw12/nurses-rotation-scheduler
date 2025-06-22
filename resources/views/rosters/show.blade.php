@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Roster</title>
    <style>
        @media print {
            body {
                font-family: 'Times New Roman', serif;
                margin: 0;
            }
            .container {
                width: 100%;
                padding: 0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed; /* Prevent column resizing */
            }
            th, td {
                border: 1px solid black;
                padding: 5px;
                text-align: left;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            th.names, td.names {
                width: 200px; /* Fixed width for names */
                min-width: 200px;
                max-width: 200px;
                white-space: nowrap; /* Prevent wrapping */
            }
            th {
                background-color: #f2f2f2;
            }
            .text-center {
                text-align: center;
            }
            h1 {
                font-size: 18pt;
                font-weight: bold;
                text-align: center;
                margin-bottom: 20px;
            }
            .section-title {
                font-size: 14pt;
                font-weight: bold;
                margin-top: 20px;
            }
            .signature-line, .remarks-line {
                border-bottom: 1px solid black;
                height: 40px;
                width: 100%;
            }
            @page {
                size: A4 portrait;
                margin: 1cm;
            }
            .no-print {
                display: none;
            }
            .no-data {
                text-align: center;
                padding: 20px;
                color: #666;
            }
        }
        /* Screen styles for preview */
        body {
            font-family: 'Times New Roman', serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Prevent column resizing */
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        th.names, td.names {
            width: 200px; /* Fixed width for names */
            min-width: 200px;
            max-width: 200px;
            white-space: nowrap; /* Prevent wrapping */
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <!-- Title -->
            <h1>
                ROSTER FOR {{ strtoupper($roster->discipline->name) }} (TWO-WEEK DUTY) STARTING FROM {{ \Carbon\Carbon::parse($roster->start_date)->format('d/m/Y') }} TO {{ \Carbon\Carbon::parse($roster->end_date)->format('d/m/Y') }}
            </h1>

            <!-- Roster Table -->
            @if($assignments->isEmpty())
                <div class="no-data">No student data available for this roster.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th class="names">Student</th>
                            <th>Unit</th>
                            <th>Subunit</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $a)
                            <tr>
                                <td class="names" title="{{ $a->student_name }}">{{ $a->student_name }}</td>
                                <td>{{ $a->unit->name }}</td>
                                <td>{{ $a->subunit->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($a->start_date)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($a->end_date)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- Signature and Remarks Sections -->
            <div class="section-title">SIGN OF</div>
            <div class="signature-line"></div>

            <div class="section-title">REMARKS</div>
            <div class="remarks-line"></div>

            <!-- Print and Reshuffle Buttons (Hidden on Print) -->
            <div class="mt-4 text-center no-print">
                <form action="{{ route('rosters.reshuffle', $roster) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button class="bg-yellow-500 text-white px-4 py-2 rounded">Reshuffle</button>
                </form>
                <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 font-semibold">
                    Print Roster
                </button>
            </div>
        </div>
    </div>
</body>
</html>
@endsection