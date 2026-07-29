@extends('app') {{-- Disesuaikan ke layouts.app sesuai layout utama Anda --}}

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 antialiased">

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
        <div class="relative w-full mb-6 overflow-hidden group">
            <div id="banner-carousel-home" class="flex overflow-x-hidden rounded-lg w-full min-h-[120px] h-auto" style="scroll-behavior: smooth;">
                
                @forelse($banner_home as $banner)
                    <div class="min-w-full snap-center">
                        <a href="{{ $banner->link_tujuan ?? '#' }}" class="block w-full h-full">
                            <img src="{{ asset('storage/' . $banner->image_path) }}" 
                                alt="{{ $banner->judul }}" 
                                class="w-full h-auto min-h-[120px] object-cover">
                        </a>
                    </div>
                @empty
                    <div class="min-w-full snap-center">
                        <img src="{{ asset('images/banner.png') }}" 
                            alt="Banner Default" 
                            class="w-full h-auto min-h-[120px] object-cover">
                    </div>
                @endforelse

            </div>

            @if($banner_home->count() > 1)
                <button onclick="scrollBannerHome(-1)" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 p-2 rounded-full shadow-lg hover:bg-white transition opacity-0 group-hover:opacity-100">
                    &larr;
                </button>
                <button onclick="scrollBannerHome(1)" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 p-2 rounded-full shadow-lg hover:bg-white transition opacity-0 group-hover:opacity-100">
                    &rarr;
                </button>
            @endif
        </div>

        <!-- REKOMENDASI PRODUK GRID (Ganti md:grid-cols-4, lg:grid-cols-6 agar proporsional) -->
        <div class="mb-10">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xs md:text-sm font-bold text-gray-800 tracking-wider uppercase">Mungkin Kamu Cari</h2>
            </div>
            
            {{-- Bungkus grid produk agar pesan tidak masuk ke dalam grid --}}
            <div id="product-grid-container">
                <div id="product-grid-rekomendasi" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4">
                    @foreach($katalog as $item)
                        <div class="product-item" data-kategori="{{ $item->id_kategori }}">
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition relative">
                                
                                <div class="aspect-square w-full overflow-hidden bg-gray-100 relative">
                                    <img src="{{ !empty($item->gambar) ? asset('storage/' . $item->gambar[0]) : asset('images/default.jpg') }}" 
                                        alt="{{ $item->nama_produk }}" 
                                        class="w-full h-full object-cover">
                                    
                                    @if(isset($item->status_produk) && $item->status_produk == false)
                                        <div class="absolute inset-0 bg-white/60 flex items-center justify-center backdrop-blur-[1px] z-20">
                                            <span class="text-[#FF7017] px-4 py-2 font-bold text-lg rounded-lg">
                                                Habis
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-3">
                                    <h3 class="product-name text-xs md:text-sm font-semibold text-gray-800 line-clamp-2 min-h-[2.5rem]">
                                        {{-- Judul produk juga dibungkus link --}}
                                        <a href="{{ route('produk.detail', $item->id) }}" class="focus:outline-none">
                                            <span class="absolute inset-0 z-10" aria-hidden="true"></span>
                                            {{ $item->nama_produk }}
                                        </a>
                                    </h3>

                                    <div class="mt-2 flex justify-center relative z-20">
                                        <a href="{{ route('produk.detail', $item->id) }}" class="text-[#F2B705] text-xs font-bold hover:underline">
                                            Lihat Produk 
                                        </a>
                                    </div>

                                    <div class="flex items-center justify-between mt-3 text-[10px] md:text-xs text-gray-500">
                                        <div class="flex items-center bg-orange-100 px-1.5 py-0.5 rounded text-orange-600 font-bold">
                                            ★ {{ number_format($item->averageRating, 1) }}
                                        </div>
                                        <span>{{ $item->sold ?? 0 }} terjual</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pesan ditempatkan di luar grid agar tidak merusak layout --}}
                <div id="no-product-message" class="hidden flex flex-col items-center justify-center py-20 text-center px-4">
                    <div class="bg-gray-100 p-6 rounded-full mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Oops! Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500 max-w-sm">Maaf, saat ini belum ada produk yang tersedia untuk kategori yang Anda pilih.</p>
                    <button onclick="filterProduk(null, this, '.product-item')" class="mt-6 px-6 py-2 bg-[#24420A] text-white rounded-full hover:bg-[#3a5a1f] transition">
                        Lihat Semua Produk
                    </button>
                </div>
            </div>
        </div>

        <div class="relative w-full mb-10 overflow-hidden group">
            <div id="banner-carousel" class="flex gap-x-4 overflow-x-auto rounded-lg aspect-[21/9] sm:aspect-[3/1] md:aspect-[4/1]">
                
                @forelse($banners as $banner)
                    <div class="w-full flex-shrink-0">
                        <a href="{{ $banner->link_tujuan ?? '#' }}" class="block w-full h-full">
                            <img src="{{ asset('storage/' . $banner->image_path) }}" 
                                alt="{{ $banner->judul }}" 
                                class="w-full h-full object-cover rounded-lg">
                        </a>
                    </div>
                @empty
                <div class="min-w-full snap-center">
                    <img src="{{ asset('images/Banner_baru.png') }}" alt="Default" class="w-full h-full object-cover">
                </div>
                @endforelse

                @foreach($banners as $banner)
                    <div class="w-full flex-shrink-0">
                        <a href="{{ $banner->link_tujuan ?? '#' }}" class="block w-full h-full">
                            <img src="{{ asset('storage/' . $banner->image_path) }}" 
                                alt="{{ $banner->judul }}" 
                                class="w-full h-full object-cover rounded-lg">
                        </a>
                    </div>
                @endforeach
            </div>
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
                        <li>
                            <button onclick="filterProduk(null, this, '.product-item-all')" class="w-full text-left text-[#24420A] font-bold flex items-center hover:underline">
                                <span class="mr-1">></span> Semua Produk
                            </button>
                        </li>
                        
                        @foreach($kategoriList as $kategori)
                            <li>
                                <button onclick="filterProduk({{ $kategori->id }}, this, '.product-item-all')" 
                                        class="w-full text-left pl-3 hover:text-[#24420A] cursor-pointer font-semibold transition hover:underline">
                                    {{ $kategori->nama_kategori }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            <!-- BLOCK KATALOG: Daftar Semua Produk -->
            <div class="w-full lg:w-4/5">
                
                <!-- Bilah Penyortiran / Urutkan Bar (Flex Wrap Otomatis jika di HP) -->
                <div class="bg-[#EAEFD6] p-2 rounded flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-6 text-xs">
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                        <span class="text-gray-600 font-medium pl-1 sm:pl-2 w-full sm:w-auto mb-1 sm:mb-0">Urutkan</span>
                        
                        <button onclick="sortProducts('populer', this)" class="sort-btn px-3 py-1.5 rounded font-semibold bg-[#24420A] text-white">Populer</button>
                        <button onclick="sortProducts('terbaru', this)" class="sort-btn px-3 py-1.5 rounded font-semibold bg-gray-200 text-gray-700">Terbaru</button>
                        <button onclick="sortProducts('terlaris', this)" class="sort-btn px-3 py-1.5 rounded font-semibold bg-gray-200 text-gray-700">Terlaris</button>
                    </div>
                    
                    <div id="pagination-links">
                        {{ $katalog->links() }}
                    </div>
                </div>

                <!-- MAIN PRODUCTS GRID -->
                <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4">
                    @foreach($katalog as $item)
                        <div class="product-item" data-kategori="{{ $item->id_kategori }}">
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition relative">
                                
                                <div class="aspect-square w-full overflow-hidden bg-gray-100 relative">
                                    <img src="{{ !empty($item->gambar) ? asset('storage/' . $item->gambar[0]) : asset('images/default.jpg') }}" 
                                        alt="{{ $item->nama_produk }}" 
                                        class="w-full h-full object-cover">
                                    
                                    @if(isset($item->status_produk) && $item->status_produk == false)
                                        <div class="absolute inset-0 bg-white/60 flex items-center justify-center backdrop-blur-[1px] z-20">
                                            <span class="text-[#FF7017] px-4 py-2 font-bold text-lg rounded-lg">
                                                Habis
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-3">
                                    <h3 class="product-name text-xs md:text-sm font-semibold text-gray-800 line-clamp-2 min-h-[2.5rem]">
                                        {{-- Judul produk juga dibungkus link --}}
                                        <a href="{{ route('produk.detail', $item->id) }}" class="focus:outline-none">
                                            <span class="absolute inset-0 z-10" aria-hidden="true"></span>
                                            {{ $item->nama_produk }}
                                        </a>
                                    </h3>

                                    <div class="mt-2 flex justify-center relative z-20">
                                        <a href="{{ route('produk.detail', $item->id) }}" class="text-[#F2B705] text-xs font-bold hover:underline">
                                            Lihat Produk 
                                        </a>
                                    </div>

                                    <div class="flex items-center justify-between mt-3 text-[10px] md:text-xs text-gray-500">
                                        <div class="flex items-center bg-orange-100 px-1.5 py-0.5 rounded text-orange-600 font-bold">
                                            ★ {{ number_format($item->averageRating, 1) }}
                                        </div>
                                        <span>{{ $item->sold ?? 0 }} terjual</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    function sortProducts(type, btn) {
        // 1. Update UI Button
        document.querySelectorAll('.sort-btn').forEach(b => {
            b.classList.remove('bg-[#24420A]', 'text-white');
            b.classList.add('bg-gray-200', 'text-gray-700');
        });
        btn.classList.add('bg-[#24420A]', 'text-white');
        btn.classList.remove('bg-gray-200', 'text-gray-700');

        // 2. Kirim Request ke Controller (Atau sort array lokal)
        // Jika data tidak terlalu banyak, kita bisa sort lokal:
        const grid = document.getElementById('product-grid');
        let items = Array.from(grid.getElementsByClassName('product-item-all'));

        items.sort((a, b) => {
            if (type === 'terbaru') {
                // Asumsi ada atribut data-created_at
                return new Date(b.dataset.created) - new Date(a.dataset.created);
            } else if (type === 'terlaris') {
                return b.dataset.terjual - a.dataset.terjual;
            } else { // Populer (berdasarkan rating)
                return b.dataset.rating - a.dataset.rating;
            }
        });

        // 3. Masukkan kembali ke grid
        grid.innerHTML = '';
        items.forEach(item => grid.appendChild(item));
    }

    const carousel = document.getElementById('banner-carousel');
    let isDown = false;
    let startX;
    let scrollLeft;
    let isAutoScrolling = true;

    // --- LOGIKA DRAG ---
    carousel.addEventListener('mousedown', (e) => {
        isDown = true;
        isAutoScrolling = false; // Hentikan marquee saat ditarik
        startX = e.pageX - carousel.offsetLeft;
        scrollLeft = carousel.scrollLeft;
    });

    carousel.addEventListener('mouseleave', () => { isDown = false; });
    
    carousel.addEventListener('mouseup', () => { 
        isDown = false; 
        isAutoScrolling = true; // Lanjutkan marquee setelah dilepas
    });

    carousel.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - carousel.offsetLeft;
        const walk = (x - startX) * 2; // Kecepatan drag (x2)
        carousel.scrollLeft = scrollLeft - walk;
    });

    // --- LOGIKA MARQUEE ---
    function marquee() {
        if (isAutoScrolling) {
            carousel.scrollLeft += 0.8; // Kecepatan geser otomatis
            if (carousel.scrollLeft >= (carousel.scrollWidth / 2)) {
                carousel.scrollLeft = 0;
            }
        }
        requestAnimationFrame(marquee);
    }

    // Fungsi Navigasi Tombol
    function scrollBanner(direction) {
        const gap = 16; 
        const slideWidth = carousel.clientWidth + gap;
        carousel.scrollBy({ left: direction * slideWidth, behavior: 'smooth' });
    }

    marquee(); 
</script>
@endsection