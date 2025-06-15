<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Jika Anda ingin menentukan nama tabel secara eksplisit, gunakan baris berikut:
    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'nama_toko',
        'alamat',
        'amount',
        'type',
        'transaction_date',
        'receipt',
        'accuracies',
        'raw_text'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'accuracies' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}