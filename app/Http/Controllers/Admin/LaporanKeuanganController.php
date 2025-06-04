<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Input;
use App\Models\Barang;
use App\Models\DB;
use Carbon\Carbon;
use PDF;

class LaporanKeuanganController extends Controller
{
    public function cetakDashboard()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Calculate this month's transactions and inputs
        $thisMonthTransactions = Transaction::whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');
        
        $thisMonthInputs = Input::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('totalbayar');

        // Calculate monthly profit/loss
        $monthlyProfit = $thisMonthInputs - $thisMonthTransactions;
        $monthlyProfitPercentage = $thisMonthInputs != 0 ? ($monthlyProfit / $thisMonthInputs) * 100 : 0;

        // Calculate last month's data for comparison
        $lastMonthTransactions = Transaction::whereMonth('transaction_date', Carbon::now()->subMonth()->month)
            ->whereYear('transaction_date', Carbon::now()->subMonth()->year)
            ->sum('amount');
        
        $lastMonthInputs = Input::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('totalbayar');

        $lastMonthProfit = $lastMonthInputs - $lastMonthTransactions;

        // Calculate percentage changes
        $transactionChange = $lastMonthTransactions != 0 
            ? (($thisMonthTransactions - $lastMonthTransactions) / $lastMonthTransactions) * 100 
            : 100;
        
        $inputChange = $lastMonthInputs != 0 
            ? (($thisMonthInputs - $lastMonthInputs) / $lastMonthInputs) * 100 
            : 100;

        $profitChange = $lastMonthProfit != 0 
            ? (($monthlyProfit - $lastMonthProfit) / abs($lastMonthProfit)) * 100 
            : 100;

        // Get monthly data for charts
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $expenses = Transaction::whereMonth('transaction_date', $i)
                ->whereYear('transaction_date', $currentYear)
                ->sum('amount');
            
            $income = Input::whereMonth('created_at', $i)
                ->whereYear('created_at', $currentYear)
                ->sum('totalbayar');
            
            $profit = $income - $expenses;
            
            $monthlyData[] = [
                'month' => Carbon::create()->month($i)->format('F'),
                'income' => (float)$income,
                'expenses' => (float)$expenses,
                'profit' => (float)$profit
            ];
        }

        // Calculate average monthly profit
        $avgMonthlyProfit = collect($monthlyData)->avg('profit');

        // Get total inventory value
        $totalInventoryValue = Barang::sum(DB::raw('harga * stok'));

        $pdf = PDF::loadView('admin.laporankeuangan.pdf.dashboard', compact(
            'thisMonthTransactions',
            'thisMonthInputs',
            'monthlyProfit',
            'monthlyProfitPercentage',
            'transactionChange',
            'inputChange',
            'profitChange',
            'avgMonthlyProfit',
            'totalInventoryValue'
        ));

        return $pdf->stream('laporan-keuangan-dashboard.pdf');
    }
} 