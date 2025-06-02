<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Input;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Hutang;
use Illuminate\Support\Facades\Auth;

class InputController extends Controller
{


    public function index()
    {
        // Ambil semua data transaksi dengan mengaitkan detail faktur
        $inputs = Input::with('details')->orderBy('created_at', 'desc')->paginate(10); // Memuat data input beserta relasi details, dengan pagination 10 per halaman
        foreach ($inputs as $transaction) {
            // Hitung total jumlah pendapatan untuk setiap transaksi berdasarkan jumlah di detail faktur
            $transaction->total_amount = $transaction->details->sum('jumlah');
        }

        return view('admin.input.index', compact('inputs')); // Kirim data ke view
    }

    public function create()
    {
        $barangs = Barang::all();
        $customers = Customer::all(); // Ambil semua data barang

        $countInput = Input::count() + 1;
        $uniq_noTagihan = "INV" . str_pad($countInput, 4, '0', STR_PAD_LEFT);
        return view('admin.input.create', compact('barangs', 'customers', 'uniq_noTagihan'));
    }



    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nomor_tagihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nama_customer' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'keterangan' => 'nullable|string',
            'banyaknya.*' => 'required|numeric|min:1',
            'harga.*' => 'required|numeric|min:1',
            'status' => 'required|string|in:Lunas,Tidak Lunas',
        ]);

        // Data faktur untuk model Input
        $dataFaktur = [
            'user_id' => auth()->id(), // Pastikan user sudah login, menggunakan auth() untuk mengambil ID user yang login
            'nomor_tagihan' => $request->input('nomor_tagihan'),
            'nama_customer' => $request->input('nama_customer'),
            'nomor_whatsapp' => $request->input('telepon'),
            'keterangan' => $request->input('keterangan'),
            'due_date' => $request->input('tanggal'),
            'totalbayar' => $request->input('totalbayar'),
            'status' => $request->input('status'),
        ];


        // Menyimpan faktur
        $faktur = Input::create($dataFaktur);

        if ($request->input('status') == 'Tidak Lunas') {
            $kuragepiran = (int) ($request->input('total') - $faktur->totalbayar);
            Hutang::create([
                'user_id' => auth()->id(),
                'nomor_tagihan' => $request->input('nomor_tagihan'),
                'customer_id' => Customer::where('nama_customer', $request->input('nama_customer'))->firstOrFail()->id,
                'due_date' => $request->input('tanggal'),
                'status' => "Hutang",
                'keterangan' => $request->input('keterangan'),
                'total_tagihan' => $request->input('total'),
                'sisa_hutang' => $kuragepiran,
                'total_hutang' => $kuragepiran,
                'transaction_id' => $faktur->id
            ]);
        }
        // Menyimpan detail barang
        foreach ($request->input('no') as $index => $no) {
            $nama_barang_id = $request->input('nama_barang')[$index];
            $banyaknya = $request->input('banyaknya')[$index];

            // Kurangi stok barang
            $barang = Barang::find($nama_barang_id);
            if ($barang) {
                $barang->stok -= $banyaknya;
                $barang->save();
            }

            $faktur->details()->create([
                'no' => $no,
                'banyaknya' => $banyaknya,
                'nama_barang' => $barang->nama_barang,
                'harga' => $request->input('harga')[$index],
                'jumlah' => $request->input('jumlah')[$index],
            ]);
        }
        // Transaction::create([
        //     'user_id' => auth()->id(), // Pastikan user sudah login, menggunakan auth() untuk mengambil ID user yang login
        //     'nomor_tagihan' => $request->input('nomor_tagihan'),
        //     'transaction_date' => $request->input('tanggal'),
        //     'amount' => $faktur->details->sum('jumlah'),
        //     'nama_customer' => $request->input('nama_customer'),
        // ]);



        // Redirect dengan pesan sukses
        return redirect()->route('input')->with('success', 'Faktur berhasil disimpan!');
    }

    // Menghapus transaksi
    public function destroy($id)
    {
        // Temukan transaksi berdasarkan ID
        $transaction = Input::findOrFail($id);

        // Hapus semua detail yang terkait dengan transaksi ini
        $transaction->details()->delete();

        $transaction->hutang()->delete();

        // Hapus transaksi utama (faktur)
        $transaction->delete();

       
        // Return response sukses
        return response()->json(['status' => 'success']);
    }

    // Menampilkan form edit faktur
    public function edit($id)
    {
        $transaction = Input::with('details')->findOrFail($id);
        return view('admin.input.edit', compact('transaction'));
    }

    // Update data faktur
    public function update(Request $request, $id)
    {
        $transaction = Input::findOrFail($id);

        // Validasi input
        $request->validate([
            'nomor_tagihan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nama_customer' => 'required|string|max:255',
            'nomor_wa' => 'required|string|max:15',
            'keterangan' => 'nullable|string',
            'banyaknya.*' => 'required|numeric|min:1',
            'status' => 'required|string|in:Lunas,Tidak Lunas',
            'totalbayar' => 'required|numeric|min:1',
            'harga.*' => 'required|numeric|min:1',
        ]);

        // Update data faktur
        $transaction->update([
            'nomor_tagihan' => $request->input('nomor_tagihan'),
            'nama_customer' => $request->input('nama_customer'),
            'nomor_whatsapp' => $request->input('nomor_wa'),
            'keterangan' => $request->input('keterangan'),
            'status' => $request->input('status'),
            'due_date' => $request->input('tanggal'),
        ]);

        // Hapus semua detail yang ada dan tambah yang baru
        $transaction->details()->delete();
        foreach ($request->input('no') as $index => $no) {
            $transaction->details()->create([
                'no' => $no,
                'banyaknya' => $request->input('banyaknya')[$index],
                'nama_barang' => $request->input('nama_barang')[$index],
                'harga' => $request->input('harga')[$index],
                'jumlah' => $request->input('banyaknya')[$index] * $request->input('harga')[$index],
            ]);
        }

        return redirect()->route('input')->with('success', 'Faktur berhasil diperbarui!');
    }
    public function createNota($id)
    {
        $transaction = Input::with('details')->findOrFail($id);
        // dd($transaction);
        if (!$transaction) {
            return redirect()->back()->with('error', 'Faktur tidak ditemukan');
        }
        $total = $transaction->details->sum('jumlah');
        return view('admin.input.createnota', compact('transaction', 'total'));
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = Input::findOrFail($id);

        // Validasi input
        $request->validate([
            'status' => 'required|string|in:Lunas,Tidak Lunas',
        ]);


        // Update status
        $transaction->update([
            'status' => $request->input('status'),
        ]);


        return redirect()->route('input')->with('success', 'Status berhasil diperbarui!');
    }
}
