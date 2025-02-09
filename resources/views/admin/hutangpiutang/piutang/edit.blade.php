@extends('templates.app')

@section('title', 'Edit Piutang')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Piutang</h1>

    <form enctype="multipart/form-data" method="POST" action="{{ route('piutang.update', $credit->id) }}">
        @csrf
        @method('PATCH') <!-- Menggunakan metode PATCH untuk update -->
        <div class="mb-3">
            <label for="pihakBerhutang" class="form-label">Pihak yang Berhutang</label>
            <input type="text" class="form-control" id="pihakBerhutang" name="pihakBerhutang" value="{{ $credit->debtor }}" required>
        </div>
        <div class="mb-3">
            <label for="jumlahPiutang" class="form-label">Jumlah Piutang</label>
            <input type="number" class="form-control" id="jumlahPiutang" name="jumlahPiutang" value="{{ $credit->amount }}" required>
        </div>
        <div class="mb-3">
            <label for="tanggalJatuhTempo" class="form-label">Tanggal Jatuh Tempo</label>
            <input type="date" class="form-control" id="tanggalJatuhTempo" name="tanggalJatuhTempo" value="{{ $credit->due_date }}" required>
        </div>
        <div class="mb-3">
            <label for="pengingat" class="form-label">Pengingat Pembayaran</label>
            <input type="date" class="form-control" id="pengingat" name="pengingat" value="{{ $credit->reminder_date }}">
        </div>
        <div class="mb-3">
            <label for="nota" class="form-label">Upload Foto Nota (Kosongkan jika tidak ingin mengubah)</label>
            <input type="file" class="form-control" id="nota" name="nota" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success">Update Piutang</button>
        <a href="{{ route('piutang.index') }}" class="btn btn-secondary ms-2">Kembali</a>
    </form>
</div>
@endsection