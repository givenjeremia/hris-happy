<!DOCTYPE html>
<html>
<head>
    <title>Presence Report</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif; 
            margin: 10px;
        }
        h1, h2, h3 {
            text-align: center;
        }
        .report-header {
            margin-bottom: 10px;
            text-align: center;
        }
        .report-header h2 {
            margin: 5px 0;
            font-size: 15px;
            color: #555;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        tfoot td {
            font-weight: bold;
        }
        .statistics {
            margin-top: 10px;
            font-size: 14px;
        }
        .statistics ul {
            list-style: none;
            padding: 0;
        }
        .statistics li {
            margin-bottom: 5px;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
    
    
</head>
<body>
    <div class="report-header">
        <h1>Presence Report</h1>
        <h2>Period: {{ $start_date }} to {{ $end_date }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Area Cek In</th>
                <th>Time Out</th>
                <th>Area Cek Out</th>
                <th>Status</th>
                {{-- <th>Description</th> --}}
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $presence)
                <tr>
                    <td>{{ $presence->employee->full_name }}</td>
                    <td>{{ $presence->date }}</td>
                    <td>{{ $presence->time_in }}</td>
                    <td>{{ $presence->area_cek_in }} KM</td>
                    <td>{{ $presence->time_out }}</td>
                    <td>{{ $presence->area_cek_out }} KM</td>
                    <td>{{ $presence->status }}</td>
                    {{-- <td>{{ $presence->information }}</td> --}}
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="statistics">
        <h3>Statistics</h3>
        <ul>
            @foreach ($employee_statistics as $employee => $days_present)
                <li>{{ $employee }} has been present for {{ $days_present }} days.</li>
            @endforeach
        </ul>
    </div>

    <div class="footer">
        Printed on {{ now()->format('l, d F Y H:i') }}
    </div>
</body>
</html>
