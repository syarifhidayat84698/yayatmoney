@extends('templates.app')

@section('title', 'OCR Nota')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <h1 class="display-5 mb-0">
                    <i class="fas fa-file-invoice text-primary me-2"></i>Scan Nota
                </h1>
                <p class="text-muted mt-2">Upload nota untuk mengekstrak data secara otomatis</p>
            </div>

            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body p-4">
                    <form id="ocrForm" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-area mb-4">
                            <div class="text-center p-4 border-2 border-dashed rounded-lg bg-light">
                                <i class="fas fa-cloud-upload-alt text-primary mb-3" style="font-size: 2.5rem;"></i>
                                <h4 class="mb-2">Upload Gambar Nota</h4>
                                <p class="text-muted mb-3">Drag & drop file atau klik untuk memilih</p>
                                <input type="file" 
                                       name="image" 
                                       id="image" 
                                       accept="image/*" 
                                       class="form-control" 
                                       onchange="previewImage(event)">
                            </div>
                        </div>
                        
                        <div class="text-center mb-4">
                            <img id="imagePreview" 
                                 src="#" 
                                 alt="Preview" 
                                 class="d-none img-preview rounded-lg shadow-sm">
                        </div>

                        <button type="submit" 
                                class="btn btn-primary btn-lg w-100 position-relative">
                            <i class="fas fa-magic me-2"></i>Proses OCR
                            <div class="position-absolute end-0 top-50 translate-middle-y me-3">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <div id="result" class="card shadow-sm border-0 rounded-lg mt-4 d-none">
                <div class="card-header bg-success bg-gradient text-white py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>Hasil Ekstraksi
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form id="reviewForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="result-item">
                                    <label class="text-muted mb-1">Nomor Tagihan</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-hashtag text-primary me-2"></i>
                                        <input type="text" id="nomor_tagihan" name="nomor_tagihan" class="form-control fw-medium" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="result-item">
                                    <label class="text-muted mb-1">Nama Customer</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user text-primary me-2"></i>
                                        <input type="text" id="nama_customer" name="nama_customer" class="form-control fw-medium" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="result-item">
                                    <label class="text-muted mb-1">Nomor WhatsApp</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fab fa-whatsapp text-success me-2"></i>
                                        <input type="text" id="nomor_whatsapp" name="nomor_whatsapp" class="form-control fw-medium" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="result-item">
                                    <label class="text-muted mb-1">Total</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                                        <input type="text" id="amount" name="amount" class="form-control fw-bold fs-5" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="result-item">
                                    <label class="text-muted mb-1">Tanggal</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        <input type="text" id="transaction_date" name="transaction_date" class="form-control fw-medium" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="receipt" name="receipt">
                        <div class="mt-4 d-flex gap-2 justify-content-end">
                            <button type="button" id="editBtn" class="btn btn-warning"><i class="fas fa-edit me-1"></i>Sesuaikan</button>
                            <button type="submit" id="saveBtn" class="btn btn-success"><i class="fas fa-save me-1"></i>Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .upload-area {
        transition: all 0.3s ease;
    }
    .upload-area:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    .img-preview {
        max-width: 300px;
        height: auto;
        margin: 0 auto;
    }
    .result-item {
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        height: 100%;
    }
    .result-item label {
        font-size: 0.875rem;
        font-weight: 500;
    }
    .btn-lg {
        padding: 1rem 2rem;
        font-size: 1rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    .card {
        overflow: hidden;
    }
    .card-header {
        border-bottom: 0;
    }
    .bg-gradient {
        background: linear-gradient(45deg, #28a745, #20c997);
    }
    .form-control {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
    }
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.src = URL.createObjectURL(event.target.files[0]);
        imagePreview.classList.remove('d-none');
        imagePreview.classList.add('d-block');
    }

    document.getElementById('ocrForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData();
        formData.append('image', document.getElementById('image').files[0]);

        Swal.fire({
            title: '<i class="fas fa-cog fa-spin me-2"></i>Memproses Nota',
            html: 'Sedang mengekstrak data dari gambar...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch("{{ url('/ocr-extract') }}", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.status === 'success') {
                const result = document.getElementById("result");
                result.classList.remove("d-none");
                result.classList.add("animate__animated", "animate__fadeIn");
                
                document.getElementById("nomor_tagihan").value = data.data.nomor_tagihan || "";
                document.getElementById("nama_customer").value = data.data.nama_customer || "";
                document.getElementById("nomor_whatsapp").value = data.data.nomor_whatsapp || "";
                document.getElementById("amount").value = data.data.amount ? data.data.amount.toLocaleString('id-ID') : "";
                document.getElementById("transaction_date").value = data.data.transaction_date || "";
                document.getElementById("receipt").value = data.data.receipt || "";
                setReviewFormReadonly(true);
            } else {
                Swal.fire({
                    icon: "error",
                    title: '<i class="fas fa-exclamation-circle me-2"></i>Gagal!',
                    html: '<div class="text-danger">' + (data.message || "Terjadi kesalahan saat memproses OCR.") + '</div>',
                    confirmButtonColor: "#dc3545"
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: "error",
                title: '<i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan',
                html: '<div class="text-danger">Proses OCR gagal. Silakan coba lagi.</div>',
                confirmButtonColor: "#dc3545"
            });
        });
    });

    function setReviewFormReadonly(readonly) {
        ["nomor_tagihan", "nama_customer", "nomor_whatsapp", "amount"].forEach(function(id) {
            document.getElementById(id).readOnly = readonly;
        });
        const dateInput = document.getElementById('transaction_date');
        if (readonly) {
            // Kembalikan ke text dan tampilkan tanggal terformat
            if (dateInput.type === 'date') {
                const val = dateInput.value;
                if (val) {
                    // Format ke dd-mm-yyyy
                    const [y, m, d] = val.split('-');
                    dateInput.type = 'text';
                    dateInput.value = `${d}-${m}-${y}`;
                } else {
                    dateInput.type = 'text';
                }
            }
            dateInput.readOnly = true;
        } else {
            // Ubah ke date picker dan konversi ke yyyy-mm-dd jika perlu
            if (dateInput.type === 'text') {
                let val = dateInput.value;
                // Jika format dd-mm-yyyy, ubah ke yyyy-mm-dd
                if (/^\d{2}-\d{2}-\d{4}$/.test(val)) {
                    const [d, m, y] = val.split('-');
                    val = `${y}-${m}-${d}`;
                }
                dateInput.type = 'date';
                dateInput.value = val;
            }
            dateInput.readOnly = false;
        }
    }

    document.getElementById('editBtn').addEventListener('click', function() {
        setReviewFormReadonly(false);
    });

    document.getElementById('reviewForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const nomor_tagihan = document.getElementById('nomor_tagihan').value;
        const nama_customer = document.getElementById('nama_customer').value;
        const nomor_whatsapp = document.getElementById('nomor_whatsapp').value;
        const amount = document.getElementById('amount').value.replace(/[^\d,\.]/g, '').replace(/\./g, '');
        let transaction_date = document.getElementById('transaction_date').value;
        const receipt = document.getElementById('receipt').value;
        // Pastikan format tanggal ke yyyy-mm-dd
        if (document.getElementById('transaction_date').type === 'date') {
            // Sudah yyyy-mm-dd
        } else if (/^\d{2}-\d{2}-\d{4}$/.test(transaction_date)) {
            // dd-mm-yyyy ke yyyy-mm-dd
            const [d, m, y] = transaction_date.split('-');
            transaction_date = `${y}-${m}-${d}`;
        }
        if (!nomor_tagihan || !nama_customer || !nomor_whatsapp || !amount || !transaction_date) {
            Swal.fire({
                icon: "warning",
                title: "Data Belum Lengkap",
                text: "Mohon lengkapi semua data sebelum menyimpan.",
                confirmButtonColor: "#dc3545"
            });
            return;
        }
        setReviewFormReadonly(true); // Kunci kembali setelah simpan
        fetch("{{ url('/ocr-save') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                nomor_tagihan,
                nama_customer,
                nomor_whatsapp,
                amount,
                transaction_date,
                receipt
            })
        })
        .then(response => response.json())
        .then(saveResult => {
            if (saveResult.status === 'success') {
                Swal.fire({
                    icon: "success",
                    title: '<i class="fas fa-check-circle me-2"></i>OCR Berhasil! ',
                    html: '<div class="text-success">Data berhasil diekstrak dan disimpan.</div>',
                    confirmButtonColor: "#28a745",
                    confirmButtonText: '<i class="fas fa-eye me-2"></i>Lihat Hasil',
                    showCancelButton: true,
                    cancelButtonText: '<i class="fas fa-redo me-2"></i>Scan Lagi',
                    cancelButtonColor: "#6c757d",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ url('/pengeluaran') }}";
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: '<i class="fas fa-exclamation-circle me-2"></i>Gagal Menyimpan! ',
                    html: '<div class="text-danger">' + (saveResult.message || "Terjadi kesalahan saat menyimpan data.") + '</div>',
                    confirmButtonColor: "#dc3545"
                });
            }
        })
        .catch(error => {
            console.error('Error saving:', error);
            Swal.fire({
                icon: "error",
                title: '<i class="fas fa-exclamation-triangle me-2"></i>Gagal Menyimpan',
                html: '<div class="text-danger">Terjadi kesalahan saat menyimpan data.</div>',
                confirmButtonColor: "#dc3545"
            });
        });
    });
</script>
@endsection
