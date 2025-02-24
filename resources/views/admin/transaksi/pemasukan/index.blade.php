@extends('templates.app')

@section('title', 'Pemasukan')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">💰 Pemasukan</h1>
    
    @if (session('success'))
        <script>
            Swal.fire({
                icon: "success",
                title: "✅ Berhasil!",
                text: "{{ session('success') }}",
                confirmButtonColor: "#28a745"
            });
        </script>
    @endif

    <div class="row mb-3">
        <div class="col-md-4">
            <a href="{{ route('tambah_pemasukan') }}" class="btn btn-primary px-3 py-2">
                ➕ Tambah Pemasukan
            </a>
            <a href="{{ route('ocr') }}" class="btn btn-secondary px-3 py-2">
                📄 Scan Nota
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>No Tagihan</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jumlah Pendapatan</th>
                    <th>Foto Nota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->nomor_tagihan }}</td>
                    <td>{{ $transaction->nama_customer }}</td>
                    <td>{{ $transaction->transaction_date }}</td>
                    <td><strong>Rp {{ number_format($transaction->amount, 2, ',', '.') }}</strong></td>
                    <td>
                        @if ($transaction->receipt)
                            <a href="{{ asset('storage/' . $transaction->receipt) }}" target="_blank">
                                <img src="{{ asset('storage/' . $transaction->receipt) }}" alt="Nota" style="width: 100px; height: auto; border-radius: 5px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">
                            </a>
                            <br>
                            <a href="{{ asset('storage/' . $transaction->receipt) }}" download class="btn btn-sm btn-success mt-2">
                                ⬇️ Download
                            </a>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('transaksi.edit', $transaction->id) }}" class="btn btn-warning btn-sm">
                            ✏️ Edit
                        </a>
                        <button onclick="confirmDelete({{ $transaction->id }})" class="btn btn-danger btn-sm">
                            🗑 Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(transactionId) {
        Swal.fire({
            title: "⚠️ Hapus Transaksi?",
            text: "Data ini akan dihapus secara permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch("{{ url('/transaksi/delete') }}/" + transactionId, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "✅ Berhasil!",
                            text: "Transaksi berhasil dihapus.",
                            confirmButtonColor: "#28a745"
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "❌ Gagal!",
                            text: "Terjadi kesalahan saat menghapus transaksi.",
                            confirmButtonColor: "#dc3545"
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "❌ Kesalahan Server",
                        text: "Tidak dapat menghapus transaksi saat ini.",
                        confirmButtonColor: "#dc3545"
                    });
                });
            }
        });
    }
</script>


@endsection
