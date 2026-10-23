<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BalanceExport implements FromArray, WithHeadings
{
    protected $daily;
    protected $totalPemasukan;
    protected $totalPengeluaran;
    protected $saldoAkhir;
    protected $filter;

    public function __construct($daily, $totalPemasukan, $totalPengeluaran, $saldoAkhir, $filter)
    {
        $this->daily = $daily;
        $this->totalPemasukan = $totalPemasukan;
        $this->totalPengeluaran = $totalPengeluaran;
        $this->saldoAkhir = $saldoAkhir;
        $this->filter = $filter;
    }

    public function headings(): array
    {
        return ['Tanggal', 'Total Pemasukan', 'Total Pengeluaran', 'Saldo'];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->daily as $row) {
            $rows[] = [
                $row['tanggal'],
                $row['pemasukan'],
                $row['pengeluaran'],
                $row['saldo'],
            ];
        }

        return $rows;
    }
}
