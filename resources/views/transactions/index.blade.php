@extends('layouts.app')

@section('content')
    <div class="container">

        <h3 class="mb-3">Semua Transaksi</h3>

        {{-- Tombol tambah transaksi berdasarkan jenis --}}
        <a href="{{ route('transactions.create', ['type' => 'pemasukan']) }}" class="btn btn-success mb-3">
            + Tambah Pemasukan
        </a>

        <a href="{{ route('transactions.create', ['type' => 'pengeluaran']) }}" class="btn btn-danger mb-3">
            + Tambah Pengeluaran
        </a>

        {{-- Tombol laporan & saldo --}}
        <div class="mb-3">
            <a href="{{ route('balance.create') }}" class="btn btn-warning">
                💰 Atur Saldo Awal
            </a>
            <a href="{{ route('reports.incomes') }}" class="btn btn-info">
                📊 Laporan Pemasukan
            </a>
            <a href="{{ route('reports.expenses') }}" class="btn btn-secondary">
                📊 Laporan Pengeluaran
            </a>
            <a href="{{ route('reports.balances') }}" class="btn btn-primary">
                💰 Laporan Saldo
            </a>
        </div>

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $t)
                    <tr>
                        {{-- ✅ Format tanggal + jam --}}
                        <td>{{ \Carbon\Carbon::parse($t->date)->format('d-m-Y H:i') }}</td>

                        <td>{{ $t->description ?? '-' }}</td>

                        {{-- ✅ Kategori aman jika null --}}
                        <td>{{ $t->category->name ?? '-' }}</td>

                        <td>{{ ucfirst($t->type) }}</td>

                        {{-- ✅ Warna jumlah sesuai jenis --}}
                        <td>
                            @if($t->type === 'pengeluaran')
                                <span class="text-danger">- Rp {{ number_format($t->amount, 0, ',', '.') }}</span>
                            @else
                                <span class="text-success">Rp {{ number_format($t->amount, 0, ',', '.') }}</span>
                            @endif
                        </td>

                        <td>
                            <form action="{{ route('transactions.destroy', $t->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Hapus transaksi ini?')" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
@endsection