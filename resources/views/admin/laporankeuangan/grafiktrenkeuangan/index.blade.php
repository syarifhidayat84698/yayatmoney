@extends('templates.app')

@section('title', 'Grafik Tren Keuangan')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Grafik Tren Keuangan</h1>

    <div class="chart-container">
        <h2>Grafik Pendapatan dan Pengeluaran</h2>
        <div class="bar-chart">
            @php
                // Data fiktif untuk pendapatan dan pengeluaran
                $data = "Pendapatan:3000;Pengeluaran:2000;Laba:1000";

                // Menggunakan regex untuk memecah data
                preg_match_all('/(\w+):(\d+)/', $data, $matches);

                // Mengambil label dan nilai
                $labels = $matches[1];
                $values = $matches[2];
                $maxValue = max($values); // Mendapatkan nilai maksimal untuk perhitungan skala
            @endphp

            @foreach($values as $index => $value)
                <div class="bar" style="height: {{ ($value / $maxValue) * 100 }}%;">
                    <span class="label">{{ $labels[$index] }}</span>
                    <span class="value">{{ $value }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    /* Container styling */
    .chart-container {
        text-align: center;
        margin-top: 20px;
    }

    /* Bar chart styling */
    .bar-chart {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        height: 300px;
        gap: 20px;
        margin-top: 20px;
    }

    /* Bar styling */
    .bar {
        width: 100px;
        background: linear-gradient(135deg, #4CAF50, #8BC34A);
        border-radius: 8px 8px 0 0;
        position: relative;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        overflow: hidden;
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .bar:hover {
        transform: scale(1.1); /* Zoom efek saat hover */
        background: linear-gradient(135deg, #43A047, #7CB342); /* Gradasi lebih terang */
    }

    /* Label styling */
    .bar span.label {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin-top: 10px;
    }

    /* Value styling */
    .bar span.value {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        font-size: 1.2rem;
        font-weight: bold;
        color: #fff;
        padding: 5px 10px;
        background-color: rgba(0, 0, 0, 0.6);
        border-radius: 4px;
        margin-bottom: 5px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .bar:hover span.value {
        opacity: 1; /* Menampilkan nilai saat hover */
    }
</style>
@endsection
