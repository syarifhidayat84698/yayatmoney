<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreditController extends Controller
{
    // Menampilkan form untuk menambah piutang
    public function create()
    {
        return view('admin.hutangpiutang.piutang.create');
    }

    // Menyimpan data piutang
    public function store(Request $request)
    {
        $request->validate([
            'pihakBerhutang' => 'required|string|max:255',
            'jumlahPiutang' => 'required|numeric',
            'tanggalJatuhTempo' => 'required|date',
            'pengingat' => 'nullable|date',
            'nota' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string|in:Belum Terbayar,Terbayar', // Validasi status
        ]);

        $receiptPath = null;
        if ($request->hasFile('nota')) {
            $receiptPath = $request->file('nota')->store('receipts', 'public');
        }

        Credit::create([
            'user_id' => Auth::id(),
            'amount' => $request->jumlahPiutang,
            'debtor' => $request->pihakBerhutang,
            'due_date' => $request->tanggalJatuhTempo,
            'reminder_date' => $request->pengingat,
            'receipt' => $receiptPath,
            'status' => $request->status, // Menyimpan status
        ]);

        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil ditambahkan!');
    }

    // Menampilkan daftar piutang
    public function index()
    {
        $credits = Credit::where('user_id', Auth::id())->get();
        return view('admin.hutangpiutang.piutang.index', compact('credits'));
    }

    // Menampilkan form untuk mengedit piutang
    public function edit($id)
    {
        $credit = Credit::findOrFail($id);
        return view('admin.hutangpiutang.piutang.edit', compact('credit'));
    }

    // Memperbarui data piutang
    public function update(Request $request, $id)
    {
        $request->validate([
            'pihakBerhutang' => 'required|string|max:255',
            'jumlahPiutang' => 'required|numeric',
            'tanggalJatuhTempo' => 'required|date',
            'pengingat' => 'nullable|date',
            'nota' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string|in:Belum Terbayar,Terbayar', // Validasi status
        ]);

        $credit = Credit::findOrFail($id);
        $receiptPath = $credit->receipt;

        if ($request->hasFile('nota')) {
            // Hapus file lama jika ada
            if ($receiptPath) {
                Storage::disk('public')->delete($receiptPath);
            }
            $receiptPath = $request->file('nota')->store('receipts', 'public');
        }

        $credit->update([
            'amount' => $request->jumlahPiutang,
            'debtor' => $request->pihakBerhutang,
            'due_date' => $request->tanggalJatuhTempo,
            'reminder_date' => $request->pengingat,
            'receipt' => $receiptPath,
            'status' => $request->status, // Memperbarui status
        ]);

        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil diperbarui!');
    }

    // Menghapus piutang
    public function destroy($id)
    {
        $credit = Credit::findOrFail($id);
        if ($credit->receipt) {
            Storage::disk('public')->delete($credit->receipt);
        }
        $credit->delete();

        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil dihapus!');
    }

    // Menandai piutang sebagai terbayar
    public function markAsPaid($id)
    {
        $credit = Credit::findOrFail($id);
        $credit->status = 'Terbayar';
        $credit->save();

        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil ditandai sebagai terbayar!');
    }
}
