@extends('app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    {{-- Featured Article --}}
    @if($featured)
        <a
            href="{{ route('articles.detailArtikel', $featured->slug) }}"
            class="relative block w-full h-[280px] sm:h-[350px] lg:h-[400px] rounded-2xl overflow-hidden mb-8 sm:mb-12 shadow-lg group"
        >
            <img
                src="{{ asset('storage/' . $featured->image) }}"
                class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                alt="{{ $featured->title }}"
            >

            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

            <div class="absolute bottom-0 left-0 p-5 sm:p-8 text-white max-w-3xl">
                <span class="bg-[#24420A] text-white text-[10px] px-3 py-1 rounded uppercase font-bold mb-2 sm:mb-3 inline-block">
                    {{ $featured->category->name ?? 'Umum' }}
                </span>

                <h1 class="text-xl sm:text-3xl lg:text-4xl font-bold mb-2 sm:mb-3 leading-snug group-hover:text-gray-200 transition">
                    {{ $featured->title }}
                </h1>

                <div class="text-xs sm:text-sm text-gray-300">
                    {{ $featured->created_at->format('d F Y') }}
                </div>
            </div>
        </a>
    @else
        <div class="w-full h-[280px] sm:h-[350px] lg:h-[400px] rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 flex flex-col items-center justify-center text-center mb-8 sm:mb-12 px-6">
            <div class="w-20 h-20 rounded-full bg-[#24420A]/10 flex items-center justify-center mb-5">
                <i class="fas fa-newspaper text-3xl text-[#24420A]"></i>
            </div>

            <h2 class="text-xl font-bold text-gray-800">
                Belum Ada Artikel Unggulan
            </h2>

            <p class="mt-2 max-w-lg text-sm text-gray-500 leading-relaxed">
                Artikel unggulan akan ditampilkan di sini setelah tersedia.
                Silakan kunjungi kembali nanti untuk membaca konten terbaru.
            </p>
        </div>
    @endif

    {{-- Banner Carousel Atas --}}
    <div class="relative w-full mb-8 sm:mb-12 overflow-hidden">
        <div
            id="banner-carousel-artikel"
            class="flex overflow-x-hidden snap-x snap-mandatory rounded-xl w-full bg-gray-100"
        >
            @forelse($banner_artikel as $banner)
                <div class="min-w-full snap-center flex justify-center items-center">
                    <a
                        href="{{ $banner->link_tujuan ?? '#' }}"
                        class="block w-full h-full"
                    >
                        <img
                            src="{{ asset('storage/' . $banner->image_path) }}"
                            alt="{{ $banner->judul }}"
                            class="w-full h-auto max-h-80 object-contain rounded-xl mx-auto"
                        >
                    </a>
                </div>
            @empty
                <div class="min-w-full snap-center flex justify-center items-center">
                    <img
                        src="{{ asset('images/Banner_baru.png') }}"
                        alt="Banner Default"
                        class="w-full h-auto max-h-80 object-contain rounded-xl mx-auto"
                    >
                </div>
            @endforelse
        </div>

        @if($banner_artikel->count() > 1)
            <button
                type="button"
                onclick="scrollBannerArtikel(-1)"
                class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 p-2 rounded-full shadow-lg"
                aria-label="Banner sebelumnya"
            >
                &larr;
            </button>

            <button
                type="button"
                onclick="scrollBannerArtikel(1)"
                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 p-2 rounded-full shadow-lg"
                aria-label="Banner berikutnya"
            >
                &rarr;
            </button>
        @endif
    </div>

    {{-- Konten dan Sidebar --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10">

        {{-- Daftar Artikel --}}
        <div
            id="article-results"
            class="lg:col-span-2 space-y-6 sm:space-y-8"
            aria-live="polite"
        >
            @include('articles.partials.article-list', [
                'articles' => $articles
            ])
        </div>

        {{-- Sidebar --}}
        <div class="space-y-8 lg:space-y-10">

            {{-- Kategori --}}
            <div>
                <h3 class="font-bold text-base sm:text-lg border-b-2 border-gray-800 mb-4 sm:mb-5 pb-1 text-[#24420A]">
                    Kategori
                </h3>

                <div id="article-categories" class="space-y-2">
                    <a
                        href="{{ url()->current() }}"
                        data-category=""
                        class="category-filter flex justify-between px-4 py-2.5 rounded-lg shadow-sm text-xs font-bold items-center transition
                            {{ empty($selectedCategory)
                                ? 'bg-[#F2B705] text-[#24420A]'
                                : 'bg-[#24420A] text-white hover:bg-[#355e0e]' }}"
                    >
                        <span>Semua Kategori</span>
                    </a>

                    @foreach($categories as $cat)
                        <a
                            href="{{ url()->current() }}?category={{ $cat->id }}"
                            data-category="{{ $cat->id }}"
                            class="category-filter flex justify-between px-4 py-2.5 rounded-lg shadow-sm text-xs font-bold items-center transition
                                {{ (string) $selectedCategory === (string) $cat->id
                                    ? 'bg-[#F2B705] text-[#24420A]'
                                    : 'bg-[#24420A] text-white hover:bg-[#355e0e]' }}"
                        >
                            <span>{{ $cat->name }}</span>

                            <span
                                class="category-count px-2 py-0.5 rounded
                                    {{ (string) $selectedCategory === (string) $cat->id
                                        ? 'bg-white/40 text-[#24420A]'
                                        : 'text-[#F2B705] bg-black/20' }}"
                            >
                                {{ $cat->articles_count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Media Sosial --}}
            <div>
                <h3 class="font-bold text-base sm:text-lg border-b-2 border-gray-800 mb-4 sm:mb-5 pb-1 text-[#24420A]">
                    Kunjungi Kami!
                </h3>

                <div class="flex gap-4 items-center">
                    <a
                        href="https://wa.me/6289612821257"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 hover:bg-green-500 hover:text-white transition shadow-sm"
                        aria-label="WhatsApp"
                    >
                        <i class="fab fa-whatsapp text-lg"></i>
                    </a>

                    <a
                        href="https://www.facebook.com/share/1Bcndmmckw/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm"
                        aria-label="Facebook"
                    >
                        <i class="fab fa-facebook text-lg"></i>
                    </a>
                </div>
            </div>

            {{-- Artikel Terpopuler --}}
            <div class="space-y-4">
                <h3 class="font-bold text-base sm:text-lg border-b-2 border-gray-800 mb-4 sm:mb-5 pb-1 text-[#24420A]">
                    Terpopuler
                </h3>

                @forelse($popular as $pop)
                    <a
                        href="{{ route('articles.detailArtikel', $pop->slug) }}"
                        class="block group bg-gray-50 p-3 rounded-xl border border-gray-100"
                    >
                        <div class="flex flex-col gap-2">
                            <div class="relative w-full h-36 sm:h-32 overflow-hidden rounded-lg bg-gray-200">
                                <img
                                    src="{{ asset('storage/' . $pop->image) }}"
                                    class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                                    alt="{{ $pop->title }}"
                                >

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
                    </a>
                @empty
                    <p class="text-sm text-gray-500">
                        Belum ada artikel populer.
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Banner Carousel Bawah --}}
    <div class="relative w-full mt-12 sm:mt-16 mb-6 overflow-hidden group">
        <div
            id="banner-carousel"
            class="flex gap-x-4 overflow-x-auto rounded-xl aspect-[21/9] sm:aspect-[3/1] md:aspect-[4/1] scrollbar-none"
        >
            @forelse($banners as $banner)
                <div class="w-full flex-shrink-0">
                    <a
                        href="{{ $banner->link_tujuan ?? '#' }}"
                        class="block w-full h-full"
                    >
                        <img
                            src="{{ asset('storage/' . $banner->image_path) }}"
                            alt="{{ $banner->judul }}"
                            class="w-full h-full object-cover rounded-xl shadow-sm"
                        >
                    </a>
                </div>
            @empty
                <div class="min-w-full snap-center">
                    <img
                        src="{{ asset('images/Banner_baru.png') }}"
                        alt="Banner Default"
                        class="w-full h-full object-cover rounded-xl shadow-sm"
                    >
                </div>
            @endforelse

            {{-- Duplikasi banner agar marquee terlihat menyambung --}}
            @foreach($banners as $banner)
                <div class="w-full flex-shrink-0">
                    <a
                        href="{{ $banner->link_tujuan ?? '#' }}"
                        class="block w-full h-full"
                    >
                        <img
                            src="{{ asset('storage/' . $banner->image_path) }}"
                            alt="{{ $banner->judul }}"
                            class="w-full h-full object-cover rounded-xl shadow-sm"
                        >
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    /*
     |--------------------------------------------------------------------------
     | Filter artikel tanpa reload
     |--------------------------------------------------------------------------
     */

    const articleResults = document.getElementById('article-results');
    const categoryContainer = document.getElementById('article-categories');

    let articleRequestController = null;

    function setActiveCategory(categoryId) {
        const selectedId = String(categoryId ?? '');

        document.querySelectorAll('.category-filter').forEach(function (item) {
            const isActive = item.dataset.category === selectedId;
            const countElement = item.querySelector('.category-count');

            item.classList.toggle('bg-[#F2B705]', isActive);
            item.classList.toggle('text-[#24420A]', isActive);

            item.classList.toggle('bg-[#24420A]', !isActive);
            item.classList.toggle('text-white', !isActive);
            item.classList.toggle('hover:bg-[#355e0e]', !isActive);

            if (countElement) {
                countElement.classList.toggle('bg-white/40', isActive);
                countElement.classList.toggle('text-[#24420A]', isActive);

                countElement.classList.toggle('bg-black/20', !isActive);
                countElement.classList.toggle('text-[#F2B705]', !isActive);
            }
        });
    }

    async function loadArticles(url, updateHistory = true) {
        if (!articleResults) {
            return;
        }

        // Batalkan request lama apabila pengguna cepat mengganti kategori.
        if (articleRequestController) {
            articleRequestController.abort();
        }

        articleRequestController = new AbortController();

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                },
                signal: articleRequestController.signal
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil artikel.');
            }

            const html = await response.text();
            const requestedUrl = new URL(url, window.location.origin);
            const categoryId = requestedUrl.searchParams.get('category') ?? '';

            articleResults.innerHTML = html;
            setActiveCategory(categoryId);

            if (updateHistory) {
                window.history.pushState(
                    {
                        articleFilter: true,
                        category: categoryId
                    },
                    '',
                    requestedUrl.toString()
                );
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Gagal memuat artikel:', error);
            }
        } finally {
            articleRequestController = null;
        }
    }

    if (categoryContainer) {
        categoryContainer.addEventListener('click', function (event) {
            const categoryLink = event.target.closest('.category-filter');

            if (!categoryLink) {
                return;
            }

            event.preventDefault();

            // Warna tombol langsung berubah ketika diklik.
            setActiveCategory(categoryLink.dataset.category);

            loadArticles(categoryLink.href);
        });
    }

    if (articleResults) {
        articleResults.addEventListener('click', function (event) {
            const paginationLink = event.target.closest('.article-pagination a');

            if (!paginationLink) {
                return;
            }

            event.preventDefault();
            loadArticles(paginationLink.href);
        });
    }

    window.addEventListener('popstate', function () {
        const currentUrl = new URL(window.location.href);
        const categoryId = currentUrl.searchParams.get('category') ?? '';

        setActiveCategory(categoryId);
        loadArticles(currentUrl.toString(), false);
    });

    /*
     |--------------------------------------------------------------------------
     | Carousel banner atas
     |--------------------------------------------------------------------------
     */

    window.scrollBannerArtikel = function (direction) {
        const carouselArtikel = document.getElementById(
            'banner-carousel-artikel'
        );

        if (!carouselArtikel) {
            return;
        }

        carouselArtikel.scrollBy({
            left: direction * carouselArtikel.clientWidth,
            behavior: 'smooth'
        });
    };

    /*
     |--------------------------------------------------------------------------
     | Carousel banner bawah
     |--------------------------------------------------------------------------
     */

    const carousel = document.getElementById('banner-carousel');

    if (!carousel) {
        return;
    }

    let isDown = false;
    let startX = 0;
    let initialScrollLeft = 0;
    let isAutoScrolling = true;

    carousel.addEventListener('mousedown', function (event) {
        isDown = true;
        isAutoScrolling = false;

        startX = event.pageX - carousel.offsetLeft;
        initialScrollLeft = carousel.scrollLeft;
    });

    carousel.addEventListener('mouseleave', function () {
        isDown = false;
        isAutoScrolling = true;
    });

    carousel.addEventListener('mouseup', function () {
        isDown = false;
        isAutoScrolling = true;
    });

    carousel.addEventListener('mousemove', function (event) {
        if (!isDown) {
            return;
        }

        event.preventDefault();

        const currentX = event.pageX - carousel.offsetLeft;
        const movement = (currentX - startX) * 2;

        carousel.scrollLeft = initialScrollLeft - movement;
    });

    // Dukungan drag pada layar sentuh.
    carousel.addEventListener(
        'touchstart',
        function () {
            isAutoScrolling = false;
        },
        { passive: true }
    );

    carousel.addEventListener(
        'touchend',
        function () {
            isAutoScrolling = true;
        },
        { passive: true }
    );

    function marquee() {
        if (isAutoScrolling && carousel.scrollWidth > carousel.clientWidth) {
            carousel.scrollLeft += 0.8;

            if (carousel.scrollLeft >= carousel.scrollWidth / 2) {
                carousel.scrollLeft = 0;
            }
        }

        window.requestAnimationFrame(marquee);
    }

    marquee();
});
</script>
@endsection