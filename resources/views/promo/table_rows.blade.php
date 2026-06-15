@forelse($promos as $promo)
<tr class="hover:bg-gray-50/80 transition">
    <td class="py-4 px-4">
        <div class="flex items-center space-x-3">
            <div class="truncate">
                <span class="block font-bold text-gray-900 text-sm truncate uppercase">{{ $promo->nama_promo }}</span>
            </div>
        </div>
    </td>
    <td class="py-4 px-4">
        <span class="block text-gray-800 font-bold">Potongan Tetap</span>
        <span class="text-xs text-green-600 font-semibold">
            Rp {{ number_format($promo->potongan, 0, ',', '.') }}
        </span>
    </td>
    <td class="py-4 px-4 text-gray-500 leading-relaxed">
        <span class="block text-gray-800 font-semibold">{{ \Carbon\Carbon::parse($promo->tgl_mulai)->format('d M Y') }}</span>
        <span class="block text-[11px] text-gray-400">s/d {{ \Carbon\Carbon::parse($promo->tgl_akhir)->format('d M Y') }}</span>
    </td>
    <td class="py-4 px-4">
        @if(\Carbon\Carbon::parse($promo->tgl_akhir)->format('Y-m-d') < now()->format('Y-m-d'))
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">
                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1"></span> Berakhir
            </span>
        @else
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1"></span> Aktif
            </span>
        @endif
    </td>
    <td class="py-4 px-4 text-center">
        <div class="flex items-center justify-center space-x-2">
            <button onclick="prepareEdit({{ $promo->id }})" class="p-1.5 bg-amber-50 text-[#E0A226] hover:bg-amber-100 rounded transition" title="Ubah Data">
                📝
            </button>
            <button onclick="deletePromo({{ $promo->id }})" class="p-1.5 bg-red-50 text-red-500 hover:bg-red-100 rounded transition" title="Hapus">
                🗑️
            </button>
        </div>

        <script type="application/json" id="promo-json-{{ $promo->id }}">
            {!! json_encode($promo) !!}
        </script>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="py-8 text-center text-gray-400 italic">Belum ada data promo yang terdaftar.</td>
</tr>
@endforelse