<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranHutang extends Model
{
    protected $fillable = [
        'hutang_id',
        'jumlah_bayar',
        'keterangan'
    ];

    public function hutang()
    {
        return $this->belongsTo(Hutang::class);
    }
} 