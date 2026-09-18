<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ArticleGenerateKeyword extends Model
{
    protected $fillable = [
        'keyword',
        'article_prompt',
        'image_prompt',
        'singleton',
    ];

    protected $hidden = [
        'singleton',
    ];

    public static function configurationForDate(mixed $date = null): ?Model
    {
        $targetDate = $date
            ? Carbon::parse($date, 'Asia/Jakarta')
            : now('Asia/Jakarta');

        return ArticleGenerateKeywordPlan::query()
            ->whereDate('planned_date', $targetDate->toDateString())
            ->first()
            ?? static::query()->first();
    }
}
