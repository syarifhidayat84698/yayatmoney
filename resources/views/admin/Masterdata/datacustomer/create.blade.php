@extends('templates.app')

@section('title', 'Tambah Customer')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-user-plus me-2 text-primary"></i>Tambah Customer Baru
                            </h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Informasi Utama -->
                            <div class="col-md-12">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm bg-primary-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <h6 class="mb-0">Informasi Utama</h6>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user-circle me-1 text-primary"></i>
                                                Nama Customer
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                name="nama_customer" 
                                                class="form-control @error('nama_customer') is-invalid @enderror" 
                                                value="{{ old('nama_customer') }}"
                                                placeholder="Masukkan nama customer"
                                                required>
                                            @error('nama_customer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-envelope me-1 text-primary"></i>
                                                Email
                                            </label>
                                            <input type="email" 
                                                name="email" 
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}"
                                                placeholder="Masukkan email (opsional)">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kontak & Alamat -->
                            <div class="col-md-12">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm bg-info-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-address-card text-info"></i>
                                    </div>
                                    <h6 class="mb-0">Kontak & Alamat</h6>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fab fa-whatsapp me-1 text-success"></i>
                                                No. WhatsApp
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">+62</span>
                                                <input type="text" 
                                                    name="telepon" 
                                                    class="form-control @error('telepon') is-invalid @enderror"
                                                    value="{{ old('telepon') }}"
                                                    placeholder="Contoh: 81234567890"
                                                    required>
                                            </div>
                                            @error('telepon')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Masukkan nomor tanpa angka 0 di depan</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                                Alamat Lengkap
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea 
                                                name="alamat" 
                                                class="form-control @error('alamat') is-invalid @enderror" 
                                                rows="3"
                                                placeholder="Masukkan alamat lengkap"
                                                required>{{ old('alamat') }}</textarea>
                                            @error('alamat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="reset" class="btn btn-secondary rounded-pill px-4 me-2">
                                <i class="fas fa-redo me-1"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-save me-1"></i>Simpan
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

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 25%);
}

.input-group-text {
    background-color: #f8f9fa;
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

@endsection

