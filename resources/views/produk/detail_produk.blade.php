@extends('app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="text-xs text-gray-500 mb-6">
        Toko > Hobi > Tanaman > Hias > Asli > {{ $produk->nama_produk }}
    </div>

    <div class="grid md:grid-cols-2 gap-8">
        <div class="space-y-4">
            @php $images = $produk->gambar; @endphp
            <div class="w-full max-w-[620px] h-[300px] md:h-[528px] overflow-hidden rounded-2xl border border-gray-100 shadow-sm bg-gray-50 flex items-center justify-center">
                <img id="mainImage" 
                    src="{{ asset('storage/' . $images[0]) }}" 
                    alt="{{ $produk->nama_produk }}" 
                    class="w-full h-full object-contain transition-opacity duration-300">
            </div>
            <div class="flex flex-wrap items-center gap-6 mt-4 text-sm">
                {{-- Favorit --}}
                <button type="button" 
                    id="btn-favorite-{{ $produk->id }}"
                    onclick="toggleFavorite({{ $produk->id }})"
                    class="flex items-center gap-2 transition-colors {{ $isFavorited ? 'text-red-500' : 'text-gray-600 hover:text-red-500' }}">

                    <span class="font-medium" id="text-favorite-{{ $produk->id }}">
                        {{ $isFavorited ? 'Favorit' : 'Tambahkan Favorit' }}
                    </span>

                    <svg class="w-5 h-5" 
                        fill="{{ $isFavorited ? 'currentColor' : 'none' }}" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                {{-- Share --}}
                <div class="flex items-center gap-3">

                    <span class="font-medium text-gray-700">
                        Share:
                    </span>

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

                    <button type="button"
                        onclick="copyToClipboard()"
                        class="text-red-500 hover:text-red-700 transition"
                        title="Salin Link">

                        <i class="fas fa-link text-lg"></i>

                    </button>

                </div>

            </div>
        </div>

        <div class="flex flex-col">
            <h1 class="text-xl md:text-2xl font-semibold text-gray-900 mb-2">{{ $produk->nama_produk }}</h1>
            
            <div class="flex items-center gap-4 mb-4">
                <span class="text-gray-600">120 Terjual</span>
                <div class="flex items-center text-yellow-400">
                    <span class="text-gray-900 font-bold mr-1">4.9</span>
                    ★★★★★
                </div>
            </div>

            <div class="flex gap-2 mb-6">
                @foreach($images as $img)
                    <div class="w-16 h-16 rounded-lg border overflow-hidden cursor-pointer hover:border-[#24420A]" 
                        onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $img) }}'">
                        <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>

            <div class="bg-gray-50 p-2 mb-6 border-gray-100">
                <h3 class="font-bold text-[#24420A] mb-3">Hubungi Kami Untuk Harga Terbaik</h3>
                
                <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-[#24420A] font-medium">
                    
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/Subtract_detail2.png') }}" alt="Grosir" class="w-5 h-5 object-contain flex-shrink-0">
                        <span>Harga grosir tersedia</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/Subtract_detail.png') }}" alt="Diskon" class="w-5 h-5 object-contain flex-shrink-0">
                        <span>Diskon pembelian jumlah besar</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/Subtract_detail1.png') }}" alt="Konsultasi" class="w-5 h-5 object-contain flex-shrink-0">
                        <span>Konsultasi kebutuhan produk gratis</span>
                    </div>

                </div>
            </div>

            <div class="mb-6">
                <span class="text-sm font-medium mb-2 block">Variasi</span>
                <div class="flex flex-wrap gap-2" id="variasiContainer">
                    @foreach(['1KG', '2KG', '3KG', '5KG', 'BUNDLE'] as $v)
                        <button type="button" 
                                onclick="pilihVariasi(this)"
                                class="px-6 py-2 border rounded-md transition text-sm font-medium hover:border-[#24420A] focus:outline-none">
                            {{ $v }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-4 mb-8">
                <span class="text-sm text-gray-600">Kuantiti</span>
                <div class="flex items-center border rounded-md overflow-hidden">
                    <button type="button" onclick="updateKuantiti(-1)" class="px-3 py-1 bg-gray-100 hover:bg-gray-200">-</button>
                    <input type="number" id="kuantitiInput" value="1" min="1" class="w-12 text-center text-sm outline-none border-x">
                    <button type="button" onclick="updateKuantiti(1)" class="px-3 py-1 bg-gray-100 hover:bg-gray-200">+</button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button type="button" 
                    id="btn-add-cart-{{ $produk->id }}"
                    onclick="addToCart({{ $produk->id }})"
                    class="flex items-center justify-center gap-2 border border-[#24420A] text-[#24420A] py-3 px-6 rounded-full font-bold hover:bg-green-50 transition-all">
                    
                    <img src="{{ asset('images/basket_alt_3.png') }}" alt="Keranjang" class="w-6 h-6 object-contain">
                    
                    <span id="text-cart-{{ $produk->id }}">
                        {{ $inWishlist ? 'Sudah di Keranjang' : 'Masuk Keranjang' }}
                    </span>
                </button>

                <button type="button" 
                        onclick="checkoutWhatsAppDetail()" 
                        class="flex items-center justify-center gap-2 bg-[#24420A] text-white py-3 px-6 rounded-full font-bold transition-all hover:bg-[#1a3007]">
                    <i class="fab fa-whatsapp text-lg"></i>
                    <span>Pesan Sekarang</span>
                </button>
            </div>
        </div>
    </div>
    <div class="space-y-6 mt-[8%]">
        <div class="bg-[#E7F7DA] p-3 rounded-t-lg">
            <h2 class="text-sm md:text-base font-bold text-gray-800 uppercase">Spesifikasi Produk</h2>
        </div>
        
        <ul class="space-y-3 text-sm text-gray-700 px-2">
            @if(is_array($produk->spec_produk))
                @foreach($produk->spec_produk as $key => $value)
                    <li class="flex border-b border-gray-100 pb-2">
                        <span class="w-1/3 text-gray-500 font-medium">{{ $key }} :</span>
                        <span class="w-2/3 font-semibold text-gray-900">{{ $value }}</span>
                    </li>
                @endforeach
            @endif
            <li class="flex pb-2">
                <span class="w-1/3 text-gray-500 font-medium">Kategori :</span>
                <span class="w-2/3 font-semibold text-gray-900">{{ $produk->kategori->nama ?? 'Bumbu' }}</span>
            </li>
        </ul>

        <div class="bg-[#E7F7DA] p-3 rounded-t-lg mt-8">
            <h2 class="text-sm md:text-base font-bold text-gray-800 uppercase">Detail Produk</h2>
        </div>

        <div class="prose max-w-none text-sm text-gray-700 leading-relaxed px-2">
            <p>{{ $produk->detail_produk }}</p>
        </div>
    </div>

<div class="mt-8 bg-[#E7F7DA] p-6 rounded-2xl border border-green-100 flex flex-col md:flex-row items-start md:items-center gap-8">
    <div class="flex items-center gap-4">
        <span class="text-5xl font-bold text-gray-800">
            {{ number_format($produk->ratings->avg('rating'), 1) }}
        </span>
        <div class="flex flex-col">
            <span class="text-sm text-gray-500">/5</span>
            <div class="text-yellow-400 text-xl">
                {{ str_repeat('★', round($produk->ratings->avg('rating'))) }}{{ str_repeat('☆', 5 - round($produk->ratings->avg('rating'))) }}
            </div>
        </div>
    </div>

    <div class="flex overflow-x-auto w-full md:w-auto gap-2 pb-2 md:pb-0 scrollbar-hide">
        @foreach(['Semua' => 'all', 'Dengan Foto' => 'photo', '5 Bintang' => 5, '4 Bintang' => 4, '3 Bintang' => 3, '2 Bintang' => 2, '1 Bintang' => 1] as $label => $value)
            <button onclick="filterReviews('{{ $value }}', this)" 
                    class="filter-btn-review px-5 py-2 rounded-full border border-gray-300 text-sm transition bg-white shadow-sm hover:border-[#24420A] whitespace-nowrap">
                {{ $label }}
            </button>
        @endforeach
    </div>
</div>

    <div class="bg-white p-6 rounded-2xl border mb-8 shadow-sm">
        <h3 class="font-bold text-gray-800 mb-4">Berikan Ulasan Anda</h3>
        <form id="reviewForm" onsubmit="kirimUlasan(event, this)" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center gap-2 mb-4">
                <label class="text-sm">Rating:</label>
                <div class="flex flex-row-reverse justify-end gap-1 cursor-pointer" id="starRating">
                    @for($i = 5; $i >= 1; $i--)
                        <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" class="hidden peer" required>
                        <label for="star{{ $i }}" class="text-2xl text-gray-300 peer-hover:text-yellow-400 peer-checked:text-yellow-400 transition-colors">★</label>
                    @endfor
                </div>
            </div>
            <textarea name="ulasan" class="w-full border rounded-xl p-4 mb-4" placeholder="Tulis ulasan Anda..." rows="3" required></textarea>
            <input type="file" name="foto[]" multiple class="mb-4 text-sm">
            <button type="submit" class="bg-[#24420A] text-white px-6 py-2 rounded-lg font-bold hover:bg-green-900 transition">Kirim Ulasan</button>
        </form>
    </div>

    <div id="reviewList" class="space-y-6">
        @foreach($produk->ratings as $review)
        <div class="review-item pb-6 border-b border-gray-100" 
            data-rating="{{ $review->rating }}" 
            data-has-photo="{{ !empty($review->foto) ? 'true' : 'false' }}">
            
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-[#24420A] flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                </div>

                <div class="flex-1">
                    <p class="font-bold text-sm text-[#24420A]">{{ $review->user->name }}</p>
                    
                    <div class="text-yellow-400 text-xs mb-1">
                        {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                    </div>

                    <div class="text-gray-400 text-xs mb-2 flex items-center gap-2">
                        <span>{{ $review->created_at->format('Y-m-d H:i') }}</span>
                        @if($review->variasi)
                            <span class="text-gray-300">|</span>
                            <span>Variasi : {{ $review->variasi }}</span>
                        @endif
                    </div>

                    <p class="text-gray-800 text-sm mb-3">{{ $review->ulasan }}</p>

                    @if(!empty($review->foto))
                        <div class="flex gap-2 mb-3">
                            @foreach($review->foto as $path)
                                <div class="w-20 h-20 rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($review->respon_penjual)
                        <div class="bg-gray-50 p-3 rounded-lg text-sm border-l-4 border-gray-200 mt-2">
                            <p class="font-bold text-[#24420A] text-xs">Respon Penjual:</p>
                            <p class="text-gray-600">{{ $review->respon_penjual }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="max-w-7xl mx-auto px-4 py-10">
        <h2 class="text-xl font-bold text-gray-800 mb-6 uppercase tracking-wide">Produk Serupa</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            
            @foreach($produkSerupa as $item)
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition">
                    <div class="aspect-square w-full overflow-hidden bg-gray-100">
                        <img src="{{ isset($item->gambar[0]) ? asset('storage/' . $item->gambar[0]) : asset('images/default.jpg') }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover">
                    </div>

                    <div class="p-3">
                        <h3 class="text-xs md:text-sm font-semibold text-gray-800 line-clamp-2 min-h-[2.5rem]">
                            {{ $item->nama_produk }}
                        </h3>

                        <div class="mt-2 flex justify-center">
                            <a href="{{ route('produk.detail', $item->id) }}" class="text-[#F2B705] text-xs font-bold hover:underline">
                                Lihat Produk
                            </a>
                        </div>

                        <div class="flex items-center justify-between mt-3 text-[10px] md:text-xs text-gray-500">
                            <div class="flex items-center gap-1 bg-yellow-50 px-1.5 py-0.5 rounded text-xs text-yellow-600 font-bold">
                                <span>★</span> {{ number_format($item->averageRating, 1) }}
                            </div>
                            <span class="text-[10px] text-gray-400">{{ $item->terjual }} terjual</span>
                        </div>
                    </div>
                </div>
            @endforeach
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
            textSpan.innerText = "Error!";
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