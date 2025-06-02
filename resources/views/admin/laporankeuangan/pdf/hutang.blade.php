<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Data Hutang</title>
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
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            display: inline-block;
        }
        .status-lunas {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
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
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA HUTANG</h1>
        <p class="subtitle">Per Tanggal: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 100px;">No Tagihan</th>
                <th>Nama Customer</th>
                <th>No. WhatsApp</th>
                <th>Jatuh Tempo</th>
                <th class="text-right">Total Harga</th>
                <th class="text-right">Total Hutang</th>
                <th class="text-right">Sisa Hutang</th>
                <th class="text-center">Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHarga = 0;
                $totalHutang = 0;
                $sisaHutang = 0;
                $totalLunas = 0;
                $totalPending = 0;
            @endphp

            @foreach ($hutangs as $index => $hutang)
            @php
                $totalHarga += $hutang->total_tagihan;
                $totalHutang += $hutang->total_hutang;
                $sisaHutang += $hutang->sisa_hutang;
                if($hutang->status == 'Lunas') {
                    $totalLunas++;
                } else {
                    $totalPending++;
                }
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $hutang->nomor_tagihan }}</td>
                <td>{{ $hutang->customer->nama_customer }}</td>
                <td>{{ $hutang->customer->telepon }}</td>
                <td>{{ date('d/m/Y', strtotime($hutang->due_date)) }}</td>
                <td class="amount text-right">Rp {{ number_format($hutang->total_tagihan, 0, ',', '.') }}</td>
                <td class="amount text-right">Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</td>
                <td class="amount text-right">Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</td>
                <td class="text-center">
                    <span class="status-badge {{ $hutang->status == 'Lunas' ? 'status-lunas' : 'status-pending' }}">
                        {{ $hutang->status }}
                    </span>
                </td>
                <td>{{ $hutang->keterangan ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-section">
        <h2 class="summary-title">Ringkasan</h2>
        <table class="summary-table">
            <tr>
                <th>Total Tagihan</th>
                <td class="text-right">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Hutang</th>
                <td class="text-right">Rp {{ number_format($totalHutang, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Sisa Hutang</th>
                <td class="text-right">Rp {{ number_format($sisaHutang, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Jumlah Status Lunas</th>
                <td class="text-right">{{ $totalLunas }}</td>
            </tr>
            <tr>
                <th>Jumlah Status Pending</th>
                <td class="text-right">{{ $totalPending }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>