@props(['title', 'image', 'price', 'rating', 'sold'])

<div class="bg-white border border-gray-200 rounded overflow-hidden shadow-sm hover:shadow-md transition flex flex-col h-full">
    <div class="aspect-square w-full bg-gray-100 relative">
        <img src="{{ asset('images/' . $image) }}" alt="{{ $title }}" class="w-full h-full object-cover">
    </div>
    
    <div class="p-3 flex flex-col flex-grow justify-between">
        <div>
            <h4 class="text-[11px] font-bold text-gray-800 line-clamp-2 uppercase tracking-tight leading-tight mb-2">
                {{ $title }}
            </h4>
        </div>
        <div>
            <span class="text-xs font-bold text-[#F29F05] block mb-1">
                Rp{{ number_format($price, 0, ',', '.') }}
            </span>
            <div class="flex items-center text-[10px] text-gray-500 gap-1.5 border-t pt-1.5 border-gray-50">
                <span class="flex items-center text-yellow-500 bg-yellow-50 px-1 rounded font-bold">
                    ★ {{ $rating }}
                </span>
                <span>{{ $sold }} terjual</span>
            </div>
        </div>
    </div>
</div>