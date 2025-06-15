<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Pengeluaran</title>
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
        .company-details {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
            color: #555;
        }
        .company-details p {
            margin: 2px 0;
        }
        .company-details .bank-row {
            display: flex;
            justify-content: center;
            gap: 20px; /* Space between bank details */
            margin-top: 5px;
            flex-wrap: wrap; /* Allow items to wrap to next line if needed */
        }
        .company-details .bank-account {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-basis: calc(50% - 10px); /* Distribute space for two items per row, accounting for gap */
        }
        .company-details .bank-account svg {
            width: 10px; /* Smaller icons for report */
            height: 10px;
        }
        .company-details .bank-name {
            font-weight: bold;
        }
        .company-details .account-number,
        .company-details .account-name {
            font-size: 9px;
            color: #777;
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
        .category-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            display: inline-block;
        }
        .category-operasional {
            background-color: #cfe2ff;
            color: #084298;
        }
        .category-gaji {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .category-utilitas {
            background-color: #fff3cd;
            color: #664d03;
        }
        .category-lainnya {
            background-color: #e2e3e5;
            color: #41464b;
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
        <h1>LAPORAN PENGELUARAN</h1>
        <p class="subtitle">Per Tanggal: {{ date('d F Y') }}</p>
        <div class="company-details">
            <p>Hidayat Collection</p>
            <p>Kios 1 Pasar Sandang Tegal Gubuk Blok G193</p>
            <p>Kios 2 Tembok Kidul RT 12/RW 02, Adiwerna, Tegal</p>
            <p>WA: 082333305520</p>
            <div class="bank-row">
                <div class="bank-account">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span class="bank-name">BCA:</span> <span class="account-number">0990885745</span> <span class="account-name">(a.n. Muhamad Syarif Hidayatullah)</span>
                </div>
                <div class="bank-account">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span class="bank-name">BRI:</span> <span class="account-number">054501003553505</span> <span class="account-name">(a.n. Akhmad Zaeni)</span>
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 100px;">Tanggal</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th class="text-right">Jumlah</th>
                <th>Metode Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPengeluaran = 0;
                $validCategories = ['Operasional', 'Gaji', 'Utilitas', 'Lainnya'];
                $totalByKategori = array_fill_keys($validCategories, 0);
            @endphp

            @foreach ($pengeluarans as $index => $pengeluaran)
            @php
                $totalPengeluaran += $pengeluaran->amount;
                $kategori = in_array($pengeluaran->sumber, $validCategories) ? $pengeluaran->sumber : 'Lainnya';
                $totalByKategori[$kategori] += $pengeluaran->amount;
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ date('d/m/Y', strtotime($pengeluaran->transaction_date)) }}</td>
                <td>
                    <span class="category-badge category-{{ strtolower($kategori) }}">
                        {{ $kategori }}
                    </span>
                </td>
                <td>{{ $pengeluaran->description }}</td>
                <td class="amount text-right">Rp {{ number_format($pengeluaran->amount, 0, ',', '.') }}</td>
                <td>{{ $pengeluaran->metode_pembayaran }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-section">
        <h2 class="summary-title">Ringkasan</h2>
        <table class="summary-table">
            <tr>
                <th>Total Pengeluaran</th>
                <td class="text-right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Pengeluaran Operasional</th>
                <td class="text-right">Rp {{ number_format($totalByKategori['Operasional'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Pengeluaran Gaji</th>
                <td class="text-right">Rp {{ number_format($totalByKategori['Gaji'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Pengeluaran Utilitas</th>
                <td class="text-right">Rp {{ number_format($totalByKategori['Utilitas'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Pengeluaran Lainnya</th>
                <td class="text-right">Rp {{ number_format($totalByKategori['Lainnya'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Rata-rata Pengeluaran</th>
                <td class="text-right">Rp {{ number_format(count($pengeluarans) > 0 ? $totalPengeluaran / count($pengeluarans) : 0, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html> 