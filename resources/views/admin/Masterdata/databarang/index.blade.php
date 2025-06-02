@extends('templates.app')

@section('title', 'Data Barang')

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
    }
    .badge {
        font-weight: 500;
    }
    .rounded-pill {
        border-radius: 50rem !important;
    }
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    .table-hover > tbody > tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
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
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-box me-2 text-primary"></i>Data Barang
                            </h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('barangs.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="fas fa-plus me-1"></i>Tambah Barang
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="barangTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4" width="40">#</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-end">Harga</th>
                                    <th>Deskripsi</th>
                                    <th class="text-end px-4" width="130">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangs as $barang)
                                <tr>
                                    <td class="px-4 text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="fas fa-box-open text-primary"></i>
                                            </div>
                                            <span class="fw-medium">{{ $barang->nama_barang }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="badge {{ $barang->stok > 10 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-2 py-1">
                                            <i class="fas {{ $barang->stok > 10 ? 'fa-cubes' : 'fa-exclamation-circle' }} me-1"></i>
                                            {{ $barang->stok }}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-medium">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $barang->deskripsi ?: '-' }}</span>
                                    </td>
                                    <td class="text-end px-4">
                                        <a href="{{ route('barangs.edit', $barang->id) }}" 
                                            class="btn btn-warning btn-sm rounded-pill px-3 me-1">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <button type="button" 
                                            class="btn btn-danger btn-sm rounded-pill px-3 delete-barang"
                                            data-id="{{ $barang->id }}"
                                            data-name="{{ $barang->nama_barang }}">
                                            <i class="fas fa-trash-alt me-1"></i>Hapus
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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
        $('#barangTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [5] } // Kolom aksi tidak bisa diurutkan
            ],
            "order": [[0, 'asc']],
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        });

        // Event hapus barang
        $('.delete-barang').on('click', function() {
            var barangId = $(this).data('id');
            var barangName = $(this).data('name');
            Swal.fire({
                title: "⚠️ Hapus Barang?",
                text: "Barang '" + barangName + "' akan dihapus secara permanen!",
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
                    fetch("{{ url('/barangs') }}/" + barangId, {
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
                                text: "Barang berhasil dihapus.",
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
                                text: "Terjadi kesalahan saat menghapus barang.",
                                confirmButtonColor: "#dc3545"
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire({
                            icon: "error",
                            title: "❌ Kesalahan Server",
                            text: "Tidak dapat menghapus barang saat ini.",
                            confirmButtonColor: "#dc3545"
                        });
                    });
                }
            });
        });
    });
</script>
@endpush

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
