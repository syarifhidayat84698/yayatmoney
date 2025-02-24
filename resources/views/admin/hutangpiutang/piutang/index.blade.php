@extends('templates.app')

@section('title', 'Manajemen Piutang')
<script src="https://code.highcharts.com/highcharts.js"></script>

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Manajemen Piutang</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row mb-3">
        <div class="col">
            <a href="{{ route('piutang.create') }}" class="btn btn-primary">Tambah Piutang</a>
            <a href="{{ route('piutang.create') }}" class="btn btn-primary">Scan Nota</a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Daftar Piutang</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Pihak yang Berhutang</th>
                                <th scope="col">Jumlah Piutang</th>
                                <th scope="col">Tanggal Jatuh Tempo</th>
                                <th scope="col">Pengingat</th>
                                <th scope="col">Foto Nota</th>
                                <th scope="col">Status</th> <!-- Kolom Status -->
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($credits as $credit)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $credit->debtor }}</td>
                                <td>Rp {{ number_format($credit->amount, 2, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($credit->due_date)->format('d-m-Y') }}</td>
                                <td>{{ $credit->reminder_date ? \Carbon\Carbon::parse($credit->reminder_date)->format('d-m-Y') : 'Tidak ada' }}</td>
                                <td>
                                    @if ($credit->receipt)
                                        <img src="{{ asset('storage/' . $credit->receipt) }}" alt="Nota" style="width: 100px; height: auto;">
                                    @else
                                        <span>Tidak ada nota</span>
                                    @endif
                                </td>
                                <td>{{ $credit->status }}</td> <!-- Menampilkan status piutang -->
                                <td>
                                    <a href="{{ route('piutang.edit', $credit->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('piutang.destroy', $credit->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus piutang ini?')">Hapus</button>
                                    </form>
                                    <form action="{{ route('piutang.markAsPaid', $credit->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-info btn-sm">Tandai Terbayar</button>
                                    </form>
                                    <a href="https://wa.me/?text=Pengingat%20pembayaran%20piutang%20dari%20{{ urlencode($credit->debtor) }}%20sebesar%20Rp%20{{ urlencode(number_format($credit->amount, 2, ',', '.')) }}%20jatuh%20tempo%20pada%20{{ urlencode($credit->due_date) }}" class="btn btn-success btn-sm" target="_blank">Kirim Pengingat WhatsApp</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada piutang yang ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.highcharts.com/10/highcharts.js"></script>
@endsection