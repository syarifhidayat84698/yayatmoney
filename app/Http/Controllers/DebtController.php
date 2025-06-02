<?php

namespace App\Http\Controllers;

use App\Models\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'nama_customer' => 'required|string|max:255',
            'nomor_whatsapp' => 'required|string|max:15',
            'due_date' => 'required|date',
            'keterangan' => 'nullable|string',
            'totalbayar' => 'required|numeric',
        ]);

        // Menyimpan hutang
        Input::create([
            'user_id' => Auth::id(),
            'nomor_tagihan' => '',
            'nama_customer' => $request->nama_customer,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'due_date' => $request->due_date,
            'status' => 'Hutang',
            'keterangan' => $request->keterangan,
            'totalbayar' => $request->totalbayar,
        ]);

        return redirect()->route('hutang.index')->with('success', 'Hutang berhasil ditambahkan!');
    }

    // Menampilkan daftar hutang
    public function index()
    {
        $debts = Input::where('user_id', Auth::id())->where('status', 'Hutang')->get(); // Ambil hutang untuk pengguna yang sedang login
        return view('admin.hutangpiutang.hutang.index', compact('debts'));
    }

    // Menampilkan form untuk mengedit hutang
    public function edit($id)
    {
        $debt = Input::findOrFail($id); // Ambil hutang berdasarkan ID
        return view('admin.hutangpiutang.hutang.edit', compact('debt')); // Kembalikan view edit dengan data hutang
    }

    // Memperbarui data hutang
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'nomor_whatsapp' => 'required|string|max:15',
            'due_date' => 'required|date',
            'keterangan' => 'nullable|string',
            'totalbayar' => 'required|numeric',
        ]);

        $debt = Input::findOrFail($id); // Ambil hutang berdasarkan ID

        // Memperbarui hutang
        $debt->update([
            'nama_customer' => $request->nama_customer,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'due_date' => $request->due_date,
            'keterangan' => $request->keterangan,
            'totalbayar' => $request->totalbayar,
        ]);

        return redirect()->route('hutang.index')->with('success', 'Hutang berhasil diperbarui!');
    }

    // Menghapus hutang
    public function destroy($id)
    {
        $debt = Input::findOrFail($id); // Ambil hutang berdasarkan ID
        $debt->delete(); // Hapus hutang

        return redirect()->route('hutang.index')->with('success', 'Hutang berhasil dihapus!');
    }

    public function markAsPaid($id)
    {
        $debt = Input::findOrFail($id);
        $debt->status = 'Terbayar';
        $debt->save();

        return redirect()->route('hutang.index')->with('success', 'Hutang berhasil ditandai sebagai terbayar!');
    }
}
