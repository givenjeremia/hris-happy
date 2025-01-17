<!DOCTYPE html>
<html>
<head>
    <title>Overtime Report</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 20px;
            color: #333; 
        }
        h1, h2, h3 {
            text-align: center;
            margin: 0;
            padding: 5px;
        }
        .report-header {
            margin-bottom: 20px;
            text-align: center;
        }
        .report-header h2 {
            margin: 5px 0;
            font-size: 18px;
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
            padding: 10px;
            text-align: center;
        }
        .table tbody td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .table tbody td:first-child {
            text-align: left; 
        }
        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9; 
        }
        .table tfoot td {
            border-top: 2px solid #4CAF50; 
            font-weight: bold;
            padding: 10px;
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Overtime Report</h1>
        <h2>Period: {{ $start_date ?? 'N/A' }} to {{ $end_date ?? 'N/A' }}</h2>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Long Overtime (Minute)</th>
                <th>Information</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $overtime)
                <tr>
                    <td>{{ $overtime->employee->full_name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($overtime->date)->format('d-m-Y') }}</td>
                    <td>{{ $overtime->long_overtime }}</td>
                    <td>{{ $overtime->information }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: red;">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Printed on {{ \Carbon\Carbon::now()->format('l, d F Y H:i') }}
    </div>
</body>
</html>
