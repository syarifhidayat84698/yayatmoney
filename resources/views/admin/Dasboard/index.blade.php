@extends('templates.app')

@section('title', ' Dashboard') 
<script src="https://code.highcharts.com/highcharts.js"></script>

<?php
    use App\Models\Transaction;
    use App\Models\Barang;
    use App\Models\Input;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;

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
            'month' => Carbon::create()->month($i)->format('M'),
            'monthFull' => Carbon::create()->month($i)->format('F'),
            'income' => (float)$income,
            'expenses' => (float)$expenses,
            'profit' => (float)$profit
        ];
    }

    // Calculate average monthly profit
    $avgMonthlyProfit = collect($monthlyData)->avg('profit');

    // Get total inventory value
    $totalInventoryValue = Barang::sum(DB::raw('harga * stok'));
?>

@section('content')
<div class="sales-report-area py-5">
    <div class="row">
        @php
            $cards = [
                [
                    'title' => 'Pemasukan Bulan Ini',
                    'value' => 'Rp '. number_format($thisMonthInputs, 2, ',', '.'),
                    'color' => 'success',
                    'change' => $inputChange,
                    'subtitle' => 'Perubahan dari bulan lalu'
                ],
                [
                    'title' => 'Pengeluaran Bulan Ini',
                    'value' =>  'Rp '. number_format($thisMonthTransactions, 2, ',', '.'),
                    'color' => 'danger',
                    'change' => $transactionChange,
                    'subtitle' => 'Perubahan dari bulan lalu'
                ],
                [
                    'title' => 'Laba/Rugi Bulan Ini',
                    'value' => 'Rp '. number_format($monthlyProfit, 2, ',', '.'),
                    'color' => $monthlyProfit >= 0 ? 'success' : 'danger',
                    'change' => $profitChange,
                    'subtitle' => 'Rata-rata laba bulanan: Rp ' . number_format($avgMonthlyProfit, 2, ',', '.')
                ],
                [
                    'title' => 'Total Nilai Inventori',
                    'value' => 'Rp '. number_format($totalInventoryValue, 2, ',', '.'),
                    'color' => 'info',
                    'subtitle' => 'Total nilai barang dalam stok'
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
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
                <div id="monthlyProfitChart" style="height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Report Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Laporan Keuangan - {{ Carbon::now()->format('F Y') }}</h5>
                <a href="{{ route('laporan-keuangan.dashboard.cetak') }}" class="btn btn-light btn-sm" target="_blank">
                    <i class="fas fa-print"></i> Cetak PDF
                </a>
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
                                    <th>Laba/Rugi</th>
                                    <td class="{{ $monthlyProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($monthlyProfit, 2, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Persentase Laba/Rugi</th>
                                    <td class="{{ $monthlyProfitPercentage >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($monthlyProfitPercentage, 1) }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Perbandingan dengan Bulan Lalu</h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Perubahan Pendapatan</th>
                                    <td class="{{ $inputChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($inputChange, 1) }}%
                                    </td>
                                </tr>
                                <tr>
                                    <th>Perubahan Pengeluaran</th>
                                    <td class="{{ $transactionChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($transactionChange, 1) }}%
                                    </td>
                                </tr>
                                <tr>
                                    <th>Perubahan Laba/Rugi</th>
                                    <td class="{{ $profitChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($profitChange, 1) }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Monthly Profit/Loss Chart
    Highcharts.chart('monthlyProfitChart', {
        chart: {
            type: 'column',
            style: {
                fontFamily: 'Arial, sans-serif'
            }
        },
        title: {
            text: 'Laba/Rugi Bulanan ' + {{ $currentYear }},
            style: {
                fontSize: '18px',
                fontWeight: 'bold'
            }
        },
        xAxis: {
            categories: @json(collect($monthlyData)->pluck('month')),
            crosshair: true,
            labels: {
                style: {
                    fontSize: '12px'
                }
            },
            title: {
                text: 'Bulan',
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Jumlah (Rp)',
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold'
                }
            },
            labels: {
                formatter: function() {
                    return 'Rp ' + Highcharts.numberFormat(this.value, 0, ',', '.');
                },
                style: {
                    fontSize: '12px'
                }
            }
        },
        tooltip: {
            shared: true,
            headerFormat: '<b>{point.key} {{ $currentYear }}</b><br/>',
            formatter: function() {
                let tooltip = '';
                this.points.forEach(function(point) {
                    tooltip += '<span style="color:' + point.color + '">●</span> ' + 
                        point.series.name + ': <b>Rp ' + 
                        Highcharts.numberFormat(point.y, 0, ',', '.') + '</b><br/>';
                });
                return tooltip;
            },
            style: {
                fontSize: '12px'
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: false
                }
            },
            spline: {
                dataLabels: {
                    enabled: true,
                    formatter: function() {
                        return 'Rp ' + Highcharts.numberFormat(this.y, 0, ',', '.');
                    },
                    style: {
                        fontSize: '11px',
                        fontWeight: 'bold'
                    }
                }
            }
        },
        legend: {
            itemStyle: {
                fontSize: '12px'
            }
        },
        series: [{
            name: 'Pemasukan',
            data: @json(collect($monthlyData)->pluck('income')),
            color: '#28a745'
        }, {
            name: 'Pengeluaran',
            data: @json(collect($monthlyData)->pluck('expenses')),
            color: '#dc3545'
        }, {
            name: 'Laba/Rugi',
            type: 'spline',
            data: @json(collect($monthlyData)->pluck('profit')),
            color: '#007bff',
            marker: {
                lineWidth: 2,
                lineColor: '#007bff',
                fillColor: 'white'
            }
        }]
    });
</script>
@endsection

