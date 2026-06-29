@extends('app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 antialiased">

    <div class="bg-[#24420A] py-4 px-4 md:px-6 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center space-x-3 w-full sm:w-auto text-white">
                <span class="text-2xl">🖼️</span>
                <div>
                    <h1 class="text-md md:text-lg font-bold uppercase tracking-wide">Kelola Banner</h1>
                    <p class="text-xs text-white/80 font-medium">Atur tampilan banner utama di halaman depan</p>
                </div>
            </div>
            
            <button onclick="openBannerModal()" class="w-full sm:w-auto bg-[#F2B705] text-white px-5 py-2 rounded font-bold text-xs md:text-sm hover:bg-opacity-90 transition shadow-md">
                Tambah Banner Baru
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-6">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-200">
                        <th class="py-3.5 px-4">Preview</th>
                        <th class="py-3.5 px-4">Judul</th>
                        <th class="py-3.5 px-4 text-center">Urutan</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="voucher-table-body" class="divide-y divide-gray-100 font-medium text-gray-700">
                    @include('banner.table_rows', ['vouchers' => $banners])
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="banner-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeBannerModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full sm:max-w-md text-xs">
            <div class="bg-[#24420A] px-4 py-3.5 text-white flex justify-between">
                <h3 class="font-bold uppercase tracking-wider">Tambah Banner Baru</h3>
                <button onclick="closeBannerModal()" class="text-lg font-bold">&times;</button>
            </div>

            <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                @csrf
                
                <div>
                    <label class="block font-bold mb-1">Judul Banner *</label>
                    <input type="text" name="judul" required class="w-full px-3 py-2 border rounded">
                </div>

                <div>
                    <label class="block font-bold mb-1">Tipe Banner *</label>
                    <select name="tipe" onchange="gantiTipe(this.value)" required class="w-full px-3 py-2 border rounded">
                        <option value="home">Halaman Home</option>
                        <option value="artikel">Halaman Artikel</option>
                        <option value="keduanya">Kedua Halaman</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold mb-1">Link Tujuan (Opsional)</label>
                    <input type="url" name="link_tujuan" placeholder="https://..." class="w-full px-3 py-2 border rounded">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold mb-1">Urutan *</label>
                        <input type="number" name="urutan" id="urutan_id" value="0" class="w-full px-3 py-2 border rounded" readonly>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Status</label>
                        <select name="is_active" class="w-full px-3 py-2 border rounded">
                            <option value="1">Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold mb-1">Upload Gambar</label>
                    <input type="file" name="image" accept="image/png, image/jpeg, image/webp" 
                        class="w-full px-3 py-2 border rounded file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#EAEFD6] file:text-[#24420A] hover:file:bg-[#d5dcb8]">
                    
                    <p class="text-[10px] text-gray-500 mt-1 italic">
                        Format: JPG, PNG, WEBP. Ukuran disarankan: 1200 x 300px. 
                        <span class="text-red-500">*Biarkan kosong jika tidak ingin mengganti gambar.</span>
                    </p>
                </div>

                <div class="pt-3 border-t flex justify-end space-x-2">
                    <button type="button" onclick="closeBannerModal()" class="bg-gray-100 px-4 py-2 rounded font-bold">Batal</button>
                    <button type="submit" class="bg-[#24420A] text-white px-5 py-2 rounded font-bold">Simpan Banner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function gantiTipe(value){
        switch(value){
            case "keduanya":
                document.getElementById('urutan_id').readOnly = false;
                break;
            default:
                document.getElementById('urutan_id').readOnly = true;
        }
    }
    function editBanner(id) {
        const data = JSON.parse(document.getElementById(`banner-json-${id}`).textContent);
        
        // Normalisasi data boolean ke string untuk select box
        data.is_active = data.is_active ? "1" : "0";
        
        const modal = document.getElementById('banner-modal');
        const form = document.querySelector('#banner-modal form');
        
        // Ubah Judul Modal
        modal.querySelector('h3').innerText = "Ubah Banner";
        
        // Ubah Action Form
        form.action = `/banner/${id}`;
        
        // Tambahkan method PUT untuk Laravel
        if(!form.querySelector('input[name="_method"]')) {
            form.insertAdjacentHTML('afterbegin', '<input type="hidden" name="_method" value="PUT">');
        }

        // Isi data ke input
        form.querySelector('input[name="judul"]').value = data.judul;
        
        // BARIS BARU: Mengisi select tipe
        form.querySelector('select[name="tipe"]').value = data.tipe; 
        
        form.querySelector('input[name="link_tujuan"]').value = data.link_tujuan || '';
        form.querySelector('input[name="urutan"]').value = data.urutan;
        form.querySelector('select[name="is_active"]').value = data.is_active;

        modal.classList.remove('hidden');
    }
    function openBannerModal() {
        const modal = document.getElementById('banner-modal');
        const form = modal.querySelector('form'); // Cari form di dalam modal
        
        // CARA AMAN: Cari h3 di dalam modal, bukan di dalam form
        const titleElement = modal.querySelector('h3'); 
        if (titleElement) {
            titleElement.innerText = "Tambah Banner Baru";
        }

        form.action = "{{ route('banner.store') }}";
        
        // Hapus method PUT jika ada
        const methodInput = form.querySelector('input[name="_method"]');
        if(methodInput) methodInput.remove();
        
        form.reset();
        modal.classList.remove('hidden');
    }
    function closeBannerModal() { document.getElementById('banner-modal').classList.add('hidden'); }

    function deleteBanner(id) {
        if(!confirm('Hapus banner ini?')) return;
        
        // Buat form dinamis untuk DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/banner/${id}`;
        form.innerHTML = `@csrf @method('DELETE')`;
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection