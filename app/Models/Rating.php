<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';
    protected $fillable = ['produk_id', 'user_id', 'rating', 'ulasan', 'foto', 'respon_penjual'];
    protected $casts = ['foto' => 'array'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
