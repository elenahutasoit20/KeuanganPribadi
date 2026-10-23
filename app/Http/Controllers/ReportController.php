<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

// Tambahan untuk export
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BalanceExport;

class ReportController extends Controller
{
    /**
     * LAPORAN PEMASUKAN
     */
    public function incomes()
    {
        $userId = auth()->id();

        $incomes = Transaction::where('user_id', $userId)
            ->where('type', 'pemasukan')
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        $totalIncome = $incomes->sum('amount');

        return view('reports.incomes', compact('incomes', 'totalIncome'));
    }

    /**
     * LAPORAN PENGELUARAN
     */
    public function expenses()
    {
        $userId = auth()->id();

        $expenses = Transaction::where('user_id', $userId)
            ->where('type', 'pengeluaran')
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        $totalExpense = $expenses->sum('amount');

        return view('reports.expenses', compact('expenses', 'totalExpense'));
    }

    /**
     * LAPORAN SALDO + FILTER + EXPORT + GRAFIK
     */
    public function balances(Request $request)
    {
        $userId = auth()->id();

        // Base query
        $query = Transaction::where('user_id', $userId);

        /**
         * ✅ FILTER TANGGAL
         */
        if ($request->range === 'week') {
            $query->whereDate('date', '>=', Carbon::now()->subDays(7));
        }

        if ($request->range === 'month') {
            $query->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year);
        }

        if ($request->range === 'custom') {
            if ($request->from) {
                $query->whereDate('date', '>=', $request->from);
            }
            if ($request->to) {
                $query->whereDate('date', '<=', $request->to);
            }
        }

        // Ambil transaksi
        $transactions = $query->orderBy('date', 'asc')->get();

        /**
         * ✅ PROSES SALDO HARIAN
         */
        $grouped = $transactions->groupBy(function ($t) {
            return Carbon::parse($t->date)->format('Y-m-d');
        });

        $daily = [];
        $saldo = 0;

        foreach ($grouped as $date => $items) {
            $pemasukan = $items->where('type', 'pemasukan')->sum('amount');
            $pengeluaran = $items->where('type', 'pengeluaran')->sum('amount');

            $saldo += ($pemasukan - $pengeluaran);

            $daily[] = [
                'tanggal' => Carbon::parse($date)->format('d-m-Y'),
                'raw_date' => $date,
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $saldo,
            ];
        }

        /**
         * ✅ TOTAL KESELURUHAN
         */
        $totalPemasukan = $transactions->where('type', 'pemasukan')->sum('amount');
        $totalPengeluaran = $transactions->where('type', 'pengeluaran')->sum('amount');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        /**
         * ✅ EXPORT PDF
         */
        if ($request->export === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.balances_pdf', [
                'daily' => $daily,
                'totalPemasukan' => $totalPemasukan,
                'totalPengeluaran' => $totalPengeluaran,
                'saldoAkhir' => $saldoAkhir,
                'filter' => $request->all(),
            ]);

            return $pdf->download('laporan-saldo.pdf');
        }


        /**
         * ✅ EXPORT EXCEL
         */
        if ($request->export === 'excel') {
            return Excel::download(
                new BalanceExport($daily, $totalPemasukan, $totalPengeluaran, $saldoAkhir, $request->all()),
                'laporan-saldo.xlsx'
            );
        }

        /**
         * ✅ TAMPILKAN VIEW
         */
        return view('reports.balances', compact(
            'daily',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir'
        ));
    }
}
