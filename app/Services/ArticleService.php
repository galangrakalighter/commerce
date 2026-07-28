<?php

namespace App\Services;
use App\Models\Article;
use App\Models\ArticleCategory;
class ArticleService {
    public function storeFromN8n(array $data) {
        $getID = ArticleCategory::where('name', $data['categories'])->first();
        return Article::updateOrCreate(
            ['slug' => $data['slug']], // Cek slug agar tidak duplikat
            [
                'title'            => $data['title'],
                'category_id'      => $getID->id,
                'content'          => $data['content'],
                'excerpt'          => $data['excerpt'],
                'image'            => $data['image'],
                'status'           => $data['status'],
                'published_at'     => null,
                'meta_title'       => $data['meta_title'],
                'meta_description' => $data['meta_description'],
            ]
        );
    }
}