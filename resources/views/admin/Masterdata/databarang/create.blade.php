@extends('templates.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-box-open me-2 text-primary"></i>Tambah Barang Baru
                            </h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('barangs.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('barangs.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Informasi Produk -->
                            <div class="col-md-12">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm bg-primary-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-box text-primary"></i>
                                    </div>
                                    <h6 class="mb-0">Informasi Produk</h6>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-box me-1 text-primary"></i>
                                                Nama Barang
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                name="nama_barang" 
                                                class="form-control @error('nama_barang') is-invalid @enderror" 
                                                value="{{ old('nama_barang') }}"
                                                placeholder="Masukkan nama barang"
                                                required>
                                            @error('nama_barang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-money-bill-wave me-1 text-success"></i>
                                                Harga
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" 
                                                    name="harga" 
                                                    class="form-control @error('harga') is-invalid @enderror"
                                                    value="{{ old('harga') }}"
                                                    placeholder="Masukkan harga barang"
                                                    min="0"
                                                    required>
                                            </div>
                                            @error('harga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-cubes me-1 text-info"></i>
                                                Stok
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                name="stok" 
                                                class="form-control @error('stok') is-invalid @enderror"
                                                value="{{ old('stok') }}"
                                                placeholder="Masukkan jumlah stok"
                                                min="0"
                                                required>
                                            @error('stok')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-align-left me-1 text-secondary"></i>
                                                Deskripsi
                                            </label>
                                            <textarea 
                                                name="deskripsi" 
                                                class="form-control @error('deskripsi') is-invalid @enderror" 
                                                rows="3"
                                                placeholder="Masukkan deskripsi barang (opsional)">{{ old('deskripsi') }}</textarea>
                                            @error('deskripsi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('barangs.index') }}" class="btn btn-secondary rounded-pill px-4 me-2">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
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
