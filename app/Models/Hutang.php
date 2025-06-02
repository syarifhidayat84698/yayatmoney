<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nomor_tagihan',
        'customer_id',
        'due_date',
        'status',
        'keterangan',
        'total_tagihan',
        'sisa_hutang',
        'total_hutang',
        'transaction_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(PembayaranHutang::class);
    }
}

