@extends('app')
@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <nav class="overflow-x-auto scrollbar-none whitespace-nowrap bg-white border-b border-gray-200 flex-grow">
            <ul class="flex items-center text-xs font-medium text-gray-600">
                <li class="relative">
                    <button onclick="filterProdukFavorit(null, this)" class="tab-btn block py-4 px-4 font-bold text-[#24420A] border-b-2 border-[#24420A]">
                        Semua Produk
                    </button>
                </li>
                @foreach($kategoriList as $kategori)
                    <li class="relative">
                        <button onclick="filterProdukFavorit('{{ $kategori->id }}', this)" 
                                class="tab-btn block py-4 px-4 font-semibold hover:text-[#24420A] border-b-2 border-transparent hover:border-[#24420A] transition-all">
                            {{ $kategori->nama_kategori }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="shrink-0">
            <select id="statusFilter" onchange="filterProdukFavorit()" class="border p-2 rounded-md text-xs text-gray-600 outline-none focus:border-[#24420A]">
                <option value="all">Semua Status</option>
                <option value="available">Tersedia</option>
                <option value="out">Habis</option>
            </select>
        </div>
    </div>

    <div id="productGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($favorites as $item)
            @php $produk = $item->product; @endphp

            <div class="product-item" data-kategori="{{ $produk->id_kategori }}" data-status="{{ $produk->status_produk == true ? 'available' : 'out' }}">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition relative">
                    
                    <div class="aspect-square w-full overflow-hidden bg-gray-100 relative">
                        <img src="{{ !empty($produk->gambar) ? asset('storage/' . $produk->gambar[0]) : asset('images/default.jpg') }}" 
                            alt="{{ $produk->nama_produk }}" 
                            class="w-full h-full object-cover">
                        
                        @if(!$produk->status_product)
                            <div class="absolute inset-0 bg-white/60 flex items-center justify-center backdrop-blur-[1px]">
                                <span class="text-[#FF7017] px-4 py-2 font-bold text-lg rounded-lg">
                                    Habis
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="p-3">
                        <h3 class="product-name text-xs md:text-sm font-semibold text-gray-800 line-clamp-2 min-h-[2.5rem]">
                            {{ $produk->nama_produk }}
                        </h3>

                        <div class="mt-2 flex justify-center">
                            <a href="{{ route('produk.detail', $produk->id) }}" class="text-[#F2B705] text-xs font-bold hover:underline">
                                Lihat Produk
                            </a>
                        </div>

                        <div class="flex items-center justify-between mt-3 text-[10px] md:text-xs text-gray-500">
                            <div class="flex items-center bg-orange-100 px-1.5 py-0.5 rounded text-orange-600 font-bold">
                                ★ {{ number_format($produk->averageRating, 1) }}
                            </div>
                            <span>{{ $produk->sold ?? 0 }} terjual</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500 py-10">Belum ada produk difavoritkan.</p>
        @endforelse
    </div>
</div>

<script>
    // Menyimpan state kategori yang sedang dipilih
    let activeKategori = null;

    function filterProdukFavorit(idKategori = null, element = null) {
        // 1. Update style tombol kategori
        if (element) {
            activeKategori = idKategori;
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-[#24420A]', 'border-[#24420A]');
                btn.classList.add('text-gray-600', 'border-transparent');
            });
            element.classList.add('text-[#24420A]', 'border-[#24420A]');
            element.classList.remove('text-gray-600', 'border-transparent');
        }

        // 2. Ambil nilai status dan filter produk
        const status = document.getElementById('statusFilter').value;

        const items = document.querySelectorAll('.product-item');

        items.forEach(item => {
            const itemStatus = item.getAttribute('data-status');
            
            const itemKategori = item.getAttribute('data-kategori');

            const matchKategori = (activeKategori === null || itemKategori == activeKategori);
            const matchStatus = (status === 'all' || itemStatus === status);

            item.style.display = (matchKategori && matchStatus) ? 'block' : 'none';
        });
    }
</script>

@endsection