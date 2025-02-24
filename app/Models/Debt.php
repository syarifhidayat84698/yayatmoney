<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'creditor',
        'due_date',
        'reminder_date', // Jika Anda juga menyimpan pengingat
        'receipt',
        'status', // Tambahkan receipt di sini
    ];
}