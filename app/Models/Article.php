<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [
        'title', 
        'category_id',
        'slug', 
        'excerpt', 
        'content', 
        'image', 
        'status', 
        'published_at', 
        'meta_title', 
        'meta_description'
    ];

    /**
     * Casting agar kolom tertentu otomatis terkonversi
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Boot method untuk generate slug otomatis
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            // Jika slug kosong, buat otomatis dari title
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    // Contoh Scope untuk mempermudah query artikel yang sudah terbit
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    public function category() {
        return $this->belongsTo(ArticleCategory::class);
    }
}