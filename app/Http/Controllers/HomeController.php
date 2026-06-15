<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekomendasi = [
            ['title' => 'BUMBU TABUR RASA HONEY BUTTER 1KG', 'image' => 'honey_butter.jpg', 'price' => 100000, 'rating' => '4.9', 'sold' => '120'],
            ['title' => 'BUMBU TABUR MANIS GREEN TEA PREMIUM', 'image' => 'green_tea.jpg', 'price' => 200000, 'rating' => '5.0', 'sold' => '155'],
            ['title' => 'SPESIAL BUMBU ASIN GURIH NAGIH TANPA MSG', 'image' => 'keju_asin.jpg', 'price' => 125000, 'rating' => '4.9', 'sold' => '94'],
            ['title' => 'BUBUK KECAP ASIN 1 KG BEST SELLER ASLI', 'image' => 'kecap_asin.jpg', 'price' => 137000, 'rating' => '4.9', 'sold' => '100'],
            ['title' => '1KG BUMBU TABUR PEDAS MANIS COCOK SNACK', 'image' => 'pedas_manis.jpg', 'price' => 80000, 'rating' => '4.9', 'sold' => '120'],
            ['title' => 'ANGGUR MANIS ENAK WANGI BUMBU TABUR', 'image' => 'anggur.jpg', 'price' => 100000, 'rating' => '4.9', 'sold' => '120'],
        ];

        // Data Dummy untuk Katalog Utama Bawah - 5 Item
        $katalog = [
            ['title' => 'NON MSG TABURAN KEJU ASIN KUNING 1KG', 'image' => 'keju_asin.jpg', 'price' => 100000, 'rating' => '4.9', 'sold' => '120'],
            ['title' => 'CHEDDAR KEJU 1KG COCOK UNTUK BUMBU', 'image' => 'cheddar.jpg', 'price' => 120000, 'rating' => '4.9', 'sold' => '120'],
            ['title' => 'KEJU MANIS ORANGE JERUK TABURAN BUMBU', 'image' => 'keju_orange.jpg', 'price' => 85000, 'rating' => '4.9', 'sold' => '120'],
            ['title' => 'BUMBU TABUR COCOK CIMOL RASA BBQ KOREA', 'image' => 'korean_bbq.jpg', 'price' => 115000, 'rating' => '4.9', 'sold' => '120'],
            ['title' => 'PRAKTIS BUMBU NASI GORENG LEZAT 1KG', 'image' => 'nasi_goreng.jpg', 'price' => 100000, 'rating' => '4.9', 'sold' => '120'],
        ];

        return view('home', compact('rekomendasi', 'katalog'));
    }

    public function toko()
    {
        $rekomendasi = [
            ['title' => 'BUMBU TABUR RASA HONEY BUTTER 1KG', 'image' => 'honey_butter.jpg', 'price' => 100000, 'rating' => '4.9', 'sold' => '120'],
            ['title' => 'BUMBU TABUR MANIS GREEN TEA PREMIUM', 'image' => 'green_tea.jpg', 'price' => 200000, 'rating' => '5.0', 'sold' => '155'],
            ['title' => 'SPESIAL BUMBU ASIN GURIH NAGIH TANPA MSG', 'image' => 'keju_asin.jpg', 'price' => 125000, 'rating' => '4.9', 'sold' => '94'],
            ['title' => 'BUBUK KECAP ASIN 1 KG BEST SELLER ASLI', 'image' => 'kecap_asin.jpg', 'price' => 137000, 'rating' => '4.9', 'sold' => '100'],
            ['title' => '1KG BUMBU TABUR PEDAS MANIS COCOK SNACK', 'image' => 'pedas_manis.jpg', 'price' => 80000, 'rating' => '4.9', 'sold' => '120'],
            ['title' => 'ANGGUR MANIS ENAK WANGI BUMBU TABUR', 'image' => 'anggur.jpg', 'price' => 100000, 'rating' => '4.9', 'sold' => '120'],
        ];

        // Data Dummy untuk Katalog Utama Bawah - 5 Item
        $katalog = [
            ['title' => 'NON MSG TABURAN KEJU ASIN KUNING 1KG', 'image' => 'keju_asin.jpg', 'price' => 100000, 'rating' => '4.9', 'sold' => '120'],
            ['title' => 'CHEDDAR KEJU 1KG COCOK UNTUK BUMBU', 'image' => 'cheddar.jpg', 'price' => 120000, 'rating' => '4.9', 'sold' => '120'],
            ['title' => 'KEJU MANIS ORANGE JERUK TABURAN BUMBU', 'image' => 'keju_orange.jpg', 'price' => 85000, 'rating' => '4.9', 'sold' => '120'],
            ['title' => 'BUMBU TABUR COCOK CIMOL RASA BBQ KOREA', 'image' => 'korean_bbq.jpg', 'price' => 115000, 'rating' => '4.9', 'sold' => '120'],
            ['title' => 'PRAKTIS BUMBU NASI GORENG LEZAT 1KG', 'image' => 'nasi_goreng.jpg', 'price' => 100000, 'rating' => '4.9', 'sold' => '120'],
        ];

        return view('home', compact('rekomendasi', 'katalog'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
