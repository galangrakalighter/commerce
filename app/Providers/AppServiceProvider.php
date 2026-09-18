<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kategori;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Article;
use App\Models\Promo;
use App\Models\Produk;
use Illuminate\Support\Facades\URL;
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
        View::composer(['app', 'home'], function ($view) {
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
            $newProductsQuery = Produk::where('created_at', '>=', $threeDaysAgo);

            $newProducts = $newProductsQuery->get();
            $newPromos = Promo::where('created_at', '>=', $threeDaysAgo)->get(['id', 'nama_promo', 'created_at']);
            $newArticles = Article::where('created_at', '>=', $threeDaysAgo)->get(['id', 'title', 'created_at']);

            // 2. HITUNG ANGKA NOTIFIKASI: Hanya hitung item yang dibuat SETELAH terakhir dibaca
            if ($lastReadAt) {
                $unreadProductsCount = $newProducts->where('created_at', '>', $lastReadAt)
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


            $randomCategories = $categories->random(min(6, $categories->count()));
            $view->with(compact('newProducts', 'newPromos', 'newArticles', 'totalNotifications', 'categories', 'randomCategories'));
        });

        URL::forceScheme('https');

        Paginator::useTailwind();

        // DB::listen(function ($query) {
        //     logger([
        //         'sql' => $query->sql,
        //         'time' => $query->time . ' ms',
        //         'bindings' => $query->bindings
        //     ]);
        // });
    }
}
