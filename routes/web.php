<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InitialBalanceController;
use App\Models\Category;

// =========================
// AUTH ROUTES
// =========================

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================
// PROTECTED ROUTES
// =========================

Route::middleware('auth')->group(function () {

    // Redirect root → dashboard
    Route::get('/', fn() => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =========================
    // CRUD TRANSAKSI
    // =========================
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // =========================
    // AJAX: Ambil kategori berdasarkan jenis
    // =========================
    Route::get('/kategori-by-jenis/{jenis}', function ($jenis) {
        return Category::where('type', $jenis)->get();
    });

    // =========================
    // LAPORAN
    // =========================
    Route::get('/reports/incomes', [ReportController::class, 'incomes'])->name('reports.incomes');
    Route::get('/reports/expenses', [ReportController::class, 'expenses'])->name('reports.expenses');
    Route::get('/reports/balances', [ReportController::class, 'balances'])->name('reports.balances');

    // =========================
    // SALDO AWAL
    // =========================
    Route::get('/balance/create', [InitialBalanceController::class, 'create'])->name('balance.create');
    Route::post('/balance/store', [InitialBalanceController::class, 'store'])->name('balance.store');
    Route::get('/balance/history', [InitialBalanceController::class, 'history'])->name('balance.history');
    Route::delete('/balance/{id}', [InitialBalanceController::class, 'destroy'])->name('balance.destroy');

});
