<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Gaji', 'type' => 'pemasukan'],
            ['name' => 'Bonus', 'type' => 'pemasukan'],
            ['name' => 'Penjualan', 'type' => 'pemasukan'],
            ['name' => 'Hadiah', 'type' => 'pemasukan'],
            ['name' => 'Investasi Masuk', 'type' => 'pemasukan'],

           
            ['name' => 'Belanja', 'type' => 'pengeluaran'],
            ['name' => 'Transportasi', 'type' => 'pengeluaran'],
            ['name' => 'Makan & Minum', 'type' => 'pengeluaran'],
            ['name' => 'Tagihan', 'type' => 'pengeluaran'],
            ['name' => 'Hiburan', 'type' => 'pengeluaran'],
        ]);
    }
}
