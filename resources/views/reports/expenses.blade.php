@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-3">📊 Laporan Pengeluaran</h3>

        <div class="alert alert-danger">
            <strong>Total Pengeluaran:</strong> Rp {{ number_format($totalExpense, 0, ',', '.') }}
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
                @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->date->format('d-m-Y H:i') }}</td>
                        <td>{{ $expense->category->name ?? '-' }}</td>
                        <td>{{ $expense->description ?? '-' }}</td>
                        <td><span class="text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data pengeluaran</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>
@endsection
