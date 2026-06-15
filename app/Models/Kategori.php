<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'id_promo',
        'nama_kategori'
    ];

    /**
     * Relasi ke tabel Promo
     */
    public function promo()
    {
        return $this->belongsTo(Promo::class, 'id_promo');
    }
}