@forelse($articleCategories as $kategori)
<tr class="hover:bg-gray-50/80 transition">
    <td class="py-4 px-4 font-bold text-gray-900 text-sm uppercase">
        {{ $kategori->name }}
    </td>
    <td class="py-4 px-4 text-center">
        <div class="flex items-center justify-center space-x-2">
            <button onclick="prepareEdit({{ $kategori->id }})" class="p-1.5 bg-amber-50 text-[#E0A226] hover:bg-amber-100 rounded transition" title="Ubah Data">
                📝
            </button>
            <button onclick="deleteArticle({{ $kategori->id }})" class="p-1.5 bg-red-50 text-red-500 hover:bg-red-100 rounded transition" title="Hapus">
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