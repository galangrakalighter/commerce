<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\ArticleCategory;

#[Signature('app:trigger-n8n-webhook')]
#[Description('Command description')]
class TriggerN8nWebhook extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $waktuSekarang = now('Asia/Jakarta');
        $jam = (int)$waktuSekarang->format('H');

        // Jam 08 sampai 16 (Total 9 jam kerja)
        if ($jam >= 8 && $jam < 17) {
            
            // Daftar ID Kategori yang Anda miliki (Total 10)
            $daftarKategori = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
            
            // Indeks berdasarkan jam (jam 8 = indeks 0, jam 16 = indeks 8)
            $index = $jam - 8;
            
            // Pastikan index tidak melebihi jumlah kategori
            $targetCategoryId = $daftarKategori[$index % count($daftarKategori)];

            $namaCategory = ArticleCategory::find(2);

            $response = Http::get('https://bmglbl3.n8n.bocindonesia.com/webhook-test/4ac54138-1d5d-4da0-9967-71758c4c510a', [
                'category_id' => $namaCategory->name,
                'jam' => $jam,
                'message' => 'Trigger jam ' . $jam . ':00 WIB untuk Kategori ID ' . $targetCategoryId
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $service = new \App\Services\ArticleService();
                $service->storeFromN8n($data);
                $this->info("Artikel '{$data['title']}' berhasil disimpan ke database!");
            } else {
                $this->error("Webhook Gagal.");
            }
        }
    }
}
