@extends('templates.app')

@section('title', ' Dashboard') 
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/heatmap.js"></script>
<script src="https://code.highcharts.com/modules/treemap.js"></script>

<?php
    use App\Models\Transaction;
    use App\Models\Barang;
    use App\Models\Input;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;

    $totalAmount = Transaction::sum('amount');
    $jumlahbarang = Barang::count();
    $totalbayar = Input::sum('totalbayar') ?? 0; 
    $jumlahtransaksi = Input::count();

    // Get current month and year
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;

    // Calculate this month's transactions and inputs
    $thisMonthTransactions = Transaction::whereMonth('transaction_date', $currentMonth)
        ->whereYear('transaction_date', $currentYear)
        ->sum('amount');
    
    $thisMonthInputs = Input::whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->sum('totalbayar');

    // Calculate percentage changes
    $lastMonthTransactions = Transaction::whereMonth('transaction_date', Carbon::now()->subMonth()->month)
        ->whereYear('transaction_date', Carbon::now()->subMonth()->year)
        ->sum('amount');
    
    $lastMonthInputs = Input::whereMonth('created_at', Carbon::now()->subMonth()->month)
        ->whereYear('created_at', Carbon::now()->subMonth()->year)
        ->sum('totalbayar');

    $transactionChange = $lastMonthTransactions != 0 
        ? (($thisMonthTransactions - $lastMonthTransactions) / $lastMonthTransactions) * 100 
        : 100;
    
    $inputChange = $lastMonthInputs != 0 
        ? (($thisMonthInputs - $lastMonthInputs) / $lastMonthInputs) * 100 
        : 100;

    // Get monthly data for charts
    $transactionsPerMonth = Transaction::select(
        DB::raw('MONTH(transaction_date) as month'),
        DB::raw('SUM(amount) as total_amount'),
        DB::raw('COUNT(*) as transaction_count')
    )
    ->whereYear('transaction_date', date('Y'))
    ->groupBy('month')
    ->get();

    $inputsPerMonth = Input::select(
        DB::raw('MONTH(created_at) as month'),
        DB::raw('SUM(totalbayar) as total_bayar'),
        DB::raw('COUNT(*) as input_count')
    )
    ->whereYear('created_at', date('Y'))
    ->groupBy('month')
    ->get();

    // Calculate daily trends for the current month
    $dailyTrends = Transaction::select(
        DB::raw('DATE(transaction_date) as date'),
        DB::raw('SUM(amount) as daily_amount'),
        DB::raw('COUNT(*) as transaction_count')
    )
    ->whereMonth('transaction_date', $currentMonth)
    ->whereYear('transaction_date', $currentYear)
    ->groupBy('date')
    ->get();

    // Calculate average transaction value
    $avgTransactionValue = $jumlahtransaksi > 0 ? $totalAmount / $jumlahtransaksi : 0;

    // Get quarterly data
    $quarterlyData = Transaction::select(
        DB::raw('QUARTER(transaction_date) as quarter'),
        DB::raw('SUM(amount) as total_amount')
    )
    ->whereYear('transaction_date', $currentYear)
    ->groupBy('quarter')
    ->get();

    $labels = collect(range(1, 12))->map(function ($month) {
        return Carbon::create()->month($month)->format('F');
    });

    // Prepare data for heatmap (real data)
    $heatmapData = [];
    $firstDayOfMonth = Carbon::now()->startOfMonth();
    $lastDayOfMonth = Carbon::now()->endOfMonth();
    $transactions = Transaction::whereBetween('transaction_date', [$firstDayOfMonth, $lastDayOfMonth])->get();

    $heatmapMatrix = [];
    foreach ($transactions as $trx) {
        $date = Carbon::parse($trx->transaction_date);
        $dayOfWeek = $date->dayOfWeek; // 0 = Minggu, 6 = Sabtu
        $weekOfMonth = intval(floor(($date->day - 1) / 7)); // 0 = Minggu 1, dst

        if (!isset($heatmapMatrix[$dayOfWeek][$weekOfMonth])) {
            $heatmapMatrix[$dayOfWeek][$weekOfMonth] = 0;
        }
        $heatmapMatrix[$dayOfWeek][$weekOfMonth]++;
    }

    // Format data for Highcharts
    for ($dayOfWeek = 0; $dayOfWeek <= 6; $dayOfWeek++) {
        for ($weekOfMonth = 0; $weekOfMonth <= 3; $weekOfMonth++) {
            $heatmapData[] = [
                $dayOfWeek,
                $weekOfMonth,
                $heatmapMatrix[$dayOfWeek][$weekOfMonth] ?? 0
            ];
        }
    }

    // Year over year comparison
    $lastYearTotal = Transaction::whereYear('transaction_date', $currentYear - 1)->sum('amount');
    $yearOverYearChange = $lastYearTotal != 0 
        ? (($totalAmount - $lastYearTotal) / $lastYearTotal) * 100 
        : 100;
?>

@section('content')
<div class="sales-report-area py-5">
    <div class="row">
        @php
            $cards = [
                [
                    'title' => 'Pendapatan',
                    'value' => 'Rp '. number_format($totalbayar, 2, ',', '.'),
                    'color' => 'success',
                    'change' => $inputChange,
                    'subtitle' => 'Rata-rata per transaksi: Rp ' . number_format($avgTransactionValue, 2, ',', '.')
                ],
                [
                    'title' => 'Pengeluaran',
                    'value' =>  'Rp '. number_format($totalAmount, 2, ',', '.'),
                    'color' => 'danger',
                    'change' => $transactionChange,
                    'subtitle' => 'YoY Change: ' . number_format($yearOverYearChange, 1) . '%'
                ],
                [
                    'title' => 'Total Barang',
                    'value' => $jumlahbarang,
                    'color' => 'info',
                    'subtitle' => 'Inventory Status'
                ],
                [
                    'title' => 'Jumlah Data Transaksi',
                    'value' => $jumlahtransaksi,
                    'color' => 'warning',
                    'subtitle' => 'Total Transactions'
                ],
            ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                    <h5 class="card-title text-{{ $card['color'] }}">{{ $card['title'] }}</h5>
                        <p class="text-muted small mb-2">{{ $card['subtitle'] }}</p>
                    <h2 class="fw-bold text-dark">{{ $card['value'] }}</h2>
                    </div>
                    @if(isset($card['change']))
                        <div class="mt-2">
                            <small class="text-{{ $card['change'] >= 0 ? 'success' : 'danger' }}">
                                <i class="fas fa-{{ $card['change'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ number_format(abs($card['change']), 1) }}% dari bulan lalu
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-body">
        <div id="pieKeuangan" style="height: 400px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-body">
        <canvas id="splineChart" height="400"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tren Harian - {{ Carbon::now()->format('F Y') }}</h5>
            </div>
            <div class="card-body">
                <div id="dailyTrendChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Analisis Kuartal</h5>
            </div>
            <div class="card-body">
                <div id="quarterlyChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Activity Heatmap -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Peta Panas Aktivitas Transaksi</h5>
            </div>
            <div class="card-body">
                <div id="heatmapContainer" style="height: 200px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Report Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Laporan Keuangan - {{ Carbon::now()->format('F Y') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Ringkasan Bulan Ini</h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Total Pendapatan</th>
                                    <td>Rp {{ number_format($thisMonthInputs, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Total Pengeluaran</th>
                                    <td>Rp {{ number_format($thisMonthTransactions, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Selisih</th>
                                    <td class="{{ $thisMonthInputs - $thisMonthTransactions >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($thisMonthInputs - $thisMonthTransactions, 2, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Rata-rata Transaksi</th>
                                    <td>Rp {{ number_format($avgTransactionValue, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>YoY Perubahan</th>
                                    <td class="{{ $yearOverYearChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($yearOverYearChange, 1) }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Analisis Tren</h6>
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                        <th>Transaksi</th>
                                        <th>Perubahan</th>
                                        <th>Trend</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach($quarterlyData as $quarter)
                                <tr>
                                        <td>Q{{ $quarter->quarter }}</td>
                                        <td>Rp {{ number_format($quarter->total_amount, 2, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $prevQuarter = $loop->index > 0 ? $quarterlyData[$loop->index - 1]->total_amount : null;
                                                $change = $prevQuarter ? (($quarter->total_amount - $prevQuarter) / $prevQuarter) * 100 : 0;
                                            @endphp
                                            <span class="{{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($change, 1) }}%
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-{{ $change >= 0 ? 'arrow-up text-success' : 'arrow-down text-danger' }}"></i>
                                        </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pie Chart
    Highcharts.chart('pieKeuangan', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Perbandingan Pendapatan dan Pengeluaran'
        },
        tooltip: {
            pointFormat: '<b>{point.percentage:.1f}%</b> (Rp {point.y:,.0f})'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            name: 'Jumlah',
            colorByPoint: true,
            data: [
                { name: 'Pendapatan', y: <?= $totalbayar ?? 0 ?>, color: '#28a745' },
                { name: 'Pengeluaran', y: <?= $totalAmount ?? 0 ?>, color: '#dc3545' }
            ]
        }]
    });

    // Spline Chart
    const ctx = document.getElementById('splineChart').getContext('2d');
    const splineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Total Transaksi',
                    data: @json($transactionsPerMonth->pluck('total_amount')),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.4,
                    spanGaps: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Total Pembayaran Input',
                    data: @json($inputsPerMonth->pluck('total_bayar')),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true,
                    tension: 0.4,
                    spanGaps: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik Spline Transaksi & Input per Bulan ({{ date("Y") }})',
                    font: {
                        size: 16,
                        weight: 'bold'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString();
                            return label;
                        }
                    }
                },
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0,
                    }
                }
            }
        }
    });

    // Daily Trend Chart
    Highcharts.chart('dailyTrendChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Tren Transaksi Harian'
        },
        xAxis: {
            categories: @json($dailyTrends->pluck('date')),
            crosshair: true
        },
        yAxis: [{
            title: {
                text: 'Jumlah (Rp)'
            }
        }, {
            title: {
                text: 'Jumlah Transaksi'
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        series: [{
            name: 'Total Transaksi',
            data: @json($dailyTrends->pluck('daily_amount')),
            yAxis: 0
        }, {
            name: 'Jumlah Transaksi',
            data: @json($dailyTrends->pluck('transaction_count')),
            yAxis: 1,
            type: 'spline'
        }]
    });

    // Quarterly Chart
    Highcharts.chart('quarterlyChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Analisis Kuartal'
        },
        xAxis: {
            categories: ['Q1', 'Q2', 'Q3', 'Q4']
        },
        yAxis: {
            title: {
                text: 'Total (Rp)'
            }
        },
        series: [{
            name: 'Total Transaksi',
            data: @json($quarterlyData->pluck('total_amount'))
        }]
    });

    // Heatmap
    Highcharts.chart('heatmapContainer', {
        chart: {
            type: 'heatmap'
        },
        title: {
            text: 'Peta Panas Aktivitas Transaksi'
        },
        xAxis: {
            categories: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
        },
        yAxis: {
            categories: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
            title: null
        },
        colorAxis: {
            min: 0,
            minColor: '#FFFFFF',
            maxColor: Highcharts.getOptions().colors[0]
        },
        legend: {
            align: 'right',
            layout: 'vertical',
            margin: 0,
            verticalAlign: 'top',
            y: 25,
            symbolHeight: 280
        },
        series: [{
            name: 'Jumlah Transaksi',
            borderWidth: 1,
            data: @json($heatmapData),
            dataLabels: {
                enabled: true,
                color: '#000000'
            }
        }]
    });
</script>
@endsection
