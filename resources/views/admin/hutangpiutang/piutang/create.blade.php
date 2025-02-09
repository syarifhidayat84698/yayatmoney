@extends('templates.app')

@section('title', 'Tambah Piutang')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Tambah Piutang</h1>

    <form enctype="multipart/form-data" method="POST" action="{{ route('piutang.store') }}">
        @csrf
        <div class="mb-3">
            <label for="pihakBerhutang" class="form-label">Pihak yang Berhutang</label>
            <input type="text" class="form-control" id="pihakBerhutang" name="pihakBerhutang" placeholder="Masukkan nama pihak yang berhutang" required>
        </div>
        <div class="mb-3">
            <label for="jumlahPiutang" class="form-label">Jumlah Piutang</label>
            <input type="number" class="form-control" id="jumlahPiutang" name="jumlahPiutang" placeholder="Masukkan jumlah piutang" required>
        </div>
        <div class="mb-3">
            <label for="tanggalJatuhTempo" class="form-label">Tanggal Jatuh Tempo</label>
            <input type="date" class="form-control" id="tanggalJatuhTempo" name="tanggalJatuhTempo" required>
        </div>
        <div class="mb-3">
            <label for="pengingat" class="form-label">Pengingat Pembayaran</label>
            <input type="date" class="form-control" id="pengingat" name="pengingat">
        </div>
        <div class="mb-3">
            <label for="nota" class="form-label">Upload Foto Nota</label>
            <input type="file" class="form-control" id="nota" name="nota" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan Piutang</button>
        <a href="{{ route('piutang.index') }}" class="btn btn-secondary ms-2">Kembali</a>
    </form>
</div>
@endsection