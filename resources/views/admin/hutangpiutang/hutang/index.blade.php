@extends('templates.app')

@section('title', 'Manajemen Hutang')
<script src="https://code.highcharts.com/highcharts.js"></script>

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Manajemen Hutang</h1> <!-- Judul Utama -->

    <div class="row">
        <form enctype="multipart/form-data" method="POST" action="{{ route('hutang.store') }}">
            @csrf <!-- Jika menggunakan Laravel, jangan lupa untuk menambahkan token CSRF -->
            <div class="mb-3">
                <label for="pemberiPinjaman" class="form-label">Pihak Pemberi Pinjaman</label>
                <input type="text" class="form-control" id="pemberiPinjaman" name="pemberiPinjaman" placeholder="Masukkan nama pemberi pinjaman" required>
            </div>
            <div class="mb-3">
                <label for="jumlahHutang" class="form-label">Jumlah Hutang</label>
                <input type="number" class="form-control" id="jumlahHutang" name="jumlahHutang" placeholder="Masukkan jumlah hutang" required>
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
            <button type="submit" class="btn btn-success">Simpan Hutang</button>
            <button type="button" class="btn btn-primary ms-2" id="searchButton">Cari</button> <!-- Tombol Cari -->
            <button type="button" class="btn btn-primary ms-2" id="searchButton">Scan Nota</button>
            <input type="text" class="form-control ms-2" id="searchInput" placeholder="Cari..." style="display: inline-block; width: auto;">
        </form>
    </div>

    <div class="row mt-4">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Pemberi Pinjaman</th>
                    <th scope="col">Jumlah Hutang</th>
                    <th scope="col">Tanggal Jatuh Tempo</th>
                    <th scope="col">Pengingat</th>
                    <th scope="col">Foto Nota</th> <!-- Kolom Foto Nota -->
                    <th scope="col">Aksi</th> <!-- Kolom Aksi -->
                </tr>
            </thead>
            <tbody>
                @foreach ($debts as $debt)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $debt->creditor }}</td>
                    <td>Rp {{ number_format($debt->amount, 2, ',', '.') }}</td>
                    <td>{{ $debt->due_date }}</td>
                    <td>{{ $debt->reminder_date ?? 'Tidak ada' }}</td>
                    <td>
                        @if ($debt->receipt)
                            <img src="{{ asset('storage/' . $debt->receipt) }}" alt="Nota" style="width: 100px; height: auto;">
                        @else
                            <span>Tidak ada nota</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('hutang.edit', $debt->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('hutang.destroy', $debt->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus hutang ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.highcharts.com/10/highcharts.js"></script>
@endsection