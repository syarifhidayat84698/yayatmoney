<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\Customer;
use App\Models\PembayaranHutang;
use App\Models\Input as ModelsInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\input;
use Symfony\Component\Console\Input\Input as InputInput;

class HutangController extends Controller
{
    /**
     * Tampilkan daftar hutang user yang sedang login.
     */
    public function index()
    {
        $hutangs = Hutang::with('customer')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('admin.hutangpiutang.hutang.index', compact('hutangs'));
    }

    /**
     * Tampilkan form tambah hutang.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('admin.hutangpiutang.hutang.create', compact('customers'));
    }

    /**
     * Tampilkan riwayat pembayaran hutang (JSON).
     */
    public function history($id)
    {
        $hutang = Hutang::findOrFail($id);
        $pembayaran = $hutang->pembayaran()->latest()->get();
        return response()->json($pembayaran);
    }

    /**
     * Proses pembayaran hutang.
     */
    public function bayar(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $hutang = Hutang::findOrFail($id);

        // Simpan riwayat pembayaran
        PembayaranHutang::create([
            'hutang_id' => $hutang->id,
            'jumlah_bayar' => $request->jumlah_bayar,
            'keterangan' => $request->keterangan,
        ]);

        // Update sisa hutang
        $hutang->sisa_hutang -= $request->jumlah_bayar;

        // Jika lunas, update status
        if ($hutang->sisa_hutang <= 0) {
            $hutang->sisa_hutang = 0;
            $hutang->status = 'Lunas';

            // Update status transaksi terkait jika ada
            $transaksi = ModelsInput::where('nomor_tagihan', $hutang->nomor_tagihan)->first();
            if ($transaksi) {
                $transaksi->status = 'Lunas';
                $transaksi->save();
            }
        }

        // Simpan keterangan terakhir jika ada
        if ($request->filled('keterangan')) {
            $hutang->keterangan = $request->keterangan;
        }

        $hutang->save();

        return redirect()->route('hutangs.index')
            ->with('success', 'Pembayaran hutang berhasil dicatat!');
    }

    /**
     * Tampilkan form edit hutang.
     */
    public function edit($id)
    {
        $hutang = Hutang::where('user_id', Auth::id())->findOrFail($id);
        $customers = Customer::all();
        return view('admin.hutangpiutang.hutang.edit', compact('hutang', 'customers'));
    }

    /**
     * Proses update data hutang.
     */
    public function update(Request $request, $id)
    {
        $hutang = Hutang::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'nomor_tagihan' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'due_date' => 'required|date',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
            'total_tagihan' => 'required|numeric|min:0',
            'total_bayar' => 'required|numeric|min:0|max:' . $request->total_tagihan,
        ]);

        $hutang->update([
            'nomor_tagihan' => $request->nomor_tagihan,
            'customer_id' => $request->customer_id,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'total_tagihan' => $request->total_tagihan,
            'total_bayar' => $request->total_bayar,
        ]);

        return redirect()->route('hutangs.index')
            ->with('success', 'Data hutang berhasil diperbarui!');
    }

    /**
     * Hapus data hutang.
     */
    public function destroy($id)
    {
        $hutang = Hutang::where('user_id', Auth::id())->findOrFail($id);
        $hutang->delete();

        return response()->json(['status' => 'success']);
    }
}
