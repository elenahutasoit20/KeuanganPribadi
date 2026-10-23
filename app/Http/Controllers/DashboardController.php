<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Balance;
use App\Models\InitialBalance;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Ambil total dari tabel Income dan Expense (lebih akurat)
        $totalIncome = Income::where('user_id', $userId)->sum('amount');
        $totalExpense = Expense::where('user_id', $userId)->sum('amount');

        // Ambil saldo awal terbaru (efektif)
        $initialBalance = InitialBalance::where('user_id', $userId)
            ->orderBy('effective_date', 'desc')
            ->first();

        $initialAmount = $initialBalance ? $initialBalance->amount : 0;

        // Kalkulasi saldo: Saldo Awal + Total Pemasukan - Total Pengeluaran
        $balance = $initialAmount + $totalIncome - $totalExpense;

        // Transaksi terbaru dari semua tabel
        $recentTransactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Detail untuk bagan ringkasan
        $stats = [
            'initial_balance' => $initialAmount,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'final_balance' => $balance,
        ];

        return view('dashboard', compact('totalIncome', 'totalExpense', 'balance', 'recentTransactions', 'initialBalance', 'stats'));
    }
}
