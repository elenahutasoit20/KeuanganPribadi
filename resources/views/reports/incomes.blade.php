@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-3">📊 Laporan Pemasukan</h3>

        <div class="alert alert-success">
            <strong>Total Pemasukan:</strong> Rp {{ number_format($totalIncome, 0, ',', '.') }}
        </div>

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incomes as $income)
                    <tr>
                        <td>{{ $income->date->format('d-m-Y H:i') }}</td>
                        <td>{{ $income->category->name ?? '-' }}</td>
                        <td>{{ $income->description ?? '-' }}</td>
                        <td><span class="text-success">Rp {{ number_format($income->amount, 0, ',', '.') }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data pemasukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>
@endsection
