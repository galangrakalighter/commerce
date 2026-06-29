<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = "banner";
    protected $fillable = ['judul', 'tipe','image_path', 'link_tujuan', 'is_active', 'urutan'];
}
