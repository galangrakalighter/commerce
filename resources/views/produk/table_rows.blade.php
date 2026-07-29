@forelse($products as $produk)
    @php
        // Pastikan gambar di-decode hanya jika berbentuk string
        $gambarList = is_string($produk->gambar) ? json_decode($produk->gambar, true) : $produk->gambar;
        
        // Pastikan spec_produk di-decode hanya jika berbentuk string
        $specs = is_string($produk->spec_produk) ? json_decode($produk->spec_produk, true) : $produk->spec_produk;
    @endphp

<tr class="hover:bg-gray-50/80 transition">
    <td class="py-4 px-4">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gray-100 rounded border overflow-hidden shrink-0 flex items-center justify-center">
                @if(is_array($produk->gambar) && !empty($produk->gambar[0]))
                    <img src="{{ asset('storage/' . $produk->gambar[0]) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-[9px] text-gray-400 font-bold">NO IMG</span>
                @endif
            </div>
            <div class="truncate">
                <span class="block font-bold text-gray-900 text-sm truncate uppercase">{{ $produk->nama_produk }}</span>
                <span class="inline-block px-1.5 py-0.5 rounded text-[10px] bg-[#EAEFD6] text-[#24420A] font-bold mt-0.5">
                    {{ $produk->kategori->nama_kategori ?? 'N/A' }}
                </span>
            </div>
        </div>
    </td>
    <td class="py-4 px-4">
        <div class="flex flex-wrap gap-1 max-w-xs">
            @if(!empty($specs) && is_array($specs))
                @foreach($specs as $key => $val)
                    <span class="text-[10px] bg-gray-100 px-1.5 py-0.5 rounded text-gray-600">
                        <strong>{{ $key }}:</strong> {{ $val }}
                    </span>
                @endforeach
            @endif
        </div>
    </td>
    <td class="py-4 px-4 text-center">
        <div class="flex items-center justify-center space-x-2">
            <button onclick="prepareEdit({{ $produk->id }})" class="p-1.5 bg-amber-50 text-[#E0A226] hover:bg-amber-100 rounded transition">
                📝
            </button>
            <button onclick="deleteProduk({{ $produk->id }})" class="p-1.5 bg-red-50 text-red-500 hover:bg-red-100 rounded transition">
                🗑️
            </button>
        </div>

        <script type="application/json" id="produk-json-{{ $produk->id }}">
            {!! json_encode($produk) !!}
        </script>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="py-8 text-center text-gray-400 italic text-xs">Belum ada data produk di katalog.</td>
</tr>
@endforelse