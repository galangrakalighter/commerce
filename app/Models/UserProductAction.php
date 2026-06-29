<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProductAction extends Model
{
    protected $table = "user_product_actions";

    protected $fillable = ['user_id', 'product_id', 'tipe'];

    public function product() {
        return $this->belongsTo(Produk::class, 'product_id');
    }
}
