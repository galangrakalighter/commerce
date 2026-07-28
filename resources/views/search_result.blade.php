@extends('app')

@section('content')
<div class="container mx-auto px-4 py-8 mb-10">
    <!-- Judul Header Hasil Pencarian -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-sm md:text-base font-bold text-gray-800 tracking-wider uppercase">
                Hasil Pencarian
            </h2>
            @if(!empty($keyword))
                <p class="text-xs text-gray-500 mt-1">Kata kunci: <span class="font-semibold text-gray-700">"{{ $keyword }}"</span></p>
            @endif
        </div>
    </div>
    
    {{-- Bungkus grid produk agar pesan tidak masuk ke dalam grid --}}
    <div id="product-grid-container">
        @if($products->count() > 0)
            <!-- Grid Produk Hasil Pencarian -->
            <div id="product-grid-search" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4">
                @foreach($products as $item)
                    <div class="product-item" data-kategori="{{ $item->id_kategori ?? $item->category_id }}">
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition relative flex flex-col h-full">
                            
                            <div class="aspect-square w-full overflow-hidden bg-gray-100 relative">
                                <img src="{{ !empty($item->gambar) && is_array($item->gambar) ? asset('storage/' . $item->gambar[0]) : (!empty($item->gambar) ? asset('storage/' . $item->gambar) : asset('images/default.jpg')) }}" 
                                     alt="{{ $item->nama_produk ?? $item->name }}" 
                                     class="w-full h-full object-cover">
                                
                                {{-- Sesuaikan status produk sesuai kolom database Anda (misal: status_produk / stock) --}}
                                @if(isset($item->status_produk) && !$item->status_produk)
                                    <div class="absolute inset-0 bg-white/60 flex items-center justify-center backdrop-blur-[1px]">
                                        <span class="text-[#FF7017] px-4 py-2 font-bold text-lg rounded-lg">
                                            Habis
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-3 flex flex-col flex-grow justify-between">
                                <div>
                                    <h3 class="product-name text-xs md:text-sm font-semibold text-gray-800 line-clamp-2 min-h-[2.5rem]">
                                        {{ $item->nama_produk ?? $item->name }}
                                    </h3>

                                    <div class="mt-1 text-xs font-bold text-gray-900">
                                        Rp {{ number_format($item->harga ?? $item->price ?? 0, 0, ',', '.') }}
                                    </div>

                                    <div class="mt-2 flex justify-center">
                                        <a href="{{ route('produk.detail', $item->id) }}" class="text-[#F2B705] text-xs font-bold hover:underline">
                                            Lihat Produk
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100 text-[10px] md:text-xs text-gray-500">
                                    <div class="flex items-center bg-orange-100 px-1.5 py-0.5 rounded text-orange-600 font-bold">
                                        ★ {{ number_format($item->averageRating ?? 0, 1) }}
                                    </div>
                                    <span>{{ $item->sold ?? 0 }} terjual</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination (Jika menggunakan paginate di controller) -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <!-- Pesan Jika Produk Tidak Ditemukan -->
            <div id="no-product-message" class="flex flex-col items-center justify-center py-20 text-center px-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                <div class="bg-gray-100 p-6 rounded-full mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Oops! Produk Tidak Ditemukan</h3>
                <p class="text-gray-500 max-w-sm mb-6">Maaf, kami tidak dapat menemukan produk yang sesuai dengan kriteria pencarian Anda.</p>
                <a href="{{ route('search') }}" class="px-6 py-2 bg-[#24420A] text-white rounded-full hover:bg-[#3a5a1f] transition text-sm font-medium">
                    Reset Pencarian
                </a>
            </div>
        @endif
    </div>
</div>
@endsection