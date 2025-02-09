<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function ecommerceDashboard()
    {
        return view('index2'); // Ganti dengan nama view Anda untuk Ecommerce Dashboard
    }

    public function icoDashboard()
    {
        return view('ico-dashboard'); // Return the ICO dashboard view
    }

    public function barChart()
    {
        return view('admin.barchart');
    }

    public function lineChart()
    {
        return view('admin.linechart');
    }

    public function pieChart()
    {
        return view('admin.piechart');
    }
}
