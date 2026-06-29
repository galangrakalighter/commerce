@extends('app')

@section('content')

<div id="toast-container" class="fixed top-5 right-5 z-[100] space-y-2"></div>
<div class="bg-gray-50 min-h-screen pb-12 antialiased">

    <div class="bg-[#24420A] py-4 px-4 md:px-6 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center space-x-3 w-full sm:w-auto text-white">
                <span class="text-2xl">📝</span>
                <div>
                    <h1 class="text-md md:text-lg font-bold uppercase tracking-wide">Kelola Artikel</h1>
                    <p class="text-xs text-white/80 font-medium">Buat dan atur postingan blog atau informasi produk</p>
                </div>
            </div>
            
            <button onclick="openArtikelModal()" class="w-full sm:w-auto bg-[#F2B705] text-white px-5 py-2 rounded font-bold text-xs md:text-sm hover:bg-opacity-90 transition shrink-0 flex items-center justify-center space-x-2 shadow-md">
                <span>Tambah Artikel Baru</span>
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-8">
        <div class="bg-[#F8F9F5] p-4 rounded-xl border border-[#EAEFD6] flex flex-col sm:flex-row items-center gap-3 mb-6 shadow-sm">
            <div class="relative flex-1 w-full">
                <input type="text" id="el-search" placeholder="Cari judul artikel..." 
                    class="w-full pl-4 pr-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#24420A] focus:border-transparent outline-none text-sm transition">
            </div>
            <button onclick="fetchArtikelList()" class="w-full sm:w-auto bg-[#24420A] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#355e0e] transition shadow-md">
                Cari Artikel
            </button>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-400 text-[11px] font-bold uppercase tracking-widest border-b border-gray-100">
                            <th class="py-4 px-6 w-3/5">Detail Artikel</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="artikel-table-body" class="divide-y divide-gray-50">
                        @include('articles.table_rows', ['articles' => $articles])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="artikel-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeArtikelModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-2xl w-full sm:max-w-3xl text-xs overflow-hidden">
            <div class="bg-[#24420A] px-4 py-3.5 text-white flex items-center justify-between">
                <h3 class="text-sm font-bold uppercase tracking-wider" id="modal-title-text">Formulir Artikel</h3>
                <button type="button" onclick="closeArtikelModal()" class="text-white text-lg font-bold hover:text-gray-200">&times;</button>
            </div>

            <form id="artikel-form" onsubmit="handleArtikelSubmit(event)" enctype="multipart/form-data" class="p-5 space-y-4 text-gray-700">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block font-bold mb-1">Kategori *</label>
                        <div class="flex gap-2">
                            <select name="category_id" id="input-category" required class="flex-1 px-3 py-2 border rounded bg-white focus:ring-2 focus:ring-[#24420A] outline-none">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="openCategoryModal()" class="bg-[#24420A] text-white px-4 rounded font-bold hover:bg-[#355e0e]">+</button>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-bold mb-1">Judul Artikel *</label>
                        <input type="text" id="input-title" name="title" required class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-[#24420A] outline-none">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Status Publikasi *</label>
                        <select name="status" id="input-status" class="w-full px-3 py-2 border rounded bg-white">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Gambar Cover</label>
                        <input type="file" name="image" accept="image/*" class="w-full px-2 py-1.5 border rounded">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-bold mb-1">Konten Artikel *</label>
                        <textarea id="input-content" name="content" rows="6" required class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-[#24420A] outline-none"></textarea>
                    </div>
                </div>

                <div class="pt-3 border-t flex justify-end gap-2">
                    <button type="button" onclick="closeArtikelModal()" class="bg-gray-100 px-4 py-2 rounded font-bold hover:bg-gray-200">Batal</button>
                    <button type="submit" class="bg-[#24420A] text-white px-5 py-2 rounded font-bold shadow-sm hover:bg-[#355e0e]">Simpan Artikel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="category-modal" class="fixed inset-0 z-[60] overflow-y-auto hidden">
    <div class="fixed inset-0 bg-black bg-opacity-60" onclick="closeCategoryModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="bg-white rounded-lg p-5 w-full max-w-sm text-xs shadow-2xl relative">
            <h3 class="font-bold text-sm mb-4 border-b pb-2">Tambah Kategori Baru</h3>
            <form id="category-form">
                @csrf
                <label class="block font-bold mb-1">Nama Kategori</label>
                <input type="text" id="cat-name" name="name" placeholder="Contoh: Teknologi" required class="w-full px-3 py-2 border rounded mb-4 outline-none focus:ring-2 focus:ring-[#24420A]">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeCategoryModal()" class="px-4 py-2 bg-gray-100 rounded font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#24420A] text-white rounded font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    let currentEditId = null;

    function openCategoryModal() { document.getElementById('category-modal').classList.remove('hidden'); }
    function closeCategoryModal() { document.getElementById('category-modal').classList.add('hidden'); }
    
    document.getElementById('category-form').onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        try {
            const res = await fetch('/categories', {
                method: 'POST',
                body: formData,
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json' // PENTING: Memaksa respons JSON
                }
            });

            const data = await res.json();

            if (res.ok) {
                showToast(data.message);
                closeCategoryModal();
                location.reload(); 
            } else {
                // Menampilkan error validasi dari Laravel (misal: "Nama kategori sudah ada")
                const errorMsg = data.errors ? Object.values(data.errors)[0][0] : data.message;
                alert("Error: " + errorMsg);
            }
        } catch (err) {
            console.error(err);
            alert("Terjadi kesalahan sistem.");
        }
    };
    // 2. Fungsi untuk menutup modal
    function closeArtikelModal() {
        const modal = document.getElementById('artikel-modal');
        modal.classList.add('hidden');
        
        // Reset form saat ditutup agar tidak ada sisa data
        document.getElementById('artikel-form').reset();
        currentEditId = null;
    }

    // 3. Tambahkan fungsi showToast (Wajib agar tidak error saat submit)
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `pointer-events-auto px-4 py-3 rounded shadow-lg text-white font-semibold text-xs flex items-center space-x-2 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        toast.innerHTML = `<span>${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3500);
    }
    function handleArtikelSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('artikel-form');
        const formData = new FormData(form);
        const url = document.getElementById('form-method').value === "PUT" ? `/articles/${currentEditId}` : '/articles';
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => {
            return res.json().then(data => ({ status: res.status, body: data }));
        })
        .then(({ status, body }) => {
            if (status === 200) {
                showToast(body.message);
                closeArtikelModal();
                fetchArtikelList();
            } else if (status === 422) {
                // Gabungkan semua pesan error menjadi satu string yang terbaca
                let errorMessage = "";
                const errors = body.message; // Ini adalah objek { title: [...], content: [...] }
                
                for (let key in errors) {
                    errorMessage += errors[key][0] + "\n"; // Ambil error pertama tiap field
                }
                
                alert("Gagal Validasi:\n" + errorMessage);
            } else {
                alert("Terjadi kesalahan: " + body.message);
            }
        })
    }

    function fetchArtikelList() {
        fetch('/articles/fetch', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            // Mengganti isi tbody dengan HTML baru dari server
            document.getElementById('artikel-table-body').innerHTML = html;
        })
        .catch(error => console.error('Gagal memuat data:', error));
    }

    function deleteArtikel(id) {
        if (!confirm('Yakin ingin menghapus?')) return;
        fetch(`/articles/${id}`, {
            method: 'POST',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(res => res.json())
        .then(data => {
            showToast(data.message);
            fetchArtikelList();
        });
    }

    function openArtikelModal(data = null) {
        const modal = document.getElementById('artikel-modal');
        const form = document.getElementById('artikel-form');
        modal.classList.remove('hidden');
        
        if (data) {
            currentEditId = data.id;
            document.getElementById('form-method').value = "PUT";
            document.getElementById('input-title').value = data.title;
            document.getElementById('input-content').value = data.content;
            document.getElementById('input-category').value = data.category_id || "";
        } else {
            document.getElementById('form-method').value = "POST";
            form.reset();
        }
    }
</script>
@endsection