@extends('templates.app')

@section('title', 'Laporan Data Hutang')

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
    <h1 class="mb-4 text-center">📄 Laporan Data Hutang</h1>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('laporan.data_hutang') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari Nama Customer" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="Hutang" {{ request('status') == 'Hutang' ? 'selected' : '' }}>Hutang</option>
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
    <a href="{{ route('laporan.hutang.export', request()->query()) }}" class="btn btn-danger mb-4">Ekspor PDF</a>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="alert alert-info">
                <strong>Total Hutang:</strong> Rp {{ number_format($hutangs->sum('total_hutang'), 0, ',', '.') }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-success">
                <strong>Total Lunas:</strong> Rp {{ number_format($hutangs->where('status', 'Lunas')->sum('total_hutang'), 0, ',', '.') }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-danger">
                <strong>Total Hutang:</strong> Rp {{ number_format($hutangs->where('status', 'Hutang')->sum('total_hutang'), 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered" id="hutangTable">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>No Tagihan</th>
                    <th>Nama Customer</th>
                    <th>No. WhatsApp</th>
                    <th>Jatuh Tempo</th>
                    <th>Total Harga</th>
                    <th>Total Hutang</th>
                    <th>Sisa Hutang</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($hutangs as $index => $hutang)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $hutang->nomor_tagihan }}</td>
                    <td>{{ $hutang->customer->nama_customer }}</td>
                    <td>{{ $hutang->customer->telepon }}</td>
                    <td>{{ \Carbon\Carbon::parse($hutang->due_date)->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($hutang->total_tagihan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-{{ $hutang->status == 'Lunas' ? 'success' : 'warning' }}">
                            {{ $hutang->status }}
                        </span>
                    </td>
                    <td>{{ $hutang->keterangan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">Data tidak ditemukan</td>
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
        $('#hutangTable').DataTable({
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
