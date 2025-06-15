@extends('templates.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">Edit Transaksi</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form enctype="multipart/form-data" method="POST" action="{{ route('transaksi.update', $transaction->id) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label for="nama_toko" class="form-label fw-bold">
                                <i class="fas fa-store text-primary me-1"></i>
                                Nama Toko
                            </label>
                            <input type="text" name="nama_toko" id="nama_toko" class="form-control" required 
                                value="{{ old('nama_toko', $transaction->nama_toko) }}" placeholder="Masukkan nama toko">
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                Alamat
                            </label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="3" required 
                                placeholder="Masukkan alamat toko">{{ old('alamat', $transaction->alamat) }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="amount" class="form-label fw-bold">
                                <i class="fas fa-money-bill-wave text-success me-1"></i>
                                Jumlah
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="amount" id="amount" class="form-control" required 
                                    value="{{ old('amount', $transaction->amount) }}">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="date" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt text-primary me-1"></i>
                                Tanggal
                            </label>
                            <input type="date" name="date" id="date" class="form-control" required 
                                value="{{ old('date', $transaction->transaction_date) }}">
                        </div>
                        
                        <div class="mb-4">
                            <label for="receipt" class="form-label fw-bold">
                                <i class="fas fa-file-image text-primary me-1"></i>
                                Foto Nota
                            </label>
                            <input type="file" name="receipt" id="receipt" class="form-control" accept="image/*">
                            @if ($transaction->receipt)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $transaction->receipt) }}" 
                                         alt="Nota" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px;"
                                         onclick="showImage(this.src)">
                                </div>
                            @endif
                        </div>

                        @if($transaction->accuracies)
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-chart-line text-primary me-1"></i>
                                Akurasi OCR
                            </label>
                            <div class="d-flex gap-2">
                                @foreach(json_decode($transaction->accuracies, true) as $field => $accuracy)
                                    <span class="badge bg-{{ $accuracy >= 80 ? 'success' : ($accuracy >= 60 ? 'warning' : 'danger') }}">
                                        {{ ucfirst($field) }}: {{ $accuracy }}%
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($transaction->raw_text)
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-file-alt text-primary me-1"></i>
                                Teks Mentah OCR
                            </label>
                            <div class="p-3 bg-light rounded">
                                <pre class="mb-0 small">{{ $transaction->raw_text }}</pre>
                            </div>
                        </div>
                        @endif
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Perbarui Transaksi
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Nota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showImage(src) {
        const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        document.getElementById('modalImage').src = src;
        modal.show();
    }
</script>
@endpush