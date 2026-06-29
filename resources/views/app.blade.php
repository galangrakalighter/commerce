<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAFI - Golden Aroma Food Indonesia</title>
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
    </style>
</head>
<body class="bg-gray-50 antialiased">

    <nav class="bg-[#1C3508] text-white px-4 md:px-6 py-4 shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-2 focus:outline-none shrink-0">
                <img src="{{ asset('images/Logo_gafi.png') }}" alt="Logo GAFI" class="h-10 md:h-14 w-auto object-contain">
            </a>
            
            <div class="hidden md:flex items-center space-x-8 font-medium">
                <ul class="flex items-center space-x-8 text-[15px] tracking-wide">
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
                                    <a href="{{ route('promo.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#24420A] transition">Kelola Promo</a>
                                    <a href="{{ route('voucher.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#24420A] transition">Kelola Voucher</a>
                                    <a href="{{ route('banner.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#24420A] transition">Kelola Banner</a>
                                    <a href="{{ route('articles.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#24420A] transition">Kelola Artikel</a>
                                </div>
                            </li>
                            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Toko</a></li>
                            <li><a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Beranda</a></li>
                            <li><a href="{{ route('articles.all') }}" class="text-white hover:text-[#E0A226] transition">Artikel</a></li>
                            <li><a href="#" class="text-white hover:text-[#E0A226] transition">Promo</a></li>
                            <li><a href="#" class="text-white hover:text-[#E0A226] transition">Kontak</a></li>
                        @else
                            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Toko</a></li>
                            <li><a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Beranda</a></li>
                            <li><a href="{{ route('articles.all') }}" class="text-white hover:text-[#E0A226] transition">Artikel</a></li>
                            <li><a href="#" class="text-white hover:text-[#E0A226] transition">Promo</a></li>
                            <li><a href="#" class="text-white hover:text-[#E0A226] transition">Kontak</a></li>
                        @endif
                    @endauth

                    @guest
                        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Toko</a></li>
                        <li><a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Beranda</a></li>
                        <li><a href="{{ route('articles.all') }}" class="text-white hover:text-[#E0A226] transition">Artikel</a></li>
                        <li><a href="#" class="text-white hover:text-[#E0A226] transition">Promo</a></li>
                        <li><a href="#" class="text-white hover:text-[#E0A226] transition">Kontak</a></li>
                    @endguest
                </ul>

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

            <div class="flex items-center md:hidden">
                <button id="mobile-menu-btn" class="text-white hover:text-[#E0A226] focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden border-t border-green-800/60 mt-4 pt-2 space-y-2">
            <ul class="space-y-2 text-sm tracking-wide font-medium">
                @auth
                    @if(Auth::user()->is_admin)
                        <li class="block border-b border-green-800/40 pb-2 mb-2">
                            <button id="admin-mobile-trigger" class="w-full flex items-center justify-between py-2 px-2 rounded font-bold text-[#E0A226] hover:bg-green-900 transition focus:outline-none">
                                <span class="flex items-center space-x-2">
                                    <span>Manajemen CMS</span>
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 transition-transform duration-300" id="admin-mobile-arrow">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <div id="admin-mobile-dropdown" style="max-height: 0px;" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out pl-4 border-l-2 border-[#E0A226]/40 space-y-1 mt-1">
                                <a href="{{ route('kategori.index') }}" class="block py-2 px-2 text-sm text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Kategori</a>
                                <a href="{{ route('produk.index') }}" class="block py-2 px-2 text-sm text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Produk</a>
                                <a href="{{ route('promo.index') }}" class="block py-2 px-2 text-sm text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Promo</a>
                                <a href="{{ route('voucher.index') }}" class="block py-2 px-2 text-sm text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Voucher</a>
                                <a href="{{ route('banner.index') }}" class="block py-2 px-2 text-sm text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Banner</a>
                                <a href="{{ route('articles.index') }}" class="block py-2 px-2 text-sm text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Artikel</a>
                            </div>
                        </li>
                        <li><a href="{{ route('home') }}" class="block py-2 px-2 rounded {{ request()->routeIs('home') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Toko</a></li>
                        <li><a href="{{ route('beranda') }}" class="block py-2 px-2 rounded {{ request()->routeIs('beranda') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Beranda</a></li>
                        <li><a href="{{ route('articles.all') }}" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Artikel</a></li>
                        <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Promo</a></li>
                        <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Kontak</a></li>
                    @else
                        <li><a href="{{ route('home') }}" class="block py-2 px-2 rounded {{ request()->routeIs('home') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Toko</a></li>
                        <li><a href="{{ route('beranda') }}" class="block py-2 px-2 rounded {{ request()->routeIs('beranda') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Beranda</a></li>
                        <li><a href="{{ route('articles.all') }}" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Artikel</a></li>
                        <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Promo</a></li>
                        <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Kontak</a></li>
                    @endif
                @endauth

                @guest
                    <li><a href="{{ route('home') }}" class="block py-2 px-2 rounded {{ request()->routeIs('home') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Toko</a></li>
                    <li><a href="{{ route('beranda') }}" class="block py-2 px-2 rounded {{ request()->routeIs('beranda') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Beranda</a></li>
                    <li><a href="{{ route('articles.all') }}" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Artikel</a></li>
                    <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Promo</a></li>
                    <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Kontak</a></li>
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
    <div class="{{ Route::is('produk.favorit_view') || Route::is('produk.keranjang') ? 'bg-[#E7F7DA]' : 'bg-[#F2B705]' }} py-3 px-4 md:px-6 shadow-sm transition-colors duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">

            @if(Route::is('produk.favorit_view'))
                <h1 class="text-[#24420A] font-bold text-xl md:text-2xl whitespace-nowrap hidden md:flex items-center mr-4">
                    <i class="far fa-heart mr-2 text-xl"></i>Favorit Saya
                </h1>
            @endif

            @if(Route::is('produk.keranjang'))
                <h1 class="text-[#24420A] font-bold text-xl md:text-2xl whitespace-nowrap hidden md:flex items-center mr-4">
                    <i class="fas fa-shopping-cart mr-2 text-xl"></i>Keranjang Saya
                </h1>
            @endif
            
            <form action="#" method="GET" class="flex-grow flex bg-white rounded overflow-hidden shadow-sm">
                <input type="text" placeholder="Cari di toko" class="w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-400 focus:outline-none">
                <div class="border-l border-gray-200"></div>
                <select class="text-xs px-2 text-gray-500 focus:outline-none hidden sm:block bg-transparent">
                    <option>Kategori</option>
                </select>
                <button type="submit" class="bg-[#24420A] text-white px-4 flex items-center justify-center hover:bg-opacity-90 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                    </svg>
                </button>
            </form>

            <div class="flex items-center space-x-5 text-[#24420A] shrink-0">
                <a href="{{ route('produk.favorit_view') }}" class="hover:text-green-900 transition">
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
                <a href="{{ route('produk.keranjang') }}" class="hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75 2.75 0 1 1-1.5 0 .75 2.75 0 0 1 1.5 0Zm12.75 0a.75 2.75 0 1 1-1.5 0 .75 2.75 0 0 1 1.5 0Z" />
                    </svg>
                </a>
                <a href="#" class="relative hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <span class="absolute -top-1.5 -right-1.5 bg-green-900 text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-bold border border-white">2</span>
                </a>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-16">
                <!-- Kolom 1: Profil -->
                <div class="col-span-1">
                    <h3 class="text-[#24420A] font-bold text-lg mb-4 uppercase tracking-wider">Golden Aroma Food Indonesia</h3>
                    <div class="w-16 h-0.5 bg-[#F2B705] mb-6"></div>
                    <p class="text-gray-600 leading-relaxed">
                        Supplier bumbu makanan, bubuk minuman, cabe bubuk, rempah-rempah, essen flavor, dan bahan baku F&B Halal untuk kebutuhan UMKM, distributor, reseller, hingga industri makanan dan minuman di Indonesia.
                    </p>
                </div>

                <!-- Kolom 2 & 3: Navigasi & Layanan -->
                <div class="col-span-2 grid grid-cols-2 md:grid-cols-3 gap-8">
                    <div>
                        <h4 class="text-[#24420A] font-bold mb-6 uppercase text-sm">Navigasi</h4>
                        <ul class="space-y-3 text-gray-600 text-sm">
                            <li><a href="{{ route('beranda') }}" class="hover:text-[#24420A]">Beranda</a></li>
                            <li><a href="{{ route('beranda') }}" class="hover:text-[#24420A]">Tentang Kami</a></li>
                            <li><a href="{{ route('home') }}" class="hover:text-[#24420A]">Produk</a></li>
                            <li><a href="#" class="hover:text-[#24420A]">Custom Bumbu</a></li>
                            <li><a href="{{ route('articles.all') }}" class="hover:text-[#24420A]">Artikel</a></li>
                            <li><a href="#" class="hover:text-[#24420A]">Kontak Kami</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[#24420A] font-bold mb-6 uppercase text-sm">Produk</h4>
                        <ul class="space-y-3 text-gray-600 text-sm">
                            <li><a href="#" class="hover:text-[#24420A]">Bumbu Tabur</a></li>
                            <li><a href="#" class="hover:text-[#24420A]">Bubuk Minuman</a></li>
                            <li><a href="#" class="hover:text-[#24420A]">Cabe Bubuk</a></li>
                            <li><a href="#" class="hover:text-[#24420A]">Bubuk Rempah</a></li>
                            <li><a href="#" class="hover:text-[#24420A]">Essen Flavor</a></li>
                            <li><a href="#" class="hover:text-[#24420A]">Bahan Baku F&B</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[#24420A] font-bold mb-6 uppercase text-sm">Layanan</h4>
                        <ul class="space-y-3 text-gray-600 text-sm">
                            <li><a href="#" class="hover:text-[#24420A]">Custom Pembuatan Bumbu</a></li>
                            <li><a href="#" class="hover:text-[#24420A]">Konsultasi Produk</a></li>
                            <li><a href="#" class="hover:text-[#24420A]">Pemesanan Grosir</a></li>
                            <li><a href="#" class="hover:text-[#24420A]">Kerja Sama Distributor</a></li>
                            <li><a href="#" class="hover:text-[#24420A]">Private Label</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Bagian Kontak -->
            <div class="mb-12">
                <h4 class="text-[#24420A] font-bold mb-4 uppercase text-sm">Kontak</h4>
                <p class="text-gray-600 mb-6 text-sm max-w-lg">Gerbang Kuning Gudang Bumbu, Jalan Ceuri no 51 Kampung Sindang Asih, Sebelah Sawah, Jl. Raya Kopo, Katapang, Pamentasan, Kabupaten Bandung, Jawa Barat 40921</p>
                <div class="flex flex-wrap gap-8 items-center text-sm text-[#24420A] font-medium">
                    <span class="flex items-center gap-2">WhatsApp: 0813-2270-0999</span>
                    <span class="flex items-center gap-2">Email: goldenaromafood@gmail.com</span>
                    <div class="flex gap-4 ml-auto">
                        <!-- Social Icons Placeholder -->
                        <a href="#" class="hover:opacity-75">IG</a>
                        <a href="#" class="hover:opacity-75">IN</a>
                        <a href="#" class="hover:opacity-75">FB</a>
                        <a href="#" class="hover:opacity-75">YT</a>
                    </div>
                </div>
            </div>

            <!-- Jam Operasional -->
            <div class="border-y border-[#F2B705] py-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-center text-[#24420A]">
                <div class="font-bold">JAM OPERASIONAL</div>
                <div><span class="block font-bold">SENIN-JUM'AT</span> 09.00 - 16.00 WIB</div>
                <div><span class="block font-bold">SABTU</span> Closed</div>
                <div><span class="block font-bold">MINGGU</span> 09.00 - 16.00 WIB</div>
            </div>

            <!-- Baris Terakhir -->
            <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="font-bold text-[#24420A]">Butuh supplier bumbu Halal untuk bisnis Anda?</p>
                <a href="#" class="bg-[#24420A] text-white px-8 py-3 rounded-lg font-bold flex items-center gap-2 hover:bg-[#3a5a1f]">
                    Hubungi Kami
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
        const slider = document.getElementById('banner-carousel');
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2; // Kecepatan geser
            slider.scrollLeft = scrollLeft - walk;
        });

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
    <script src="{{ asset('script.js') }}"></script>
</body>
</html>