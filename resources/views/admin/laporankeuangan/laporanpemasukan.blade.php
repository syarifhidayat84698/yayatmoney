@extends('templates.app')

@section('title', 'Laporan Pemasukan')

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
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.25rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.5rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0d6efd !important;
        color: white !important;
        border: none;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef !important;
        border: none;
        color: #0d6efd !important;
    }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">💰 Laporan Pemasukan</h1>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('laporan.pemasukan') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari Nama Customer" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <input type="date" name="date_from" class="form-control" placeholder="Dari Tanggal" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-4">
                <input type="date" name="date_to" class="form-control" placeholder="Sampai Tanggal" value="{{ request('date_to') }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Filter</button>
    </form>

    <!-- Ekspor PDF Button -->
    <a href="{{ route('laporan.pemasukan.export') }}" class="btn btn-danger mb-4">Ekspor PDF</a>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="alert alert-info">
                <strong>Total Pemasukan:</strong> Rp {{ number_format($inputs->sum('total_amount'), 2, ',', '.') }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-success">
                <strong>Total Lunas:</strong> Rp {{ number_format($inputs->where('status', 'Lunas')->sum('total_amount'), 2, ',', '.') }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-danger">
                <strong>Total Tidak Lunas:</strong> Rp {{ number_format($inputs->where('status', 'Tidak Lunas')->sum('total_amount'), 2, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover" id="pemasukanTable">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>No Tagihan</th>
                    <th>Nama Customer</th>
                    <th>Tanggal</th>
                    <th>Jumlah Pendapatan</th>
                    <th>Foto Nota</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inputs as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->nomor_tagihan }}</td>
                    <td>{{ $transaction->nama_customer }}</td>
                    <td>{{ $transaction->due_date }}</td>
                    <td>Rp {{ number_format($transaction->total_amount, 2, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('nota.create', $transaction->id) }}" class="btn btn-info btn-sm">
                            Lihat Nota
                        </a>
                    </td>
                    <td>
                        @if ($transaction->status == 'Tidak Lunas')
                            <span class="badge badge-danger">Belum Lunas</span>
                        @else
                            <span class="badge badge-success">Lunas</span>
                        @endif
                    </td>
                    <td>{{ $transaction->keterangan }}</td>
                </tr>
                @endforeach
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
        $('#pemasukanTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "order": [[0, 'asc']],
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        });
    });
</script>
@endpush