<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Saldo</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>
    <h3>Laporan Saldo</h3>

    <p>
        <strong>Total Pemasukan:</strong> Rp {{ number_format($totalPemasukan, 0, ',', '.') }}<br>
        <strong>Total Pengeluaran:</strong> Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}<br>
        <strong>Saldo Akhir:</strong> Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Total Pemasukan</th>
                <th>Total Pengeluaran</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daily as $row)
                <tr>
                    <td>{{ $row['tanggal'] }}</td>
                    <td>Rp {{ number_format($row['pemasukan'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>