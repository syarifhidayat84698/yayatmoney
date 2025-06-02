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

        // Debugging hasil OCR sebelum diproses
        Log::info("Hasil OCR Mentah: " . $text);


        // Preprocessing teks agar lebih mudah diproses
        $cleaned_text = $this->preprocessText($text);

        // Debugging hasil setelah diproses
        Log::info("Hasil OCR Setelah Preprocessing: " . $cleaned_text);

        $nama_customer_full = $this->extractData('/Nama Customer\s*:\s*([\w\s]+)/i', $cleaned_text);
        $nama_customer = trim(str_replace("Nomor Whatsapp", "", $nama_customer_full));

        // Ekstrak data dan hitung akurasi
        $nomor_tagihan = $this->extractData('/Nomor Tagihan\s*:\s*(\d+)/i', $cleaned_text);
        $nomor_whatsapp = $this->extractData('/Nomor Whatsapp\s*:\s*(\d+)/i', $cleaned_text);
        $amount = $this->extractData('/Total\s*:\s*([\d,.]+)/i', $cleaned_text);
        $date = $this->extractData('/Tanggal :\s*(\S+)/', $text);

        // Hitung akurasi berdasarkan pola yang ditemukan dan kualitas ekstraksi
        $accuracies = [
            'nomor_tagihan' => $this->calculateAccuracy($nomor_tagihan, '/^\d{3,}$/'),
            'nama_customer' => $this->calculateAccuracy($nama_customer, '/^[A-Za-z\s]{3,}$/'),
            'nomor_whatsapp' => $this->calculateAccuracy($nomor_whatsapp, '/^\d{10,13}$/'),
            'amount' => $this->calculateAccuracy($amount, '/^[\d,.]+$/'),
            'transaction_date' => $this->calculateAccuracy($date, '/^\d{2}-\d{2}-\d{4}$/')
        ];

        $data = [
            'nomor_tagihan' => $nomor_tagihan,
            'nama_customer' => $nama_customer,
            'nomor_whatsapp' => $nomor_whatsapp,
            'amount' => floatval(str_replace(',', '', $amount)),
            'transaction_date' => $date ? Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d') : null,
            'receipt' => $imagePath,
            'accuracies' => $accuracies,
            'raw_text' => $cleaned_text // Menambahkan raw text untuk review
        ];

        // Setelah proses ekstraksi atau simpan
        $message = "Halo {$data['nama_customer']},\n"
                 . "Nota Anda berhasil diproses.\n"
                 . "Nomor Tagihan: {$data['nomor_tagihan']}\n"
                 . "Total: Rp " . number_format($data['amount'], 0, ',', '.') . "\n"
                 . "Tanggal: {$data['transaction_date']}";
        $this->sendWhatsapp($data['nomor_whatsapp'], $message);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diekstrak',
            'data' => $data
        ]);
    }

    public function saveTransaction(Request $request)
    {
        $data = $request->validate([
            'nomor_tagihan' => 'required',
            'nama_customer' => 'required',
            'nomor_whatsapp' => 'required',
            'amount' => 'required|numeric',
            'transaction_date' => 'required|date',
            'receipt' => 'required'
        ]);

        // Tambahkan user_id dan type
        $data['user_id'] = auth()->id() ?? 1;
        $data['type'] = 'outcome';

        // Simpan ke database
        $transaction = Transaction::create($data);

        // Setelah proses ekstraksi atau simpan
        $message = "Halo {$data['nama_customer']},\n"
                 . "Nota Anda berhasil diproses.\n"
                 . "Nomor Tagihan: {$data['nomor_tagihan']}\n"
                 . "Total: Rp " . number_format($data['amount'], 0, ',', '.') . "\n"
                 . "Tanggal: {$data['transaction_date']}";
        $this->sendWhatsapp($data['nomor_whatsapp'], $message);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan',
            'data' => $transaction
        ]);
    }

    private function calculateAccuracy($value, $pattern)
    {
        if (empty($value)) {
            return 0;
        }

        $baseAccuracy = 70; // Akurasi dasar jika nilai ditemukan
        
        // Tambah skor berdasarkan pola yang sesuai
        if (preg_match($pattern, $value)) {
            $baseAccuracy += 20;
        }

        // Tambah skor berdasarkan panjang string
        if (strlen($value) >= 3) {
            $baseAccuracy += 10;
        }

        return min(100, $baseAccuracy); // Maksimum 100%
    }

    private function preprocessText($text)
    {
        // Hapus spasi ekstra dan karakter yang tidak diperlukan
        $text = preg_replace("/\s+/", " ", $text); // Ubah banyak spasi menjadi satu
        $text = trim(str_replace("\n", " ", $text)); // Hapus newline yang tidak perlu

        return $text;
    }

    private function extractData($pattern, $text)
    {
        preg_match($pattern, $text, $matches);
        return isset($matches[1]) ? trim($matches[1]) : null;
    }

    private function sendWhatsapp($number, $message)
    {
        $token = env('WA_GATEWAY_TOKEN'); // Simpan token di .env
        $url = 'https://api.fonnte.com/send'; // Ganti sesuai gateway kamu

        $data = [
            'target' => $number,
            'message' => $message,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: $token"
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function waWebhook(Request $request)
    {
        // Contoh payload Fonnte/Ultramsg, sesuaikan dengan gateway kamu
        $number = $request->input('number'); // nomor pengirim
        $fileUrl = $request->input('file_url'); // url file gambar nota

        // Download gambar ke storage
        $imageContents = file_get_contents($fileUrl);
        $filename = 'ocr_images/' . uniqid() . '.jpg';
        $path = storage_path('app/public/' . $filename);
        file_put_contents($path, $imageContents);

        // Jalankan OCR
        $text = (new TesseractOCR($path))
            ->lang('ind+eng')
            ->run();

        $cleaned_text = $this->preprocessText($text);

        $nama_customer_full = $this->extractData('/Nama Customer\s*:\s*([\w\s]+)/i', $cleaned_text);
        $nama_customer = trim(str_replace("Nomor Whatsapp", "", $nama_customer_full));
        $nomor_tagihan = $this->extractData('/Nomor Tagihan\s*:\s*(\d+)/i', $cleaned_text);
        $amount = $this->extractData('/Total\s*:\s*([\d,.]+)/i', $cleaned_text);
        $date = $this->extractData('/Tanggal :\s*(\S+)/', $text);

        // Compose hasil pesan
        $message = "Hasil OCR Nota Anda:\n"
                 . "Nama: {$nama_customer}\n"
                 . "Nomor Tagihan: {$nomor_tagihan}\n"
                 . "Total: Rp " . number_format(floatval(str_replace(',', '', $amount)), 0, ',', '.') . "\n"
                 . "Tanggal: {$date}\n\n"
                 . "Jika ada kesalahan, silakan upload ulang nota yang lebih jelas.";

        // Kirim hasil ke nomor pengirim
        $this->sendWhatsapp($number, $message);

        return response()->json(['status' => 'ok']);
    }
}
