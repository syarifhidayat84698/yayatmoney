@extends('templates.app')

@section('title', 'Data Hutang')

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                                <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Data Hutang
                            </h5>
                        </div>
                        <div class="col-auto">
                            <!-- <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control form-control-sm border-start-0 ps-0" 
                                    id="searchCustomer" 
                                    placeholder="Cari nama customer..."
                                    style="min-width: 200px;">
                            </div> -->
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="hutangTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4" width="40">#</th>
                                    <th>No Tagihan</th>
                                    <th>Nama Customer</th>
                                    <th>No. WhatsApp</th>
                                    <th>Jatuh Tempo</th>
                                    <th class="text-end">Total Harga</th>
                                    <th class="text-end">Total Hutang</th>
                                    <th class="text-end">Sisa Hutang</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th class="text-end px-4" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="customerTableBody">
                                @foreach ($hutangs as $index => $hutang)
                                <tr>
                                    <td class="px-4 text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="fw-medium text-primary">{{ $hutang->nomor_tagihan }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <span class="fw-medium">{{ $hutang->customer->nama_customer }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="https://wa.me/{{ $hutang->customer->telepon }}" 
                                            class="text-decoration-none d-inline-flex align-items-center" 
                                            target="_blank">
                                            <span class="avatar-sm bg-success-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="fab fa-whatsapp text-success"></i>
                                            </span>
                                            <span class="text-body">{{ $hutang->customer->telepon }}</span>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-inline-flex align-items-center px-2 py-1 rounded-pill 
                                            {{ strtotime($hutang->due_date) < time() ? 'bg-danger-subtle text-danger' : 'bg-light text-body' }}">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($hutang->due_date)->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-medium">Rp {{ number_format($hutang->total_tagihan, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-medium">Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-medium {{ $hutang->sisa_hutang > 0 ? 'text-danger' : '' }}">
                                            Rp {{ number_format($hutang->sisa_hutang, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($hutang->status == 'Lunas')
                                            <div class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">
                                                <i class="fas fa-check-circle me-1"></i>Lunas
                                            </div>
                                        @else
                                            <div class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill">
                                                <i class="fas fa-clock me-1"></i>Belum Lunas
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $hutang->keterangan ?: '-' }}</span>
                                    </td>
                                    <td class="text-end px-4">
                                        @if($hutang->status != 'Lunas')
                                            <button type="button" 
                                                class="btn btn-sm btn-success rounded-pill px-3 bayar-hutang" 
                                                data-id="{{ $hutang->id }}"
                                                data-tagihan="{{ $hutang->nomor_tagihan }}"
                                                data-customer="{{ $hutang->customer->nama_customer }}"
                                                data-total="{{ $hutang->total_hutang }}"
                                                data-sisa="{{ $hutang->sisa_hutang }}">
                                                <i class="fas fa-money-bill-wave me-1"></i>Bayar
                                            </button>
                                        @endif
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

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.table-hover > tbody > tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}
</style>

@if (session('success'))
    <script>
        Swal.fire({
            icon: "success",
            title: "✅ Berhasil!",
            text: "{{ session('success') }}",
            confirmButtonColor: "#28a745",
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

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
        // Initialize DataTable
        const table = $('#hutangTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [10] } // Disable sorting on the "Aksi" column
            ],
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            "responsive": true
        });

        // Rest of your existing JavaScript code
        document.querySelectorAll('.bayar-hutang').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const tagihan = this.getAttribute('data-tagihan');
                const customer = this.getAttribute('data-customer');
                const totalHutang = this.getAttribute('data-total');
                const sisaHutang = this.getAttribute('data-sisa');
                
                // Fetch payment history
                fetch(`/hutangs/${id}/history`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(history => {
                        let historyHtml = '';
                        if (history.length > 0) {
                            historyHtml = `
                                <div class="mt-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-history text-primary me-2"></i>
                                        <h6 class="mb-0">Riwayat Pembayaran</h6>
                                    </div>
                                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                        <table class="table table-sm table-hover border">
                                            <thead class="bg-light sticky-top">
                                                <tr>
                                                    <th class="px-3">Tanggal</th>
                                                    <th class="text-end px-3">Jumlah Bayar</th>
                                                    <th class="px-3">Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${history.map(item => `
                                                    <tr>
                                                        <td class="px-3">
                                                            <i class="far fa-calendar-alt text-muted me-1"></i>
                                                            ${new Date(item.created_at).toLocaleDateString('id-ID', {
                                                                day: 'numeric',
                                                                month: 'long',
                                                                year: 'numeric'
                                                            })}
                                                        </td>
                                                        <td class="text-end px-3">
                                                            <span class="fw-medium">Rp ${formatRupiah(item.jumlah_bayar)}</span>
                                                        </td>
                                                        <td class="px-3 text-muted">${item.keterangan || '-'}</td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                            <tfoot class="bg-light fw-bold">
                                                <tr>
                                                    <td class="px-3">Total Pembayaran</td>
                                                    <td class="text-end px-3">Rp ${formatRupiah(history.reduce((sum, item) => sum + parseFloat(item.jumlah_bayar), 0))}</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            `;
                        }

                        Swal.fire({
                            title: '<i class="fas fa-money-check-alt me-2"></i>Pelunasan Hutang',
                            html: `
                                <div class="text-start">
                                    <div class="card border-0 bg-light mb-3">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-file-invoice fa-fw text-primary me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">No Tagihan</small>
                                                            <strong>${tagihan}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user fa-fw text-primary me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Customer</small>
                                                            <strong>${customer}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-money-bill-alt fa-fw text-primary me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Total Hutang</small>
                                                            <strong>Rp ${formatRupiah(totalHutang)}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-wallet fa-fw text-primary me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Sisa Hutang</small>
                                                            <strong class="text-danger">Rp ${formatRupiah(sisaHutang)}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="jumlah_bayar" class="form-label">
                                            <i class="fas fa-hand-holding-usd text-primary me-2"></i>Jumlah Bayar
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" id="jumlah_bayar" class="form-control" 
                                                placeholder="Masukkan jumlah pembayaran"
                                                max="${sisaHutang}">
                                        </div>
                                        <small class="text-muted">Maksimal pembayaran: Rp ${formatRupiah(sisaHutang)}</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="keterangan_bayar" class="form-label">
                                            <i class="fas fa-sticky-note text-primary me-2"></i>Keterangan
                                        </label>
                                        <textarea id="keterangan_bayar" class="form-control" rows="2" 
                                            placeholder="Tambahkan keterangan pembayaran (opsional)"></textarea>
                                    </div>

                                    ${historyHtml}
                                </div>
                            `,
                            showCancelButton: true,
                            confirmButtonText: '<i class="fas fa-check me-2"></i>Bayar',
                            cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#dc3545',
                            width: '700px',
                            customClass: {
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-danger'
                            },
                            preConfirm: () => {
                                const jumlahBayar = document.getElementById('jumlah_bayar').value;
                                const keterangan = document.getElementById('keterangan_bayar').value;
                                
                                if (!jumlahBayar) {
                                    Swal.showValidationMessage('Jumlah pembayaran harus diisi');
                                    return false;
                                }

                                if (parseFloat(jumlahBayar) > parseFloat(sisaHutang)) {
                                    Swal.showValidationMessage('Jumlah pembayaran tidak boleh melebihi sisa hutang');
                                    return false;
                                }
                                
                                return { jumlahBayar, keterangan };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Show loading state
                                Swal.fire({
                                    title: 'Memproses pembayaran...',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                // Submit the form
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = `/hutangs/${id}/bayar`;
                                form.style.display = 'none';
                                
                                const csrfToken = document.createElement('input');
                                csrfToken.type = 'hidden';
                                csrfToken.name = '_token';
                                csrfToken.value = '{{ csrf_token() }}';
                                form.appendChild(csrfToken);
                                
                                const jumlahBayarInput = document.createElement('input');
                                jumlahBayarInput.type = 'hidden';
                                jumlahBayarInput.name = 'jumlah_bayar';
                                jumlahBayarInput.value = result.value.jumlahBayar;
                                form.appendChild(jumlahBayarInput);
                                
                                const keteranganInput = document.createElement('input');
                                keteranganInput.type = 'hidden';
                                keteranganInput.name = 'keterangan';
                                keteranganInput.value = result.value.keterangan;
                                form.appendChild(keteranganInput);
                                
                                document.body.appendChild(form);
                                form.submit();
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat mengambil data pembayaran. Silakan coba lagi.',
                            confirmButtonColor: '#dc3545'
                        });
                    });
            });
        });
    });
    
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }
</script>
@endpush