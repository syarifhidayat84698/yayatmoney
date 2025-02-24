<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OCRController extends Controller
{
    public function extractText(Request $request)
{
    // Validasi input
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg'
    ]);

    // Simpan gambar sementara
    $imagePath = $request->file('image')->store('ocr_images', 'public');

    // Jalankan OCR dengan bahasa Indonesia + Inggris
    $text = (new TesseractOCR(storage_path('app/public/' . $imagePath)))
        ->lang('ind+eng')
        ->run();

    // dd($text);

    // Debugging hasil OCR sebelum diproses
    Log::info("Hasil OCR Mentah: " . $text);

    // Preprocessing teks agar lebih mudah diproses
    $cleaned_text = $this->preprocessText($text);

    // Debugging hasil setelah diproses
    Log::info("Hasil OCR Setelah Preprocessing: " . $cleaned_text);

    $nama_customer_full = $this->extractData('/Nama Customer\s*:\s*([\w\s]+)/i', $cleaned_text);
    $nama_customer = trim(str_replace("Nomor Whatsapp", "", $nama_customer_full)); // Bersihkan tambahan teks

    // Ekstrak data penting
    $data = [
        'nomor_tagihan'   => $this->extractData('/Nomor Tagihan\s*:\s*(\d+)/i', $cleaned_text),
        'nama_customer'   => $nama_customer,
        'nomor_whatsapp'  => $this->extractData('/Nomor Whatsapp\s*:\s*(\d+)/i', $cleaned_text),
        'amount'          => floatval(str_replace(',', '', $this->extractData('/Total\s*:\s*([\d,.]+)/i', $cleaned_text))),
        'transaction_date' => Carbon::createFromFormat('d-m-Y', $this->extractData('/Tanggal :\s*(\S+)/', $text))->format('Y-m-d'),
        'receipt'         => $imagePath, // Simpan path gambar sebagai bukti
    ];

    // Tambahkan user_id default jika tidak ada user yang login
    $data['user_id'] = auth()->id() ?? 1;

    // dd($data, $cleaned_text, $text);

    // Simpan ke database
    Transaction::create($data);

    return response()->json([
        'status' => 'success',
        'message' => 'Data berhasil disimpan',
        'data' => $data
    ]);
}

/**
 * Fungsi untuk membersihkan teks hasil OCR agar lebih mudah diproses.
 */
private function preprocessText($text)
{
    // Hapus spasi ekstra dan karakter yang tidak diperlukan
    $text = preg_replace("/\s+/", " ", $text); // Ubah banyak spasi menjadi satu
    $text = trim(str_replace("\n", " ", $text)); // Hapus newline yang tidak perlu

    return $text;
}

/**
 * Fungsi untuk mengekstrak teks berdasarkan pola regex
 */
private function extractData($pattern, $text)
{
    preg_match($pattern, $text, $matches);
    return isset($matches[1]) ? trim($matches[1]) : null;
}

/**
 * Fungsi khusus untuk menangani ekstraksi tanggal
 */
private function extractDate($text)
{
    // Regex untuk menangkap format tanggal seperti "24-02-2025" atau "24 Februari 2025"
    preg_match('/Tanggal\s*:\s*([\d]{1,2}[-\s][A-Za-z0-9]+[-\s][\d]{4})/i', $text, $matches);

    if (!empty($matches[1])) {
        try {
            return Carbon::parse(str_replace("-", " ", $matches[1]))->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error("Gagal parsing tanggal: " . $matches[1]);
        }
    }

    return null;
}


}
