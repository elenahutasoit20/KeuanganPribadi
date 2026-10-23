@extends('layouts.app')

@section('content')

    <h3 class="mb-3">Daftar Transaksi</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>ID</th>
                <th>Jenis</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($transactions as $t)
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ ucfirst($t->type) }}</td>
                    <td>{{ $t->category->name }}</td>
                    <td>{{ $t->date }}</td>
                    <td>Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                    <td>{{ $t->description ?? '-' }}</td>
                    <td>
                        <form action="{{ route('transactions.destroy', $t->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus transaksi ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

@endsection