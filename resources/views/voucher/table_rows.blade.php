@forelse($vouchers as $voucher)
<tr class="hover:bg-gray-50/80 transition">
    <td class="py-4 px-4">
        <div class="flex items-center space-x-3">
            <div class="truncate">
                <span class="block font-bold text-gray-900 text-sm truncate uppercase">{{ $voucher->kode }}</span>
            </div>
        </div>
    </td>

    <td class="py-4 px-4">
        <span class="block text-gray-800 font-bold">Potongan Tetap</span>
        <span class="text-xs text-green-600 font-semibold">
            Rp {{ number_format($voucher->potongan, 0, ',', '.') }}
        </span>
    </td>

    <td class="py-4 px-4 text-center">
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1"></span> Aktif
        </span>
    </td>

    <td class="py-4 px-4 text-center">
        <div class="flex items-center justify-center space-x-2">
            <button onclick="prepareEdit({{ $voucher->id }})" class="p-1.5 bg-amber-50 text-[#E0A226] hover:bg-amber-100 rounded transition" title="Ubah Data">
                📝
            </button>
            <button onclick="deleteVoucher({{ $voucher->id }})" class="p-1.5 bg-red-50 text-red-500 hover:bg-red-100 rounded transition" title="Hapus">
                🗑️
            </button>
        </div>

        <script type="application/json" id="voucher-json-{{ $voucher->id }}">
            {!! json_encode($voucher) !!}
        </script>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="py-8 text-center text-gray-400 italic">Belum ada data voucher yang terdaftar.</td>
</tr>
@endforelse