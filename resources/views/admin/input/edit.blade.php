@extends('templates.app')

@section('title', 'Edit Faktur')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-file-invoice me-2 text-primary"></i>Edit Faktur
                            </h5>
                            <p class="text-muted small mb-0">Silakan edit informasi faktur sesuai kebutuhan</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('input') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('input.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <!-- Informasi Faktur -->
                            <div class="col-md-12">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm bg-primary-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-info-circle text-primary"></i>
                                    </div>
                                    <h6 class="mb-0">Informasi Faktur</h6>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-hashtag me-1 text-primary"></i>
                                                Nomor Tagihan
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                name="nomor_tagihan" 
                                                class="form-control @error('nomor_tagihan') is-invalid @enderror" 
                                                value="{{ old('nomor_tagihan', $transaction->nomor_tagihan) }}"
                                                required>
                                            @error('nomor_tagihan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-calendar me-1 text-success"></i>
                                                Tanggal
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" 
                                                name="tanggal" 
                                                class="form-control @error('tanggal') is-invalid @enderror"
                                                value="{{ old('tanggal', $transaction->due_date) }}"
                                                required>
                                            @error('tanggal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Customer -->
                            <div class="col-md-12">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm bg-info-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user text-info"></i>
                                    </div>
                                    <h6 class="mb-0">Informasi Customer</h6>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user-circle me-1 text-info"></i>
                                                Nama Customer
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                name="nama_customer" 
                                                class="form-control @error('nama_customer') is-invalid @enderror"
                                                value="{{ old('nama_customer', $transaction->nama_customer) }}"
                                                required>
                                            @error('nama_customer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fab fa-whatsapp me-1 text-success"></i>
                                                Nomor WhatsApp
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">+62</span>
                                                <input type="text" 
                                                    name="nomor_wa" 
                                                    class="form-control @error('nomor_wa') is-invalid @enderror"
                                                    value="{{ old('nomor_wa', $transaction->nomor_whatsapp) }}"
                                                    placeholder="Contoh: 81234567890"
                                                    required>
                                            </div>
                                            @error('nomor_wa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-sticky-note me-1 text-warning"></i>
                                                Keterangan
                                            </label>
                                            <textarea 
                                                name="keterangan" 
                                                class="form-control @error('keterangan') is-invalid @enderror" 
                                                rows="3"
                                                style="resize: none;">{{ old('keterangan', $transaction->keterangan) }}</textarea>
                                            @error('keterangan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Barang -->
                            <div class="col-md-12">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm bg-warning-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-box text-warning"></i>
                                    </div>
                                    <h6 class="mb-0">Detail Barang</h6>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr class="text-center">
                                                <th style="width: 5%">No</th>
                                                <th style="width: 15%">Banyaknya</th>
                                                <th style="width: 35%">Nama Barang</th>
                                                <th style="width: 20%">Harga</th>
                                                <th style="width: 25%">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transaction->details as $index => $detail)
                                            <tr>
                                                <td>
                                                    <input type="number" 
                                                        name="no[]" 
                                                        value="{{ $detail->no }}" 
                                                        class="form-control form-control-sm text-center" 
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                        name="banyaknya[]" 
                                                        value="{{ $detail->banyaknya }}" 
                                                        class="form-control form-control-sm text-end" 
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="text" 
                                                        name="nama_barang[]" 
                                                        value="{{ $detail->nama_barang }}" 
                                                        class="form-control form-control-sm" 
                                                        required>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" 
                                                            name="harga[]" 
                                                            value="{{ $detail->harga }}" 
                                                            class="form-control text-end" 
                                                            required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" 
                                                            name="jumlah[]" 
                                                            value="{{ $detail->jumlah }}" 
                                                            class="form-control text-end bg-light" 
                                                            readonly>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Total Bayar -->
                            <div class="col-md-12">
                                <div class="row justify-content-end">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-money-bill-wave me-1 text-success"></i>
                                                Total Bayar
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" 
                                                    name="totalbayar" 
                                                    class="form-control @error('totalbayar') is-invalid @enderror"
                                                    value="{{ old('totalbayar', $transaction->totalbayar) }}"
                                                    required>
                                            </div>
                                            @error('totalbayar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('input') }}" class="btn btn-secondary rounded-pill px-4 me-2">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
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

.form-label {
    font-weight: 500;
}

.rounded-pill {
    border-radius: 50rem !important;
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 25%);
}

.input-group-text {
    background-color: #f8f9fa;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.form-control {
    border-radius: 0.5rem;
}

.input-group .form-control {
    border-start-start-radius: 0;
    border-end-start-radius: 0;
}

.input-group-text {
    border-start-start-radius: 0.5rem;
    border-end-start-radius: 0.5rem;
}

.table {
    border-radius: 0.5rem;
    overflow: hidden;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
}

.form-control-sm {
    height: calc(1.5em + 0.5rem + 2px);
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
}
</style>

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: '❌ Gagal!',
        text: "{{ session('error') }}",
        confirmButtonColor: '#dc3545',
        showConfirmButton: false,
        timer: 2000
    });
</script>
@endif

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '✅ Berhasil!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#198754',
        showConfirmButton: false,
        timer: 2000
    });
</script>
@endif

@endsection

