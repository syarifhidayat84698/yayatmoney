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
        'amount',
        'type',
        'description',
        'transaction_date',
        'sumber',
        'receipt',
    ];
}