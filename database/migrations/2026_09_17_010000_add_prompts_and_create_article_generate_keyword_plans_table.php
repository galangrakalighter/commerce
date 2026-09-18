<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('article_generate_keywords', function (Blueprint $table) {
            $table->text('article_prompt')->nullable()->after('keyword');
            $table->text('image_prompt')->nullable()->after('article_prompt');
        });

        Schema::create('article_generate_keyword_plans', function (Blueprint $table) {
            $table->id();
            $table->date('planned_date')->unique();
            $table->string('keyword');
            $table->text('article_prompt');
            $table->text('image_prompt');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_generate_keyword_plans');

        Schema::table('article_generate_keywords', function (Blueprint $table) {
            $table->dropColumn(['article_prompt', 'image_prompt']);
        });
    }
};
