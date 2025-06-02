<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Models\Input;
use App\Http\Controllers\OCRController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HutangController;
use App\http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Rute untuk halaman utama
Route::get('/', function () {
    return view('welcome');
});


Route::get('/test', function () {
    return view('admin.dashboard.index');
});

// barang
Route::resource('barangs', BarangController::class)
    ->middleware(['auth', 'verified'])
    ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

// Rute untuk data user
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('data-user', UserController::class);
});

// Laporan
Route::get('/laporan/data_hutang', [LaporanController::class, 'dataHutang'])
    ->middleware(['auth', 'verified'])
    ->name('laporan.data_hutang');

Route::get('/laporan/pemasukan', [LaporanController::class, 'pemasukan'])
    ->middleware(['auth', 'verified'])
    ->name('laporan.pemasukan');

Route::get('/laporan/hutang', [LaporanController::class, 'hutang'])
    ->middleware(['auth', 'verified'])
    ->name('laporan.hutang');


// Rute untuk ekspor PDF
Route::get('/laporan/hutang/export', [LaporanController::class, 'exportHutangPDF'])->middleware(['auth', 'verified'])->name('laporan.hutang.export');
Route::get('/laporan/pemasukan/export', [LaporanController::class, 'exportPemasukanPDF'])->middleware(['auth', 'verified'])->name('laporan.pemasukan.export');


// customer
Route::resource('customers', CustomerController::class)
    ->middleware(['auth', 'verified'])
    ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

// input
// Rute untuk update status input
Route::post('/input/updateStatus/{id}', [InputController::class, 'updateStatus'])
    ->middleware(['auth', 'verified'])
    ->name('input.updateStatus');

// Rute untuk update total bayar transaksi
Route::patch('/input/updateTotalBayar/{id}', [InputController::class, 'updateTotalBayar'])
    ->middleware(['auth', 'verified'])
    ->name('input.updateTotalBayar');

// Rute untuk menampilkan daftar pemasukan
Route::get('/input_nota/{id}', [InputController::class, 'createNota'])
    ->name('nota.create');

Route::get('/input', [InputController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('input');

Route::get('/input_pemasukan', [InputController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('input_pemasukan');

// Rute untuk menyimpan data pendapatan
Route::post('/input_pendapatan', [InputController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('input.store');

// Rute untuk menghapus transaksi
Route::delete('/input/delete/{id}', [InputController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('input.destroy');


//    pop up
Route::post('/hutangs/{hutang}/bayar', [HutangController::class, 'bayar'])
->name('hutangs.bayar');

// Rute untuk mendapatkan riwayat pembayaran
Route::get('/hutangs/{id}/history', [HutangController::class, 'history'])
->middleware(['auth', 'verified'])
->name('hutangs.history');

// Rute untuk menampilkan form edit faktur
Route::get('/input/edit/{id}', [InputController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('input.edit');

// Rute untuk memperbarui data faktur
Route::put('/input/update/{id}', [InputController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('input.update');


// HUTANG
Route::resource('hutangs', HutangController::class)->middleware(['auth', 'verified']);


// TRANSAKSI
// Rute untuk menyimpan data pendapatan
Route::post('/pendapatan', [TransactionController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('transaksi.store'); // Ubah nama rute di sini

// Rute untuk menampilkan daftar pengeluaran
Route::get('/pengeluaran', [TransactionController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('pengeluaran');

// Rute untuk menampilkan formulir create pengeluaran
Route::get('/tambah_pengeluaran', [TransactionController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('tambah_pengeluaran');

// Rute untuk menyimpan data pendapatan
Route::post('/pendapatan', [TransactionController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('pendapatan.store');

// Rute untuk menampilkan form edit transaksi
Route::get('/transaksi/edit/{id}', [TransactionController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('transaksi.edit');

// Rute untuk memperbarui data transaksi
Route::patch('/transaksi/update/{id}', [TransactionController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('transaksi.update');

// Rute untuk menghapus transaksi
Route::delete('/transaksi/delete/{id}', [TransactionController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('transaksi.destroy');


// PIUTANG
// Rute untuk menampilkan daftar piutang
Route::get('/piutang', [CreditController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('piutang.index');

// Rute untuk menampilkan formulir tambah piutang
Route::get('/piutang/create', [CreditController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('piutang.create');

// Rute untuk menyimpan data piutang
Route::post('/piutang', [CreditController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('piutang.store');

// Rute untuk menampilkan formulir edit piutang
Route::get('/piutang/edit/{id}', [CreditController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('piutang.edit');

// Rute untuk memperbarui data piutang
Route::patch('/piutang/update/{id}', [CreditController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('piutang.update');

// Rute untuk menghapus piutang
Route::delete('/piutang/delete/{id}', [CreditController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('piutang.destroy');

// Route::get('/piutang', function () {
//      return view('admin.hutangpiutang.piutang.index');
// });


// ocr

Route::get('/ocr', function () {
    return view('admin.transaksi.pemasukan.ocr');
})->middleware(['auth', 'verified'])->name('ocr');


Route::post('/ocr-extract', [OCRController::class, 'extractText']);
Route::post('/ocr-save', [OCRController::class, 'saveTransaction'])->middleware(['auth', 'verified']);


// Rute untuk dashboard utama
Route::get('/dashboard', function () {
    return view('admin/Dasboard/index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/pendapatan', function () {
    return view('admin/pendapatan'); // Mengarah ke view admin/piechart.blade.php
})->middleware(['auth', 'verified'])->name('');

// // Route untuk menampilkan formulir create pendapatan
// Route::get('/pendapatan/create', [PendapatanController::class, 'create'])
//     ->middleware(['auth', 'verified'])
//     ->name('pendapatan.create');

// // Route untuk menyimpan data pendapatan
// Route::post('/pendapatan', [PendapatanController::class, 'store'])
//     ->middleware(['auth', 'verified'])
//     ->name('pendapatan.store');


// Rute untuk profilcreate
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Memuat rute otentikasi
require __DIR__ . '/auth.php';


