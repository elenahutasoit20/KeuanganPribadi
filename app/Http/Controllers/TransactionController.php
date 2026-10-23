<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Income;
use App\Models\Expense;
use App\Traits\BalanceManager;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use BalanceManager;
    public function index()
    {
        $transactions = Transaction::with('category')
            ->where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->get();

        return view('transactions.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        // Ambil jenis dari query string, default pemasukan
        $jenis = $request->get('type', 'pemasukan');

        // Filter kategori berdasarkan jenis
        $categories = Category::where('type', $jenis)->get();

        return view('transactions.create', compact('categories', 'jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'category_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'description' => 'nullable|string',
        ]);

        // Gabungkan date dan time menjadi datetime
        $dateTime = $request->date . ' ' . $request->time . ':00';
        $userId = auth()->id();

        // Simpan ke Transaction (untuk history)
        Transaction::create([
            'user_id' => $userId,
            'type' => $request->type,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'date' => $dateTime,
            'description' => $request->description,
        ]);

        // Simpan ke Income atau Expense
        if ($request->type === 'pemasukan') {
            Income::create([
                'user_id' => $userId,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'amount' => $request->amount,
                'date' => $dateTime,
            ]);
        } else {
            Expense::create([
                'user_id' => $userId,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'amount' => $request->amount,
                'date' => $dateTime,
            ]);
        }

        // Update saldo
        self::updateBalance($userId, $dateTime);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $categories = Category::all();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $userId = auth()->id();

        $request->validate([
            'type' => 'required',
            'category_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'description' => 'nullable|string',
        ]);

        // Gabungkan date dan time menjadi datetime
        $dateTime = $request->date . ' ' . $request->time . ':00';
        $oldType = $transaction->type;
        $oldDate = $transaction->date;

        // Update Transaction
        $transaction->update([
            'type' => $request->type,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'date' => $dateTime,
            'description' => $request->description,
        ]);

        // Update atau pindahkan ke Income/Expense
        if ($oldType === 'pemasukan') {
            Income::where('user_id', $userId)->whereDate('date', $oldDate->format('Y-m-d'))->delete();
        } else {
            Expense::where('user_id', $userId)->whereDate('date', $oldDate->format('Y-m-d'))->delete();
        }

        if ($request->type === 'pemasukan') {
            Income::create([
                'user_id' => $userId,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'amount' => $request->amount,
                'date' => $dateTime,
            ]);
        } else {
            Expense::create([
                'user_id' => $userId,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'amount' => $request->amount,
                'date' => $dateTime,
            ]);
        }

        // Update saldo untuk tanggal lama dan baru
        self::updateBalance($userId, $oldDate);
        self::updateBalance($userId, $dateTime);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $userId = auth()->id();
        $transDate = $transaction->date;

        // Hapus dari Income atau Expense
        if ($transaction->type === 'pemasukan') {
            Income::where('user_id', $userId)->whereDate('date', $transDate->format('Y-m-d'))->delete();
        } else {
            Expense::where('user_id', $userId)->whereDate('date', $transDate->format('Y-m-d'))->delete();
        }

        // Hapus dari Transaction
        $transaction->delete();

        // Update saldo
        self::updateBalance($userId, $transDate);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}
