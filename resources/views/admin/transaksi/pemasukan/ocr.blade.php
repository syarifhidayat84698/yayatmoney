@extends('templates.app')

@section('title', 'OCR Nota')

@section('content')
<div class="container mx-auto max-w-lg mt-12">
    <h2 class="text-3xl font-bold text-center text-gray-900">📄 Upload Nota untuk OCR</h2>

    <div class="bg-white p-6 mt-6 rounded-xl shadow-lg">
        <form id="ocrForm" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="flex flex-col items-center">
                <label for="image" class="text-lg font-medium text-gray-700">Pilih Gambar Nota</label>
                <input type="file" 
                       name="image" 
                       id="image" 
                       accept="image/*" 
                       class="mt-3 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" 
                       onchange="previewImage(event)">
            </div>
            
            <div class="flex justify-center">
                <img id="imagePreview" 
                     src="#" 
                     alt="Preview" 
                     class="mt-4 hidden w-72 rounded-lg shadow-md">
            </div>

            <button type="submit" 
                    class="mt-4 bg-blue-600 text-white px-5 py-3 rounded-lg w-full hover:bg-blue-700 transition duration-200 text-lg font-semibold shadow-md">
                🚀 Proses OCR
            </button>
        </form>
    </div>

    <div id="result" class="mt-8 hidden bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">📋 Hasil Ekstraksi</h3>
        <div class="grid gap-3 text-gray-700 text-lg">
            <p><strong>📌 Nomor Tagihan:</strong> <span id="nomor_tagihan" class="text-blue-600"></span></p>
            <p><strong>👤 Nama Customer:</strong> <span id="nama_customer" class="text-blue-600"></span></p>
            <p><strong>📞 Nomor WhatsApp:</strong> <span id="nomor_whatsapp" class="text-blue-600"></span></p>
            <p><strong>💰 Total:</strong> <span id="amount" class="text-green-600 font-bold"></span></p>
            <p><strong>📅 Tanggal:</strong> <span id="transaction_date" class="text-blue-600"></span></p>
        </div>
    </div>
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.src = URL.createObjectURL(event.target.files[0]);
        imagePreview.classList.remove('hidden');
    }

    document.getElementById('ocrForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData();
        formData.append('image', document.getElementById('image').files[0]);

        // Menampilkan Swal Loading
        Swal.fire({
            title: "⏳ Memproses Nota...",
            text: "Harap tunggu beberapa saat.",
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
            Swal.close(); // Menutup loading Swal
            
            if (data.status === 'success') {
                document.getElementById("result").classList.remove("hidden");
                document.getElementById("nomor_tagihan").textContent = data.data.nomor_tagihan || "-";
                document.getElementById("nama_customer").textContent = data.data.nama_customer || "-";
                document.getElementById("nomor_whatsapp").textContent = data.data.nomor_whatsapp || "-";
                document.getElementById("amount").textContent = "Rp " + data.data.amount.toLocaleString();
                document.getElementById("transaction_date").textContent = data.data.transaction_date || "-";

                // Menampilkan Swal Sukses
                Swal.fire({
                    icon: "success",
                    title: "✅ OCR Berhasil!",
                    text: "Data berhasil diekstrak dan disimpan.",
                    confirmButtonColor: "#28a745",
                    confirmButtonText: "Lihat Hasil"
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "❌ Gagal!",
                    text: data.message || "Terjadi kesalahan saat memproses OCR.",
                    confirmButtonColor: "#dc3545"
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: "error",
                title: "❌ Terjadi Kesalahan",
                text: "Proses OCR gagal. Silakan coba lagi.",
                confirmButtonColor: "#dc3545"
            });
        });
    });
</script>
@endsection
