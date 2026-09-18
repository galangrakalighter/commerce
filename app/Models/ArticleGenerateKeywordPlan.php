<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleGenerateKeywordPlan extends Model
{
    protected $fillable = [
        'planned_date',
        'keyword',
        'article_prompt',
        'image_prompt',
    ];

    protected function casts(): array
    {
        return [
            'planned_date' => 'date:Y-m-d',
        ];
    }
}
