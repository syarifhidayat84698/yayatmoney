<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'debtor',
        'due_date',
        'reminder_date',
        'receipt',
        'status',
    ];
}