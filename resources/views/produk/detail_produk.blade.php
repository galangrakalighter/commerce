@extends('app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="text-xs text-gray-500 mb-6">
        Toko > Hobi > Tanaman > Hias > Asli > {{ $produk->nama_produk }}
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 items-start">
        <!-- Kolom Kiri: Gambar Produk & Aksi Pendukung -->
        <div class="space-y-4 w-full">
            @php $images = $produk->gambar; @endphp
            <div class="w-full max-w-full md:max-w-[620px] h-[300px] sm:h-[400px] md:h-[528px] overflow-hidden rounded-2xl border border-gray-100 shadow-sm bg-gray-50 flex items-center justify-center">
                <img id="mainImage" 
                    src="{{ asset('storage/' . $images[0]) }}" 
                    alt="{{ $produk->nama_produk }}" 
                    class="w-full h-full object-contain transition-opacity duration-300">
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-4 text-sm border-t sm:border-t-0 pt-4 sm:pt-0 border-gray-100">
                {{-- Favorit --}}
                <button type="button" 
                    id="btn-favorite-{{ $produk->id }}"
                    onclick="toggleFavorite({{ $produk->id }})"
                    class="need-login flex items-center gap-2 transition-colors {{ $isFavorited ? 'text-red-500' : 'text-gray-600 hover:text-red-500' }}">

                    <span class="font-medium" id="text-favorite-{{ $produk->id }}">
                        {{ $isFavorited ? 'Favorit' : 'Tambahkan Favorit' }}
                    </span>

                    <svg class="w-5 h-5 flex-shrink-0" 
                        fill="{{ $isFavorited ? 'currentColor' : 'none' }}" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                {{-- Share --}}
                <div class="flex items-center gap-3 overflow-x-auto pb-2 sm:pb-0">
                    <span class="font-medium text-gray-700 flex-shrink-0">
                        Share:
                    </span>

                    <a href="https://wa.me/?text={{ urlencode(url()->current()) }}"
                        target="_blank"
                        class="text-green-500 hover:text-green-700 transition flex-shrink-0">
                        <i class="fab fa-whatsapp text-lg"></i>
                    </a>

                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                        target="_blank"
                        class="text-blue-600 hover:text-blue-800 transition flex-shrink-0">
                        <i class="fab fa-facebook text-lg"></i>
                    </a>

                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}"
                        target="_blank"
                        class="text-black hover:text-gray-700 transition flex-shrink-0">
                        <i class="fab fa-x-twitter text-lg"></i>
                    </a>

                    <button type="button"
                        onclick="copyToClipboard()"
                        class="text-red-500 hover:text-red-700 transition flex-shrink-0"
                        title="Salin Link">
                        <i class="fas fa-link text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Detail Produk, Harga, & Tombol Aksi -->
        <div class="flex flex-col w-full">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-2">{{ $produk->nama_produk }}</h1>
            
            <div class="flex items-center gap-4 mb-4 text-sm sm:text-base">
                <span class="text-gray-600">120 Terjual</span>
                <div class="flex items-center text-yellow-400">
                    <span class="text-gray-900 font-bold mr-1">4.9</span>
                    ★★★★★
                </div>
            </div>

            <!-- Thumbnail / Galeri Gambar Kecil -->
            <div class="flex gap-2 mb-6 overflow-x-auto pb-2 scrollbar-thin">
                @foreach($images as $img)
                    <div class="w-16 h-16 rounded-lg border overflow-hidden cursor-pointer hover:border-[#24420A] flex-shrink-0" 
                        onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $img) }}'">
                        <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>

            <!-- Kotak Info Grosir -->
            <div class="bg-gray-50 p-3 sm:p-4 mb-6 rounded-lg border border-gray-100">
                <h3 class="font-bold text-[#24420A] mb-2 text-sm sm:text-base">Hubungi Kami Untuk Harga Terbaik</h3>
                
                <div class="flex flex-col sm:flex-row flex-wrap items-start sm:items-center gap-x-6 gap-y-2 text-xs sm:text-sm text-[#24420A] font-medium">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/Subtract_detail2.png') }}" alt="Grosir" class="w-4 h-4 sm:w-5 sm:h-5 object-contain flex-shrink-0">
                        <span>Harga grosir tersedia</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/Subtract_detail.png') }}" alt="Diskon" class="w-4 h-4 sm:w-5 sm:h-5 object-contain flex-shrink-0">
                        <span>Diskon pembelian jumlah besar</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/Subtract_detail1.png') }}" alt="Konsultasi" class="w-4 h-4 sm:w-5 sm:h-5 object-contain flex-shrink-0">
                        <span>Konsultasi kebutuhan produk gratis</span>
                    </div>
                </div>
            </div>

            <!-- Variasi Produk -->
            <div class="mb-6">
                <span class="text-sm font-medium mb-2 block text-gray-700">Variasi</span>
                <div class="flex flex-wrap gap-2" id="variasiContainer">
                    @foreach(['1KG', '2KG', '3KG', '5KG', 'BUNDLE'] as $v)
                        <button type="button" 
                                onclick="pilihVariasi(this)"
                                class="px-4 sm:px-6 py-2 border rounded-md transition text-sm font-medium hover:border-[#24420A] focus:outline-none">
                            {{ $v }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Kuantiti -->
            <div class="flex items-center gap-4 mb-6">
                <span class="text-sm text-gray-600 font-medium">Kuantiti</span>
                <div class="flex items-center border rounded-md overflow-hidden bg-white">
                    <button type="button" onclick="updateKuantiti(-1)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 transition">-</button>
                    <input type="number" id="kuantitiInput" value="1" min="1" class="w-12 text-center text-sm outline-none border-x py-1">
                    <button type="button" onclick="updateKuantiti(1)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 transition">+</button>
                </div>
            </div>

            <!-- Tombol Aksi (Keranjang & WhatsApp) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mt-auto">
                <button type="button" 
                    id="btn-add-cart-{{ $produk->id }}"
                    onclick="addToCart({{ $produk->id }})"
                    class="need-login flex items-center justify-center gap-2 border border-[#24420A] text-[#24420A] py-3 px-4 sm:px-6 rounded-full font-bold hover:bg-green-50 transition-all text-sm sm:text-base">
                    
                    <img src="{{ asset('images/basket_alt_3.png') }}" alt="Keranjang" class="w-5 h-5 sm:w-6 sm:h-6 object-contain flex-shrink-0">
                    
                    <span id="text-cart-{{ $produk->id }}">
                        {{ $inWishlist ? 'Sudah di Keranjang' : 'Masuk Keranjang' }}
                    </span>
                </button>

                <button type="button" 
                        onclick="checkoutWhatsAppDetail()" 
                        class="flex items-center justify-center gap-2 bg-[#24420A] text-white py-3 px-4 sm:px-6 rounded-full font-bold transition-all hover:bg-[#1a3007] text-sm sm:text-base">
                    <i class="fab fa-whatsapp text-lg"></i>
                    <span>Pesan Sekarang</span>
                </button>
            </div>
        </div>
    </div>
    <div class="space-y-6 mt-6 md:mt-8">
        <!-- Header Spesifikasi -->
        <div class="bg-[#E7F7DA] p-3 rounded-lg shadow-sm">
            <h2 class="text-sm md:text-base font-bold text-[#1F3018] uppercase tracking-wide">Spesifikasi Produk</h2>
        </div>
        
        <!-- List Spesifikasi -->
        <ul class="space-y-3 text-sm text-gray-700 px-2">
            @if(is_array($produk->spec_produk) || is_object($produk->spec_produk))
                @foreach($produk->spec_produk as $key => $value)
                    <li class="flex items-start border-b border-gray-100 pb-2.5">
                        <span class="w-2/5 sm:w-1/3 text-gray-500 font-medium pr-2">{{ $key }}</span>
                        <span class="w-3/5 sm:w-2/3 font-semibold text-gray-900 break-words">: {{ $value }}</span>
                    </li>
                @endforeach
            @endif
            
            <li class="flex items-start pb-2.5">
                <span class="w-2/5 sm:w-1/3 text-gray-500 font-medium pr-2">Kategori</span>
                <span class="w-3/5 sm:w-2/3 font-semibold text-gray-900 break-words">: {{ $produk->kategori->nama ?? 'Bumbu' }}</span>
            </li>
        </ul>

        <!-- Header Detail / Deskripsi -->
        <div class="bg-[#E7F7DA] p-3 rounded-lg shadow-sm mt-8">
            <h2 class="text-sm md:text-base font-bold text-[#1F3018] uppercase tracking-wide">Detail Produk</h2>
        </div>

        <!-- Konten Detail Produk (Mendukung Format Baris Baru / Line-break) -->
        <div class="prose max-w-none text-sm text-gray-700 leading-relaxed px-2 break-words">
            {!! nl2br(e($produk->detail_produk)) !!}
        </div>
    </div>

@php
    $avgRating = $produk->ratings->avg('rating') ?? 0;
    $roundedRating = round($avgRating);
@endphp

<div class="mt-8 bg-[#E7F7DA] p-4 sm:p-6 rounded-2xl border border-green-100 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
    <!-- Bagian Rating Angka & Bintang -->
    <div class="flex items-center gap-4 flex-shrink-0">
        <span class="text-4xl sm:text-5xl font-bold text-gray-800">
            {{ number_format($avgRating, 1) }}
        </span>
        <div class="flex flex-col">
            <span class="text-xs sm:text-sm text-gray-500">/5</span>
            <div class="text-yellow-400 text-lg sm:text-xl tracking-tight">
                {{ str_repeat('★', $roundedRating) }}{{ str_repeat('☆', 5 - $roundedRating) }}
            </div>
            <span class="text-xs text-gray-600 mt-0.5">({{ $produk->ratings->count() }} Ulasan)</span>
        </div>
    </div>

    <!-- Bagian Tombol Filter Review (Bisa digeser horizontal di HP) -->
    <div class="flex overflow-x-auto w-full lg:w-auto gap-2 pb-2 lg:pb-0 scrollbar-none items-center">
        @foreach(['Semua' => 'all', 'Dengan Foto' => 'photo', '5 Bintang' => '5', '4 Bintang' => '4', '3 Bintang' => '3', '2 Bintang' => '2', '1 Bintang' => '1'] as $label => $value)
            <button onclick="filterReviews('{{ $value }}', this)" 
                    class="filter-btn-review px-4 sm:px-5 py-2 rounded-full border border-gray-300 text-xs sm:text-sm transition bg-white shadow-sm hover:border-[#24420A] whitespace-nowrap {{ $value === 'all' ? 'border-[#24420A] bg-green-50/50 font-medium text-[#24420A]' : 'text-gray-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>
</div>

    <div class="bg-white p-4 sm:p-6 rounded-2xl border border-gray-100 mb-8 shadow-sm">
        <h3 class="font-bold text-gray-800 mb-4 text-base sm:text-lg">Berikan Ulasan Anda</h3>
        
        <form id="reviewForm" onsubmit="kirimUlasan(event, this)" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <!-- Pilihan Bintang (Rating) -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                <label class="text-sm font-medium text-gray-700">Rating Kualitas:</label>
                <div class="flex flex-row-reverse justify-end gap-1 cursor-pointer w-fit" id="starRating">
                    @for($i = 5; $i >= 1; $i--)
                        <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" class="hidden peer" required>
                        <label for="star{{ $i }}" class="text-2xl sm:text-3xl text-gray-300 peer-hover:text-yellow-400 peer-checked:text-yellow-400 transition-colors select-none">★</label>
                    @endfor
                </div>
            </div>

            <!-- Input Komentar / Teks Ulasan -->
            <div>
                <textarea name="ulasan" 
                    class="w-full border border-gray-300 rounded-xl p-3 sm:p-4 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#24420A] focus:border-transparent transition" 
                    placeholder="Tuliskan pengalaman, rasa, atau kualitas produk ini..." 
                    rows="4" 
                    required></textarea>
            </div>

            <!-- Input Unggah Foto (Multiple) -->
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Unggah Foto (Opsional, bisa lebih dari 1)</label>
                <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                        <div class="flex flex-col items-center justify-center pt-3 pb-3 px-4 text-center">
                            <svg class="w-6 h-6 mb-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-xs text-gray-500"><span class="font-semibold">Klik untuk pilih foto</span> atau seret file ke sini</p>
                        </div>
                        <input type="file" name="foto[]" multiple accept="image/*" class="hidden">
                    </label>
                </div>
            </div>

            <!-- Tombol Kirim -->
            <div class="pt-2">
                <button type="submit" class="need-login w-full sm:w-auto bg-[#24420A] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#1a3307] transition shadow-sm text-sm sm:text-base flex items-center justify-center gap-2">
                    <span>Kirim Ulasan</span>
                </button>
            </div>
        </form>
    </div>

    <div id="reviewList" class="space-y-6">
        @forelse($produk->ratings as $review)
            @php
                // Menangani foto ulasan baik dalam bentuk array langsung maupun JSON string dari database
                $reviewPhotos = $review->foto;
                if (is_string($reviewPhotos)) {
                    $reviewPhotos = json_decode($reviewPhotos, true);
                }
            @endphp

            <div class="review-item pb-6 border-b border-gray-100 last:border-b-0" 
                data-rating="{{ $review->rating }}" 
                data-has-photo="{{ !empty($reviewPhotos) ? 'true' : 'false' }}">
                
                <div class="flex items-start gap-3 sm:gap-4">
                    <!-- Avatar Inisial Pengguna -->
                    <div class="w-10 h-10 rounded-full bg-[#24420A] flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-sm">
                        {{ strtoupper(substr($review->user->name ?? 'User', 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm sm:text-base text-[#24420A] truncate">{{ $review->user->name ?? 'Pengguna' }}</p>
                        
                        <!-- Bintang Rating -->
                        <div class="text-yellow-400 text-xs sm:text-sm mb-1">
                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                        </div>

                        <!-- Tanggal & Variasi Produk -->
                        <div class="text-gray-400 text-xs mb-2 flex flex-wrap items-center gap-2">
                            <span>{{ $review->created_at ? $review->created_at->format('d M Y, H:i') : '' }}</span>
                            @if($review->variasi)
                                <span class="text-gray-300">|</span>
                                <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600 font-medium">Variasi: {{ $review->variasi }}</span>
                            @endif
                        </div>

                        <!-- Teks Ulasan -->
                        <p class="text-gray-800 text-sm mb-3 leading-relaxed break-words">{{ $review->ulasan }}</p>

                        <!-- Lampiran Foto Ulasan -->
                        @if(!empty($reviewPhotos) && is_array($reviewPhotos))
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($reviewPhotos as $path)
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden border border-gray-200 shadow-sm cursor-pointer hover:opacity-90 transition">
                                        <img src="{{ asset('storage/' . $path) }}" alt="Foto Ulasan" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Kotak Respon Penjual -->
                        @if($review->respon_penjual)
                            <div class="bg-[#E7F7DA]/50 p-3 sm:p-3.5 rounded-xl text-sm border-l-4 border-[#24420A] mt-3">
                                <p class="font-bold text-[#24420A] text-xs uppercase tracking-wide mb-1">Respon Penjual:</p>
                                <p class="text-gray-700 text-xs sm:text-sm leading-relaxed">{{ $review->respon_penjual }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500 text-sm">
                Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!
            </div>
        @endforelse
    </div>

    <div class="max-w-7xl mx-auto px-4 py-10">
        <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-6 uppercase tracking-wide">Produk Serupa</h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
            
            @forelse($produkSerupa as $item)
                @php
                    // Menangani data gambar produk serupa baik array maupun JSON string
                    $itemImages = $item->gambar;
                    if (is_string($itemImages)) {
                        $itemImages = json_decode($itemImages, true);
                    }
                    $firstImage = !empty($itemImages[0]) ? asset('storage/' . $itemImages[0]) : asset('images/default.jpg');
                @endphp

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition relative flex flex-col justify-between">
                    
                    <!-- Kotak Gambar & Label Habis -->
                    <div class="aspect-square w-full overflow-hidden bg-gray-100 relative">
                        <img src="{{ $firstImage }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover">

                        {{-- Label Habis jika status_produk bernilai false / 0 --}}
                        @if(isset($item->status_produk) && $item->status_produk == false)
                            <div class="absolute inset-0 bg-white/60 flex items-center justify-center backdrop-blur-[1px] z-20">
                                <span class="text-[#FF7017] px-3 py-1.5 font-bold text-sm sm:text-base rounded-lg bg-white/80 shadow-sm">
                                    Habis
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Konten Kartu Produk -->
                    <div class="p-3 flex flex-col flex-1 justify-between">
                        <div>
                            <h3 class="text-xs md:text-sm font-semibold text-gray-800 line-clamp-2 mb-2">
                                {{-- Membuat seluruh card bisa diklik via link overlay --}}
                                <a href="{{ route('produk.detail', $item->id) }}" class="focus:outline-none">
                                    <span class="absolute inset-0 z-10" aria-hidden="true"></span>
                                    {{ $item->nama_produk }}
                                </a>
                            </h3>
                        </div>

                        <div>
                            <!-- Tombol Teks Lihat Produk -->
                            <div class="mb-3 relative z-20">
                                <a href="{{ route('produk.detail', $item->id) }}" class="text-[#24420A] text-xs font-bold hover:underline block text-center bg-gray-50 hover:bg-green-50 py-1.5 rounded-lg transition">
                                    Lihat Produk
                                </a>
                            </div>

                            <!-- Rating & Terjual -->
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100 text-[10px] md:text-xs text-gray-500">
                                <div class="flex items-center gap-1 bg-yellow-50 px-1.5 py-0.5 rounded text-xs text-yellow-600 font-bold">
                                    <span>★</span> {{ number_format($item->averageRating ?? 0, 1) }}
                                </div>
                                <span class="text-[10px] text-gray-400">{{ $item->terjual ?? $item->sold ?? 0 }} terjual</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500 text-sm">
                    Belum ada produk serupa yang tersedia.
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
     async function kirimUlasan(event, formElement) {
        event.preventDefault();
        let formData = new FormData(formElement);

        try {
            const response = await fetch("{{ route('produk.review', $produk->id) }}", {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });

            const data = await response.json();

            if (data.success) {
                const reviewList = document.getElementById('reviewList');
                const stars = '★'.repeat(data.review.rating) + '☆'.repeat(5 - data.review.rating);
                
                // Render foto dinamis
                let fotoHtml = '';
                if (data.review.foto && data.review.foto.length > 0) {
                    fotoHtml = `<div class="flex gap-2 mb-4">` + 
                        data.review.foto.map(path => `
                            <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-200">
                                <img src="/storage/${path}" class="w-full h-full object-cover">
                            </div>
                        `).join('') + 
                    `</div>`;
                }

                const newReview = `
                    <div class="border-b pb-6 animate-fade-in">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-full bg-emerald-800 flex items-center justify-center text-white font-bold text-sm">${data.review.user_name.charAt(0).toUpperCase()}</div>
                            <div><p class="font-bold text-sm text-gray-800">${data.review.user_name}</p><div class="text-yellow-400 text-xs">${stars}</div></div>
                        </div>
                        <p class="text-gray-400 text-xs mb-2">Baru saja</p>
                        <p class="text-gray-800 text-sm mb-3">${data.review.ulasan}</p>
                        ${fotoHtml}
                    </div>`;
                
                reviewList.insertAdjacentHTML('afterbegin', newReview);
                formElement.reset();
                document.querySelectorAll('#starRating input').forEach(i => i.checked = false);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim ulasan.');
        }
    }

    async function toggleFavorite(productId) {
        const btn = document.getElementById(`btn-favorite-${productId}`);
        const text = document.getElementById(`text-favorite-${productId}`);
        
        try {
            const response = await fetch(`/product/${productId}/toggle-favorite`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ tipe: 'favorit' })
            });

            const result = await response.json();

            if (result.status === 'added') {
                btn.classList.replace('text-gray-600', 'text-red-500');
                text.innerText = "Favorit";
                btn.querySelector('svg').setAttribute('fill', 'currentColor');
            } else {
                btn.classList.replace('text-red-500', 'text-gray-600');
                text.innerText = "Tambahkan Favorit";
                btn.querySelector('svg').setAttribute('fill', 'none');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function addToCart(productId) {
        const btn = document.getElementById(`btn-add-cart-${productId}`);
        const textSpan = document.getElementById(`text-cart-${productId}`);
        
        btn.disabled = true;
        textSpan.innerText = "Memproses...";

        try {
            const response = await fetch(`/product/${productId}/toggle-wishlist`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (result.status === 'added') {
                textSpan.innerText = "Sudah di Keranjang";
                btn.classList.add('bg-green-100'); // Opsional: kasih efek warna
            } else if (result.status === 'removed') {
                textSpan.innerText = "Masuk Keranjang";
                btn.classList.remove('bg-green-100');
            }
        } catch (error) {
            textSpan.innerText = "Masuk Keranjang";
        } finally {
            btn.disabled = false;
        }
    }

    let selectedVarian = null;

    function pilihVariasi(btn) {
        // Hapus class aktif dari semua tombol di container
        const buttons = document.querySelectorAll('#variasiContainer button');
        buttons.forEach(b => b.classList.remove('border-[#24420A]', 'bg-green-50', 'ring-1', 'ring-[#24420A]'));
        
        // Tambahkan class aktif ke tombol yang diklik
        btn.classList.add('border-[#24420A]', 'bg-green-50', 'ring-1', 'ring-[#24420A]');
        selectedVarian = btn.innerText;
    }

    function checkoutWhatsAppDetail() {
        if (!selectedVarian) {
            alert('Silakan pilih varian terlebih dahulu!');
            return;
        }

        let qty = document.getElementById('kuantitiInput').value;
        let namaProduk = "{{ $produk->nama_produk }}"; // Mengambil nama dari blade
        
        let pesan = `Halo admin! Saya ingin memesan produk berikut (Pesanan via Website):\n\n` +
                    `- *${namaProduk}* (${selectedVarian} | Qty: ${qty})\n\n` +
                    `Mohon dicek ketersediaannya ya. Terima kasih!`;

        window.open(`https://wa.me/62895428171038?text=${encodeURIComponent(pesan)}`, '_blank');
    }

    function updateKuantiti(change) {
        const input = document.getElementById('kuantitiInput');
        let val = parseInt(input.value) + change;
        if (val < 1) val = 1; // Tidak bisa kurang dari 1
        input.value = val;
    }
</script>
@endsection