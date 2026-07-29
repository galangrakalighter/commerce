@extends('app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <!-- Bagian Featured Article -->
    @if($featured)
        <a href="{{ route('articles.detailArtikel', $featured->slug) }}" class="relative block w-full h-[280px] sm:h-[350px] lg:h-[400px] rounded-2xl overflow-hidden mb-8 sm:mb-12 shadow-lg group">
            <img src="{{ asset('storage/' . $featured->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $featured->title }}">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-5 sm:p-8 text-white max-w-3xl">
                <span class="bg-[#24420A] text-white text-[10px] px-3 py-1 rounded uppercase font-bold mb-2 sm:mb-3 inline-block">
                    {{ $featured->category->name ?? 'Umum' }}
                </span>
                <h1 class="text-xl sm:text-3xl lg:text-4xl font-bold mb-2 sm:mb-3 group-hover:text-gray-200 transition leading-snug">{{ $featured->title }}</h1>
                <div class="text-xs sm:text-sm text-gray-300">
                    <span>{{ $featured->created_at->format('d F Y') }}</span>
                </div>
            </div>
        </a>
    @endif

    <!-- Banner Carousel Atas -->
    <div class="relative w-full mb-8 sm:mb-12 overflow-hidden group">
        <div id="banner-carousel-artikel" class="flex overflow-x-hidden snap-x snap-mandatory rounded-xl w-full h-28 sm:h-36 md:h-40" style="scroll-behavior: smooth;">
            @forelse($banner_artikel as $banner)
                <div class="min-w-full snap-center">
                    <a href="{{ $banner->link_tujuan ?? '#' }}" class="block w-full h-full">
                        <img src="{{ asset('storage/' . $banner->image_path) }}" 
                            alt="{{ $banner->judul }}" 
                            class="w-full h-full object-cover rounded-xl">
                    </a>
                </div>
            @empty
                <div class="min-w-full snap-center">
                    <img src="{{ asset('images/Banner_baru.png') }}" 
                        alt="Banner Default" 
                        class="w-full h-full object-cover rounded-xl">
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

    <!-- Layout Utama: Konten & Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10">
        
        <!-- Kolom Kiri: Daftar Artikel -->
        <div class="lg:col-span-2 space-y-6 sm:space-y-8">
            @foreach($articles as $article)
                <div class="relative flex flex-col sm:flex-row gap-4 sm:gap-5 border-b border-gray-100 pb-6 hover:bg-gray-50/80 transition p-3 rounded-xl group">
                    
                    <!-- Link Absolute -->
                    <a href="{{ route('articles.detailArtikel', $article->slug) }}" class="absolute inset-0 z-10" aria-label="Baca artikel {{ $article->title }}"></a>

                    <!-- Bagian Gambar & Kategori -->
                    <div class="w-full sm:w-1/3 h-48 sm:h-32 flex-shrink-0 relative rounded-lg overflow-hidden bg-gray-100">
                        <img src="{{ asset('storage/' . $article->image) }}" class="w-full h-full object-cover shadow-sm group-hover:scale-105 transition duration-300" alt="{{ $article->title }}">
                        
                        <span class="absolute top-2 left-2 bg-[#24420A] text-white text-[9px] px-2 py-1 rounded uppercase font-bold shadow-md z-20">
                            {{ $article->category->name ?? 'Umum' }}
                        </span>
                    </div>

                    <!-- Bagian Teks & Konten -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800 text-base sm:text-lg leading-snug mt-1 group-hover:text-[#24420A] transition line-clamp-2">
                                {{ $article->title }}
                            </h3>
                            <p class="text-[10px] sm:text-xs text-gray-400 mt-1">{{ $article->created_at->format('F d, Y') }}</p>
                            <p class="text-xs text-gray-600 mt-2 line-clamp-2 leading-relaxed">{{ $article->excerpt }}</p>
                        </div>
                        
                        <div>
                            <span class="inline-block bg-[#24420A] text-white text-[10px] px-4 py-1.5 mt-3 w-max rounded shadow-sm group-hover:bg-[#355e0e] transition relative z-20 pointer-events-none font-semibold">
                                Read More
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Kolom Kanan: Sidebar -->
        <div class="space-y-8 lg:space-y-10">
            <!-- Kategori -->
            <div>
                <h3 class="font-bold text-base sm:text-lg border-b-2 border-gray-800 mb-4 sm:mb-5 pb-1 text-[#24420A]">Kategori</h3>
                <div class="space-y-2">
                    @foreach($categories as $cat)
                    <div class="flex justify-between bg-[#24420A] text-white px-4 py-2.5 rounded-lg shadow-sm text-xs font-bold items-center hover:bg-[#355e0e] transition">
                        <span>{{ $cat->name }}</span>
                        <span class="text-[#F2B705] bg-black/20 px-2 py-0.5 rounded">{{ $cat->articles_count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Kunjungi Kami / Media Sosial -->
            <div>
                <h3 class="font-bold text-base sm:text-lg border-b-2 border-gray-800 mb-4 sm:mb-5 pb-1 text-[#24420A]">Kunjungi Kami!</h3>
                <div class="flex gap-4 items-center">
                    <a href="https://wa.me/?text={{ urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 hover:bg-green-500 hover:text-white transition shadow-sm" aria-label="WhatsApp">
                        <i class="fab fa-whatsapp text-lg"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm" aria-label="Facebook">
                        <i class="fab fa-facebook text-lg"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-800 hover:bg-black hover:text-white transition shadow-sm" aria-label="X Twitter">
                        <i class="fab fa-x-twitter text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Terpopuler -->
            <div class="space-y-4">
                <h3 class="font-bold text-base sm:text-lg border-b-2 border-gray-800 mb-4 sm:mb-5 pb-1 text-[#24420A]">Terpopuler</h3>
                
                @foreach($popular as $pop)
                <div class="flex flex-col gap-2 group bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <div class="relative w-full h-36 sm:h-32 overflow-hidden rounded-lg bg-gray-200">
                        <img src="{{ asset('storage/' . $pop->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $pop->title }}">
                        
                        <span class="absolute top-2 left-2 bg-[#24420A] text-white text-[9px] px-2 py-1 rounded font-bold uppercase shadow">
                            {{ $pop->category->name ?? 'Umum' }}
                        </span>
                    </div>

                    <h4 class="font-bold text-xs sm:text-sm text-gray-800 leading-snug group-hover:text-[#24420A] transition line-clamp-2 mt-1">
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
    
    <!-- Banner Carousel Bawah -->
    <div class="relative w-full mt-12 sm:mt-16 mb-6 overflow-hidden group">
        <div id="banner-carousel" class="flex gap-x-4 overflow-x-auto rounded-xl aspect-[21/9] sm:aspect-[3/1] md:aspect-[4/1] scrollbar-none">
            @forelse($banners as $banner)
                <div class="w-full flex-shrink-0">
                    <a href="{{ $banner->link_tujuan ?? '#' }}" class="block w-full h-full">
                        <img src="{{ asset('storage/' . $banner->image_path) }}" 
                            alt="{{ $banner->judul }}" 
                            class="w-full h-full object-cover rounded-xl shadow-sm">
                    </a>
                </div>
            @empty
                <div class="min-w-full snap-center">
                    <img src="{{ asset('images/Banner_baru.png') }}" alt="Default" class="w-full h-full object-cover rounded-xl shadow-sm">
                </div>
            @endforelse

            @foreach($banners as $banner)
                <div class="w-full flex-shrink-0">
                    <a href="{{ $banner->link_tujuan ?? '#' }}" class="block w-full h-full">
                        <img src="{{ asset('storage/' . $banner->image_path) }}" 
                            alt="{{ $banner->judul }}" 
                            class="w-full h-full object-cover rounded-xl shadow-sm">
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