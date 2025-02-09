<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreditController extends Controller
{
    public function create()
    {
        return view('admin.hutangpiutang.piutang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pihakBerhutang' => 'required|string|max:255',
            'jumlahPiutang' => 'required|numeric',
            'tanggalJatuhTempo' => 'required|date',
            'pengingat' => 'nullable|date',
            'nota' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        ]);

        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil ditambahkan!');
    }

    public function index()
    {
        $credits = Credit::where('user_id', Auth::id())->get();
        return view('admin.hutangpiutang.piutang.index', compact('credits'));
    }

    public function edit($id)
    {
        $credit = Credit::findOrFail($id);
        return view('admin.hutangpiutang.piutang.edit', compact('credit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pihakBerhutang' => 'required|string|max:255',
            'jumlahPiutang' => 'required|numeric',
            'tanggalJatuhTempo' => 'required|date',
            'pengingat' => 'nullable|date',
            'nota' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        ]);

        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $credit = Credit::findOrFail($id);
        if ($credit->receipt) {
            Storage::disk('public')->delete($credit->receipt);
        }
        $credit->delete();

        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil dihapus!');
    }
}