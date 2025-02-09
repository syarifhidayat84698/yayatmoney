<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->input('dateRange');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $query = Transaction::where('user_id', Auth::id());

        if ($dateRange === 'daily') {
            $query->whereDate('transaction_date', '>=', $startDate)
                  ->whereDate('transaction_date', '<=', $endDate);
        } elseif ($dateRange === 'weekly') {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        } elseif ($dateRange === 'monthly') {
            $query->whereMonth('transaction_date', '=', date('m', strtotime($startDate)))
                  ->whereYear('transaction_date', '=', date('Y', strtotime($startDate)));
        }

        $transactions = $query->get();

        return view('admin.laporankeuangan.lihatlaporan.index', compact('transactions', 'startDate', 'endDate', 'dateRange'));
    }
}