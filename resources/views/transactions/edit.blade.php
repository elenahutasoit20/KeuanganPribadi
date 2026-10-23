@extends('layouts.app')

@section('content')
<h3>Edit Transaksi</h3>

<div class="card p-4 table-wrap">
    <form action="{{ route('transactions.update',$transaction) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-3">
                <label>Type</label>
                <select name="type" class="form-select" required>
                    <option value="pemasukan" @selected($transaction->type=='pemasukan')>Pemasukan</option>
                    <option value="pengeluaran" @selected($transaction->type=='pengeluaran')>Pengeluaran</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Kategori</label>
                <select name="category_id" class="form-select">
                    <option value="">-- Pilih kategori --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected($transaction->category_id==$c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Jumlah (angka)</label>
                <input name="amount" class="form-control" value="{{ $transaction->amount }}" required>
            </div>
            <div class="col-md-2">
                <label>Tanggal</label>
                <input name="date" type="date" class="form-control" value="{{ $transaction->date->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-2">
                <label>Jam</label>
                <input name="time" type="time" class="form-control" value="{{ $transaction->date->format('H:i') }}" required>
            </div>
            <div class="col-12">
                <label>Keterangan / Catatan</label>
                <textarea name="description" class="form-control" rows="3">{{ $transaction->description }}</textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">Update</button>
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
