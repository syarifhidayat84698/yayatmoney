<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans';

    protected $fillable = [
        'tanggal',
        'kategori',
        'keterangan',
        'jumlah',
        'metode_pembayaran',
        'bukti_transaksi'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2'
    ];
} 