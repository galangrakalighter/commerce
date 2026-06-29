<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    protected $table = 'articles_categories';

    protected $fillable = [
        'name', 
        'slug', 
    ];

    public function articles() {
        return $this->hasMany(Article::class, 'category_id', 'id');
    }
}
