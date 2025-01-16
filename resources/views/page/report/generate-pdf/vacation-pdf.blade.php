<!DOCTYPE html>
<html>
<head>
    <title>Vacation Report</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 20px;
            color: #333;
        }
        h1, h2 {
            text-align: center;
            margin: 0;
            padding: 5px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-header h2 {
            margin: 5px 0;
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
            text-align: left; /* Nama karyawan rata kiri */
        }
        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
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
        <h1>Vacation Report</h1>
        <h2>Period: {{ $filterstartdate ?? 'N/A' }} to {{ $filterenddate ?? 'N/A' }}</h2>
    </div>

    <table class="table">
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
                    <td>{{ \Carbon\Carbon::parse($vacation->start_date)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($vacation->end_date)->format('d-m-Y') }}</td>
                    <td>{{ $vacation->subject }}</td>
                    <td>{{ $vacation->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: red;">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Printed on {{ \Carbon\Carbon::now()->format('l, d F Y H:i') }}
    </div>
</body>
</html>
