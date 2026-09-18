<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_generate_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->unsignedTinyInteger('singleton')->default(1)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_generate_keywords');
    }
};
