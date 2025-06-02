@extends('templates.app')

@section('title', 'Tambah Faktur')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Tambah Faktur</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger border-start border-danger border-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('input.store') }}" method="POST">
                @csrf
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nomor_tagihan" class="form-label">
                                <i class="fas fa-hashtag me-1"></i>Nomor Tagihan
                            </label>
                            <input type="text" name="nomor_tagihan" id="nomor_tagihan" 
                                   class="form-control form-control-lg" 
                                   value="{{ $uniq_noTagihan ?? '' }}" required>
                        </div>

                        <div class="form-group mt-3">
                            <label for="tanggal" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Tanggal
                            </label>
                            <input type="date" name="tanggal" id="tanggal" 
                                   class="form-control form-control-lg" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_customer" class="form-label">
                                <i class="fas fa-user me-1"></i>Nama Customer
                            </label>
                            <select name="nama_customer" id="nama_customer" 
                                    class="form-select form-select-lg" required>
                                <option value="">Pilih Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->nama_customer }}" 
                                            data-telepon="{{ $customer->telepon }}">
                                        {{ $customer->nama_customer }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label for="telepon" class="form-label">
                                <i class="fab fa-whatsapp me-1"></i>Nomor WhatsApp
                            </label>
                            <input type="text" name="telepon" id="telepon" 
                                   class="form-control form-control-lg bg-light" required readonly>
                        </div>
                    </div>
                </div>

                <div class="card mt-4 mb-4 border-primary">
                    <div class="card-header bg-primary bg-gradient text-white">
                        <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Detail Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">No</th>
                                        <th style="width: 100px;">Banyaknya</th>
                                        <th>Nama Barang</th>
                                        <th style="width: 200px;">Harga</th>
                                        <th style="width: 200px;">Jumlah</th>
                                        <th style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="barang_table_body">
                                    <tr>
                                        <td class="text-center">
                                            <input type="number" name="no[]" value="1" 
                                                   class="form-control form-control-sm text-center" required>
                                        </td>
                                        <td>
                                            <input type="number" name="banyaknya[]" 
                                                   class="form-control form-control-sm" required 
                                                   oninput="hitungJumlah(this)">
                                        </td>
                                        <td>
                                            <select name="nama_barang[]" 
                                                    class="form-select form-select-sm" required 
                                                    onchange="updateHargaAndStok(this)">
                                                <option value="">Pilih Barang</option>
                                                @foreach($barangs as $barang)
                                                    <option value="{{ $barang->id }}" 
                                                            data-harga="{{ $barang->harga }}" 
                                                            data-stok="{{ $barang->stok }}">
                                                        {{ $barang->nama_barang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" name="harga[]" 
                                                       class="form-control" required 
                                                       oninput="hitungJumlah(this)">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control bg-light" 
                                                       required readonly id="jumlah_display[]">
                                                <input type="hidden" name="jumlah[]" required>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="hapusBarang(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-secondary" onclick="tambahBarang()">
                            <i class="fas fa-plus me-1"></i> Tambah Barang
                        </button>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 border-info">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-check-circle me-1"></i>Status Pembayaran
                                    </label>
                                    <select name="status" id="status" class="form-select form-select-lg" required>
                                        <option value="">Pilih Status</option>
                                        <option value="Lunas">Lunas</option>
                                        <option value="Tidak Lunas">Tidak Lunas</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="keterangan" class="form-label">
                                        <i class="fas fa-sticky-note me-1"></i>Keterangan
                                    </label>
                                    <textarea name="keterangan" id="keterangan" 
                                              class="form-control" rows="4" 
                                              placeholder="Tambahkan catatan atau keterangan..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100 border-success">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="total" class="form-label">
                                        <i class="fas fa-calculator me-1"></i>Total
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-success text-white">Rp</span>
                                        <input type="text" id="totalan" name="totalan" 
                                               class="form-control form-control-lg bg-light fw-bold" 
                                               value="0" readonly>
                                    </div>
                                    <input type="text" id="total" name="total" value="0" hidden>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="totalbayar" class="form-label">
                                        <i class="fas fa-money-bill-wave me-1"></i>Total Bayar
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-primary text-white">Rp</span>
                                        <input type="number" name="totalbayar" id="totalbayar" 
                                               class="form-control form-control-lg" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kembalian" class="form-label">
                                        <i class="fas fa-hand-holding-usd me-1"></i>Kembalian
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-info text-white">Rp</span>
                                        <input type="text" name="kembalian" id="kembalian" 
                                               class="form-control form-control-lg bg-light fw-bold" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Faktur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-label {
        color: #2c3e50;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
    }
    .card-header {
        border-bottom: 0;
    }
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid #e0e0e0;
        padding: 0.75rem 1rem;
    }
    .form-control-lg, .form-select-lg {
        font-size: 1rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    .btn {
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.2s;
    }
    .btn:hover {
        transform: translateY(-1px);
    }
    .btn-lg {
        padding: 1rem 2rem;
    }
    .input-group-text {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .bg-light {
        background-color: #f8f9fc !important;
    }
</style>

<script>
    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    document.getElementById('totalbayar').addEventListener('input', function() {
        var total = parseInt(document.getElementById('total').dataset.raw) || 0;
        var bayar = parseInt(this.value) || 0;
        var kembalian = bayar - total;

        document.getElementById('kembalian').value = formatRupiah(kembalian >= 0 ? kembalian : 0);

        var statusSelect = document.getElementById('status');
        if (bayar >= total) {
            statusSelect.value = 'Lunas';
        } else {
            statusSelect.value = 'Tidak Lunas';
        }
    });

    window.onload = function() {
        var namaCustomerDropdown = document.getElementById('nama_customer');

        namaCustomerDropdown.addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var nomorWa = selectedOption.getAttribute('data-telepon');
            document.getElementById('telepon').value = nomorWa || '';
        });
    };

    function tambahBarang() {
        var tbody = document.getElementById("barang_table_body");
        var tr = document.createElement("tr");

        tr.innerHTML = `
            <td class="text-center">
                <input type="number" name="no[]" value="${tbody.rows.length + 1}" 
                       class="form-control form-control-sm text-center" required>
            </td>
            <td>
                <input type="number" name="banyaknya[]" 
                       class="form-control form-control-sm" required 
                       oninput="hitungJumlah(this)">
            </td>
            <td>
                <select name="nama_barang[]" 
                        class="form-select form-select-sm" required 
                        onchange="updateHargaAndStok(this)">
                    <option value="">Pilih Barang</option>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id }}" 
                                data-harga="{{ $barang->harga }}" 
                                data-stok="{{ $barang->stok }}">
                            {{ $barang->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="harga[]" 
                           class="form-control" required 
                           oninput="hitungJumlah(this)">
                </div>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control bg-light" 
                           required readonly id="jumlah_display[]">
                    <input type="hidden" name="jumlah[]" required>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" 
                        onclick="hapusBarang(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        hitungTotal();
    }

    function hapusBarang(button) {
        var row = button.closest('tr');
        row.remove();
        hitungTotal();
    }

    function hitungJumlah(element) {
        var row = element.closest('tr');
        var banyaknya = parseInt(row.querySelector('[name="banyaknya[]"]').value) || 0;
        var harga = parseInt(row.querySelector('[name="harga[]"]').value) || 0;
        var jumlah = banyaknya * harga;
        
        // Update the display with formatted currency
        row.querySelector('#jumlah_display\\[\\]').value = formatRupiah(jumlah);
        // Store the raw number in the hidden input
        row.querySelector('[name="jumlah[]"]').value = jumlah;
        hitungTotal();
    }

    function hitungTotal() {
        var total = 0;
        document.querySelectorAll('[name="jumlah[]"]').forEach(function(input) {
            total += parseInt(input.value) || 0;
        });
        document.getElementById("totalan").value = formatRupiah(total);
        document.getElementById("total").value = total;
        document.getElementById("total").dataset.raw = total;
    }

    function updateHargaAndStok(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var harga = selectedOption.getAttribute('data-harga');
        var row = selectElement.closest('tr');
        row.querySelector('[name="harga[]"]').value = harga;
        hitungJumlah(row.querySelector('[name="harga[]"]'));
    }
</script>
@endsection
