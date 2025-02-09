@extends('templates.app')

@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Tambah Pengeluaran</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengeluaran.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="amount">Jumlah Pengeluaran</label>
            <input type="number" name="amount" id="amount" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="date">Tanggal Transaksi</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="sumber">Sumber Pengeluaran</label>
            <input type="text" name="sumber" id="sumber" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="receipt">Foto Nota</label>
            <input type="file" name="receipt" id="receipt" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection