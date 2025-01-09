<!DOCTYPE html>
<html>
<head>
    <title>Vacation Report</title>
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
            font-size: 18px;
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
            font-size: 13px;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
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
        <h1>Vacation Report</h1>
        <h2>Period: {{ $start_date }} to {{ $end_date }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $vacation)
                <tr>
                    <td>{{ $vacation->employee->full_name ?? '-' }}</td>
                    <td>{{ $vacation->start_date }}</td>
                    <td>{{ $vacation->end_date }}</td>
                    <td>{{ $vacation->subject }}</td>
                    <td>{{ $vacation->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Printed on {{ now()->format('l, d F Y H:i') }}
    </div>
</body>
</html>
