<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presence Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 5px; 
            color: #333;
        }
        h1, h2, h3 {
            text-align: center;
            margin: 0;
            padding: 5px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-header h2 {
            font-size: 16px;
            color: #555;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table thead th {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 13px;
        }
        .table tbody td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 12px;
        }
        .table tbody td:first-child {
            text-align: center;
        }
        .table tbody td.name-column {
            text-align: left;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table tfoot td {
            border-top: 2px solid #4CAF50;
            font-weight: bold;
            padding: 12px;
            text-align: center;
        }
        .statistics {
            margin-top: 20px;
            font-size: 16px;
        }
        .statistics ul {
            list-style: none;
            padding: 0;
        }
        .statistics li {
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Presence Report</h1>
        <h2>Period: {{ $start_date ?? 'N/A' }} to {{ $end_date ?? 'N/A' }}</h2>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Employee</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Area In</th>
                <th>Time Out</th>
                <th>Area Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $presence)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="name-column">{{ $presence->employee->full_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($presence->date)->format('d-m-Y') }}</td>
                    <td>{{ $presence->time_in ?? '-' }}</td>
                    <td>{{ $presence->area_cek_in ?? '-' }} KM</td>
                    <td>{{ $presence->time_out ?? '-' }}</td>
                    <td>{{ $presence->area_cek_out ?? '-' }} KM</td>
                    <td>{{ $presence->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: red;">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="statistics">
        <h3>Statistics</h3>
        <ul>
            @if ($employee_statistics && count($employee_statistics) > 0)
                @foreach ($employee_statistics as $employee => $days_present)
                    <li>{{ $employee }} has been present for <strong>{{ $days_present }}</strong> days.</li>
                @endforeach
            @else
                <li style="color: red;">No statistics available.</li>
            @endif
        </ul>
    </div>

    <div class="footer">
        Printed on {{ \Carbon\Carbon::now()->format('l, d F Y H:i') }}
    </div>
</body>
</html>
