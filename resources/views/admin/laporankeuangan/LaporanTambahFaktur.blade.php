@extends('templates.app')

@section('title', 'Tambah Faktur')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Tambah Faktur</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('input.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nomor_tagihan">Nomor Tagihan</label>
            <input type="text" name="nomor_tagihan" id="nomor_tagihan" class="form-control" value="{{ $uniq_noTagihan ?? '' }}" required>
        </div>

        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="nama_customer">Nama Customer</label>
            <select name="nama_customer" id="nama_customer" class="form-control" required>
                <option value="">Pilih Customer</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->nama_customer }}" data-telepon="{{ $customer->telepon }}">
                        {{ $customer->nama_customer }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="telepon">Nomor WhatsApp</label>
            <input type="text" name="telepon" id="telepon" class="form-control" required readonly>
        </div>

        <h3 class="mt-4">Detail Barang</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Banyaknya</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="barang_table_body">
                <tr>
                    <td><input type="number" name="no[]" value="1" class="form-control" required></td>
                    <td><input type="number" name="banyaknya[]" class="form-control" required oninput="hitungJumlah(this)"></td>
                    <td>
                        <select name="nama_barang[]" class="form-control" required onchange="updateHargaAndStok(this)">
                            <option value="">Pilih Barang</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" data-harga="{{ $barang->harga }}" data-stok="{{ $barang->stok }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="harga[]" class="form-control" required oninput="hitungJumlah(this)"></td>
                    <td><input type="text" name="jumlah[]" class="form-control" required readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusBarang(this)">Hapus</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-secondary" onclick="tambahBarang()">Tambah Barang</button>
        <br><br>

        <div class="form-group">
            <label for="total">Total</label>
            <input type="text" id="totalan" name="totalan" class="form-control" value="0" readonly>
            <input type="text" id="total" name="total" class="form-control" value="0" hidden>
        </div>

        <div class="form-group mt-4">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="">Pilih Status</option>
                <option value="Lunas">Lunas</option>
                <option value="Tidak Lunas">Tidak Lunas</option>
            </select>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="totalbayar">Total Bayar</label>
            <input type="number" name="totalbayar" id="totalbayar" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="kembalian">Kembalian</label>
            <input type="text" name="kembalian" id="kembalian" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Faktur</button>
    </form>
</div>

<script>
    // JavaScript functions for handling the form interactions
    // (Include the JavaScript functions from your original code here)
</script>
@endsection