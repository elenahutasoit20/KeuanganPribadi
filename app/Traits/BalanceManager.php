<?php

namespace App\Traits;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Balance;
use App\Models\InitialBalance;

trait BalanceManager
{
    /**
     * Update saldo setelah transaksi ditambah/diubah
     * Formula: Saldo Awal + Total Pemasukan - Total Pengeluaran
     */
    public static function updateBalance($userId, $date)
    {
        $dateOnly = \Carbon\Carbon::parse($date)->format('Y-m-d');

        // Ambil saldo awal yang efektif (sebelum atau sama dengan tanggal transaksi)
        $initialBalance = InitialBalance::where('user_id', $userId)
            ->where('effective_date', '<=', $dateOnly)
            ->orderBy('effective_date', 'desc')
            ->first();

        $initialAmount = $initialBalance ? $initialBalance->amount : 0;

        // Hitung total pemasukan dan pengeluaran dari tanggal awal sampai tanggal transaksi
        $totalIncome = Income::where('user_id', $userId)
            ->whereDate('date', '<=', $dateOnly)
            ->sum('amount');

        $totalExpense = Expense::where('user_id', $userId)
            ->whereDate('date', '<=', $dateOnly)
            ->sum('amount');

        $balance = $initialAmount + $totalIncome - $totalExpense;

        Balance::updateOrCreate(
            ['user_id' => $userId, 'date' => $dateOnly],
            [
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'balance' => $balance,
            ]
        );
    }

    /**
     * Update saldo untuk semua tanggal setelah saldo awal diubah
     */
    public static function recalculateAllBalances($userId)
    {
        // Ambil semua tanggal dengan transaksi
        $dates = Income::where('user_id', $userId)
            ->pluck('date')
            ->merge(Expense::where('user_id', $userId)->pluck('date'))
            ->unique()
            ->sort();

        foreach ($dates as $date) {
            self::updateBalance($userId, $date);
        }
    }
}
