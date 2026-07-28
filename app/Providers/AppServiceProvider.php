<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kategori;
use Illuminate\Support\Facades\View;
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

            // Ambil data produk baru (3 hari terakhir)
            $newProducts = Produk::where('created_at', '>=', $threeDaysAgo)->get();

            // Ambil data promo baru (3 hari terakhir)
            $newPromos = Promo::where('created_at', '>=', $threeDaysAgo)->get();

            // Ambil data artikel baru (3 hari terakhir)
            $newArticles = Article::where('created_at', '>=', $threeDaysAgo)->get();

            // Hitung total notifikasi
            $totalNotifications = $newProducts->count() + $newPromos->count() + $newArticles->count();
            
            $categories = Kategori::all();
            $view->with(compact('newProducts', 'newPromos', 'newArticles', 'totalNotifications', 'categories'));
        });
    }
}
