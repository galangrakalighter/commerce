@extends('app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 antialiased">

    <div class="bg-[#24420A] py-4 px-4 md:px-6 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center space-x-3 w-full sm:w-auto text-white">
                <span class="text-2xl">📦</span>
                <div>
                    <h1 class="text-md md:text-lg font-bold uppercase tracking-wide">Katalog Kelola Produk</h1>
                    <p class="text-xs text-white/80 font-medium">Atur varian bumbu tabur dan bubuk minuman premium GAFI</p>
                </div>
            </div>
            
            <button onclick="openProdukModal()" class="w-full sm:w-auto bg-[#F2B705] text-white px-5 py-2 rounded font-bold text-xs md:text-sm hover:bg-opacity-90 transition shrink-0 flex items-center justify-center space-x-2 shadow-md">
                <span>Tambah Produk Baru</span>
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-6">
        <!-- Filter Live Search -->
        <div class="bg-[#EAEFD6] p-2 rounded flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-4 text-xs">
            <div class="flex items-center gap-1.5 sm:gap-2 flex-1">
                <input type="text" id="el-search" placeholder="Cari nama produk..." class="px-3 py-1.5 rounded border border-gray-200 focus:outline-none flex-1 sm:flex-none sm:w-64 text-gray-700 bg-white">
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-200">
                            <th class="py-3.5 px-4 w-1/3">Produk & Kategori</th>
                            <th class="py-3.5 px-4">Harga Jual</th>
                            <th class="py-3.5 px-4">Spesifikasi (JSON)</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="produk-table-body" class="divide-y divide-gray-100 font-medium text-gray-700">
                        @include('produk.table_rows', ['products' => $products])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="produk-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeProdukModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full sm:max-w-2xl text-left text-xs">
            <div class="bg-[#24420A] px-4 py-3.5 text-white flex items-center justify-between">
                <h3 class="text-sm font-bold uppercase tracking-wider" id="modal-title-text">Formulir Produk</h3>
                <button onclick="closeProdukModal()" class="text-white text-lg font-bold focus:outline-none">&times;</button>
            </div>

            <form id="produk-form" onsubmit="handleFormSubmit(event)" enctype="multipart/form-data" class="p-5 space-y-4 text-gray-700">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold mb-1">Nama Produk *</label>
                        <input type="text" id="input-nama_produk" name="nama_produk" required class="w-full px-3 py-2 border rounded focus:outline-none focus:border-[#24420A]">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Grup Kategori Kategori *</label>
                        <select id="input-id_kategori" name="id_kategori" required class="w-full px-3 py-2 border rounded bg-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold mb-1">Harga Jali (Rp) *</label>
                        <input type="number" id="input-harga" name="harga" required class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Satuan Tipe Kemasan *</label>
                        <input type="text" id="input-tipe" name="tipe" required placeholder="Contoh: Kilogram, Pack, Bal" class="w-full px-3 py-2 border rounded">
                    </div>
                </div>

                <div>
                    <label class="block font-bold mb-1 text-gray-800 flex items-center justify-between">
                        <span>Spesifikasi Produk (Karakteristik) *</span>
                        <button type="button" onclick="addSpecRow()" class="text-blue-600 font-bold hover:underline">+ Tambah Spek</button>
                    </label>
                    <div id="spec-container" class="space-y-2 max-h-32 overflow-y-auto p-1 bg-gray-50 border rounded">
                        </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold mb-1">Upload Gambar Produk (Bisa Multi File)</label>
                        <input type="file" name="images[]" multiple accept="image/*" class="w-full px-2 py-1.5 border rounded bg-white">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Status Produk</label>
                        <select id="input-status_produk" name="status_produk" required class="w-full px-3 py-2 border rounded bg-white">
                            <option value="ada">Tersedia</option>
                            <option value="habis">Habis</option>
                        </select>
                    </div>
                </div>


                <div>
                    <label class="block font-bold mb-1">Detail Deskripsi Produk *</label>
                    <textarea id="input-detail_produk" name="detail_produk" rows="3" required class="w-full px-3 py-2 border rounded focus:outline-none"></textarea>
                </div>

                <div class="pt-3 border-t flex justify-end space-x-2">
                    <button type="button" onclick="closeProdukModal()" class="bg-gray-100 px-4 py-2 rounded font-bold">Batal</button>
                    <button type="submit" id="btn-submit" class="bg-[#24420A] text-white px-5 py-2 rounded font-bold shadow-sm">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="toast-container" class="fixed bottom-5 right-5 z-50 space-y-2 pointer-events-none"></div>

<script>
    let currentEditId = null;

    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `pointer-events-auto px-4 py-3 rounded shadow-lg text-white font-semibold text-xs flex items-center space-x-2 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        toast.innerHTML = `<span>${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3500);
    }

    function addSpecRow(key = '', value = '') {
        const container = document.getElementById('spec-container');
        const row = document.createElement('div');
        row.className = 'flex items-center space-x-2 spec-row';
        row.innerHTML = `
            <input type="text" placeholder="Label (Misal: Rasa)" value="${key}" required class="w-1/3 px-2 py-1 border rounded spec-key">
            <input type="text" placeholder="Nilai (Misal: Balado)" value="${value}" required class="w-2/3 px-2 py-1 border rounded spec-value">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 font-bold px-1">✕</button>
        `;
        container.appendChild(row);
    }

    function getSpecsAsJson() {
        const specRows = document.querySelectorAll('.spec-row');
        const specs = {};
        specRows.forEach(row => {
            const key = row.querySelector('.spec-key').value.trim();
            const val = row.querySelector('.spec-value').value.trim();
            if (key) specs[key] = val;
        });
        return JSON.stringify(specs);
    }

    function prepareEdit(id) {
        const jsonElement = document.getElementById(`produk-json-${id}`);
        openProdukModal(JSON.parse(jsonElement.textContent));
    }

    function openProdukModal(data = null) {
        const modal = document.getElementById('produk-modal');
        const form = document.getElementById('produk-form');
        const methodInput = document.getElementById('form-method');
        const specContainer = document.getElementById('spec-container');

        modal.classList.remove('hidden');
        specContainer.innerHTML = '';

        if (data) {
            currentEditId = data.id;
            methodInput.value = "PUT";
            document.getElementById('modal-title-text').innerText = "Ubah Detail Produk GAFI";
            
            document.getElementById('input-nama_produk').value = data.nama_produk;
            document.getElementById('input-id_kategori').value = data.id_kategori;
            document.getElementById('input-harga').value = data.harga;
            document.getElementById('input-tipe').value = data.tipe;
            document.getElementById('input-detail_produk').value = data.detail_produk;
            document.getElementById('input-status_produk').value = data.detail_produk;

            if (data.spec_produk) {
                Object.entries(data.spec_produk).forEach(([k, v]) => addSpecRow(k, v));
            }
        } else {
            currentEditId = null;
            methodInput.value = "POST";
            document.getElementById('modal-title-text').innerText = "Tambah Daftar Produk Baru";
            form.reset();
            addSpecRow('Rasa', 'Original'); // Default row pembantu
        }
    }

    function closeProdukModal() {
        document.getElementById('produk-modal').classList.add('hidden');
    }

    function fetchProdukList() {
        const search = document.getElementById('el-search').value;
        fetch(`/produk?search=${encodeURIComponent(search)}`, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(res => {
            document.getElementById('produk-table-body').innerHTML = res.html;
        });
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('produk-form');
        const formData = new FormData(form);
        const url = currentEditId ? `/produk/${currentEditId}` : '/produk';

        // Sisipkan data spesifikasi sebagai string JSON ter-enkapsulasi
        formData.append('spec_produk', getSpecsAsJson());

        fetch(url, {
            method: 'POST', // Menggunakan POST + _method spoofing untuk multipart form aman
            body: formData,
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(async res => {
            const data = await res.json();
            console.log(data); 
            if (!res.ok) throw data;
            showToast(data.message);
            closeProdukModal();
            fetchProdukList();
        })
        .catch(err => showToast(err.message || 'Terjadi kesalahan input.', 'error'));
    }

    function deleteProduk(id) {
        if (!confirm('Hapus produk ini beserta seluruh aset gambar terkait?')) return;

        fetch(`/produk/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(async res => {
            const data = await res.json();
            showToast(data.message);
            fetchProdukList();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('el-search');
        const tableBody = document.getElementById('produk-table-body');
        let debounceTimer;
        let currentController = null;

        if (searchInput && tableBody) {
            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                
                debounceTimer = setTimeout(() => {
                    const keyword = searchInput.value.trim();
                    fetchProdukList(keyword);
                }, 300);
            });
        }

        function fetchProdukList(keyword) {
            if (currentController) {
                currentController.abort();
            }
            currentController = new AbortController();

            tableBody.style.opacity = '0.5';

            const url = new URL(window.location.href);
            if (keyword !== '') {
                url.searchParams.set('search', keyword);
            } else {
                url.searchParams.delete('search');
            }

            fetch(url, {
                signal: currentController.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = data.html;
                tableBody.style.opacity = '1';
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Terjadi kesalahan:', error);
                    tableBody.style.opacity = '1';
                }
            });
        }
    });
</script>
@endsection