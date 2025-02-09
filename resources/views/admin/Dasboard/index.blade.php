@extends('templates.app')

@section('title', ' Dashboard') ')
<script src="https://code.highcharts.com/highcharts.js"></script>

@section('content')
{{-- Konten Apapun Selalu diisi disini --}}

<div class="sales-report-area sales-style-two">
    <div class="row">
        <!-- Card Pendapatan -->
        <div class="col-xl-3 col-ml-3 col-md-6 mt-5">
            <div class="single-report">
                <div class="s-sale-inner pt--30 mb-3">
                    <div class="s-report-title d-flex justify-content-between">
                        <h4 class="header-title mb-0">Pendapatan</h4>
                        <select class="custome-select border-0 pr-3">
                            <option selected="">Last 7 Days</option>
                            <option value="0">Last 7 Days</option>
                        </select>
                    </div>
                    <div class="total-data text-center" style="margin-top: 20px;">
                        <h1 style="font-size: 2.5rem; font-weight: bold; color: #333;">$25,000</h1>
                    </div>
                </div>
                <canvas id="coin_sales4" height="100"></canvas>
            </div>
        </div>

        <!-- Card Pengeluaran -->
        <div class="col-xl-3 col-ml-3 col-md-6 mt-5">
            <div class="single-report">
                <div class="s-sale-inner pt--30 mb-3">
                    <div class="s-report-title d-flex justify-content-between">
                        <h4 class="header-title mb-0">Pengeluaran</h4>
                        <select class="custome-select border-0 pr-3">
                            <option selected="">Last 7 Days</option>
                            <option value="0">Last 7 Days</option>
                        </select>
                    </div>
                    <div class="total-data text-center" style="margin-top: 20px;">
                        <h1 style="font-size: 2.5rem; font-weight: bold; color: #333;">$15,000</h1>
                    </div>
                </div>
                <canvas id="coin_sales5" height="100"></canvas>
            </div>
        </div>

        <!-- Card Total Customers -->
        <div class="col-xl-3 col-ml-3 col-md-6 mt-5">
            <div class="single-report">
                <div class="s-sale-inner pt--30 mb-3">
                    <div class="s-report-title d-flex justify-content-between">
                        <h4 class="header-title mb-0">Total Hutang</h4>
                        <select class="custome-select border-0 pr-3">
                            <option selected="">Last 7 Days</option>
                            <option value="0">Last 7 Days</option>
                        </select>
                    </div>
                    <div class="total-data text-center" style="margin-top: 20px;">
                        <h1 style="font-size: 2.5rem; font-weight: bold; color: #333;">$2,000</h1>
                    </div>
                </div>
                <canvas id="coin_sales6" height="100"></canvas>
            </div>
        </div>

        <!-- Card Total Hutang -->
        <div class="col-xl-3 col-ml-3 col-md-6 mt-5">
            <div class="single-report">
                <div class="s-sale-inner pt--30 mb-3">
                    <div class="s-report-title d-flex justify-content-between">
                        <h4 class="header-title mb-0">Total Piutang</h4>
                        <select class="custome-select border-0 pr-3">
                            <option selected="">Last 7 Days</option>
                            <option value="0">Last 7 Days</option>
                        </select>
                    </div>
                    <div class="total-data text-center" style="margin-top: 20px;">
                        <h1 style="font-size: 2.5rem; font-weight: bold; color: #333;">$5,000</h1>
                    </div>
                </div>
                <canvas id="coin_sales7" height="100"></canvas>
            </div>
        </div>
    </div>
</div>



{{-- piechart --}}
<div id="sobri" style="min-width: 310px; height: 400px; " class="mt-3" ></div>
<script>
Highcharts.chart('sobri', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Egg Yolk Composition'
    },
    tooltip: {
        valueSuffix: '%'
    },
    subtitle: {
        text:
        'Source:<a href="https://www.mdpi.com/2072-6643/11/3/684/htm" target="_default">MDPI</a>'
    },
    plotOptions: {
        series: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
                enabled: true,
                distance: 20
            }, {
                enabled: true,
                distance: -40,
                format: '{point.percentage:.1f}%',
                style: {
                    fontSize: '1.2em',
                    textOutline: 'none',
                    opacity: 0.7
                },
                filter: {
                    operator: '>',
                    property: 'percentage',
                    value: 10
                }
            }]
        }
    },
    series: [
        {
            name: 'Percentage',
            colorByPoint: true,
            data: [
                {
                    name: 'Water',
                    y: 55.02
                },
                {
                    name: 'Fat',
                    sliced: true,
                    selected: true,
                    y: 26.71
                },
                {
                    name: 'Carbohydrates',
                    y: 1.09
                },
                {
                    name: 'Protein',
                    y: 15.5
                },
                {
                    name: 'Ash',
                    y: 1.68
                }
            ]
        }
    ]
});

</script>


{{-- linechart --}}
<div id="faizin" style="min-width: 310px; height: 400px; " class="mt-3" ></div>

<script>
    // Data retrieved from https://www.vikjavev.no/ver/snjomengd

Highcharts.chart('faizin', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Snow depth at Vikjafjellet, Norway',
        align: 'left'
    },
    subtitle: {
        text: 'Irregular time data in Highcharts JS',
        align: 'left'
    },
    xAxis: {
        type: 'datetime',
        dateTimeLabelFormats: {
            // don't display the year
            month: '%e. %b',
            year: '%b'
        },
        title: {
            text: 'Date'
        }
    },
    yAxis: {
        title: {
            text: 'Snow depth (m)'
        },
        min: 0
    },
    tooltip: {
        headerFormat: '<b>{series.name}</b><br>',
        pointFormat: '{point.x:%e. %b}: {point.y:.2f} m'
    },

    plotOptions: {
        series: {
            marker: {
                symbol: 'circle',
                fillColor: '#FFFFFF',
                enabled: true,
                radius: 2.5,
                lineWidth: 1,
                lineColor: null
            }
        }
    },

    colors: ['#6CF', '#39F', '#06C', '#036', '#000'],

    // Define the data points. All series have a year of 1970/71 in order
    // to be compared on the same x axis. Note that in JavaScript, months start
    // at 0 for January, 1 for February etc.
    series: [
        {
            name: 'Winter 2021-2022',
            data: [
                [Date.UTC(1970, 10, 5), 0],
                [Date.UTC(1970, 10, 12), 0.1],
                [Date.UTC(1970, 10, 21), 0.15],
                [Date.UTC(1970, 10, 22), 0.19],
                [Date.UTC(1970, 10, 27), 0.17],
                [Date.UTC(1970, 10, 30), 0.27],
                [Date.UTC(1970, 11, 2), 0.25],
                [Date.UTC(1970, 11, 4), 0.27],
                [Date.UTC(1970, 11, 5), 0.26],
                [Date.UTC(1970, 11, 6), 0.25],
                [Date.UTC(1970, 11, 7), 0.26],
                [Date.UTC(1970, 11, 8), 0.26],
                [Date.UTC(1970, 11, 9), 0.25],
                [Date.UTC(1970, 11, 10), 0.25],
                [Date.UTC(1970, 11, 11), 0.25],
                [Date.UTC(1970, 11, 12), 0.26],
                [Date.UTC(1970, 11, 22), 0.22],
                [Date.UTC(1970, 11, 23), 0.22],
                [Date.UTC(1970, 11, 24), 0.22],
                [Date.UTC(1970, 11, 25), 0.24],
                [Date.UTC(1970, 11, 26), 0.24],
                [Date.UTC(1970, 11, 27), 0.24],
                [Date.UTC(1970, 11, 28), 0.24],
                [Date.UTC(1970, 11, 29), 0.24],
                [Date.UTC(1970, 11, 30), 0.22],
                [Date.UTC(1970, 11, 31), 0.18],
                [Date.UTC(1971, 0, 1), 0.17],
                [Date.UTC(1971, 0, 2), 0.23],
                [Date.UTC(1971, 0, 9), 0.5],
                [Date.UTC(1971, 0, 10), 0.5],
                [Date.UTC(1971, 0, 11), 0.53],
                [Date.UTC(1971, 0, 12), 0.48],
                [Date.UTC(1971, 0, 13), 0.4],
                [Date.UTC(1971, 0, 17), 0.36],
                [Date.UTC(1971, 0, 22), 0.69],
                [Date.UTC(1971, 0, 23), 0.62],
                [Date.UTC(1971, 0, 29), 0.72],
                [Date.UTC(1971, 1, 2), 0.95],
                [Date.UTC(1971, 1, 10), 1.73],
                [Date.UTC(1971, 1, 15), 1.76],
                [Date.UTC(1971, 1, 26), 2.18],
                [Date.UTC(1971, 2, 2), 2.22],
                [Date.UTC(1971, 2, 6), 2.13],
                [Date.UTC(1971, 2, 8), 2.11],
                [Date.UTC(1971, 2, 9), 2.12],
                [Date.UTC(1971, 2, 10), 2.11],
                [Date.UTC(1971, 2, 11), 2.09],
                [Date.UTC(1971, 2, 12), 2.08],
                [Date.UTC(1971, 2, 13), 2.08],
                [Date.UTC(1971, 2, 14), 2.07],
                [Date.UTC(1971, 2, 15), 2.08],
                [Date.UTC(1971, 2, 17), 2.12],
                [Date.UTC(1971, 2, 18), 2.19],
                [Date.UTC(1971, 2, 21), 2.11],
                [Date.UTC(1971, 2, 24), 2.1],
                [Date.UTC(1971, 2, 27), 1.89],
                [Date.UTC(1971, 2, 30), 1.92],
                [Date.UTC(1971, 3, 3), 1.9],
                [Date.UTC(1971, 3, 6), 1.95],
                [Date.UTC(1971, 3, 9), 1.94],
                [Date.UTC(1971, 3, 12), 2],
                [Date.UTC(1971, 3, 15), 1.9],
                [Date.UTC(1971, 3, 18), 1.84],
                [Date.UTC(1971, 3, 21), 1.75],
                [Date.UTC(1971, 3, 24), 1.69],
                [Date.UTC(1971, 3, 27), 1.64],
                [Date.UTC(1971, 3, 30), 1.64],
                [Date.UTC(1971, 4, 3), 1.58],
                [Date.UTC(1971, 4, 6), 1.52],
                [Date.UTC(1971, 4, 9), 1.43],
                [Date.UTC(1971, 4, 12), 1.42],
                [Date.UTC(1971, 4, 15), 1.37],
                [Date.UTC(1971, 4, 18), 1.26],
                [Date.UTC(1971, 4, 21), 1.11],
                [Date.UTC(1971, 4, 24), 0.92],
                [Date.UTC(1971, 4, 27), 0.75],
                [Date.UTC(1971, 4, 30), 0.55],
                [Date.UTC(1971, 5, 3), 0.35],
                [Date.UTC(1971, 5, 6), 0.21],
                [Date.UTC(1971, 5, 9), 0]
            ]
        },
        {
            name: 'Winter 2022-2023',
            data: [
                [Date.UTC(1970, 10, 3), 0],
                [Date.UTC(1970, 10, 9), 0],
                [Date.UTC(1970, 10, 12), 0.03],
                [Date.UTC(1970, 10, 15), 0],
                [Date.UTC(1970, 10, 24), 0],
                [Date.UTC(1970, 10, 27), 0.06],
                [Date.UTC(1970, 10, 30), 0.05],
                [Date.UTC(1970, 11, 3), 0.05],
                [Date.UTC(1970, 11, 6), 0.07],
                [Date.UTC(1970, 11, 9), 0.09],
                [Date.UTC(1970, 11, 15), 0.09],
                [Date.UTC(1970, 11, 18), 0.13],
                [Date.UTC(1970, 11, 21), 0.17],
                [Date.UTC(1970, 11, 24), 0.32],
                [Date.UTC(1970, 11, 27), 0.62],
                [Date.UTC(1971, 0, 3), 0.60],
                [Date.UTC(1971, 0, 9), 0.63],
                [Date.UTC(1971, 0, 12), 0.74],
                [Date.UTC(1971, 0, 15), 0.80],
                [Date.UTC(1971, 0, 18), 0.97],
                [Date.UTC(1971, 0, 21), 0.87],
                [Date.UTC(1971, 0, 24), 0.98],
                [Date.UTC(1971, 0, 27), 0.87],
                [Date.UTC(1971, 0, 30), 0.98],
                [Date.UTC(1971, 1, 3), 1.09],
                [Date.UTC(1971, 1, 6), 1.24],
                [Date.UTC(1971, 1, 9), 1.26],
                [Date.UTC(1971, 1, 12), 1.21],
                [Date.UTC(1971, 1, 15), 1.12],
                [Date.UTC(1971, 1, 18), 1.35],
                [Date.UTC(1971, 1, 21), 1.65],
                [Date.UTC(1971, 1, 24), 1.64],
                [Date.UTC(1971, 1, 27), 1.58],
                [Date.UTC(1971, 2, 3), 1.55],
                [Date.UTC(1971, 2, 6), 1.62],
                [Date.UTC(1971, 2, 9), 1.55],
                [Date.UTC(1971, 2, 12), 1.69],
                [Date.UTC(1971, 2, 15), 1.70],
                [Date.UTC(1971, 2, 18), 1.95],
                [Date.UTC(1971, 2, 21), 1.91],
                [Date.UTC(1971, 2, 27), 2.08],
                [Date.UTC(1971, 2, 30), 2.17],
                [Date.UTC(1971, 3, 3), 2.09],
                [Date.UTC(1971, 3, 12), 2.04],
                [Date.UTC(1971, 3, 15), 1.91],
                [Date.UTC(1971, 3, 18), 1.93],
                [Date.UTC(1971, 3, 21), 1.79],
                [Date.UTC(1971, 3, 24), 1.72],
                [Date.UTC(1971, 3, 27), 1.79],
                [Date.UTC(1971, 4, 3), 1.74],
                [Date.UTC(1971, 4, 6), 1.66],
                [Date.UTC(1971, 4, 9), 1.56],
                [Date.UTC(1971, 4, 12), 1.37],
                [Date.UTC(1971, 4, 15), 1.20],
                [Date.UTC(1971, 4, 18), 1.18],
                [Date.UTC(1971, 4, 21), 0.93],
                [Date.UTC(1971, 4, 24), 0.77],
                [Date.UTC(1971, 4, 27), 0.63],
                [Date.UTC(1971, 4, 30), 0.47],
                [Date.UTC(1971, 5, 3), 0.22],
                [Date.UTC(1971, 5, 6), 0.0]
            ]
        },
        {
            name: 'Winter 2023-2024',
            data: [
                [Date.UTC(1970, 9, 10), 0],
                [Date.UTC(1970, 10, 18), 0.2],
                [Date.UTC(1970, 10, 21), 0.08],
                [Date.UTC(1970, 10, 25), 0.60],
                [Date.UTC(1970, 11, 3), 0.11],
                [Date.UTC(1970, 11, 6), 0.49],
                [Date.UTC(1970, 11, 18), 0.38],
                [Date.UTC(1970, 11, 21), 0.70],
                [Date.UTC(1970, 11, 25), 0.81],
                [Date.UTC(1970, 11, 30), 0.77],
                [Date.UTC(1971, 0, 9), 0.65],
                [Date.UTC(1971, 0, 12), 0.71],
                [Date.UTC(1971, 0, 21), 0.86],
                [Date.UTC(1971, 0, 24), 1.07],
                [Date.UTC(1971, 0, 27), 1.19],
                [Date.UTC(1971, 0, 30), 1.12],
                [Date.UTC(1971, 1, 3), 1.31],
                [Date.UTC(1971, 1, 6), 1.43],
                [Date.UTC(1971, 1, 9), 1.33],
                [Date.UTC(1971, 1, 12), 1.41],
                [Date.UTC(1971, 1, 15), 1.49],
                [Date.UTC(1971, 1, 18), 1.46],
                [Date.UTC(1971, 1, 21), 1.55],
                [Date.UTC(1971, 1, 24), 1.58],
                [Date.UTC(1971, 1, 27), 1.61],
                [Date.UTC(1971, 2, 3), 1.80],
                [Date.UTC(1971, 2, 6), 1.64],
                [Date.UTC(1971, 2, 15), 1.66],
                [Date.UTC(1971, 2, 16), 1.91],
                [Date.UTC(1971, 2, 21), 1.86],
                [Date.UTC(1971, 2, 23), 2.08],
                [Date.UTC(1971, 2, 31), 2.01],
                [Date.UTC(1971, 3, 11), 1.86],
                [Date.UTC(1971, 3, 15), 1.82],
                [Date.UTC(1971, 3, 19), 1.81],
                [Date.UTC(1971, 3, 25), 1.79],
                [Date.UTC(1971, 4, 5), 1.43],
                [Date.UTC(1971, 4, 8), 1.13],
                [Date.UTC(1971, 4, 12), 0.98],
                [Date.UTC(1971, 4, 15), 0.71],
                [Date.UTC(1971, 4, 18), 0.50],
                [Date.UTC(1971, 4, 21), 0.28],
                [Date.UTC(1971, 4, 24), 0.09],
                [Date.UTC(1971, 4, 25), 0.0]
            ]
        }
    ]
});

</script>


<script>
    var ctx8 = document.getElementById("coin_sales8").getContext("2d");
var chart8 = new Chart(ctx8, {
    type: 'bar', // ganti dengan tipe chart yang sesuai
    data: { /* konfigurasi data */ },
    options: { /* konfigurasi opsi */ }
});

var ctx9 = document.getElementById("coin_sales9").getContext("2d");
var chart9 = new Chart(ctx9, { /* konfigurasi chart */ });

var ctx10 = document.getElementById("coin_sales10").getContext("2d");
var chart10 = new Chart(ctx10, { /* konfigurasi chart */ });

var ctx11 = document.getElementById("coin_sales11").getContext("2d");
var chart11 = new Chart(ctx11, { /* konfigurasi chart */ });

</script>




<!-- start chart js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<!-- start highcharts js -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<!-- start zingchart js -->
<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
<script>
zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
</script>
<!-- all line chart activation -->
<script src="assets/js/line-chart.js"></script>
<!-- all bar chart activation -->
<script src="assets/js/bar-chart.js"></script>
<!-- all pie chart -->
<script src="assets/js/pie-chart.js"></script>
<!-- others plugins -->
<script src="assets/js/plugins.js"></script>
<script src="assets/js/scripts.js"></script>

<script src="https://code.highcharts.com/10/highcharts.js"></script>
@endsection
