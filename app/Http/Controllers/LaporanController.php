<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\Input;
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
     * Menampilkan daftar hutang
     */
    public function hutang()
    {
        $hutangs = Hutang::with('customer')->get();
        return view('admin.hutangpiutang.hutang.index', compact('hutangs'));
    }
}
