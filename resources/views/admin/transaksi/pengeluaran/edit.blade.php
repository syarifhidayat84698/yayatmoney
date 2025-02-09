@extends('templates.app')

@section('title', 'Edit Pengeluaran')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Edit Pengeluaran</h2>
    <form enctype="multipart/form-data" method="POST" action="{{ route('pengeluaran.update', $expense->id) }}">
        @csrf
        @method('PATCH') <!-- Menandakan bahwa ini adalah permintaan PATCH -->
        
        <div class="mb-3">
            <label for="amount" class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="amount" name="amount" value="{{ $expense->amount }}" required>
        </div>
        
        <div class="mb-3">
            <label for="date" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ $expense->transaction_date }}" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $expense->description }}</textarea>
        </div>
        
        <div class="mb-3">
            <label for="sumber" class="form-label">Sumber Pengeluaran</label>
            <input type="text" class="form-control" id="sumber" name="sumber" value="{{ $expense->sumber }}" required>
        </div>
        
        <div class="mb-3">
            <label for="receipt" class="form-label">Upload Foto Nota</label>
            <input type="file" class="form-control" id="receipt" name="receipt" accept="image/*">
            @if ($expense->receipt)
                <img src="{{ asset('storage/' . $expense->receipt) }}" alt="Nota" style="width: 100px; height: auto;">
            @endif
        </div>
        
        <button type="submit" class="btn btn-primary">Perbarui Pengeluaran</button>
    </form>
</div>
@endsection