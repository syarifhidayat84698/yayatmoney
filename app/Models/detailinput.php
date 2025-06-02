<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detailinput extends Model
{
    use HasFactory;
    protected $fillable = [
        'faktur_id', 'no', 'banyaknya', 'nama_barang', 'harga', 'jumlah',
    ];

    // Relasi dengan model Input (faktur)
    public function faktur()
    {
        return $this->belongsTo(Input::class, 'input_id');
    }

}
