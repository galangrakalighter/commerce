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
        Schema::create('banner', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); // Untuk alt text
            $table->string('tipe')->nullable(); // Untuk alt text
            $table->string('image_path'); // Lokasi file gambar di storage
            $table->string('link_tujuan')->nullable(); // Jika banner diklik mau ke mana
            $table->boolean('is_active')->default(true); // Untuk menonaktifkan banner tanpa menghapus
            $table->integer('urutan')->default(0); // Untuk mengatur posisi banner
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner');
    }
};
