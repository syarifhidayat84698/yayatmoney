<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OCRController extends Controller
{
    public function extractText(Request $request)
    {
        // Validasi input
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        try {
            // Simpan gambar sementara
            $imagePath = $request->file('image')->store('ocr_images', 'public');
            $fileUrl = asset('storage/' . $imagePath);

            // dd($fileUrl);


            // Panggil API Taggun
            $ocrResponse = Http::withHeaders([
                'accept' => 'application/json',
                'apikey' => env('TAGGUN_API_KEY', '146c0c73236d4000a869b946c8d49b5a'),
                'content-type' => 'application/json',
            ])->post('https://api.taggun.io/api/receipt/v1/simple/url', [
                'headers' => ['x-custom-key' => 'string'],
                'url' => 'https://s1.bukalapak.com/bukalapak-kontenz-production/content_attachments/websites/2/95891/original/Contoh_Nota_Penjualan_Makanan.jpg',
                // 'url' => $fileUrl,
                'refresh' => false,
                'incognito' => false,
                'extractTime' => true
            ]);

            if ($ocrResponse->failed()) {
                Log::error('Taggun API Error:', [
                    'status' => $ocrResponse->status(),
                    'body' => $ocrResponse->body()
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memproses gambar dengan OCR'
                ], 500);
            }

            $result = $ocrResponse->json();


            // Ekstrak data dari response
            $namaToko = $result['merchantName']['data'] ?? 'Tidak ditemukan';
            $alamat = $result['merchantAddress']['data'] ?? 'Tidak ditemukan';
            $total = $result['totalAmount']['data'] ?? 0;
            $tanggal = $result['date']['data'] ?? null;

            // Hitung akurasi untuk setiap field
            $accuracies = [
                'nama_toko' => $this->calculateAccuracy($namaToko, '/^[A-Za-z\s]{3,}$/'),
                'alamat' => $this->calculateAccuracy($alamat, '/^[\w\s.,]{5,}$/'),
                'amount' => $this->calculateAccuracy($total, '/^[\d,.]+$/'),
                'transaction_date' => $this->calculateAccuracy($tanggal, '/^\d{4}-\d{2}-\d{2}$/')
            ];

            // Format tanggal jika ada
            $formattedDate = $tanggal ? Carbon::parse($tanggal)->format('Y-m-d') : null;

            // Format total
            $totalFormatted = is_numeric($total) ? number_format($total, 0, ',', '.') : '0';

            $data = [
                'nama_toko' => $namaToko,
                'alamat' => $alamat,
                'amount' => floatval($total),
                'transaction_date' => $formattedDate,
                'receipt' => $imagePath,
                'accuracies' => $accuracies,
                'raw_text' => json_encode($result, JSON_PRETTY_PRINT)
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diekstrak',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('OCR Processing Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memproses gambar'
            ], 500);
        }
    }

    public function saveTransaction(Request $request)
    {
        $data = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'required|string',
            'amount' => 'required|numeric',
            'transaction_date' => 'required|date',
            'receipt' => 'required',
            'accuracies' => 'nullable|array',
            'raw_text' => 'nullable|string'
        ]);

        // Tambahkan user_id dan type
        $data['user_id'] = auth()->id() ?? 1;
        $data['type'] = 'outcome';

        // Simpan ke database
        $transaction = Transaction::create($data);

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

    public function waWebhook(Request $request)
    {
        try {
            // Contoh payload Fonnte/Ultramsg, sesuaikan dengan gateway kamu
            $number = $request->input('number'); // nomor pengirim
            $fileUrl = $request->input('file_url'); // url file gambar nota

            if (!$fileUrl) {
                throw new \Exception('URL file tidak ditemukan');
            }

            // Download gambar ke storage
            $imageContents = file_get_contents($fileUrl);
            if (!$imageContents) {
                throw new \Exception('Gagal mengunduh gambar');
            }

            $filename = 'ocr_images/' . uniqid() . '.jpg';
            $path = storage_path('app/public/' . $filename);
            file_put_contents($path, $imageContents);

            // Panggil API Taggun
            $ocrResponse = Http::withHeaders([
                'accept' => 'application/json',
                'apikey' => env('TAGGUN_API_KEY', '146c0c73236d4000a869b946c8d49b5a'),
                'content-type' => 'application/json',
            ])->post('https://api.taggun.io/api/receipt/v1/simple/url', [
                'url' => $fileUrl,
                'refresh' => false,
                'incognito' => false,
                'extractTime' => true
            ]);

            if ($ocrResponse->failed()) {
                throw new \Exception('Gagal memproses gambar dengan OCR');
            }

            $result = $ocrResponse->json();

            // Ekstrak data
            $namaToko = $result['merchantName']['data'] ?? 'Tidak ditemukan';
            $alamat = $result['merchantAddress']['data'] ?? 'Tidak ditemukan';
            $total = $result['totalAmount']['data'] ?? 0;
            $tanggal = $result['date']['data'] ?? null;

            // Format pesan
            $message = "Hasil OCR Nota Anda:\n"
                     . "Nama Toko: {$namaToko}\n"
                     . "Alamat: {$alamat}\n"
                     . "Total: Rp " . number_format($total, 0, ',', '.') . "\n"
                     . "Tanggal: {$tanggal}\n\n"
                     . "Jika ada kesalahan, silakan upload ulang nota yang lebih jelas.";

            // Kirim hasil ke nomor pengirim
            $this->sendWhatsapp($number, $message);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('WhatsApp OCR Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->sendWhatsapp($number, "❌ *OCR gagal memproses gambar.*\nSilakan coba lagi dengan gambar yang lebih jelas.");

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function sendWhatsapp($number, $message)
    {
        try {
            $token = env('WA_GATEWAY_TOKEN');
            $url = env('WA_GATEWAY_URL', 'https://api.fonnte.com/send');

            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post($url, [
                'target' => $number,
                'message' => $message
            ]);

            if ($response->failed()) {
                Log::error('WhatsApp Send Error:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('WhatsApp Send Exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}
