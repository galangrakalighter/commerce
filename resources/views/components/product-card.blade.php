@props(['title', 'image', 'price', 'rating', 'sold'])

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition">
    <div class="aspect-square w-full overflow-hidden bg-gray-100">
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover">
    </div>

    <div class="p-3">
        <h3 class="text-xs md:text-sm font-semibold text-gray-800 line-clamp-2 min-h-[2.5rem]">
            {{ $title }}
        </h3>

        <div class="mt-2 flex justify-center">
            <span class="text-[#F2B705] text-xs font-bold hover:underline cursor-pointer">
                Lihat Produk
            </span>
        </div>

        <div class="flex items-center justify-between mt-3 text-[10px] md:text-xs text-gray-500">
            <div class="flex items-center bg-orange-100 px-1.5 py-0.5 rounded text-orange-600 font-bold">
                ★ {{ $rating }}
            </div>
            <span>{{ $sold }} terjual</span>
        </div>
    </div>
</div>