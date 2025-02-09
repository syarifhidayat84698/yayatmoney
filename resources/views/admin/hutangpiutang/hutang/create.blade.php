@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Hutang</h2>
    <form action="{{ route('hutang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="pemberiPinjaman">Pemberi Pinjaman</label>
            <input type="text" class="form-control" name="pemberiPinjaman" required>
        </div>
        <div class="form-group">
            <label for="jumlahHutang">Jumlah Hutang</label>
            <input type="number" class="form-control" name="jumlahHutang" required>
        </div>
        <div class="form-group">
            <label for="tanggalJatuhTempo">Tanggal Jatuh Tempo</label>
            <input type="date" class="form-control" name="tanggalJatuhTempo" required>
        </div>
        <div class="form-group">
            <label for="pengingat">Pengingat (opsional)</label>
            <input type="date" class="form-control" name="pengingat">
        </div>
        <div class="form-group">
            <label for="nota">Upload Nota (opsional)</label>
            <input type="file" class="form-control" name="nota" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Simpan Hutang</button>
    </form>
</div>
@endsection