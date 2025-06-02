@extends('templates.app')

@section('title', 'Data Customer')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-users me-2 text-primary"></i>Data Customer
                            </h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="fas fa-user-plus me-1"></i>Tambah Customer
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="customerTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4" width="40">#</th>
                                    <th>Nama Customer</th>
                                    <th>Alamat</th>
                                    <th>Kontak</th>
                                    <th class="text-end px-4" width="130">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="customerTableBody">
                                @foreach ($customers as $customer)
                                <tr>
                                    <td class="px-4 text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div>
                                                <span class="fw-medium d-block">{{ $customer->nama_customer }}</span>
                                                @if($customer->email)
                                                    <span class="text-muted small">
                                                        <i class="fas fa-envelope me-1"></i>{{ $customer->email }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="fas fa-map-marker-alt text-info"></i>
                                            </div>
                                            <span class="text-muted small">{{ $customer->alamat ?: '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="https://wa.me/{{ $customer->telepon }}" 
                                            class="text-decoration-none d-inline-flex align-items-center" 
                                            target="_blank">
                                            <span class="avatar-sm bg-success-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="fab fa-whatsapp text-success"></i>
                                            </span>
                                            <span class="text-body">{{ $customer->telepon }}</span>
                                        </a>
                                    </td>
                                    <td class="text-end px-4">
                                        <a href="{{ route('customers.edit', $customer->id) }}" 
                                            class="btn btn-warning btn-sm rounded-pill px-3 me-1">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <button type="button" 
                                            class="btn btn-danger btn-sm rounded-pill px-3 delete-customer"
                                            data-id="{{ $customer->id }}"
                                            data-name="{{ $customer->nama_customer }}">
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

.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.table-hover > tbody > tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
        $('#customerTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [4] } // Kolom aksi tidak bisa diurutkan
            ],
            "order": [[0, 'asc']],
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        });

        // Event hapus customer
        $('.delete-customer').on('click', function() {
            var customerId = $(this).data('id');
            var customerName = $(this).data('name');
            Swal.fire({
                title: "⚠️ Hapus Customer?",
                text: "Customer '" + customerName + "' akan dihapus secara permanen!",
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
                    fetch("{{ url('/customers') }}/" + customerId, {
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
                                text: "Customer berhasil dihapus.",
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
                                text: "Terjadi kesalahan saat menghapus customer.",
                                confirmButtonColor: "#dc3545"
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire({
                            icon: "error",
                            title: "❌ Kesalahan Server",
                            text: "Tidak dapat menghapus customer saat ini.",
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

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    /* ... existing styles ... */

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length select {
        padding: 0.25rem 2rem 0.25rem 0.5rem;
    }
    .dataTables_wrapper .dataTables_filter {
        float: right;
        margin-right: 1rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        padding: 0.25rem 0.5rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        margin-left: 0.5rem;
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
    /* Tambahan untuk memperbaiki layout DataTables */
    .dataTables_wrapper .row {
        margin: 0;
        align-items: center;
    }
    .dataTables_wrapper .col-sm-12 {
        padding: 0;
    }
    .dataTables_wrapper .dataTables_filter label {
        margin-bottom: 0;
    }
</style>
@endpush

