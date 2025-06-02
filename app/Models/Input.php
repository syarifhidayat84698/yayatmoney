<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'nomor_tagihan',
        'nama_customer',
        'nomor_whatsapp',
        'due_date',
        'status',
        'keterangan',
        'totalbayar',
    ];

    public function details()
    {
        return $this->hasMany(detailinput::class, 'input_id');
    }

    public function hutang()
    {
        return $this->hasOne(Hutang::class, 'input_id');
    }

}
