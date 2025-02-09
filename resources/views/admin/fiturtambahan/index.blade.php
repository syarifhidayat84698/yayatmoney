@extends('templates.app')

@section('title', 'Optimalisasi Keuangan')
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Optimalisasi Keuangan</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Saran Keuangan Berbasis AI</h5>
        </div>
        <div class="card-body">
            <p>Aplikasi kami menggunakan algoritma AI untuk memberikan rekomendasi yang dapat membantu Anda mengurangi pengeluaran dan meningkatkan efisiensi keuangan berdasarkan pola transaksi Anda.</p>
            <button class="btn btn-primary" id="viewRecommendationsBtn">Lihat Rekomendasi</button>
            <div id="recommendations" class="mt-3" style="display: none;">
                <ul>
                    <li>Kurangi pengeluaran pada kategori makanan dengan 15%.</li>
                    <li>Investasikan 10% dari pendapatan bulanan Anda ke dalam tabungan.</li>
                    <li>Gunakan promo dan diskon untuk belanja bulanan Anda.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Analisis Profitabilitas</h5>
        </div>
        <div class="card-body">
            <p>Berikut adalah analisis rasio pendapatan terhadap pengeluaran Anda:</p>
            <canvas id="profitabilityChart" width="400" height="200"></canvas>
            <p class="mt-3">Rasio Pendapatan: Rp 10.000.000</p>
            <p>Rasio Pengeluaran: Rp 7.000.000</p>
            <p><strong>Profitabilitas: 30%</strong></p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Integrasi Pembayaran Digital</h5>
        </div>
        <div class="card-body">
            <p>Anda dapat melakukan pembayaran langsung melalui dompet digital atau bank. Pilih metode pembayaran Anda di bawah ini:</p>
            <select class="form-select" id="paymentMethod">
                <option value="">Pilih Metode Pembayaran</option>
                <option value="dompetDigital">Dompet Digital</option>
                <option value="bankTransfer">Transfer Bank</option>
            </select>
            <button class="btn btn-success mt-3">Lakukan Pembayaran</button>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Pengelompokan Otomatis</h5>
        </div>
        <div class="card-body">
            <p>Transaksi Anda telah dikategorikan secara otomatis berdasarkan pola belanja Anda:</p>
            <ul>
                <li>Makanan: Rp 1.500.000</li>
                <li>Transportasi: Rp 500.000</li>
                <li>Hiburan: Rp 1.000.000</li>
                <li>Tagihan: Rp 2.000.000</li>
            </ul>
            <p><strong>Total Pengeluaran: Rp 5.000.000</strong></p>
        </div>
    </div>
</div>

<script>
    document.getElementById('viewRecommendationsBtn').addEventListener('click', function() {
        const recommendationsDiv = document.getElementById('recommendations');
        recommendationsDiv.style.display = recommendationsDiv.style.display === 'none' ? 'block' : 'none';
    });

    // Contoh grafik untuk analisis profitabilitas
    const ctx = document.getElementById('profitabilityChart').getContext('2d');
    const profitabilityChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pendapatan', 'Pengeluaran'],
            datasets: [{
                label: 'Jumlah (Rp)',
                data: [10000000, 7000000],
                backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)'],
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
