<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    // Metode untuk menampilkan form tambah pengeluaran
    public function create()
    {
        return view('admin.transaksi.pengeluaran.create');
    }

    // Metode untuk menyimpan data pengeluaran
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'sumber' => 'required|string|max:255',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        Transaction::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'type' => 'expense',
            'description' => $request->description,
            'sumber' => $request->sumber,
            'transaction_date' => $request->date,
            'receipt' => $receiptPath,
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    // Metode untuk menampilkan daftar pengeluaran
    public function index()
    {
        $expenses = Transaction::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->get();
        return view('admin.transaksi.pengeluaran.index', compact('expenses'));
    }

    // Metode untuk menampilkan form edit pengeluaran
    public function edit($id)
    {
        $expense = Transaction::findOrFail($id);
        return view('admin.transaksi.pengeluaran.edit', compact('expense'));
    }

    // Metode untuk memperbarui data pengeluaran
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'sumber' => 'required|string|max:255',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $expense = Transaction::findOrFail($id);
        $receiptPath = $expense->receipt;

        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update([
            'amount' => $request->amount,
            'type' => 'expense',
            'description' => $request->description,
            'sumber' => $request->sumber,
            'transaction_date' => $request->date,
            'receipt' => $receiptPath,
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    // Metode untuk menghapus pengeluaran
    public function destroy($id)
    {
        $expense = Transaction::findOrFail($id);
        $expense->delete();

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus!');
    }
}