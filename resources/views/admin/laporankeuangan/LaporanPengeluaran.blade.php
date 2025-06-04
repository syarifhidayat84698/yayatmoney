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
                <input type="text" name="search" class="form-control" placeholder="Cari Keterangan" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    <option value="Penjualan" {{ request('kategori') == 'Penjualan' ? 'selected' : '' }}>Penjualan</option>
                    <option value="Jasa" {{ request('kategori') == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                    <option value="Investasi" {{ request('kategori') == 'Investasi' ? 'selected' : '' }}>Investasi</option>
                    <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
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

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="alert alert-info">
                <strong>Total Pemasukan:</strong> Rp {{ number_format($pengeluarans->sum('amount'), 0, ',', '.') }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-warning">
                <strong>Pemasukan Terbesar:</strong> Rp {{ number_format($pengeluarans->max('amount'), 0, ',', '.') }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-success">
                <strong>Rata-rata Pemasukan:</strong> Rp {{ number_format($pengeluarans->avg('amount'), 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered" id="pengeluaranTable">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>No Tagihan</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                    <th>Bukti Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengeluarans as $index => $pengeluaran)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pengeluaran->nomor_tagihan }}</td>
                    <td>{{ $pengeluaran->nama_customer }}</td>
                    <td>{{ \Carbon\Carbon::parse($pengeluaran->transaction_date)->format('d-m-Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $pengeluaran->sumber == 'Penjualan' ? 'primary' : ($pengeluaran->sumber == 'Jasa' ? 'success' : ($pengeluaran->sumber == 'Investasi' ? 'warning' : 'info')) }}">
                            {{ $pengeluaran->sumber }}
                        </span>
                    </td>
                    <td>{{ $pengeluaran->description }}</td>
                    <td class="text-end">Rp {{ number_format($pengeluaran->amount, 0, ',', '.') }}</td>
                    <td>
                        @if($pengeluaran->receipt)
                            <div class="position-relative receipt-preview">
                                <a href="{{ asset('storage/' . $pengeluaran->receipt) }}" 
                                   target="_blank" 
                                   class="d-block">
                                    <img src="{{ asset('storage/' . $pengeluaran->receipt) }}" 
                                         alt="Nota" 
                                         class="receipt-image">
                                </a>
                                <a href="{{ asset('storage/' . $pengeluaran->receipt) }}" 
                                   download 
                                   class="btn btn-sm btn-success mt-2">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                            </div>
                        @else
                            <span class="text-muted fst-italic">Tidak ada nota</span>
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
            "order": [[3, 'desc']], // Sort by date descending
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        });
    });
</script>
@endpush