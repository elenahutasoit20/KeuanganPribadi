<?php

namespace App\Http\Controllers;

use App\Models\InitialBalance;
use App\Traits\BalanceManager;
use Illuminate\Http\Request;

class InitialBalanceController extends Controller
{
    use BalanceManager;

    /**
     * Tampilkan form untuk input/edit saldo awal
     */
    public function create()
    {
        $userId = auth()->id();
        $initialBalance = InitialBalance::where('user_id', $userId)
            ->orderBy('effective_date', 'desc')
            ->first();

        return view('balance.create', compact('initialBalance'));
    }

    /**
     * Simpan atau update saldo awal
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        $userId = auth()->id();

        $initialBalance = InitialBalance::updateOrCreate(
            ['user_id' => $userId, 'effective_date' => $request->effective_date],
            [
                'amount' => $request->amount,
                'notes' => $request->notes,
            ]
        );

        // Recalculate semua saldo setelah saldo awal berubah
        self::recalculateAllBalances($userId);

        return redirect()->route('balance.create')
            ->with('success', 'Saldo awal berhasil disimpan. Saldo telah direkalkculasi.');
    }

    /**
     * Tampilkan riwayat saldo awal
     */
    public function history()
    {
        $userId = auth()->id();
        $balances = InitialBalance::where('user_id', $userId)
            ->orderBy('effective_date', 'desc')
            ->get();

        return view('balance.history', compact('balances'));
    }

    /**
     * Hapus saldo awal
     */
    public function destroy($id)
    {
        $balance = InitialBalance::where('user_id', auth()->id())->findOrFail($id);
        $userId = auth()->id();

        $balance->delete();

        // Recalculate setelah saldo awal dihapus
        self::recalculateAllBalances($userId);

        return redirect()->route('balance.history')
            ->with('success', 'Saldo awal berhasil dihapus. Saldo telah direkalkkulasi.');
    }
}
