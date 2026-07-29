<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- SEO DYNAMIC --}}
    <title>@yield('meta_title', 'GAFI - Golden Aroma Food Indonesia')</title>
    <meta name="description" content="@yield('meta_description', 'Pusat seasoning powder dan beverage powder terbaik di Indonesia.')">
    <meta name="keywords" content="@yield('meta_keywords', 'GAFI, seasoning powder, bumbu tabur, minuman serbuk')">

    {{-- Open Graph (Social Media Preview) --}}
    <meta property="og:title" content="@yield('meta_title', 'GAFI - Golden Aroma Food Indonesia')">
    <meta property="og:description" content="@yield('meta_description', 'Pusat seasoning powder dan beverage powder terbaik di Indonesia.')">
    <meta property="og:image" content="@yield('og_image', asset('img/default-logo.png'))">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        html {
            scroll-behavior: smooth;
        }
        #banner-carousel {
            scroll-snap-type: none; /* Nonaktifkan snap */
            scrollbar-width: none;
        }
        #banner-carousel::-webkit-scrollbar { display: none; }
        
        /* Pastikan gambar tidak bisa didrag secara paksa oleh browser */
        #banner-carousel img {
            pointer-events: none;
            user-select: none;
        }
        .ck-editor__editable_inline {
            min-height: 300px;
        }

        .ck-editor__editable {
            min-height: 200px !important;
        }

        /* Memastikan gambar di dalam editor responsif */
        .ck-content img {
            max-width: 100% !important;
            height: auto !important;
        }
    </style>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 antialiased">

    <nav class="bg-[#1C3508] text-white px-4 md:px-6 py-3 shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2 focus:outline-none shrink-0">
                <img src="{{ asset('images/Logo_gafi.png') }}" alt="Logo GAFI" class="h-9 md:h-12 w-auto object-contain">
            </a>
            
            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-6 font-medium">
                <ul class="flex items-center space-x-6 text-[14px] lg:text-[15px] tracking-wide">
                    @auth
                        @if(Auth::user()->is_admin)
                            <li class="relative" id="admin-cms-wrapper">
                                <button id="admin-cms-btn" class="text-white hover:text-[#E0A226] font-semibold flex items-center space-x-1 focus:outline-none transition">
                                    <span>Dashboard Admin</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 transition-transform duration-200" id="admin-arrow">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>

                                <div id="admin-cms-menu" class="opacity-0 scale-95 pointer-events-none invisible transition-all duration-200 ease-out absolute left-0 mt-3 w-52 bg-white rounded-md shadow-lg py-1.5 z-50 border border-gray-100 ring-1 ring-black ring-opacity-5 origin-top-left">
                                    <div class="px-4 py-1 border-b border-gray-100 mb-1">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Fitur CMS GAFI</p>
                                    </div>
                                    <a href="{{ route('kategori.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#24420A] transition">Kelola Kategori</a>
                                    <a href="{{ route('produk.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#24420A] transition">Kelola Produk</a>
                                    <a href="{{ route('voucher.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#24420A] transition">Kelola Voucher</a>
                                    <a href="{{ route('banner.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#24420A] transition">Kelola Gambar</a>
                                    <a href="{{ route('articles.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#24420A] transition">Kelola Artikel</a>
                                </div>
                            </li>
                            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Toko</a></li>
                            <li><a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Beranda</a></li>
                            <li><a href="{{ route('articles.all') }}" class="{{ request()->routeIs('articles.all') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }}">Artikel</a></li>
                            <li><a href="{{ route('promo.home') }}" class="{{ request()->routeIs('promo.home') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Promo</a></li>
                            <li><a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Tentang</a></li>
                            <li><a href="#footer" class="text-white hover:text-[#E0A226] transition">Kontak</a></li>
                        @else
                            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Toko</a></li>
                            <li><a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Beranda</a></li>
                            <li><a href="{{ route('articles.all') }}" class="{{ request()->routeIs('articles.all') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }}">Artikel</a></li>
                            <li><a href="{{ route('promo.home') }}" class="{{ request()->routeIs('promo.home') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Promo</a></li>
                            <li><a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Tentang</a></li>
                            <li><a href="#footer" class="text-white hover:text-[#E0A226] transition">Kontak</a></li>
                        @endif
                    @endauth

                    @guest
                        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Toko</a></li>
                        <li><a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Beranda</a></li>
                        <li><a href="{{ route('articles.all') }}" class="{{ request()->routeIs('articles.all') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }}">Artikel</a></li>
                        <li><a href="{{ route('promo.home') }}" class="{{ request()->routeIs('promo.home') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Promo</a></li>
                        <li><a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Tentang Kami</a></li>
                        <li><a href="#footer" class="text-white hover:text-[#E0A226] transition">Kontak</a></li>
                    @endguest
                </ul>

                <!-- Autentikasi Desktop -->
                @auth
                    <div class="pl-4 border-l border-green-800/50 relative" id="user-dropdown-wrapper">
                        <button id="dropdown-btn" class="text-white hover:text-[#E0A226] transition block focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </button>

                        <div id="dropdown-menu" class="opacity-0 scale-95 pointer-events-none invisible transition-all duration-200 ease-out absolute right-0 mt-3 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-100 ring-1 ring-black ring-opacity-5 origin-top-right">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs text-gray-400">Selamat datang,</p>
                                <p class="text-sm font-semibold text-gray-700 truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Profil Saya</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Pesanan Saya</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST" class="block w-full">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Keluar</button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <div class="pl-4 border-l border-green-800/50">
                        <button id="open-login-btn" class="bg-[#E0A226] text-[#24420A] px-4 py-1.5 rounded font-semibold text-sm hover:bg-white hover:text-[#24420A] transition duration-200 shadow-sm focus:outline-none">
                            Masuk
                        </button>
                    </div>
                @endguest
            </div>

            <!-- Tombol Hamburger Mobile -->
            <div class="flex items-center md:hidden">
                <button id="mobile-menu-btn" class="text-white hover:text-[#E0A226] focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Dropdown Menu Mobile -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-green-800/60 mt-3 pt-2 space-y-2">
            <ul class="space-y-1.5 text-sm tracking-wide font-medium">
                @auth
                    @if(Auth::user()->is_admin)
                        <li class="block border-b border-green-800/40 pb-2 mb-2">
                            <button id="admin-mobile-trigger" class="w-full flex items-center justify-between py-2 px-2 rounded font-bold text-[#E0A226] hover:bg-green-900 transition focus:outline-none">
                                <span>Manajemen CMS</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 transition-transform duration-300" id="admin-mobile-arrow">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <div id="admin-mobile-dropdown" style="max-height: 0px;" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out pl-4 border-l-2 border-[#E0A226]/40 space-y-1 mt-1">
                                <a href="{{ route('kategori.index') }}" class="block py-1.5 px-2 text-xs text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Kategori</a>
                                <a href="{{ route('produk.index') }}" class="block py-1.5 px-2 text-xs text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Produk</a>
                                <a href="{{ route('voucher.index') }}" class="block py-1.5 px-2 text-xs text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Voucher</a>
                                <a href="{{ route('banner.index') }}" class="block py-1.5 px-2 text-xs text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Gambar</a>
                                <a href="{{ route('articles.index') }}" class="block py-1.5 px-2 text-xs text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Artikel</a>
                            </div>
                        </li>
                    @endif
                    <li><a href="{{ route('home') }}" class="block py-2 px-2 rounded {{ request()->routeIs('home') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Toko</a></li>
                    <li><a href="{{ route('beranda') }}" class="block py-2 px-2 rounded {{ request()->routeIs('beranda') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Beranda</a></li>
                    <li><a href="{{ route('articles.all') }}" class="block py-2 px-2 rounded {{ request()->routeIs('articles.all') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }}">Artikel</a></li>
                    <li><a href="{{ route('promo.home') }}" class="block py-2 px-2 rounded {{ request()->routeIs('promo.home') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Promo</a></li>
                    <li><a href="{{ route('tentang') }}" class="block py-2 px-2 rounded {{ request()->routeIs('tentang') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Tentang</a></li>
                    <li><a href="#footer" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Kontak</a></li>
                @endauth

                @guest
                    <li><a href="{{ route('home') }}" class="block py-2 px-2 rounded {{ request()->routeIs('home') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Toko</a></li>
                    <li><a href="{{ route('beranda') }}" class="block py-2 px-2 rounded {{ request()->routeIs('beranda') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Beranda</a></li>
                    <li><a href="{{ route('articles.all') }}" class="block py-2 px-2 rounded {{ request()->routeIs('articles.all') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }}">Artikel</a></li>
                    <li><a href="{{ route('promo.home') }}" class="block py-2 px-2 rounded {{ request()->routeIs('promo.home') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Promo</a></li>
                    <li><a href="{{ route('tentang') }}" class="block py-2 px-2 rounded {{ request()->routeIs('tentang') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Tentang Kami</a></li>
                    <li><a href="#footer" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Kontak</a></li>
                @endguest
            </ul>

            <div class="pt-2 border-t border-green-800/60">
                @auth
                    <div class="px-2 py-1 text-xs text-green-300">Selamat datang, <span class="font-bold text-white">{{ Auth::user()->name }}</span></div>
                    @if(!Auth::user()->is_admin)
                        <a href="#" class="block py-2 px-2 text-sm text-white hover:bg-green-900 rounded transition">Profil Saya</a>
                        <a href="#" class="block py-2 px-2 text-sm text-white hover:bg-green-900 rounded transition">Pesanan Saya</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="block w-full pt-1">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 px-2 text-sm text-red-400 font-bold hover:bg-red-400/10 rounded transition">Keluar</button>
                    </form>
                @endauth

                @guest
                    <button id="open-login-btn-mobile" class="w-full bg-[#E0A226] text-[#24420A] py-2 rounded font-bold text-sm hover:bg-white transition duration-200 shadow-sm mt-1">
                        Masuk / Daftar
                    </button>
                @endguest
            </div>
        </div>
    </nav>

    <!-- SUB-BAR (PENCARIAN & AKSI) -->
    <div class="{{ Route::is('produk.favorit_view') || Route::is('produk.keranjang') ? 'bg-[#E7F7DA]' : 'bg-[#F2B705]' }} py-3 px-4 md:px-6 shadow-sm transition-colors duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-2 md:gap-4">

            <!-- Judul Halaman Tertentu (Desktop) -->
            @if(Route::is('produk.favorit_view'))
                <h1 class="text-[#24420A] font-bold text-xl md:text-2xl whitespace-nowrap hidden md:flex items-center mr-2">
                    <i class="far fa-heart mr-2 text-xl"></i>Favorit Saya
                </h1>
            @endif

            @if(Route::is('produk.keranjang'))
                <h1 class="text-[#24420A] font-bold text-xl md:text-2xl whitespace-nowrap hidden md:flex items-center mr-2">
                    <i class="fas fa-shopping-cart mr-2 text-xl"></i>Keranjang Saya
                </h1>
            @endif
            
            <!-- Form Pencarian (Fleksibel mengisi sisa ruang di sebelah ikon) -->
            <form action="{{ route('search') }}" method="GET" class="flex-1 min-w-0 flex bg-white rounded-lg overflow-hidden shadow-sm">
                <input type="text" name="query" placeholder="Cari di toko..." class="w-full px-3 md:px-4 py-2 text-xs md:text-sm text-gray-700 placeholder-gray-400 focus:outline-none min-w-0">
                <div class="border-l border-gray-200"></div>
                <select name="category" class="text-xs px-2 text-gray-500 focus:outline-none hidden md:block bg-transparent">
                    <option value="">Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-[#24420A] text-white px-3 md:px-5 flex items-center justify-center hover:bg-opacity-90 transition shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                    </svg>
                </button>
            </form>

            <!-- Ikon Aksi Cepat (Tetap di samping search, menggunakan shrink-0 agar ukurannya tidak tertekan) -->
            <div class="flex items-center space-x-2.5 sm:space-x-4 md:space-x-5 text-[#24420A] shrink-0">
                <!-- Tombol Favorit -->
                <a href="{{ route('produk.favorit_view') }}" class="need-login hover:text-green-900 transition p-1" title="Favorit">
                    @if(Route::is('produk.favorit_view'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                    @endif
                </a>

                <!-- Tombol Keranjang -->
                <a href="{{ route('produk.keranjang') }}" class="need-login hover:text-white transition p-1" title="Keranjang">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75 2.75 0 1 1-1.5 0 .75 2.75 0 0 1 1.5 0Zm12.75 0a.75 2.75 0 1 1-1.5 0 .75 2.75 0 0 1 1.5 0Z" />
                    </svg>
                </a>

                <!-- Tombol Pesan / Notifikasi -->
                <div class="relative" x-data="{ 
                    openMessage: false, 
                    notificationCount: {{ $totalNotifications ?? 0 }},
                    markAsRead() {
                        if (this.notificationCount === 0) return;
                        
                        fetch('{{ route('notifications.markAsRead') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.notificationCount = 0;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                }">
                    @auth
                        <button @click="openMessage = !openMessage; if(openMessage) { markAsRead(); }" class="relative hover:text-white transition focus:outline-none flex items-center p-1" title="Notifikasi">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            
                            <template x-if="notificationCount > 0">
                                <span class="absolute -top-1 -right-1 bg-green-900 text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-bold border border-white" x-text="notificationCount"></span>
                            </template>
                        </button>
                    @else
                        <button @click="openMessage = !openMessage" class="relative hover:text-white transition focus:outline-none flex items-center p-1" title="Notifikasi">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            @if(($totalNotifications ?? 0) > 0)
                                <span class="absolute -top-1 -right-1 bg-green-900 text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-bold border border-white">
                                    {{ $totalNotifications }}
                                </span>
                            @endif
                        </button>
                    @endauth

                    <!-- Dropdown Box Notifikasi -->
                    <div x-show="openMessage" @click.away="openMessage = false" 
                        class="absolute right-0 mt-3 w-72 sm:w-80 bg-white rounded-xl shadow-lg border border-gray-100 py-3 z-50 text-gray-700 text-xs" 
                        style="display: none;">
                        
                        <div class="px-4 pb-2 border-b border-gray-100 font-bold text-gray-800 flex justify-between items-center">
                            <span>Informasi Terbaru (3 Hari Terakhir)</span>
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-[10px]" x-text="notificationCount > 0 ? notificationCount : '0'"></span>
                        </div>

                        <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
                            @forelse($newProducts as $prod)
                                <a href="{{ route('produk.detail', $prod->id) }}" class="need-login flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                                    <span class="bg-blue-100 text-blue-600 p-2 rounded-lg text-base shrink-0">📦</span>
                                    <div class="overflow-hidden">
                                        <p class="font-semibold text-gray-800 truncate">Produk Baru: {{ $prod->nama_produk ?? $prod->name }}</p>
                                        <p class="text-[10px] text-gray-400">Tersedia di katalog toko</p>
                                    </div>
                                </a>
                            @empty
                            @endforelse

                            @forelse($newPromos as $promo)
                                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                                    <span class="bg-orange-100 text-orange-600 p-2 rounded-lg text-base shrink-0">🔥</span>
                                    <div class="overflow-hidden">
                                        <p class="font-semibold text-gray-800 truncate">Promo: {{ $promo->title ?? $promo->nama_promo }}</p>
                                        <p class="text-[10px] text-gray-400">Jangan lewatkan penawarannya!</p>
                                    </div>
                                </a>
                            @empty
                            @endforelse

                            @forelse($newArticles as $article)
                                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                                    <span class="bg-green-100 text-green-600 p-2 rounded-lg text-base shrink-0">📰</span>
                                    <div class="overflow-hidden">
                                        <p class="font-semibold text-gray-800 truncate">Artikel: {{ $article->title ?? $article->judul }}</p>
                                        <p class="text-[10px] text-gray-400">Baca informasi menarik hari ini</p>
                                - </div>
                                </a>
                            @empty
                            @endforelse

                            @php
                                $totalAllItems = $newProducts->count() + $newPromos->count() + $newArticles->count();
                            @endphp

                            @if($totalAllItems == 0)
                                <div class="py-8 text-center text-gray-400">
                                    <p>Tidak ada informasi baru dalam 3 hari terakhir.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <div id="login-modal" class="fixed inset-0 z-50 flex items-center justify-center opacity-0 pointer-events-none invisible transition-all duration-300 ease-out p-4">
        <div id="modal-overlay" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-5 md:p-6 relative z-10 transform scale-95 transition-all duration-300 ease-out max-h-[90vh] overflow-y-auto" id="modal-box">
            
            <button id="close-login-btn" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none z-20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            <div id="modal-error-message" class="hidden mb-4 bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-xs font-medium animate-pulse"></div>

            <div class="relative overflow-hidden min-h-[380px] md:min-h-[400px] flex flex-col justify-center">

                <div id="form-login-container" class="w-full transition-all duration-300 ease-in-out opacity-100 scale-100 pointer-events-auto absolute inset-x-0 top-0">
                    <div class="text-center mb-5">
                        <h3 class="text-xl font-bold text-gray-800">Masuk ke Akun Anda</h3>
                        <p class="text-xs text-gray-500 mt-1">Belum punya akun? <button id="switch-to-register" class="text-[#24420A] font-bold hover:underline focus:outline-none">Daftar disini</button></p>
                    </div>

                    <form id="ajax-login-form" action="{{ route('login') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat Email</label>
                            <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#24420A] text-sm text-gray-700">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Password</label>
                            <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#24420A] text-sm text-gray-700">
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500 pt-1">
                            <label class="flex items-center space-x-1.5 cursor-pointer">
                                <input type="checkbox" name="remember" class="rounded text-[#24420A] focus:ring-0">
                                <span>Ingat saya</span>
                            </label>
                            <a href="#" class="hover:text-[#24420A]">Lupa password?</a>
                        </div>
                        <button type="submit" class="w-full bg-[#24420A] hover:bg-opacity-90 text-white font-semibold py-2.5 rounded text-sm transition mt-2 shadow-sm">
                            Masuk Sekarang
                        </button>
                    </form>
                </div>

                <div id="form-register-container" class="w-full transition-all duration-300 ease-in-out opacity-0 scale-105 translate-y-4 pointer-events-none invisible absolute inset-x-0 top-0">
                    <div class="text-center mb-5">
                        <h3 class="text-xl font-bold text-gray-800">Daftar Akun Baru</h3>
                        <p class="text-xs text-gray-500 mt-1">Sudah punya akun? <button id="switch-to-login" class="text-[#24420A] font-bold hover:underline focus:outline-none">Login disini</button></p>
                    </div>

                    <form id="ajax-register-form" action="{{ route('register') }}" method="POST" class="space-y-3 md:space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#24420A] text-sm text-gray-700">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat Email</label>
                            <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#24420A] text-sm text-gray-700">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Password</label>
                            <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#24420A] text-sm text-gray-700">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#24420A] text-sm text-gray-700">
                        </div>
                        <button type="submit" class="w-full bg-[#E0A226] hover:bg-opacity-90 text-[#24420A] font-bold py-2.5 rounded text-sm transition mt-2 shadow-sm">
                            Daftar Akun
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <footer class="bg-white pt-16 pb-8 border-t border-gray-100" id="footer">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Bagian Utama Footer -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 mb-16">
                <!-- Kolom 1: Profil -->
                <div class="col-span-1">
                    <h3 class="text-[#24420A] font-bold text-lg mb-4 uppercase tracking-wider">Golden Aroma Food Indonesia</h3>
                    <div class="w-16 h-0.5 bg-[#F2B705] mb-6"></div>
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base">
                        Supplier bumbu makanan, bubuk minuman, cabe bubuk, rempah-rempah, essen flavor, dan bahan baku F&B Halal untuk kebutuhan UMKM, distributor, reseller, hingga industri makanan dan minuman di Indonesia.
                    </p>
                </div>

                <!-- Kolom 2 & 3: Navigasi & Layanan -->
                <div class="col-span-1 lg:col-span-2 grid grid-cols-2 sm:grid-cols-3 gap-8">
                    <div>
                        <h4 class="text-[#24420A] font-bold mb-6 uppercase text-sm tracking-wide">Navigasi</h4>
                        <ul class="space-y-3 text-gray-600 text-sm">
                            <li><a href="{{ route('beranda') }}" class="hover:text-[#24420A] transition">Beranda</a></li>
                            <li><a href="{{ route('tentang') }}" class="hover:text-[#24420A] transition">Tentang Kami</a></li>
                            <li><a href="{{ route('home') }}" class="hover:text-[#24420A] transition">Produk</a></li>
                            <li><a href="#" class="hover:text-[#24420A] transition">Custom Bumbu</a></li>
                            <li><a href="{{ route('articles.all') }}" class="hover:text-[#24420A] transition">Artikel</a></li>
                            <li><a href="#footer" class="hover:text-[#24420A] transition">Kontak Kami</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[#24420A] font-bold mb-6 uppercase text-sm tracking-wide">Produk</h4>
                        <ul class="space-y-3 text-gray-600 text-sm">
                            <li><a href="#" class="hover:text-[#24420A] transition">Bumbu Tabur</a></li>
                            <li><a href="#" class="hover:text-[#24420A] transition">Bubuk Minuman</a></li>
                            <li><a href="#" class="hover:text-[#24420A] transition">Cabe Bubuk</a></li>
                            <li><a href="#" class="hover:text-[#24420A] transition">Bubuk Rempah</a></li>
                            <li><a href="#" class="hover:text-[#24420A] transition">Essen Flavor</a></li>
                            <li><a href="#" class="hover:text-[#24420A] transition">Bahan Baku F&B</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[#24420A] font-bold mb-6 uppercase text-sm tracking-wide">Layanan</h4>
                        <ul class="space-y-3 text-gray-600 text-sm">
                            <li><a href="#" class="hover:text-[#24420A] transition">Custom Pembuatan Bumbu</a></li>
                            <li><a href="#" class="hover:text-[#24420A] transition">Konsultasi Produk</a></li>
                            <li><a href="#" class="hover:text-[#24420A] transition">Pemesanan Grosir</a></li>
                            <li><a href="#" class="hover:text-[#24420A] transition">Kerja Sama Distributor</a></li>
                            <li><a href="#" class="hover:text-[#24420A] transition">Private Label</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Bagian Kontak -->
            <div class="mb-12 border-t border-gray-100 pt-10">
                <h4 class="text-[#24420A] font-bold mb-3 uppercase text-sm tracking-wide">Kontak & Alamat</h4>
                <p class="text-gray-600 mb-6 text-sm max-w-2xl leading-relaxed">Gerbang Kuning Gudang Bumbu, Jalan Ceuri no 51 Kampung Sindang Asih, Sebelah Sawah, Jl. Raya Kopo, Katapang, Pamentasan, Kabupaten Bandung, Jawa Barat 40921</p>
                
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 text-sm text-[#24420A] font-medium">
                    <div class="flex flex-wrap gap-4 sm:gap-8 items-center">
                        <span class="flex items-center gap-2">WhatsApp: <a href="https://wa.me/6289612821257" target="_blank" class="hover:underline">0896-1282-1257</a></span>
                        <span class="hidden sm:inline text-gray-300">|</span>
                        <span class="flex items-center gap-2">Email: <a href="mailto:gafi.bdg.adm@gmail.com" class="hover:underline">gafi.bdg.adm@gmail.com</a></span>
                    </div>

                    <!-- Ikon Media Sosial -->
                    <div class="flex items-center gap-4">
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/goldenaromafood_gafi.official?igsh=MXB1dXhhbGR4NDE4ag==" target="_blank" class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:text-white hover:bg-[#E1306C] transition-all duration-200 shadow-sm" aria-label="Instagram">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        </a>

                        <!-- LinkedIn -->
                        <a href="#" class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:text-white hover:bg-[#0A66C2] transition-all duration-200 shadow-sm" aria-label="LinkedIn">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                        </a>

                        <!-- Facebook -->
                        <a href="https://www.facebook.com/share/1Bcndmmckw/" target="_blank" class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:text-white hover:bg-[#1877F2] transition-all duration-200 shadow-sm" aria-label="Facebook">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                            </svg>
                        </a>

                        <!-- YouTube -->
                        <a href="https://youtube.com/@dapur_cuan_gafi?si=Jf6iuoZnCyzBYGgp" target="_blank" class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:text-white hover:bg-[#FF0000] transition-all duration-200 shadow-sm" aria-label="YouTube">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Jam Operasional -->
            <div class="border-y border-[#F2B705]/60 bg-[#E7F7DA]/30 py-6 px-4 rounded-xl grid grid-cols-2 md:grid-cols-4 gap-4 text-center text-[#24420A] text-xs sm:text-sm">
                <div class="font-bold flex items-center justify-center">JAM OPERASIONAL</div>
                <div><span class="block font-bold">SENIN - JUM'AT</span> 09.00 - 16.00 WIB</div>
                <div><span class="block font-bold">SABTU</span> Closed</div>
                <div><span class="block font-bold">MINGGU</span> 09.00 - 16.00 WIB</div>
            </div>

            <!-- Baris Terakhir (CTA) -->
            <div class="mt-8 pt-4 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
                <p class="font-bold text-[#24420A] text-sm sm:text-base">Butuh supplier bumbu Halal untuk bisnis Anda?</p>
                <a href="https://wa.me/6289612821257" target="_blank" class="bg-[#24420A] text-white px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-[#1a3307] transition shadow-sm text-sm sm:text-base w-full md:w-auto">
                    <span>Hubungi Kami</span>
                </a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const openModalBtnMobile = document.getElementById('open-login-btn-mobile');
            const openModalBtnDesktop = document.getElementById('open-login-btn');
            
            // Toggle Menu Hamburger di HP
            if(mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Jalankan trigger klik tombol desktop saat tombol login versi mobile di-klik
            if (openModalBtnMobile && openModalBtnDesktop) {
                openModalBtnMobile.addEventListener('click', function() {
                    // Tutup menu mobile dropdown dulu
                    mobileMenu.classList.add('hidden');
                    // Trigger buka modal login
                    openModalBtnDesktop.click();
                });
            }

            const productGrid = document.getElementById('product-grid');

            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // UI Feedback: ubah warna tombol yang aktif
                    document.querySelectorAll('.filter-btn').forEach(b => {
                        b.classList.remove('bg-[#24420A]', 'text-white');
                        b.classList.add('bg-white', 'text-gray-700');
                    });
                    this.classList.add('bg-[#24420A]', 'text-white');
                    this.classList.remove('bg-white', 'text-gray-700');
                });
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('#pagination-links a')) {
                    e.preventDefault();
                    const url = e.target.closest('a').getAttribute('href');
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(res => res.text())
                        .then(html => {
                            productGrid.innerHTML = html;
                            window.history.pushState({}, '', url);
                        });
                }
            });
        });
    </script>
    <script>

        function loadProduk(kategoriId) {
            const items = document.querySelectorAll('.product-item');
            
            items.forEach(item => {
                if (kategoriId === null || item.getAttribute('data-kategori') == kategoriId) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        function filterProduk(kategoriId, element, className) {
            const items = document.querySelectorAll(className);
            const messageElement = document.getElementById('no-product-message');
            
            if (items.length === 0) {
                console.warn(`Elemen dengan class ${className} tidak ditemukan.`);
                return; 
            }
                    
            // 1. Logika Filter
            let visibleCount = 0; // Tambahkan variabel hitung

            items.forEach(item => {
                const catId = item.getAttribute('data-kategori');
                if (kategoriId === null || catId == kategoriId) {
                    item.style.display = 'block';
                    visibleCount++; // Tambah jika produk terlihat
                } else {
                    item.style.display = 'none';
                }
            });

            // 2. Logika Pesan Kosong
            if (messageElement) {
                if (visibleCount === 0) {
                    messageElement.classList.remove('hidden'); // Tampilkan pesan
                } else {
                    messageElement.classList.add('hidden'); // Sembunyikan pesan
                }
            }

            // 3. Logika Penanda Aktif
            if (element) {
                const allButtons = document.querySelectorAll('nav button, aside button');
                allButtons.forEach(btn => btn.classList.remove('text-[#24420A]', 'font-bold'));
                element.classList.add('text-[#24420A]', 'font-bold');
            }
        }

        const stars = document.querySelectorAll('#starRating label');
        stars.forEach(star => {
            star.addEventListener('click', function() {
                stars.forEach(s => s.classList.remove('text-yellow-400'));
                this.classList.add('text-yellow-400');
                // Tambahkan logika jika ingin semua bintang di kiri juga kuning
            });
        });

        function filterReviews(filter, btn) {
            // 1. Hapus style aktif dari SEMUA tombol
            document.querySelectorAll('.filter-btn-review').forEach(b => {
                b.classList.remove('bg-[#24420A]', 'text-white', 'border-[#24420A]');
                b.classList.add('bg-white', 'text-gray-700');
            });

            // 2. Berikan style aktif ke tombol yang diklik
            btn.classList.remove('bg-white', 'text-gray-700');
            btn.classList.add('bg-[#24420A]', 'text-white', 'border-[#24420A]');

            // 3. Filter ulasan
            const reviews = document.querySelectorAll('.review-item');
            reviews.forEach(review => {
                const rating = review.getAttribute('data-rating');
                const hasPhoto = review.getAttribute('data-has-photo');

                if (filter === 'all') {
                    review.style.display = 'block';
                } else if (filter === 'photo') {
                    review.style.display = (hasPhoto === 'true') ? 'block' : 'none';
                } else {
                    review.style.display = (rating === filter.toString()) ? 'block' : 'none';
                }
            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Fungsi untuk membuat animasi section
        const animateSection = (el, direction) => {
            gsap.fromTo(el, 
            { 
                x: direction === 'left' ? -100 : 100, 
                opacity: 0 
            },
            { 
                x: 0, 
                opacity: 1, 
                duration: 0.6,
                scrollTrigger: {
                trigger: el,
                start: "top 95%", // Animasi mulai saat 80% layar
                end: "top 10%",
                toggleActions: "play reverse play reverse" // Penting: reverse saat scroll ke atas
                }
            }
            );
        };

        // Terapkan ke semua section yang punya class 'animate-section'
        document.querySelectorAll('.animate-section').forEach((section, index) => {
            // Selang-seling arah: genap ke kiri, ganjil ke kanan
            animateSection(section, index % 2 === 0 ? 'left' : 'right');
        });
        const isLoggedIn = @json(auth()->check());
    </script>
    <script src="{{ asset('script.js') }}"></script>
</body>
</html>