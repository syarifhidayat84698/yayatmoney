<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\Input;
use App\Models\Pengeluaran;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan data hutang dengan filter nama, status, dan tanggal
     */
    public function dataHutang(Request $request)
    {
        $hutangs = Hutang::with('customer');

        // Filter berdasarkan nama customer
        if ($request->filled('search')) {
            $hutangs = $hutangs->whereHas('customer', function ($query) use ($request) {
                $query->where('nama_customer', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $hutangs = $hutangs->where('status', $request->status);
        }

        // Filter berdasarkan rentang tanggal due_date
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $hutangs = $hutangs->whereBetween('due_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $hutangs = $hutangs->where('due_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $hutangs = $hutangs->where('due_date', '<=', $request->date_to);
        }

        $hutangs = $hutangs->get();

        return view('admin.laporankeuangan.LaporanDataHutang', compact('hutangs'));
    }

    /**
     * Menampilkan laporan pemasukan dengan filter
     */
    public function pemasukan(Request $request)
    {
        $inputs = Input::query();

        // Filter berdasarkan nama customer
        if ($request->filled('search')) {
            $inputs->where('nama_customer', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $inputs->whereBetween('due_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $inputs->where('due_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $inputs->where('due_date', '<=', $request->date_to);
        }

        $inputs = $inputs->get();

        return view('admin.laporankeuangan.LaporanPemasukan', compact('inputs'));
    }

    /**
     * Menampilkan laporan pengeluaran dengan filter
     */
    public function pengeluaran(Request $request)
    {
        $query = Transaction::query()->where('type', 'outcome');

        // Filter by nama_toko
        if ($request->filled('search')) {
            $query->where('nama_toko', 'like', '%' . $request->search . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        return view('admin.laporankeuangan.LaporanPengeluaran', compact('transactions'));
    }

    /**
     * Ekspor laporan data hutang ke PDF dengan filter
     */
    public function exportHutangPDF(Request $request)
    {
        $hutangs = Hutang::with('customer');

        if ($request->filled('search')) {
            $hutangs = $hutangs->whereHas('customer', function ($query) use ($request) {
                $query->where('nama_customer', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $hutangs = $hutangs->where('status', $request->status);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $hutangs = $hutangs->whereBetween('due_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $hutangs = $hutangs->where('due_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $hutangs = $hutangs->where('due_date', '<=', $request->date_to);
        }

        $hutangs = $hutangs->get();

        $pdf = FacadePdf::loadView('admin.laporankeuangan.pdf.hutang', compact('hutangs'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'isPhpEnabled' => true,
            'dpi' => 150,
            'debugCss' => false
        ]);
        
        return $pdf->stream('laporan_hutang.pdf');
    }

    /**
     * Ekspor laporan pemasukan ke PDF
     */
    public function exportPemasukanPDF(Request $request)
    {
        $inputs = Input::query();

        if ($request->filled('search')) {
            $inputs->where('nama_customer', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $inputs->whereBetween('due_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $inputs->where('due_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $inputs->where('due_date', '<=', $request->date_to);
        }

        $inputs = $inputs->get();

        $pdf = FacadePdf::loadView('admin.laporankeuangan.pdf.pemasukan', compact('inputs'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'isPhpEnabled' => true,
            'dpi' => 150,
            'debugCss' => false
        ]);
        
        return $pdf->stream('laporan_pemasukan.pdf');
    }

    /**
     * Ekspor laporan pengeluaran ke PDF
     */
    public function exportPengeluaranPDF(Request $request)
    {
        $query = Transaction::query()->where('type', 'outcome');

        // Filter by description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // Filter by category (sumber)
        if ($request->filled('kategori')) {
            $query->where('sumber', '=', $request->kategori);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $pengeluarans = $query->orderBy('transaction_date', 'desc')->get();

        $pdf = FacadePdf::loadView('admin.laporankeuangan.pdf.pengeluaran', compact('pengeluarans'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('laporan-pengeluaran.pdf');
    }

    /**
     * Menampilkan daftar hutang
     */
    public function hutang()
    {
        $hutangs = Hutang::with('customer')->get();
        return view('admin.hutangpiutang.hutang.index', compact('hutangs'));
    }
}
