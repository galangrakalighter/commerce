@extends('app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 antialiased">

    <div class="bg-[#F2B705] py-4 px-4 md:px-6 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center space-x-3 w-full sm:w-auto text-white">
                <span class="text-2xl">🗂️</span>
                <div>
                    <h1 class="text-md md:text-lg font-bold uppercase tracking-wide">Kelola Kategori Produk</h1>
                    <p class="text-xs text-white/80 font-medium">Manajemen kategori bumbu dan bubuk minuman GAFI</p>
                </div>
            </div>
            
            <button onclick="openKategoriModal()" class="w-full sm:w-auto bg-[#24420A] text-white px-5 py-2 rounded font-bold text-xs md:text-sm hover:bg-opacity-90 transition shrink-0 flex items-center justify-center space-x-2 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Kategori Baru</span>
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-6">
        
        <div class="grid grid-cols-2 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex items-center space-x-3.5">
                <div class="p-2.5 bg-green-100 text-[#24420A] rounded-md text-xl">📦</div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase">Total Jenis Kategori</p>
                    <p id="stat-total" class="text-lg font-extrabold text-gray-800">{{ $categories->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex items-center space-x-3.5">
                <div class="p-2.5 bg-amber-100 text-amber-600 rounded-md text-xl">🏷️</div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase">Kategori Terikat Promo</p>
                    <p id="stat-berpromo" class="text-lg font-extrabold text-gray-800">{{ $categories->whereNotNull('id_promo')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="w-full">
            <div class="bg-[#EAEFD6] p-2 rounded flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-4 text-xs">
                <div class="flex items-center gap-1.5 sm:gap-2 flex-1">
                    <span class="text-gray-600 font-medium pl-1 sm:pl-2 hidden sm:inline">Filter</span>
                    <input type="text" id="el-search" placeholder="Cari nama kategori..." class="px-3 py-1.5 rounded border border-gray-200 focus:outline-none flex-1 sm:flex-none sm:w-48 text-gray-700 bg-white">
                    <button onclick="fetchKategoriList()" class="bg-[#24420A] text-white px-3 py-1.5 rounded font-semibold hover:bg-opacity-90">Terapkan</button>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-200">
                                <th class="py-3.5 px-4 w-1/2">Nama Kategori</th>
                                <th class="py-3.5 px-4">Program Promo Terikat</th>
                                <th class="py-3.5 px-4">Tanggal Input</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="kategori-table-body" class="divide-y divide-gray-100 font-medium text-gray-700">
                            @include('kategori.table_rows', ['categories' => $categories])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="kategori-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeKategoriModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-lg">
            <div class="bg-[#24420A] px-4 py-3.5 text-white flex items-center justify-between">
                <h3 class="text-sm font-bold uppercase tracking-wider flex items-center">
                    <span>🗂️</span> <span class="ml-2" id="modal-title-text">Formulir Kategori</span>
                </h3>
                <button onclick="closeKategoriModal()" class="text-white/70 hover:text-white font-bold text-lg focus:outline-none">&times;</button>
            </div>

            <form id="kategori-form" onsubmit="handleFormSubmit(event)" class="p-5 space-y-4 text-xs text-gray-700">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">
                
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Nama Kelompok Kategori *</label>
                    <input type="text" id="input-nama_kategori" name="nama_kategori" required placeholder="Contoh: Bumbu Tabur Makaroni, Bubuk Minuman Premium" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-[#24420A]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Hubungkan ke Program Promo (Opsional)</label>
                    <select id="input-id_promo" name="id_promo" class="w-full px-3 py-2 border border-gray-300 rounded bg-white focus:outline-none focus:border-[#24420A]">
                        <option value="">-- Tanpa Ikatan Promo --</option>
                        @foreach($promos as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_promo }} (Potongan Rp {{ number_format($p->potongan, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1">Semua produk di dalam kategori ini otomatis mewarisi potongan harga tersebut.</p>
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-end space-x-2">
                    <button type="button" onclick="closeKategoriModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold px-4 py-2 rounded transition">Batalkan</button>
                    <button type="submit" id="btn-submit-kategori" class="bg-[#24420A] hover:bg-opacity-95 text-white font-bold px-5 py-2 rounded shadow-sm transition">Simpan Kategori</button>
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
        toast.className = `transform translate-y-2 opacity-0 transition duration-300 ease-out pointer-events-auto px-4 py-3 rounded shadow-lg text-white font-semibold text-xs flex items-center space-x-2 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        toast.innerHTML = `<span>${type === 'success' ? '✅' : '❌'}</span> <span>${message}</span>`;
        container.appendChild(toast);
        
        setTimeout(() => toast.classList.remove('translate-y-2', 'opacity-0'), 10);
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    function prepareEdit(id) {
        try {
            const jsonElement = document.getElementById(`kategori-json-${id}`);
            if (!jsonElement) throw new Error("Data kategori tidak valid.");
            openKategoriModal(JSON.parse(jsonElement.textContent));
        } catch (error) {
            showToast(error.message, 'error');
        }
    }

    function openKategoriModal(data = null) {
        const modal = document.getElementById('kategori-modal');
        const form = document.getElementById('kategori-form');
        const titleText = document.getElementById('modal-title-text');
        const methodInput = document.getElementById('form-method');

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        if (data) {
            currentEditId = data.id;
            titleText.innerText = "Ubah Detail Kategori";
            methodInput.value = "PUT";
            
            document.getElementById('input-nama_kategori').value = data.nama_kategori;
            document.getElementById('input-id_promo').value = data.id_promo || '';
        } else {
            currentEditId = null;
            titleText.innerText = "Tambah Kategori Baru";
            methodInput.value = "POST";
            form.reset();
        }
    }

    function closeKategoriModal() {
        document.getElementById('kategori-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function fetchKategoriList() {
        const search = document.getElementById('el-search').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/kategori?search=${encodeURIComponent(search)}`, {
            method: 'GET',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json' 
            }
        })
        .then(res => {
            if (res.status === 403) throw new Error('Akses Ditolak: Sesi admin tidak valid.');
            return res.json();
        })
        .then(res => {
            document.getElementById('kategori-table-body').innerHTML = res.html;
            document.getElementById('stat-total').innerText = res.stats.total;
            document.getElementById('stat-berpromo').innerText = res.stats.berpromo;
        })
        .catch((err) => showToast(err.message || 'Gagal sinkronisasi data.', 'error'));
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('kategori-form');
        const btn = document.getElementById('btn-submit-kategori');
        const formData = new FormData(form);
        const url = currentEditId ? `/kategori/${currentEditId}` : '/kategori';
        
        btn.disabled = true;
        btn.innerText = "Menyimpan...";

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw data;
            
            showToast(data.message || 'Berhasil mengamankan data kategori!');
            closeKategoriModal();
            fetchKategoriList();
        })
        .catch(err => {
            let errorMsg = err.message || 'Gagal menyimpan perubahan.';
            if (err.errors) errorMsg = Object.values(err.errors)[0][0]; 
            showToast(errorMsg, 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = "Simpan Kategori";
        });
    }

    function deleteKategori(id) {
        if (!confirm('Apakah Anda yakin? Menghapus kategori ini dapat berdampak pada status produk di dalamnya.')) return;

        fetch(`/kategori/${id}`, {
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
            if (!res.ok) throw data;
            showToast(data.message || 'Kategori berhasil dimusnahkan.');
            fetchKategoriList();
        })
        .catch(err => showToast(err.message || 'Gagal menghapus kategori.', 'error'));
    }
</script>
@endsection