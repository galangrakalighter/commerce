@extends('app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    
    @if($featured)
        <a href="{{ route('articles.detailArtikel', $featured->slug) }}" class="relative block w-full h-[400px] rounded-2xl overflow-hidden mb-12 shadow-lg group">
            <img src="{{ asset('storage/' . $featured->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $featured->title }}">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-8 text-white max-w-3xl">
                <span class="bg-[#24420A] text-white text-[10px] px-3 py-1 rounded uppercase font-bold mb-3 inline-block">
                    {{ $featured->category->name ?? 'Umum' }}
                </span>
                <h1 class="text-4xl font-bold mb-3 group-hover:text-gray-200 transition">{{ $featured->title }}</h1>
                <div class="text-sm text-gray-300">
                    <span>{{ $featured->created_at->format('d F Y') }}</span>
                </div>
            </div>
        </a>
    @endif

    <div class="relative w-full mb-12 overflow-hidden group">
    <div id="banner-carousel-artikel" class="flex overflow-x-hidden snap-x snap-mandatory rounded-xl w-full h-32 md:h-40" style="scroll-behavior: smooth;">
            
            @forelse($banner_artikel as $banner)
                <div class="min-w-full snap-center">
                    <a href="{{ $banner->link_tujuan ?? '#' }}" class="block w-full h-full">
                        <img src="{{ asset('storage/' . $banner->image_path) }}" 
                            alt="{{ $banner->judul }}" 
                            class="w-full h-full object-cover">
                    </a>
                </div>
            @empty
                <div class="min-w-full snap-center">
                    <img src="{{ asset('images/Banner_baru.png') }}" 
                        alt="Banner Default" 
                        class="w-full h-full object-cover">
                </div>
            @endforelse

        </div>

        @if($banner_artikel->count() > 1)
            <button onclick="scrollBannerArtikel(-1)" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 p-2 rounded-full shadow-lg hover:bg-white transition opacity-0 group-hover:opacity-100">
                &larr;
            </button>
            <button onclick="scrollBannerArtikel(1)" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 p-2 rounded-full shadow-lg hover:bg-white transition opacity-0 group-hover:opacity-100">
                &rarr;
            </button>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <div class="lg:col-span-2 space-y-8">
            @foreach($articles as $article)
                {{-- Kartu Utama sebagai konteks posisi relative --}}
                <div class="relative flex gap-5 border-b pb-6 hover:bg-gray-50 transition p-2 rounded-lg group">
                    
                    <!-- 1. Link Absolute membentang ke seluruh kartu -->
                    <a href="{{ route('articles.detailArtikel', $article->slug) }}" class="absolute inset-0 z-10" aria-label="Baca artikel {{ $article->title }}"></a>

                    <!-- Bagian Gambar & Kategori -->
                    <div class="w-1/3 h-32 flex-shrink-0 relative">
                        <img src="{{ asset('storage/' . $article->image) }}" class="w-full h-full object-cover rounded-lg shadow-sm" alt="{{ $article->title }}">
                        
                        <!-- z-20 agar badge kategori berada di atas link absolute dan bisa diklik secara independen jika diperlukan -->
                        <span class="absolute top-2 left-2 bg-[#24420A] text-white text-[9px] px-2 py-1 rounded uppercase font-bold shadow-md z-20">
                            {{ $article->category->name ?? 'Umum' }}
                        </span>
                    </div>

                    <!-- Bagian Teks & Konten -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg leading-tight mt-1 group-hover:text-[#24420A] transition">
                                {{ $article->title }}
                            </h3>
                            <p class="text-[11px] text-gray-400 mt-1">{{ $article->created_at->format('F d, Y') }}</p>
                            <p class="text-xs text-gray-600 mt-2 line-clamp-2">{{ $article->excerpt }}</p>
                        </div>
                        
                        <div>
                            <!-- Tombol Read More (Cukup berupa elemen visual/span karena kartu sudah bisa diklik) -->
                            <span class="inline-block bg-[#24420A] text-white text-[10px] px-4 py-1.5 mt-3 w-max rounded shadow-sm group-hover:bg-[#355e0e] transition relative z-20 pointer-events-none">
                                Read More
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="space-y-10">
            <div>
                <h3 class="font-bold text-lg border-b-2 border-gray-800 mb-5 pb-1">Kategori</h3>
                @foreach($categories as $cat)
                <div class="flex justify-between bg-[#24420A] text-white px-4 py-2 mb-2 rounded shadow-sm text-xs font-bold items-center hover:bg-[#355e0e] transition">
                    <span>{{ $cat->name }}</span>
                    <span class="text-[#F2B705]">{{ $cat->articles_count }}</span>
                </div>
                @endforeach
            </div>

            <div>
                <h3 class="font-bold text-lg border-b-2 border-gray-800 mb-5 pb-1">Kunjungi Kami!</h3>
                <div class="flex gap-4 items-center">
                    <a href="https://wa.me/?text={{ urlencode(url()->current()) }}"
                        target="_blank"
                        class="text-green-500 hover:text-green-700 transition">
                        <i class="fab fa-whatsapp text-lg"></i>
                    </a>

                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                        target="_blank"
                        class="text-blue-600 hover:text-blue-800 transition">
                        <i class="fab fa-facebook text-lg"></i>
                    </a>

                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}"
                        target="_blank"
                        class="text-black hover:text-gray-700 transition">
                        <i class="fab fa-x-twitter text-lg"></i>
                    </a>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="font-bold text-lg border-b-2 border-gray-800 mb-5 pb-1">Terpopuler</h3>
                
                @foreach($popular as $pop)
                <div class="flex flex-col gap-2 group">
                    <div class="relative w-full h-40 overflow-hidden rounded-lg">
                        <img src="{{ asset('storage/' . $pop->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $pop->title }}">
                        
                        <span class="absolute top-2 left-2 bg-[#24420A] text-white text-[10px] px-2 py-1 rounded font-bold uppercase">
                            {{ $pop->category->name ?? 'Umum' }}
                        </span>
                    </div>

                    <h4 class="font-bold text-sm text-gray-800 leading-tight group-hover:text-[#24420A] transition">
                        {{ $pop->title }}
                    </h4>

                    <p class="text-[10px] text-gray-400 font-medium">
                        {{ $pop->created_at->format('F d, Y') }}
                    </p>
                </div>
                @endforeach
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
</div>

<script>
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