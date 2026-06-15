@extends('app') {{-- Disesuaikan ke layouts.app sesuai layout utama Anda --}}

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 antialiased">

    <!-- SUB-NAVBAR: PENCARIAN & FITUR BELANJA (Responsif) -->
    <div class="bg-[#F2B705] py-3 px-4 md:px-6 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-3 md:gap-4">
            
            <!-- Form Cari Produk -->
            <form action="#" method="GET" class="w-full md:w-2/3 flex bg-white rounded overflow-hidden shadow-sm">
                <input type="text" placeholder="Cari di toko..." class="w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-400 focus:outline-none">
                <select class="bg-gray-100 text-xs px-3 border-l border-gray-200 text-gray-600 focus:outline-none hidden sm:block">
                    <option>Kategori</option>
                </select>
                <button type="submit" class="bg-[#24420A] text-white px-5 flex items-center justify-center hover:bg-opacity-90 transition shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                    </svg>
                </button>
            </form>

            <!-- Ikon Navigasi Belanja -->
            <div class="flex items-center justify-center space-x-8 md:space-x-6 text-[#24420A] w-full md:w-auto pt-1 md:pt-0 border-t border-[#dcb43c] md:border-none">
                <a href="#" class="relative hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">2</span>
                </a>
                <a href="#" class="hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                </a>
                <a href="#" class="hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75 2.75 0 1 1-1.5 0 .75 2.75 0 0 1 1.5 0Zm12.75 0a.75 2.75 0 1 1-1.5 0 .75 2.75 0 0 1 1.5 0Z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- WRAPPER KONTEN UTAMA -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-6">

        <!-- Notifikasi Error Sistem -->
        @if ($errors->any())
            <div class="mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm">
                    <strong class="font-bold">Waduh!</strong>
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

        <!-- Notifikasi Sukses Sistem -->
        @if (session('success'))
            <div class="mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        <!-- Banner 1: Bumbu Tabur -->
        <div class="w-full rounded-lg overflow-hidden shadow-sm mb-6 bg-gray-200">
            <img src="{{ asset('images/banner-bumbu-tabur.jpg') }}" alt="Banner Aneka Bumbu Tabur" class="w-full h-auto min-h-[120px] object-cover">
        </div>

        <!-- Menu Tab Kategori (Bisa di-swipe/scroll horizontal pada layar HP) -->
        <div class="border-b border-gray-200 mb-6 overflow-x-auto scrollbar-none whitespace-nowrap">
            <ul class="flex space-x-6 md:space-x-8 text-sm font-semibold text-gray-600 pb-2">
                <li class="border-b-2 border-[#24420A] text-[#24420A] pb-2 cursor-pointer shrink-0">Halaman utama</li>
                <li class="hover:text-[#24420A] pb-2 cursor-pointer transition shrink-0">Produk</li>
                <li class="hover:text-[#24420A] pb-2 cursor-pointer transition shrink-0">PAYDAY SALE</li>
                <li class="hover:text-[#24420A] pb-2 cursor-pointer transition shrink-0">PRODUK TERBARU</li>
                <li class="hover:text-[#24420A] pb-2 cursor-pointer transition shrink-0">POT BUNGA</li>
                <li class="hover:text-[#24420A] pb-2 cursor-pointer transition shrink-0">Lainnya <span class="text-xs">▼</span></li>
            </ul>
        </div>

        <!-- REKOMENDASI PRODUK GRID (Ganti md:grid-cols-4, lg:grid-cols-6 agar proporsional) -->
        <div class="mb-10">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xs md:text-sm font-bold text-gray-800 tracking-wider uppercase">Mungkin Kamu Cari</h2>
                <a href="#" class="text-xs text-gray-500 hover:text-[#24420A] flex items-center transition">Lihat Semua <span class="ml-1">></span></a>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4">
                @foreach($rekomendasi as $item)
                    <x-product-card 
                        :title="$item['title']" 
                        :image="$item['image']" 
                        :price="$item['price']" 
                        :rating="$item['rating']" 
                        :sold="$item['sold']" 
                    />
                @endforeach
            </div>
        </div>

        <!-- Banner 2: Tepung Marinasi -->
        <div class="w-full rounded-lg overflow-hidden shadow-sm mb-10 bg-gray-200">
            <img src="{{ asset('images/banner-tepung-marinasi.jpg') }}" alt="Banner Tepung Marinasi" class="w-full h-auto min-h-[120px] object-cover">
        </div>

        <!-- DUA KOLOM UTAMA (Sidebar Kategori & Katalog Katalog Utama) -->
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
            
            <!-- ASIDE: Navigasi Kategori Kiri (Sembunyi di HP, Tampil Desktop) -->
            <aside class="w-full lg:w-1/5 shrink-0 hidden lg:block">
                <div class="bg-white p-4 rounded border border-gray-200 sticky top-24">
                    <h3 class="text-sm font-bold text-gray-800 border-b pb-2 mb-3 flex items-center">
                        <span class="mr-2 text-[#24420A]">■</span> Kategori
                    </h3>
                    <ul class="text-xs space-y-2.5 font-medium text-gray-600">
                        <li class="text-[#24420A] font-bold flex items-center">
                            <span class="mr-1">></span> Semua Produk
                        </li>
                        <li class="pl-3 hover:text-[#24420A] cursor-pointer font-semibold transition">PAYDAY SALE</li>
                        <li class="pl-6 text-gray-400 hover:text-[#24420A] cursor-pointer transition">DISC UP TO 60%</li>
                        <li class="pl-6 text-gray-400 hover:text-[#24420A] cursor-pointer transition">CLEARANCE SALE</li>
                        <li class="pl-3 hover:text-[#24420A] cursor-pointer transition">PRODUK TERBARU</li>
                        <li class="pl-3 hover:text-[#24420A] cursor-pointer transition">RASA BUAH</li>
                        <li class="pl-3 hover:text-[#24420A] cursor-pointer transition">RASA DUNIA</li>
                        <li class="pl-6 text-gray-400 hover:text-[#24420A] cursor-pointer transition">KOREA</li>
                        <li class="pl-6 text-gray-400 hover:text-[#24420A] cursor-pointer transition">INDIA</li>
                    </ul>
                </div>
            </aside>

            <!-- BLOCK KATALOG: Daftar Semua Produk -->
            <div class="w-full lg:w-4/5">
                
                <!-- Bilah Penyortiran / Urutkan Bar (Flex Wrap Otomatis jika di HP) -->
                <div class="bg-[#EAEFD6] p-2 rounded flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-6 text-xs">
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                        <span class="text-gray-600 font-medium pl-1 sm:pl-2 w-full sm:w-auto mb-1 sm:mb-0">Urutkan</span>
                        <button class="bg-[#24420A] text-white px-3 sm:px-4 py-1.5 rounded font-semibold text-center flex-1 sm:flex-none">Populer</button>
                        <button class="bg-white text-gray-700 px-3 sm:px-4 py-1.5 rounded border border-gray-200 hover:bg-gray-50 text-center flex-1 sm:flex-none">Terbaru</button>
                        <button class="bg-white text-gray-700 px-3 sm:px-4 py-1.5 rounded border border-gray-200 hover:bg-gray-50 text-center flex-1 sm:flex-none">Terlaris</button>
                        <select class="bg-white text-gray-700 px-3 py-1.5 rounded border border-gray-200 focus:outline-none w-full sm:w-auto">
                            <option>Harga</option>
                        </select>
                    </div>
                    
                    <!-- Indikator Pagination Atas -->
                    <div class="flex items-center justify-between sm:justify-end space-x-3 text-gray-600 px-1 sm:pr-2 border-t sm:border-none pt-2 sm:pt-0">
                        <span><strong class="text-gray-800">1</strong>/33</span>
                        <div class="flex space-x-1">
                            <button class="p-1 px-2.5 bg-gray-100 rounded text-gray-400 cursor-not-allowed" disabled>&lt;</button>
                            <button class="p-1 px-2.5 bg-white rounded border border-gray-200 hover:bg-gray-50">&gt;</button>
                        </div>
                    </div>
                </div>

                <!-- MAIN PRODUCTS GRID -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4">
                    @foreach($katalog as $item)
                        <x-product-card 
                            :title="$item['title']" 
                            :image="$item['image']" 
                            :price="$item['price']" 
                            :rating="$item['rating']" 
                            :sold="$item['sold']" 
                        />
                    @endforeach
                </div>

            </div>
        </div>

    </div>
</div>
@endsection