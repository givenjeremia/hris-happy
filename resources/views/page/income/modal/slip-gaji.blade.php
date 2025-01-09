<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .header {
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .info {
            margin: 20px 0;
        }
        .info p {
            margin: 5px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Slip Gaji {{ $employeeName }}</h1>
            <p>Periode: {{ $period }}</p>
        </div>
        <div class="info">
            <p><strong>Nama Pegawai:</strong> {{ $employeeName }}</p>
            <p><strong>Status:</strong> {{ $status }}</p>
            <p><strong>Total Gaji:</strong> Rp. {{ $nominal }}</p>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Nominal</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $detail)
                    <tr>
                        <td>{{ $detail['category'] }}</td>
                        <td>{{ $detail['type'] }}</td>
                        <td>Rp. {{ $detail['nominal'] }}</td>
                        <td>{{ $detail['desc'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="info">
            <p><strong>Dibuat : </strong> Staff Keuangan</p>
            <p><strong>Disetujui : </strong> Manager Keuangan</p><br><br>
            <p><strong>NB: Jika Gaji Tidak Sesuai Harap Segera Lapor Ke Manager Keuangan</strong></p>
        </div>
    </div>
</body>
</html>
