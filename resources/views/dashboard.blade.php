@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📊 Dashboard Keuangan</h3>
    <div>
        <strong>Halo, {{ auth()->user()->name }}</strong>
    </div>
</div>

{{-- Ringkasan Singkat --}}
<div class="row g-3 mb-4">
    @if($initialBalance)
        <div class="col-md-3">
            <div class="card p-3 card-box" style="border-left: 4px solid #007bff;">
                <div class="text-muted small">Saldo Awal</div>
                <h5 class="text-primary">Rp {{ number_format($stats['initial_balance'], 0, ',', '.') }}</h5>
                <small class="text-muted">Per {{ $initialBalance->effective_date->format('d-m-Y') }}</small>
            </div>
        </div>
    @endif
    <div class="col-md-3">
        <div class="card p-3 card-box" style="border-left: 4px solid #28a745;">
            <div class="text-muted small">✅ Total Pemasukan</div>
            <h5 class="text-success">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 card-box" style="border-left: 4px solid #dc3545;">
            <div class="text-muted small">❌ Total Pengeluaran</div>
            <h5 class="text-danger">Rp {{ number_format($stats['total_expense'], 0, ',', '.') }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 card-box" style="border-left: 4px solid #{{ $stats['final_balance'] >= 0 ? '28a745' : 'dc3545' }};">
            <div class="text-muted small">💰 Saldo Akhir</div>
            <h5 class="{{ $stats['final_balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                Rp {{ number_format($stats['final_balance'], 0, ',', '.') }}
            </h5>
        </div>
    </div>
</div>

{{-- Tombol Aksi Cepat --}}
<div class="mb-3">
    <a href="{{ route('transactions.create', ['type' => 'pemasukan']) }}" class="btn btn-success btn-sm">
        ➕ Tambah Pemasukan
    </a>
    <a href="{{ route('transactions.create', ['type' => 'pengeluaran']) }}" class="btn btn-danger btn-sm">
        ➕ Tambah Pengeluaran
    </a>
    <a href="{{ route('balance.create') }}" class="btn btn-info btn-sm">
        ⚙️ Atur Saldo Awal
    </a>
    <a href="{{ route('transactions.index') }}" class="btn btn-primary btn-sm">
        📋 Lihat Semua Transaksi
    </a>
</div>

{{-- Tabel Transaksi Terbaru --}}
<div class="card p-3 table-wrap">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">📌 Transaksi Terbaru</h5>
        <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua →</a>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead class="table-light">
                <tr>
                    <th>Tanggal & Jam</th>
                    <th>Keterangan</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th class="text-end">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $t)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($t->date)->format('d-m-Y H:i') }}</td>
                    <td>{{ $t->description ?? '-' }}</td>
                    <td>{{ $t->category?->name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $t->type === 'pemasukan' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($t->type) }}
                        </span>
                    </td>
                    <td class="text-end">
                        @if($t->type === 'pemasukan')
                            <span class="text-success">+ Rp {{ number_format($t->amount, 0, ',', '.') }}</span>
                        @else
                            <span class="text-danger">- Rp {{ number_format($t->amount, 0, ',', '.') }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Belum ada transaksi. <a href="{{ route('transactions.create') }}">Tambah sekarang →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Quick Links --}}
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card p-3">
            <h6 class="mb-3">📊 Laporan</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('reports.incomes') }}" class="btn btn-outline-success btn-sm">Laporan Pemasukan</a>
                <a href="{{ route('reports.expenses') }}" class="btn btn-outline-danger btn-sm">Laporan Pengeluaran</a>
                <a href="{{ route('reports.balances') }}" class="btn btn-outline-primary btn-sm">Laporan Saldo</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h6 class="mb-3">⚙️ Pengaturan</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('balance.create') }}" class="btn btn-outline-info btn-sm">Atur Saldo Awal</a>
                <a href="{{ route('balance.history') }}" class="btn btn-outline-secondary btn-sm">Riwayat Saldo Awal</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Floating logo that appears when buttons are clicked */
    #keuangan-logo {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1100;
        width: 160px;
        max-width: 28vw;
        background: rgba(255,255,255,0.92);
        padding: 8px;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: scale(0.95);
        transition: opacity 220ms ease, transform 220ms ease;
        pointer-events: none;
    }
+    #keuangan-logo.show {
        opacity: 1;
        transform: scale(1);
        pointer-events: auto;
    }
    #keuangan-logo img{ max-width:100%; height:auto; display:block; }
</style>

<div id="keuangan-logo" aria-hidden="true">
    <img src="{{ asset('image/keuangan.jpg') }}" alt="{{ config('app.name', 'Keuangan') }}">
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function(){
        const logoEl = document.getElementById('keuangan-logo');
        let hideTimer = null;

        function shouldTrigger(target){
            if(!target) return false;
            if(target.closest && target.closest('#keuangan-logo')) return false;
            if(target.closest && (target.closest('button') || target.closest('a.btn') || target.closest('[role="button"]'))) return true;
            if(target.tagName === 'BUTTON') return true;
            if(target.tagName === 'A' && target.classList.contains('btn')) return true;
            if((target.tagName === 'INPUT') && (target.type === 'button' || target.type === 'submit')) return true;
            return false;
        }

        function showLogo(){
            if(!logoEl) return;
            logoEl.classList.add('show');
            if(hideTimer) clearTimeout(hideTimer);
            hideTimer = setTimeout(()=> logoEl.classList.remove('show'), 2000);
        }

        document.addEventListener('click', function(ev){
            if(shouldTrigger(ev.target)){
                showLogo();
            }
        }, {capture: true});
    })();
</script>
@endsection
