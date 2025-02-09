@extends('templates.app')

@section('title', 'Pemasukan')
<script src="https://code.highcharts.com/highcharts.js"></script>
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Pemasukan</h1>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-3">
            <a href="{{ route('tambah_pemasukan') }}" class="btn btn-primary px-2 py-2">Tambah Pemasukan</a>
            <a href="{{ route('tambah_pemasukan') }}" class="btn btn-primary px-2 py-2">Scan Nota</a>
        </div>
    </div>
    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Jumlah Pendapatan</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Sumber</th> <!-- Tambahkan kolom sumber -->
                    <th scope="col">Deskripsi</th>
                    <th scope="col">Foto Nota</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $transaction->transaction_date }}</td>
                    <td>{{ number_format($transaction->amount, 2, ',', '.') }}</td>
                    <td>{{ $transaction->type }}</td>
                    <td>{{ $transaction->sumber }}</td> <!-- Tampilkan sumber -->
                    <td>{{ $transaction->description }}</td>
                    <td>
                        @if ($transaction->receipt)
                            <img src="{{ asset('storage/' . $transaction->receipt) }}" alt="Nota" style="width: 100px; height: auto;">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('transaksi.edit', $transaction->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('transaksi.destroy', $transaction->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">Hapus</button>
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