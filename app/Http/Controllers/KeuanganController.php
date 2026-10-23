<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function input()
    {
        // Ambil semua kategori
        $kategori = Category::all();

        return view('layouts.v_html.input_keuangan', compact('kategori'));
    }

    public function store(Request $request)
    {
        // nanti kita isi bagian simpan transaksi
    }
}
