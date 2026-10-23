<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $categories = [
            ['name' => 'Gaji', 'type' => 'pemasukan'],
            ['name' => 'Bonus', 'type' => 'pemasukan'],
            ['name' => 'Investasi', 'type' => 'pemasukan'],

            ['name' => 'Makan', 'type' => 'pengeluaran'],
            ['name' => 'Transport', 'type' => 'pengeluaran'],
            ['name' => 'Belanja', 'type' => 'pengeluaran'],
            ['name' => 'Hiburan', 'type' => 'pengeluaran'],
            ['name' => 'Tagihan', 'type' => 'pengeluaran'],
            ['name' => 'Kesehatan', 'type' => 'pengeluaran'],
            ['name' => 'Lainnya', 'type' => 'pengeluaran'],
        ];

        foreach ($categories as $c) {
            DB::table('categories')->insert([
                'name' => $c['name'],
                'type' => $c['type'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}

