@extends('templates.app')

@section('title', 'Laporan Pengeluaran')

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        background-color: #f8f9fa;
        white-space: nowrap;
    }
    .table td {
        font-size: 0.875rem;
        vertical-align: middle;
    }
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
    }
    .alert {
        border-radius: 0.5rem;
        padding: 1rem;
    }
    .receipt-preview {
        display: inline-block;
    }
    .receipt-image {
        width: 100px;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .receipt-image:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .avatar-sm {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-title {
        font-size: 1rem;
        font-weight: 600;
    }
    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length select {
        padding: 0.25rem 2rem 0.25rem 0.5rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        padding: 0.25rem 0.5rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    .dataTables_wrapper .dataTables_info {
        padding-top: 1rem;
    }
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">📄 Laporan Pengeluaran</h1>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('laporan.pengeluaran') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari Nama Toko" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <!-- Ekspor PDF Button -->
    <a href="{{ route('laporan.pengeluaran.export', request()->query()) }}" class="btn btn-danger mb-4">Ekspor PDF</a>

    @if($transactions->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            Data tidak ditemukan untuk filter yang dipilih
        </div>
    @else
        <!-- Summary Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="alert alert-info">
                    <strong>Total Pengeluaran:</strong> Rp {{ number_format($transactions->sum('amount'), 0, ',', '.') }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-warning">
                    <strong>Pengeluaran Terbesar:</strong> Rp {{ number_format($transactions->max('amount'), 0, ',', '.') }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success">
                    <strong>Rata-rata Pengeluaran:</strong> Rp {{ number_format($transactions->avg('amount'), 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="pengeluaranTable">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama Toko</th>
                        <th>Tanggal</th>
                        <th>Alamat</th>
                        <th>Total</th>
                        <th>Akurasi OCR</th>
                        <th>Teks Mentah</th>
                        <th>Foto Nota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $index => $transaction)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle me-2">
                                    <span class="avatar-title text-danger">
                                        {{ substr($transaction->nama_toko, 0, 1) }}
                                    </span>
                                </div>
                                {{ $transaction->nama_toko }}
                            </div>
                        </td>
                        <td>
                            <i class="far fa-calendar-alt text-muted me-1"></i>
                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                        </td>
                        <td>
                            <span class="text-muted">{{ $transaction->alamat }}</span>
                        </td>
                        <td class="text-end">
                            <span class="fw-bold text-danger">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            @if($transaction->accuracies)
                                @foreach(json_decode($transaction->accuracies, true) as $field => $accuracy)
                                    <span class="badge bg-{{ $accuracy >= 80 ? 'success' : ($accuracy >= 60 ? 'warning' : 'danger') }} me-1" 
                                          title="{{ ucfirst($field) }}: {{ $accuracy }}%">
                                        {{ ucfirst($field) }}: {{ $accuracy }}%
                                    </span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary">Manual</span>
                            @endif
                        </td>
                        <td>
                            @if($transaction->raw_text)
                                <span class="text-muted" title="{{ $transaction->raw_text }}">
                                    {{ Str::limit($transaction->raw_text, 50) }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($transaction->receipt)
                                <div class="receipt-preview">
                                    <a href="{{ asset('storage/' . $transaction->receipt) }}" 
                                       target="_blank" 
                                       class="d-block">
                                        <img src="{{ asset('storage/' . $transaction->receipt) }}" 
                                             alt="Nota" 
                                             class="receipt-image">
                                    </a>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Data tidak ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        $('#pengeluaranTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "order": [[2, 'desc']], // Sort by date descending
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        });
    });
</script>
@endpush