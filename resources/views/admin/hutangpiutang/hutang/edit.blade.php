@extends('templates.app')

@section('title', 'Edit Hutang')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Hutang</h1>

    <form enctype="multipart/form-data" method="POST" action="{{ route('hutang.update', $debt->id) }}">
        @csrf
        @method('PATCH') <!-- Menggunakan metode PATCH untuk update -->
        <div class="mb-3">
            <label for="pemberiPinjaman" class="form-label">Pihak Pemberi Pinjaman</label>
            <input type="text" class="form-control" id="pemberiPinjaman" name="pemberiPinjaman" value="{{ $debt->creditor }}" required>
        </div>
        <div class="mb-3">
            <label for="jumlahHutang" class="form-label">Jumlah Hutang</label>
            <input type="number" class="form-control" id="jumlahHutang" name="jumlahHutang" value="{{ $debt->amount }}" required>
        </div>
        <div class="mb-3">
            <label for="tanggalJatuhTempo" class="form-label">Tanggal Jatuh Tempo</label>
            <input type="date" class="form-control" id="tanggalJatuhTempo" name="tanggalJatuhTempo" value="{{ $debt->due_date }}" required>
        </div>
        <div class="mb-3">
            <label for="pengingat" class="form-label">Pengingat Pembayaran</label>
            <input type="date" class="form-control" id="pengingat" name="pengingat" value="{{ $debt->reminder_date }}">
        </div>
        <div class="mb-3">
            <label for="nota" class="form-label">Upload Foto Nota (Kosongkan jika tidak ingin mengubah)</label>
            <input type="file" class="form-control" id="nota" name="nota" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success">Update Hutang</button>
        <a href="{{ route('hutang.index') }}" class="btn btn-secondary ms-2">Kembali</a>
    </form>
</div>
@endsection