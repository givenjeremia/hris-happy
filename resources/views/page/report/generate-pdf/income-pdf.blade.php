<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Report</title>
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
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Income Report</h1>
        <h3>Period: {{ $start_date ?? 'N/A' }} - {{ $end_date ?? 'N/A' }}</h3>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Period</th>
                <th>Status</th>
                <th>Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($data) && count($data) > 0)
                @php 
                    $totalNominal = 0; 
                    $totalNoPayment = 0; 
                    $totalPayment = 0; 
                @endphp
                @foreach($data as $income)
                    <tr>
                        <td>{{ $income->employee->full_name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($income->period)->format('d-m-Y') }}</td>
                        <td>{{ $income->status }}</td>
                        <td>{{ number_format($income->nominal, 0, ',', '.') }}</td>
                    </tr>
                    @php 
                        $totalNominal += $income->nominal; 
                        if ($income->status === 'NO_PAYMENT') {
                            $totalNoPayment += $income->nominal;
                        } elseif ($income->status === 'PAYMENT') {
                            $totalPayment += $income->nominal;
                        }
                    @endphp
                @endforeach
            @else
                <tr>
                    <td colspan="4">No income records available</td>
                </tr>
            @endif
        </tbody>
        
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td>Total</td>
                <td colspan="2">Rp. {{ number_format($totalNominal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <p style="color: green;"><strong>Total Income (PAID):</strong> <span style="color: green;">Rp {{ number_format($totalPayment, 0, ',', '.') }}</span></p>
        <p style="color: red;"><strong>Total Income (UNPAID):</strong> <span style="color: red;">Rp {{ number_format($totalNoPayment, 0, ',', '.') }}</span></p>
        <p><strong>Total Income:</strong> Rp {{ number_format($totalNominal, 0, ',', '.') }}</p>
        <p><strong>Number of Records:</strong> {{ count($data) }}</p>
    </div>

    <div class="footer">
        <p>Printed on: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
    </div>
</body>
</html>
