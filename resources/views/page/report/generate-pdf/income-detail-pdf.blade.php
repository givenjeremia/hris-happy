<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Detail Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
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
        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table tfoot td {
            border-top: 2px solid #4CAF50;
            font-weight: bold;
            padding: 10px;
            text-align: center;
        }
        .summary {
            margin-top: 20px;
            font-size: 16px;
        }
        .summary p {
            margin: 5px 0;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @if ($incomes && count($incomes) > 0)
        @foreach ($incomes as $income)
            <div class="employee-section">
                <div class="report-header">
                    <h1>Income Detail Report</h1>
                    <h3>Employee: {{ $income->employee->full_name ?? 'N/A' }}</h3>
                    <p>Period: {{ \Carbon\Carbon::parse($income->period)->format('d-m-Y') }}</p>
                    <p>Status: {{ $income->status }}</p>
                </div>

                <!-- Income Details -->
                <h4>Details:</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Nominal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $employeeTotal = 0; @endphp
                        @foreach ($income->incomeDetails as $detail)
                            <tr>
                                <td>{{ $detail->category }}</td>
                                <td>{{ $detail->type }}</td>
                                <td>{{ $detail->desc }}</td>
                                <td>{{ number_format($detail->nominal, 0, ',', '.') }}</td>
                            </tr>
                            @php $employeeTotal += $detail->nominal; @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total for {{ $income->employee->full_name ?? 'N/A' }}</td>
                            <td>Rp {{ number_format($employeeTotal, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="page-break"></div>

        @endforeach
    @else
        <p>No incomes available.</p>
    @endif

    <div class="summary">
        <p style="color: green;"><strong>Total Income (Paid):</strong> Rp {{ number_format($totalPayment, 0, ',', '.') }}</p>
        <p style="color: red;"><strong>Total Income (Unpaid):</strong> Rp {{ number_format($totalNoPayment, 0, ',', '.') }}</p>
        <p><strong>Total Income:</strong> Rp {{ number_format($totalDetails, 0, ',', '.') }}</p>
    </div>

    <div class="footer">
        <p>Printed on: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
    </div>
</body>
</html>
