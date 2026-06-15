<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = "produk";

    protected $fillable = ['id_kategori', 'nama_produk', 'tipe', 'harga', 'gambar', 'spec_produk', 'detail_produk'];

    // Casting JSON ke Array otomatis
    protected $casts = [
        'gambar' => 'array',
        'spec_produk' => 'array',
    ];

    public function kategori() {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}