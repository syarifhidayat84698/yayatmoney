<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DebtController extends Controller
{
    // Menampilkan form untuk menambah hutang
    public function create()
    {
        return view('admin.hutangpiutang.hutang.create'); // Sesuaikan dengan path view Anda
    }

    // Menyimpan data hutang
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'pemberiPinjaman' => 'required|string|max:255',
            'jumlahHutang' => 'required|numeric',
            'tanggalJatuhTempo' => 'required|date',
            'pengingat' => 'nullable|date',
            'nota' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menyimpan file jika ada
        $receiptPath = null;
        if ($request->hasFile('nota')) {
            $receiptPath = $request->file('nota')->store('receipts', 'public');
        }

        // Menyimpan hutang
        Debt::create([
            'user_id' => Auth::id(),
            'amount' => $request->jumlahHutang,
            'creditor' => $request->pemberiPinjaman,
            'due_date' => $request->tanggalJatuhTempo,
            'reminder_date' => $request->pengingat,
            'receipt' => $receiptPath,
        ]);

        return redirect()->route('hutang.index')->with('success', 'Hutang berhasil ditambahkan!');
    }

    // Menampilkan daftar hutang
    public function index()
    {
        $debts = Debt::where('user_id', Auth::id())->get(); // Ambil hutang untuk pengguna yang sedang login
        return view('admin.hutangpiutang.hutang.index', compact('debts'));
    }

    // Menampilkan form untuk mengedit hutang
    public function edit($id)
    {
        $debt = Debt::findOrFail($id); // Ambil hutang berdasarkan ID
        return view('admin.hutangpiutang.hutang.edit', compact('debt')); // Kembalikan view edit dengan data hutang
    }

    // Memperbarui data hutang
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'pemberiPinjaman' => 'required|string|max:255',
            'jumlahHutang' => 'required|numeric',
            'tanggalJatuhTempo' => 'required|date',
            'pengingat' => 'nullable|date',
            'nota' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $debt = Debt::findOrFail($id); // Ambil hutang berdasarkan ID

        // Menyimpan file jika ada
        $receiptPath = $debt->receipt; // Simpan path lama
        if ($request->hasFile('nota')) {
            // Jika ada file baru, simpan dan hapus file lama jika perlu
            if ($receiptPath) {
                Storage::disk('public')->delete($receiptPath);
            }
            $receiptPath = $request->file('nota')->store('receipts', 'public');
        }

        // Memperbarui hutang
        $debt->update([
            'amount' => $request->jumlahHutang,
            'creditor' => $request->pemberiPinjaman,
            'due_date' => $request->tanggalJatuhTempo,
            'reminder_date' => $request->pengingat,
            'receipt' => $receiptPath,
            'updated_at' => now(),
        ]);

        return redirect()->route('hutang.index')->with('success', 'Hutang berhasil diperbarui!');
    }

    // Menghapus hutang
    public function destroy($id)
    {
        $debt = Debt::findOrFail($id); // Ambil hutang berdasarkan ID
        if ($debt->receipt) {
            Storage::disk('public')->delete($debt->receipt); // Hapus file nota jika ada
        }
        $debt->delete(); // Hapus hutang

        return redirect()->route('hutang.index')->with('success', 'Hutang berhasil dihapus!');
    }

    public function markAsPaid($id)
    {
        $debt = Debt::findOrFail($id);
        $debt->status = 'Terbayar';
        $debt->save();

        return redirect()->route('hutang.index')->with('success', 'Hutang berhasil ditandai sebagai terbayar!');
    }
}