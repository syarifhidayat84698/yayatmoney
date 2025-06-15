@extends('templates.app')

@section('title', 'Pengeluaran')

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
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
    }
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
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
    .btn {
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn:hover {
        transform: translateY(-1px);
    }
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
    }
    .accuracy-badge {
        font-size: 0.75rem;
        padding: 0.25em 0.5em;
    }
    .raw-text-preview {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 mb-0">
            <i class="fas fa-wallet text-danger me-2"></i>Pengeluaran
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('tambah_pengeluaran') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle me-2"></i>Tambah Pengeluaran
            </a>
            <a href="{{ route('ocr') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-file-scan me-2"></i>Scan Nota
            </a>
        </div>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: "success",
                title: "✅ Berhasil!",
                text: "{{ session('success') }}",
                confirmButtonColor: "#28a745",
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="pengeluaranTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">Nama Toko</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Alamat</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Akurasi OCR</th>
                            <th class="py-3 px-4">Teks Mentah</th>
                            <th class="py-3 px-4">Foto Nota</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                        <tr>
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle me-2">
                                        <span class="avatar-title text-danger">
                                            {{ substr($transaction->nama_toko, 0, 1) }}
                                        </span>
                                    </div>
                                    {{ $transaction->nama_toko }}
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <i class="far fa-calendar-alt text-muted me-1"></i>
                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-muted">{{ $transaction->alamat }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="fw-bold text-danger">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($transaction->accuracies)
                                    @foreach(json_decode($transaction->accuracies, true) as $field => $accuracy)
                                        <span class="badge bg-{{ $accuracy >= 80 ? 'success' : ($accuracy >= 60 ? 'warning' : 'danger') }} accuracy-badge me-1" 
                                              title="{{ ucfirst($field) }}: {{ $accuracy }}%">
                                            {{ ucfirst($field) }}: {{ $accuracy }}%
                                        </span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary accuracy-badge">Manual</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($transaction->raw_text)
                                    <span class="raw-text-preview" title="{{ $transaction->raw_text }}">
                                        {{ $transaction->raw_text }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if ($transaction->receipt)
                                    <div class="receipt-preview">
                                        <img src="{{ asset('storage/' . $transaction->receipt) }}" 
                                             alt="Nota" 
                                             class="receipt-image"
                                             onclick="showImage(this.src)">
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('transaksi.edit', $transaction->id) }}" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <button onclick="confirmDelete({{ $transaction->id }})" 
                                            class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Pagination Info & Links --}}
    <div class="mt-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div class="small text-muted mb-2 mb-md-0">
            Menampilkan <span class="fw-semibold">{{ $transactions->firstItem() }}</span> - <span class="fw-semibold">{{ $transactions->lastItem() }}</span> dari <span class="fw-semibold">{{ $transactions->total() }}</span> data
        </div>
        <div>
            {{ $transactions->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Nota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        $('#pengeluaranTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [5, 6, 7, 8] } // Kolom yang tidak bisa diurutkan
            ],
            "order": [[0, 'asc']], // Urutkan berdasarkan kolom pertama secara ascending
            "pageLength": 10, // Jumlah data per halaman
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]], // Opsi jumlah data per halaman
        });
    });

    function confirmDelete(transactionId) {
        Swal.fire({
            title: "⚠️ Hapus Transaksi?",
            text: "Data ini akan dihapus secara permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form delete
                document.getElementById('delete-form-' + transactionId).submit();
            }
        });
    }

    function showImage(src) {
        const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        document.getElementById('modalImage').src = src;
        modal.show();
    }
</script>
@endpush
