@extends('templates.app')

@section('title', 'Pengingat Pembayaran')
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Pengingat Pembayaran</h1>

    <button class="btn btn-primary mb-4" id="addReminderBtn">Tambah Pengingat Baru</button>

    <div class="mb-3">
        <input type="text" class="form-control" id="searchReminder" placeholder="Cari Pengingat...">
    </div>

    <h3>Daftar Pengingat</h3>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">Nama Pengingat</th>
                <th scope="col">Tanggal Jatuh Tempo</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody id="reminderList">
            <!-- Data pengingat akan ditampilkan di sini -->
            <tr>
                <td>Pembayaran Sewa</td>
                <td>2024-02-01</td>
                <td>Rp 2.000.000</td>
                <td><span class="badge bg-warning">Belum Terbayar</span></td>
                <td>
                    <button class="btn btn-success btn-sm">Tandai Terbayar</button>
                    <button class="btn btn-danger btn-sm">Hapus</button>
                </td>
            </tr>
            <!-- Tambahkan baris data lainnya sesuai kebutuhan -->
        </tbody>
    </table>

    <h3>Pengaturan Notifikasi</h3>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="emailNotification" checked>
        <label class="form-check-label" for="emailNotification">Notifikasi melalui Email</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="appNotification" checked>
        <label class="form-check-label" for="appNotification">Notifikasi melalui Aplikasi</label>
    </div>

    <h3>Riwayat Notifikasi</h3>
    <ul id="notificationHistory" class="list-group mt-3">
        <li class="list-group-item">Notifikasi: Pembayaran Sewa jatuh tempo pada 2024-02-01</li>
        <!-- Tambahkan riwayat notifikasi lainnya -->
    </ul>
</div>

<script>
    document.getElementById('addReminderBtn').addEventListener('click', function() {
        // Logika untuk membuka form tambah pengingat baru
        alert('Form untuk menambah pengingat baru akan ditampilkan.');
    });
</script>
@endsection
