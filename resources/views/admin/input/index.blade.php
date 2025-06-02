@extends('templates.app')

@section('title', 'Pemasukan')

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .table {
        box-shadow: 0 0.125rem 0.5rem rgba(0,0,0,0.08);
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        background-color: #f8f9fa;
        white-space: nowrap;
        border-bottom: 2px solid #dee2e6;
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
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
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
        background: #198754 !important;
        color: white !important;
        border: none;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9f7ef !important;
        border: none;
        color: #145c32 !important;
    }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 mb-0">
            <i class="fas fa-money-bill-wave text-success me-2"></i>Input Pemasukan
        </h1>
        <div>
            <a href="{{ route('input_pemasukan') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle me-2"></i>Tambah Faktur
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
                <table class="table table-hover align-middle mb-0" id="pemasukanTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">No Tagihan</th>
                            <th class="py-3 px-4">Nama</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Jumlah Pendapatan</th>
                            <th class="py-3 px-4">Foto Nota</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Keterangan</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inputs as $transaction)
                        <tr>
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">
                                <span class="fw-medium">{{ $transaction->nomor_tagihan }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                        <span class="avatar-title text-primary">
                                            {{ substr($transaction->nama_customer, 0, 1) }}
                                        </span>
                                    </div>
                                    {{ $transaction->nama_customer }}
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <i class="far fa-calendar-alt text-muted me-1"></i>
                                {{ \Carbon\Carbon::parse($transaction->due_date)->format('d M Y') }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('nota.create', $transaction->id) }}" 
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-image me-1"></i>Lihat Nota
                                </a>
                            </td>
                            <td class="py-3 px-4">
                                @if ($transaction->status == 'Tidak Lunas')
                                    <span class="badge bg-danger-subtle text-danger">
                                        <i class="fas fa-times-circle me-1"></i>Belum Lunas
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="fas fa-check-circle me-1"></i>Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($transaction->keterangan)
                                    <span class="text-muted">{{ $transaction->keterangan }}</span>
                                @else
                                    <span class="text-muted fst-italic">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('input.edit', $transaction->id) }}" 
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
            Menampilkan <span class="fw-semibold">{{ $inputs->firstItem() }}</span> - <span class="fw-semibold">{{ $inputs->lastItem() }}</span> dari <span class="fw-semibold">{{ $inputs->total() }}</span> data
        </div>
        <div>
            {{ $inputs->links('vendor.pagination.bootstrap-5') }}
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
        $('#pemasukanTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [8] } // Kolom aksi tidak bisa diurutkan
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
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch("{{ route('input.destroy', ['id' => ':id']) }}".replace(':id', transactionId), {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "✅ Berhasil!",
                            text: "Transaksi berhasil dihapus.",
                            confirmButtonColor: "#28a745",
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "❌ Gagal!",
                            text: "Terjadi kesalahan saat menghapus transaksi.",
                            confirmButtonColor: "#dc3545"
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "❌ Kesalahan Server",
                        text: "Tidak dapat menghapus transaksi saat ini.",
                        confirmButtonColor: "#dc3545"
                    });
                });
            }
        });
    }
</script>
@endpush
