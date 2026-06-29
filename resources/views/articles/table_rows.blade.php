@forelse($articles as $article)
    <tr class="group hover:bg-[#F9FAFB] transition duration-200">
        <td class="py-4 px-6">
            <div class="text-sm font-semibold text-gray-800">{{ $article->title }}</div>
            <div class="text-[11px] text-gray-400 mt-0.5">{{ Str::limit($article->excerpt, 60) }}</div>
        </td>
        <td class="py-4 px-6">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide 
                {{ $article->status == 'published' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                {{ $article->status }}
            </span>
        </td>
        <td class="py-4 px-6 text-center">
            <div class="flex justify-center items-center gap-3">
                <button onclick="openArtikelModal({{ json_encode($article) }})" 
                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">Edit</button>
                <div class="w-[1px] h-3 bg-gray-200"></div>
                <button onclick="deleteArtikel({{ $article->id }})" 
                    class="text-xs font-semibold text-rose-600 hover:text-rose-800 transition">Hapus</button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="py-16 text-center text-gray-400">
            <div class="flex flex-col items-center">
                <div class="bg-gray-50 p-3 rounded-full mb-3">
                    <span class="text-2xl">📝</span>
                </div>
                <p class="text-sm font-medium text-gray-600">Belum ada artikel yang dibuat.</p>
                <button onclick="openArtikelModal()" class="mt-3 text-[#24420A] text-xs font-bold hover:underline">
                    + Buat artikel pertama Anda
                </button>
            </div>
        </td>
    </tr>
@endforelse