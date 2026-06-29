<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            
            // Relasi (Opsional: Jika artikel harus punya kategori)
            // $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('articles_categories')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique(); // Untuk URL SEO friendly
            $table->text('excerpt')->nullable(); // Ringkasan singkat
            $table->longText('content'); // Isi artikel (bisa pakai editor WYSIWYG)
            $table->string('image')->nullable(); // Gambar cover artikel
            
            // Status dan Metadata
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
