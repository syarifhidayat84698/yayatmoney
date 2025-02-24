<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\UserController;

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




// Rute untuk data user
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('data-user', UserController::class);
});

// TRANSAKSI
// PEMASUKAN
// Rute untuk menyimpan data pendapatan
Route::post('/pendapatan', [TransactionController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('transaksi.store'); // Ubah nama rute di sini

// Rute untuk menampilkan daftar pemasukan
Route::get('/pemasukan', [TransactionController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('pemasukan');

// Rute untuk menampilkan formulir create pendapatan
Route::get('/tambah_pemasukan', [TransactionController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('tambah_pemasukan');

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


// TRANSAKSI
// PENGELUARAN

// Rute untuk menampilkan daftar pengeluaran
Route::get('/pengeluaran', [ExpenseController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('pengeluaran.index');

// Rute untuk menampilkan formulir create pengeluaran
Route::get('/tambah_pengeluaran', [ExpenseController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('pengeluaran.create');

// Rute untuk menyimpan data pengeluaran
Route::post('/pengeluaran', [ExpenseController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('pengeluaran.store');

// Rute untuk menampilkan form edit pengeluaran
Route::get('/pengeluaran/edit/{id}', [ExpenseController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('pengeluaran.edit');

// Rute untuk memperbarui data pengeluaran
Route::patch('/pengeluaran/update/{id}', [ExpenseController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('pengeluaran.update');

// Rute untuk menghapus pengeluaran
Route::delete('/pengeluaran/delete/{id}', [ExpenseController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('pengeluaran.destroy');


// LAPORAN KEUANGAN

// Rute untuk menampilkan laporan keuangan
Route::get('/laporan-keuangan', [FinancialReportController::class, 'index'])
    ->middleware(['auth', 'verified'])->middleware('role:admin')
    ->name('laporan.keuangan');


// HUTANG

Route::patch('/hutang/mark-as-paid/{id}', [DebtController::class, 'markAsPaid'])
    ->middleware(['auth', 'verified'])
    ->name('hutang.markAsPaid');

Route::patch('/piutang/mark-as-paid/{id}', [CreditController::class, 'markAsPaid'])
    ->middleware(['auth', 'verified'])
    ->name('piutang.markAsPaid');

// Rute untuk menampilkan formulir create hutang
Route::get('/tambah_hutang', [DebtController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('tambah_hutang');

// Rute untuk menyimpan data hutang
Route::post('/hutang', [DebtController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('hutang.store');

// Rute untuk menampilkan daftar hutang
Route::get('/hutang', [DebtController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('hutang.index');

// Rute untuk menampilkan form edit hutang
Route::get('/hutang/edit/{id}', [DebtController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('hutang.edit');

// Rute untuk memperbarui hutang
Route::patch('/hutang/update/{id}', [DebtController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('hutang.update');

// Rute untuk menghapus hutang
Route::delete('/hutang/delete/{id}', [DebtController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('hutang.destroy');


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


use App\Http\Controllers\OCRController;

Route::post('/ocr-extract', [OCRController::class, 'extractText']);

Route::get('/grafik', function () {
    return view('admin.laporankeuangan.grafiktrenkeuangan.index');
});



Route::get('/fitur_tambahan', function () {
    return view('admin.fiturtambahan.index');
});

// Rute untuk dashboard utama
Route::get('/dashboard', function () {
    return view('admin/Dasboard/index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute untuk Ecommerce Dashboard
Route::get('/ecommerce-dashboard', function () {
    return view('admin/index2'); // Mengarah ke view index2
})->middleware(['auth', 'verified'])->name('ecommerce.dashboard');

// Rute untuk ICO Dashboard
Route::get('/ico-dashboard', function () {
    return view('admin/index3'); // Mengarah ke view index2
})->middleware(['auth', 'verified'])->name('ico.dashboard');


// Rute untuk Bar Chart
Route::get('bar', function () {
    return view('admin/barchart'); // Mengarah ke view admin/barchart.blade.php
})->middleware(['auth', 'verified'])->name('charts.bar');

// Rute untuk Line Chart
Route::get('line', function () {
    return view('admin/linechart'); // Mengarah ke view admin/linechart.blade.php
})->middleware(['auth', 'verified'])->name('charts.line');

// Rute untuk Pie Chart
Route::get('pie', function () {
    return view('admin/piechart'); // Mengarah ke view admin/piechart.blade.php
})->middleware(['auth', 'verified'])->name('charts.pie');

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
