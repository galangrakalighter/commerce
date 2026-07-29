<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kategori;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Article;
use App\Models\Promo;
use App\Models\Produk;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('app', function ($view) {
            $threeDaysAgo = Carbon::now()->subDays(3);
            $user = Auth::user();

            // Tentukan batas waktu awal berdasarkan kapan user terakhir membaca
            $lastReadAt = null;
            if ($user) {
                $readRecord = DB::table('user_notification_reads')->where('user_id', $user->id)->first();
                if ($readRecord && $readRecord->last_read_at) {
                    $lastReadAt = Carbon::parse($readRecord->last_read_at);
                }
            }

            // 1. DAFTAR ISI: Selalu ambil semua data 3 hari terakhir agar tetap muncul di dropdown
            $newProducts = Produk::where('created_at', '>=', $threeDaysAgo)->get();
            $newPromos   = Promo::where('created_at', '>=', $threeDaysAgo)->get();
            $newArticles = Article::where('created_at', '>=', $threeDaysAgo)->get();

            // 2. HITUNG ANGKA NOTIFIKASI: Hanya hitung item yang dibuat SETELAH terakhir dibaca
            if ($lastReadAt) {
                $unreadProductsCount = Produk::where('created_at', '>=', $threeDaysAgo)
                                            ->where('created_at', '>', $lastReadAt)
                                            ->count();

                $unreadPromosCount   = Promo::where('created_at', '>=', $threeDaysAgo)
                                            ->where('created_at', '>', $lastReadAt)
                                            ->count();

                $unreadArticlesCount = Article::where('created_at', '>=', $threeDaysAgo)
                                            ->where('created_at', '>', $lastReadAt)
                                            ->count();

                $totalNotifications = $unreadProductsCount + $unreadPromosCount + $unreadArticlesCount;
            } else {
                // Jika belum pernah baca, total notifikasi adalah semua item dalam 3 hari terakhir
                $totalNotifications = $newProducts->count() + $newPromos->count() + $newArticles->count();
            }
            
            $categories = Kategori::all();
            $view->with(compact('newProducts', 'newPromos', 'newArticles', 'totalNotifications', 'categories'));
        });
    }
}
