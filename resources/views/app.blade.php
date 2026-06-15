<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAFI - Golden Aroma Food Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 antialiased">

    <nav class="bg-[#24420A] text-white px-4 md:px-6 py-4 shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-2 focus:outline-none shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Logo GAFI" class="h-10 md:h-14 w-auto object-contain">
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
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#24420A] transition">Kelola Voucher</a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#24420A] transition">Kelola Artikel</a>
                                </div>
                            </li>
                            <li><a href="{{ route('toko') }}" class="{{ request()->routeIs('toko') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Toko</a></li>
                            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Beranda</a></li>
                            <li><a href="#" class="text-white hover:text-[#E0A226] transition">Artikel</a></li>
                            <li><a href="#" class="text-white hover:text-[#E0A226] transition">Promo</a></li>
                            <li><a href="#" class="text-white hover:text-[#E0A226] transition">Kontak</a></li>
                        @else
                            <li><a href="{{ route('toko') }}" class="{{ request()->routeIs('toko') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Toko</a></li>
                            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Beranda</a></li>
                            <li><a href="#" class="text-white hover:text-[#E0A226] transition">Artikel</a></li>
                            <li><a href="#" class="text-white hover:text-[#E0A226] transition">Promo</a></li>
                            <li><a href="#" class="text-white hover:text-[#E0A226] transition">Kontak</a></li>
                        @endif
                    @endauth

                    @guest
                        <li><a href="{{ route('toko') }}" class="{{ request()->routeIs('toko') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Toko</a></li>
                        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#E0A226]' : 'text-white hover:text-[#E0A226]' }} transition">Beranda</a></li>
                        <li><a href="#" class="text-white hover:text-[#E0A226] transition">Artikel</a></li>
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
                                <a href="#" class="block py-2 px-2 text-sm text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Promo</a>
                                <a href="#" class="block py-2 px-2 text-sm text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Voucher</a>
                                <a href="#" class="block py-2 px-2 text-sm text-gray-200 hover:text-white hover:bg-green-900/50 rounded transition">Kelola Artikel</a>
                            </div>
                        </li>
                        <li><a href="{{ route('toko') }}" class="block py-2 px-2 rounded {{ request()->routeIs('toko') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Toko</a></li>
                        <li><a href="{{ route('home') }}" class="block py-2 px-2 rounded {{ request()->routeIs('home') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Beranda</a></li>
                        <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Artikel</a></li>
                        <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Promo</a></li>
                        <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Kontak</a></li>
                    @else
                        <li><a href="{{ route('toko') }}" class="block py-2 px-2 rounded {{ request()->routeIs('toko') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Toko</a></li>
                        <li><a href="{{ route('home') }}" class="block py-2 px-2 rounded {{ request()->routeIs('home') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Beranda</a></li>
                        <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Artikel</a></li>
                        <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Promo</a></li>
                        <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Kontak</a></li>
                    @endif
                @endauth

                @guest
                    <li><a href="{{ route('toko') }}" class="block py-2 px-2 rounded {{ request()->routeIs('toko') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Toko</a></li>
                    <li><a href="{{ route('home') }}" class="block py-2 px-2 rounded {{ request()->routeIs('home') ? 'bg-green-900 text-[#E0A226]' : 'text-white hover:bg-green-900' }} transition">Beranda</a></li>
                    <li><a href="#" class="block py-2 px-2 rounded text-white hover:bg-green-900 transition">Artikel</a></li>
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
        });
    </script>
    <script src="{{ asset('script.js') }}"></script>
</body>
</html>