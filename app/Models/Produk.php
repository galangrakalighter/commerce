<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = "produk";

    protected $fillable = ['id_kategori', 'nama_produk', 'tipe', 'harga', 'gambar', 'spec_produk', 'detail_produk', 'status_produk'];

    // Casting JSON ke Array otomatis
    protected $casts = [
        'gambar' => 'array',
        'status_produk' => 'boolean',
        'spec_produk' => 'array',
    ];

    public function kategori() {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }

    // Helper untuk menghitung rata-rata
    public function getAverageRatingAttribute() {
        return $this->ratings()->avg('rating') ?? 0;
    }
}