@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-3">Tambah Transaksi</h3>

        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf

            {{-- Jenis Transaksi --}}
            <div class="mb-3">
                <label>Jenis</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="pemasukan" {{ $jenis == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ $jenis == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>

            {{-- Kategori --}}
            <div class="mb-3">
                <label>Kategori</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Nominal --}}
            <div class="mb-3">
                <label>Nominal</label>
                <input type="number" name="amount" class="form-control" required>
            </div>

            {{-- Tanggal & Jam --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="date" id="date" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label>Jam</label>
                        <input type="time" name="time" id="time" class="form-control" required>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-3">
                <label>Deskripsi</label>
                <input type="text" name="description" class="form-control">
            </div>

            <button class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>

    {{-- AJAX untuk update kategori berdasarkan jenis --}}
    <script>
        // Set tanggal & jam default ke hari ini dan jam sekarang
        window.addEventListener('load', function() {
            const now = new Date();
            
            // Format tanggal (YYYY-MM-DD)
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            document.getElementById('date').value = `${year}-${month}-${day}`;
            
            // Format jam (HH:mm)
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('time').value = `${hours}:${minutes}`;
        });

        document.getElementById('type').addEventListener('change', function () {
            let jenis = this.value;

            fetch('/kategori-by-jenis/' + jenis)
                .then(response => response.json())
                .then(data => {
                    let kategoriSelect = document.getElementById('category_id');
                    kategoriSelect.innerHTML = '<option value="">-- Pilih Kategori --</option>';

                    data.forEach(function (item) {
                        kategoriSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                    });
                });
        });
    </script>

@endsection