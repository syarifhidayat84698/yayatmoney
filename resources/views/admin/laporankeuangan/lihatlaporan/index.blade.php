@extends('templates.app')

@section('title', 'Laporan Keuangan')
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Laporan Keuangan</h1>

    <form method="GET" action="{{ route('laporan.keuangan') }}">
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="dateRange" class="form-label">Pilih Rentang Waktu</label>
                <select class="form-select" id="dateRange" name="dateRange">
                    <option value="daily">Harian</option>
                    <option value="weekly">Mingguan</option>
                    <option value="monthly">Bulanan</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="startDate" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="startDate" name="startDate" required>
            </div>
            <div class="col-md-4">
                <label for="endDate" class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" id="endDate" name="endDate" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mb-4">Tampilkan Laporan</button>
    </form>

    <!-- Tombol Ekspor -->
    <div class="mb-3">
        <button class="btn btn-success" id="exportPDF">Ekspor PDF</button>
        <button class="btn btn-info ms-2" id="exportExcel">Ekspor Excel</button>
    </div>

    <div id="reportContainer" class="mt-4">
        <h3>Laporan Keuangan</h3>
        <div id="reportContent">
            @if(isset($transactions) && $transactions->count() > 0)
                <table class="table table-striped mt-4">
                    <thead>
                        <tr>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Pemasukan</th>
                            <th scope="col">Pengeluaran</th>
                            <th scope="col">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalIncome = 0;
                            $totalExpense = 0;
                        @endphp
                        @foreach ($transactions as $transaction)
                            @if ($transaction->type === 'income')
                                @php $totalIncome += $transaction->amount; @endphp
                                <tr>
                                    <td>{{ $transaction->transaction_date }}</td>
                                    <td>{{ number_format($transaction->amount, 2, ',', '.') }}</td>
                                    <td>Rp 0</td>
                                    <td>{{ number_format($totalIncome - $totalExpense, 2, ',', '.') }}</td>
                                </tr>
                            @elseif ($transaction->type === 'expense')
                                @php $totalExpense += $transaction->amount; @endphp
                                <tr>
                                    <td>{{ $transaction->transaction_date }}</td>
                                    <td>Rp 0</td>
                                    <td>{{ number_format($transaction->amount, 2, ',', '.') }}</td>
                                    <td>{{ number_format($totalIncome - $totalExpense, 2, ',', '.') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Tidak ada transaksi yang ditemukan untuk rentang waktu ini.</p>
            @endif
        </div>
    </div>
</div>

<script>
    // Fungsi untuk mengekspor laporan ke PDF
    document.getElementById('exportPDF').addEventListener('click', function() {
        const reportContent = document.getElementById('reportContent').innerHTML;

        const pdfWindow = window.open('', '_blank');
        pdfWindow.document.write(`
            <html>
                <head>
                    <title>Laporan Keuangan</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                    </style>
                </head>
                <body>
                    <h1>Laporan Keuangan</h1>
                    ${reportContent}
                </body>
            </html>
        `);
        pdfWindow.document.close();
        pdfWindow.print(); // Memunculkan dialog cetak untuk menyimpan sebagai PDF
    });

    // Fungsi untuk mengekspor laporan ke Excel
    document.getElementById('exportExcel').addEventListener('click', function() {
        const reportContent = document.getElementById('reportContent').innerHTML;

        const blob = new Blob([`
            <html>
            <head>
                <meta charset="utf-8">
                <style>
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <h1>Laporan Keuangan</h1>
                ${reportContent}
            </body>
            </html>
        `], {
            type: 'application/vnd.ms-excel'
        });

        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'laporan_keuangan.xls';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url); // Menghapus URL Blob
    });
</script>
@endsection