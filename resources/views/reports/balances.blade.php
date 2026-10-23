@extends('layouts.app')

@section('content')
    <div class="container">

        <h3 class="mb-3">Laporan Saldo</h3>

        {{-- FILTER TANGGAL --}}
        <form method="GET" action="{{ route('reports.balances') }}" class="row g-2 mb-4">

            <div class="col-md-3">
                <label>Filter</label>
                <select name="range" class="form-control">
                    <option value="">Semua</option>
                    <option value="week" {{ request('range') == 'week' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="month" {{ request('range') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="custom" {{ request('range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>

            <div class="col-md-3">
                <label>Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary me-2">Filter</button>

                {{-- EXPORT PDF --}}
                <a href="{{ route('reports.balances', array_merge(request()->all(), ['export' => 'pdf'])) }}"
                    class="btn btn-danger me-2">
                    PDF
                </a>

                {{-- EXPORT EXCEL --}}
                <a href="{{ route('reports.balances', array_merge(request()->all(), ['export' => 'excel'])) }}"
                    class="btn btn-success">
                    Excel
                </a>
            </div>
        </form>

        {{-- RINGKASAN --}}
        <div class="mb-4">
            <div><strong>Total Pemasukan:</strong> Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div><strong>Total Pengeluaran:</strong> Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div><strong>Saldo Akhir:</strong> Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</div>
        </div>

        {{-- GRAFIK BAR --}}
        <h4 class="mt-4">Grafik Pemasukan & Pengeluaran (Bar)</h4>
        <canvas id="barChart" height="120"></canvas>

        {{-- GRAFIK LINE SALDO --}}
        <h4 class="mt-4">Grafik Saldo Harian (Line)</h4>
        <canvas id="saldoChart" height="120"></canvas>

        {{-- GRAFIK PIE --}}
        <h4 class="mt-4">Perbandingan Pemasukan vs Pengeluaran (Pie)</h4>
        <canvas id="pieChart" width="150" height="150"></canvas>


        {{-- TABEL SALDO --}}
        <h4 class="mt-5">Riwayat Saldo Harian</h4>
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Total Pemasukan</th>
                    <th>Total Pengeluaran</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($daily as $row)
                    <tr>
                        <td>{{ $row['tanggal'] }}</td>
                        <td>Rp {{ number_format($row['pemasukan'], 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">← Kembali</a>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const labels = @json(array_column($daily, 'tanggal'));
        const pemasukan = @json(array_column($daily, 'pemasukan'));
        const pengeluaran = @json(array_column($daily, 'pengeluaran'));
        const saldo = @json(array_column($daily, 'saldo'));

        // BAR CHART
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: pemasukan,
                        backgroundColor: 'rgba(0, 128, 0, 0.7)',
                    },
                    {
                        label: 'Pengeluaran',
                        data: pengeluaran,
                        backgroundColor: 'rgba(255, 0, 0, 0.7)',
                    }
                ]
            },
            options: { responsive: true }
        });

        // LINE CHART SALDO
        new Chart(document.getElementById('saldoChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Saldo',
                    data: saldo,
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 0, 255, 0.2)',
                    tension: 0.3
                }]
            },
            options: { responsive: true }
        });

        // PIE CHART
        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    data: [{{ $totalPemasukan }}, {{ $totalPengeluaran }}],
                    backgroundColor: ['rgba(0, 128, 0, 0.7)', 'rgba(255, 0, 0, 0.7)'],
                }]
            },
            options: { responsive: true }
        });
    </script>

@endsection