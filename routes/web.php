<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ArticleController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/beranda', [HomeController::class, 'beranda'])->name('beranda');
Route::get('/articles-all', [ArticleController::class, 'indexArtikel'])->name('articles.all');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});
    
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/filter-produk/{kategori_id?}', [HomeController::class, 'filter'])->name('produk.filter');
        Route::get('/produk/detail/{id}', [ProdukController::class, 'detail_produk'])->name('produk.detail');
        Route::post('/produk/{id}/review', [ProdukController::class, 'storeReview'])->name('produk.review');
        Route::get('/articles/fetch', [ArticleController::class, 'fetch'])->name('articles.fetch');
        Route::post('/categories', [ArticleController::class, 'storeCategory'])->name('articles.category');
        Route::post('/product/{product}/toggle-favorite', [ProdukController::class, 'toggleFavorite'])->name('produk.favorit');
        Route::post('/product/{product}/toggle-wishlist', [ProdukController::class, 'toggleWishlist'])->name('produk.wishlist');
        Route::get('/product-favorit', [ProdukController::class, 'indexFavorit'])->name('produk.favorit_view');
        Route::get('/keranjang', [ProdukController::class, 'indexKeranjang'])->name('produk.keranjang');
        Route::delete('/keranjang/{id}/delete', [ProdukController::class, 'hapusKeranjang'])->name('keranjang.destroy');
        Route::post('/keranjang/delete-batch', [ProdukController::class, 'hapusSemuaKeranjang'])->name('keranjang.destroy_all');
        Route::resource('promo', PromoController::class);
        Route::resource('kategori', KategoriController::class);
        Route::resource('produk', ProdukController::class);
        Route::resource('voucher', VoucherController::class);
        Route::resource('banner', BannerController::class);
        Route::resource('articles', ArticleController::class);
    });

});