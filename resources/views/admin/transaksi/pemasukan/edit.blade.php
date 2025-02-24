@extends('templates.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Edit Transaksi</h2>
    <form enctype="multipart/form-data" method="POST" action="{{ route('transaksi.update', $transaction->id) }}">
        @csrf
        @method('PATCH') <!-- Menandakan bahwa ini adalah permintaan PATCH -->
        
        <div class="mb-3">
            <label for="amount" class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="amount" name="amount" value="{{ $transaction->amount }}" required>
        </div>
        
        <div class="mb-3">
            <label for="date" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ $transaction->transaction_date }}" required>
        </div>
        
        
        <div class="mb-3">
            <label for="receipt" class="form-label">Upload Foto Nota</label>
            <input type="file" class="form-control" id="receipt" name="receipt" accept="image/*">
            @if ($transaction->receipt)
                <img src="{{ asset('storage/' . $transaction->receipt) }}" alt="Nota" style="width: 100px; height: auto;">
            @endif
        </div>
        
        <button type="submit" class="btn btn-primary">Perbarui Transaksi</button>
    </form>
</div>
@endsection