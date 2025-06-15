<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Metode untuk menampilkan form tambah pemasukan
    public function create()
    {
        return view('admin.transaksi.pemasukan.create');
    }

    // Metode untuk menyimpan data pemasukan
    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menyimpan file jika ada
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        // Menyimpan transaksi
        Transaction::create([
            'user_id' => Auth::id(),
            'nama_toko' => $request->nama_toko,
            'alamat' => $request->alamat,
            'amount' => $request->amount,
            'type' => 'outcome',
            'transaction_date' => $request->date,
            'receipt' => $receiptPath,
        ]);

        return redirect()->route('pengeluaran')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    // Metode untuk menampilkan daftar transaksi
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->where('type', 'outcome')
            ->orderBy('transaction_date', 'desc')
            ->paginate(10);
        return view('admin.transaksi.pemasukan.index', compact('transactions'));
    }

    // Metode untuk menampilkan form edit transaksi
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('admin.transaksi.pemasukan.edit', compact('transaction'));
    }

    // Metode untuk memperbarui data transaksi
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $transaction = Transaction::findOrFail($id);

        // Menyimpan file jika ada
        $receiptPath = $transaction->receipt;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        // Memperbarui transaksi
        $transaction->update([
            'nama_toko' => $request->nama_toko,
            'alamat' => $request->alamat,
            'amount' => $request->amount,
            'type' => 'outcome',
            'transaction_date' => $request->date,
            'receipt' => $receiptPath,
        ]);

        return redirect()->route('pengeluaran')->with('success', 'Transaksi berhasil diperbarui!');
    }

    // Metode untuk menghapus transaksi
    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        // Hapus transaksi
        $transaction->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil dihapus'
        ], 200);
    }
}
