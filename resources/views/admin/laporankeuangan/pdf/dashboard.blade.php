<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Keuangan Dashboard</title>
    <style>
        @page {
            margin: 1cm;
        }
        body { 
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 10px;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }
        h1 {
            color: #1e293b;
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 5px;
        }
        .subtitle {
            color: #666;
            font-size: 12px;
            margin: 0;
        }
        table { 
            width: 100%; 
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        th, td { 
            padding: 8px; 
            text-align: left;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        th { 
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .amount {
            font-family: DejaVu Sans, monospace;
            font-weight: normal;
        }
        .summary-section {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 10px;
            color: #333;
        }
        .summary-table {
            width: 100%;
            margin-top: 10px;
        }
        .summary-table th {
            width: 200px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 10px 0;
        }
        .card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .card-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .card-value {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
        }
        .card-subtitle {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        .status-success {
            color: #28a745;
        }
        .status-danger {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN DASHBOARD</h1>
        <p class="subtitle">Per Tanggal: {{ date('d F Y') }}</p>
    </div>

    <div class="row">
        <div class="card">
            <div class="card-title">Pemasukan Bulan Ini</div>
            <div class="card-value">Rp {{ number_format($thisMonthInputs, 0, ',', '.') }}</div>
            <div class="card-subtitle">
                Perubahan dari bulan lalu: 
                <span class="{{ $inputChange >= 0 ? 'status-success' : 'status-danger' }}">
                    {{ number_format(abs($inputChange), 1) }}%
                    {{ $inputChange >= 0 ? '↑' : '↓' }}
                </span>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Pengeluaran Bulan Ini</div>
            <div class="card-value">Rp {{ number_format($thisMonthTransactions, 0, ',', '.') }}</div>
            <div class="card-subtitle">
                Perubahan dari bulan lalu: 
                <span class="{{ $transactionChange >= 0 ? 'status-success' : 'status-danger' }}">
                    {{ number_format(abs($transactionChange), 1) }}%
                    {{ $transactionChange >= 0 ? '↑' : '↓' }}
                </span>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Laba/Rugi Bulan Ini</div>
            <div class="card-value {{ $monthlyProfit >= 0 ? 'status-success' : 'status-danger' }}">
                Rp {{ number_format($monthlyProfit, 0, ',', '.') }}
            </div>
            <div class="card-subtitle">
                Rata-rata laba bulanan: Rp {{ number_format($avgMonthlyProfit, 0, ',', '.') }}
            </div>
        </div>

        <div class="card">
            <div class="card-title">Total Nilai Inventori</div>
            <div class="card-value">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</div>
            <div class="card-subtitle">Total nilai barang dalam stok</div>
        </div>
    </div>

    <div class="summary-section">
        <h2 class="summary-title">Ringkasan Bulan Ini</h2>
        <table class="summary-table">
            <tr>
                <th>Total Pendapatan</th>
                <td class="text-right">Rp {{ number_format($thisMonthInputs, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Pengeluaran</th>
                <td class="text-right">Rp {{ number_format($thisMonthTransactions, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Laba/Rugi</th>
                <td class="text-right {{ $monthlyProfit >= 0 ? 'status-success' : 'status-danger' }}">
                    Rp {{ number_format($monthlyProfit, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <th>Persentase Laba/Rugi</th>
                <td class="text-right {{ $monthlyProfitPercentage >= 0 ? 'status-success' : 'status-danger' }}">
                    {{ number_format($monthlyProfitPercentage, 1) }}%
                </td>
            </tr>
        </table>
    </div>

    <div class="summary-section">
        <h2 class="summary-title">Perbandingan dengan Bulan Lalu</h2>
        <table class="summary-table">
            <tr>
                <th>Perubahan Pendapatan</th>
                <td class="text-right {{ $inputChange >= 0 ? 'status-success' : 'status-danger' }}">
                    {{ number_format($inputChange, 1) }}%
                </td>
            </tr>
            <tr>
                <th>Perubahan Pengeluaran</th>
                <td class="text-right {{ $transactionChange >= 0 ? 'status-success' : 'status-danger' }}">
                    {{ number_format($transactionChange, 1) }}%
                </td>
            </tr>
            <tr>
                <th>Perubahan Laba/Rugi</th>
                <td class="text-right {{ $profitChange >= 0 ? 'status-success' : 'status-danger' }}">
                    {{ number_format($profitChange, 1) }}%
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html> 