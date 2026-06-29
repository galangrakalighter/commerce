@forelse($banners as $banner)
    <tr id="banner-row-{{ $banner->id }}" class="hover:bg-gray-50 transition border-b border-gray-100">
        <td class="py-3.5 px-4">
            <img src="{{ asset('storage/' . $banner->image_path) }}" 
                 alt="{{ $banner->judul }}" 
                 class="w-20 h-12 object-cover rounded shadow-sm border border-gray-200">
        </td>
        <td class="py-3.5 px-4 font-bold text-gray-900">{{ $banner->judul }}</td>
        <td class="py-3.5 px-4 text-center">{{ $banner->urutan }}</td>
        <td class="py-3.5 px-4 text-center">{{ $banner->is_active == true ? "Aktif" : "Tidak Aktif" }}</td>
        <td class="py-3.5 px-4 text-center space-x-2">
            <script type="application/json" id="banner-json-{{ $banner->id }}">
                {!! json_encode($banner) !!}
            </script>
            <button onclick="editBanner({{ $banner->id }})" class="text-blue-600 hover:text-blue-800 font-bold">Ubah</button>
            <button onclick="deleteBanner({{ $banner->id }})" class="text-red-600 hover:text-red-800 font-bold">Hapus</button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="py-10 text-center text-gray-400 italic">
            Belum ada banner yang diunggah.
        </td>
    </tr>
@endforelse