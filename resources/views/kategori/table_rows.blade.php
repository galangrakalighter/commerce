@forelse($categories as $kategori)
<tr class="hover:bg-gray-50/80 transition">
    <td class="py-4 px-4 font-bold text-gray-900 text-sm uppercase">
        {{ $kategori->nama_kategori }}
    </td>
    <td class="py-4 px-4">
        @if($kategori->promo)
            <div class="space-y-0.5">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">
                    {{ $kategori->promo->nama_promo }}
                </span>
                <span class="block text-[11px] text-green-600 font-semibold">
                    Potongan: Rp {{ number_format($kategori->promo->potongan, 0, ',', '.') }}
                </span>
            </div>
        @else
            <span class="text-gray-400 italic text-xs">Tidak ada promo aktif</span>
        @endif
    </td>
    <td class="py-4 px-4 text-gray-500 text-xs">
        {{ $kategori->created_at ? $kategori->created_at->format('d M Y H:i') : '-' }}
    </td>
    <td class="py-4 px-4 text-center">
        <div class="flex items-center justify-center space-x-2">
            <button onclick="prepareEdit({{ $kategori->id }})" class="p-1.5 bg-amber-50 text-[#E0A226] hover:bg-amber-100 rounded transition" title="Ubah Data">
                📝
            </button>
            <button onclick="deleteKategori({{ $kategori->id }})" class="p-1.5 bg-red-50 text-red-500 hover:bg-red-100 rounded transition" title="Hapus">
                🗑️
            </button>
        </div>

        <script type="application/json" id="kategori-json-{{ $kategori->id }}">
            {!! json_encode($kategori) !!}
        </script>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="py-8 text-center text-gray-400 italic text-xs">Belum ada data kategori yang terdaftar.</td>
</tr>
@endforelse