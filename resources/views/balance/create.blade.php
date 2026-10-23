@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h3 class="mb-4">💰 Atur Saldo Awal</h3>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Masukkan saldo awal Anda. Saldo akhir akan dihitung otomatis dari:
                            <strong>Saldo Awal + Total Pemasukan - Total Pengeluaran</strong>
                        </p>

                        <form action="{{ route('balance.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="effective_date">Tanggal Efektif</label>
                                <input type="date" name="effective_date" id="effective_date" 
                                    class="form-control @error('effective_date') is-invalid @enderror"
                                    value="{{ old('effective_date', $initialBalance?->effective_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" 
                                    required>
                                @error('effective_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="amount">Saldo Awal (Rp)</label>
                                <input type="number" name="amount" id="amount" step="0.01"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount', $initialBalance?->amount ?? 0) }}" 
                                    required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes">Catatan (Opsional)</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="Contoh: Saldo dari bulan sebelumnya">{{ old('notes', $initialBalance?->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">💾 Simpan Saldo Awal</button>
                        </form>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('balance.history') }}" class="btn btn-info">📋 Lihat Riwayat Saldo Awal</a>
                    <a href="{{ route('reports.balances') }}" class="btn btn-success">💰 Lihat Laporan Saldo</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
