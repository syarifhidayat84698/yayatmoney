@extends('templates.app')

@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Tambah Pengeluaran</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pendapatan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="nama_toko" class="form-label fw-bold">Nama Toko</label>
                            <input type="text" name="nama_toko" id="nama_toko" class="form-control" required placeholder="Masukkan nama toko">
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="3" required placeholder="Masukkan alamat toko"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="form-label fw-bold">Jumlah Pengeluaran</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="amount" id="amount" class="form-control" required placeholder="Masukkan jumlah">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="date" class="form-label fw-bold">Tanggal Transaksi</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label for="receipt" class="form-label fw-bold">Foto Nota</label>
                            <input type="file" name="receipt" id="receipt" class="form-control" accept="image/*">
                            <div class="form-text text-muted">Upload foto nota dalam format JPG, PNG, atau PDF</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-save me-2"></i>Simpan Pengeluaran
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection