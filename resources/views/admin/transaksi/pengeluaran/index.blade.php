@extends('templates.app')

@section('title', 'Pengeluaran')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Pengeluaran</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-3">
            <a href="{{ route('pengeluaran.create') }}" class="btn btn-primary px-2 py-2">Tambah Pengeluaran</a>
        </div>
    </div>
    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Jumlah Pengeluaran</th>
                    <th scope="col">Sumber</th>
                    <th scope="col">Deskripsi</th>
                    <th scope="col">Foto Nota</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $expense->transaction_date }}</td>
                    <td>{{ number_format($expense->amount, 2, ',', '.') }}</td>
                    <td>{{ $expense->sumber }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>
                        @if ($expense->receipt)
                            <img src="{{ asset('storage/' . $expense->receipt) }}" alt="Nota" style="width: 100px; height: auto;">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pengeluaran.edit', $expense->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('pengeluaran.destroy', $expense->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pengeluaran ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection