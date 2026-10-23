@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-4">📋 Riwayat Saldo Awal</h3>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Tanggal Efektif</th>
                    <th>Saldo Awal (Rp)</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($balances as $balance)
                    <tr>
                        <td>{{ $balance->effective_date->format('d-m-Y') }}</td>
                        <td><strong>Rp {{ number_format($balance->amount, 0, ',', '.') }}</strong></td>
                        <td>{{ $balance->notes ?? '-' }}</td>
                        <td>
                            <form action="{{ route('balance.destroy', $balance->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Hapus saldo awal ini?')" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data saldo awal</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('balance.create') }}" class="btn btn-primary">← Kembali</a>
    </div>
@endsection
