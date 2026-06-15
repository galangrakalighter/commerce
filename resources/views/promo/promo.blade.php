@extends('app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 antialiased">

    <div class="bg-[#F2B705] py-4 px-4 md:px-6 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <span class="text-2xl">🏷️</span>
                <div>
                    <h1 class="text-md md:text-lg font-bold text-[#24420A] uppercase tracking-wide">Pusat Kelola Promo</h1>
                    <p class="text-xs text-[#24420A]/80 font-medium">Atur diskon dan penawaran toko GAFI</p>
                </div>
            </div>
            
            <button onclick="openPromoModal()" class="w-full sm:w-auto bg-[#24420A] text-white px-5 py-2 rounded font-semibold text-xs md:text-sm hover:bg-opacity-90 transition shrink-0 flex items-center justify-center space-x-2 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Promo Baru</span>
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-6">
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex items-center space-x-3.5">
                <div class="p-2.5 bg-green-100 text-[#24420A] rounded-md text-xl">🚀</div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase">Promo Berjalan</p>
                    <p id="stat-berjalan" class="text-lg font-extrabold text-gray-800">{{ $promos->where('tgl_akhir', '>=', now()->format('Y-m-d'))->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex items-center space-x-3.5">
                <div class="p-2.5 bg-yellow-100 text-[#F2B705] rounded-md text-xl">⏳</div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase">Menunggu Jadwal</p>
                    <p id="stat-tunggu" class="text-lg font-extrabold text-gray-800">{{ $promos->where('tgl_mulai', '>', now()->format('Y-m-d'))->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex items-center space-x-3.5">
                <div class="p-2.5 bg-gray-100 text-gray-500 rounded-md text-xl">🛑</div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase">Sudah Berakhir</p>
                    <p id="stat-berakhir" class="text-lg font-extrabold text-gray-800">{{ $promos->where('tgl_akhir', '<', now()->format('Y-m-d'))->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex items-center space-x-3.5">
                <div class="p-2.5 bg-red-100 text-red-600 rounded-md text-xl">📉</div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase">Total Items</p>
                    <p id="stat-total" class="text-lg font-extrabold text-gray-800">{{ $promos->count() }}</p>
                </div>
            </div>
        </div>

        <div class="w-full">
            <div class="bg-[#EAEFD6] p-2 rounded flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-4 text-xs">
                <div class="flex items-center gap-1.5 sm:gap-2 flex-1">
                    <span class="text-gray-600 font-medium pl-1 sm:pl-2 hidden sm:inline">Filter</span>
                    <input type="text" id="el-search" placeholder="Cari nama promo..." class="px-3 py-1.5 rounded border border-gray-200 focus:outline-none flex-1 sm:flex-none sm:w-48 text-gray-700 bg-white">
                    <button onclick="fetchPromoList()" class="bg-[#24420A] text-white px-3 py-1.5 rounded font-semibold hover:bg-opacity-90">Terapkan</button>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-200">
                                <th class="py-3.5 px-4 w-1/2">Detail Promo</th>
                                <th class="py-3.5 px-4">Nilai Potongan</th>
                                <th class="py-3.5 px-4">Masa Berlaku</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="promo-table-body" class="divide-y divide-gray-100 font-medium text-gray-700">
                            @include('promo.table_rows', ['promos' => $promos])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="promo-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closePromoModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-lg">
            <div class="bg-[#24420A] px-4 py-3.5 text-white flex items-center justify-between">
                <h3 id="modal-title" class="text-sm font-bold uppercase tracking-wider flex items-center">
                    <span>📝</span> <span class="ml-2" id="modal-title-text">Formulir Pengaturan Promo</span>
                </h3>
                <button onclick="closePromoModal()" class="text-white/70 hover:text-white font-bold text-lg focus:outline-none">&times;</button>
            </div>

            <form id="promo-form" onsubmit="handleFormSubmit(event)" class="p-5 space-y-4 text-xs text-gray-700">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">
                
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Nama Konten Promo / Judul Kampanye *</label>
                    <input type="text" id="input-nama_promo" name="nama_promo" required placeholder="Contoh: Promo Flash Sale Bumbu Balado" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-[#24420A]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Nilai Potongan (%) *</label>
                    <input type="number" id="input-potongan" name="potongan" required placeholder="Contoh masukkan 15000" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-[#24420A]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Tanggal Mulai *</label>
                        <input type="date" id="input-tgl_mulai" name="tgl_mulai" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-[#24420A]">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Tanggal Berakhir *</label>
                        <input type="date" id="input-tgl_akhir" name="tgl_akhir" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-[#24420A]">
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-end space-x-2">
                    <button type="button" onclick="closePromoModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold px-4 py-2 rounded transition">Batalkan</button>
                    <button type="submit" id="btn-submit-promo" class="bg-[#24420A] hover:bg-opacity-95 text-white font-bold px-5 py-2 rounded shadow-sm transition">Simpan Promo</button>
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
            const jsonElement = document.getElementById(`promo-json-${id}`);
            if (!jsonElement) throw new Error("Data penawaran tidak ditemukan.");
            openPromoModal(JSON.parse(jsonElement.textContent));
        } catch (error) {
            showToast(error.message, 'error');
        }
    }

    function openPromoModal(data = null) {
        const modal = document.getElementById('promo-modal');
        const form = document.getElementById('promo-form');
        const titleText = document.getElementById('modal-title-text');
        const methodInput = document.getElementById('form-method');

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        if (data) {
            currentEditId = data.id;
            titleText.innerText = "Ubah Data Promo Sistem";
            methodInput.value = "PUT";
            
            document.getElementById('input-nama_promo').value = data.nama_promo;
            document.getElementById('input-potongan').value = data.potongan;
            document.getElementById('input-tgl_mulai').value = data.tgl_mulai;
            document.getElementById('input-tgl_akhir').value = data.tgl_akhir;
        } else {
            currentEditId = null;
            titleText.innerText = "Buat Promo Baru";
            methodInput.value = "POST";
            form.reset();
        }
    }

    function closePromoModal() {
        document.getElementById('promo-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function fetchPromoList() {
        const search = document.getElementById('el-search').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/promo?search=${encodeURIComponent(search)}`, {
            method: 'GET',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json' 
            }
        })
        .then(res => {
            if (res.status === 403) throw new Error('Akses Ditolak: Silahkan muat ulang halaman.');
            return res.json();
        })
        .then(res => {
            document.getElementById('promo-table-body').innerHTML = res.html;
            document.getElementById('stat-berjalan').innerText = res.stats.berjalan;
            document.getElementById('stat-tunggu').innerText = res.stats.tunggu;
            document.getElementById('stat-berakhir').innerText = res.stats.berakhir;
            document.getElementById('stat-total').innerText = res.stats.total;
        })
        .catch((err) => showToast(err.message || 'Gagal memuat ulang data.', 'error'));
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('promo-form');
        const btn = document.getElementById('btn-submit-promo');
        const formData = new FormData(form);
        const url = currentEditId ? `/promo/${currentEditId}` : '/promo';
        
        btn.disabled = true;
        btn.innerText = "Memproses...";

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
            
            showToast(data.message || 'Data berhasil disimpan!');
            closePromoModal();
            fetchPromoList();
        })
        .catch(err => {
            let errorMsg = err.message || 'Terjadi kesalahan sistem.';
            if (err.errors) errorMsg = Object.values(err.errors)[0][0]; 
            showToast(errorMsg, 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = "Simpan Promo";
        });
    }

    function deletePromo(id) {
        if (!confirm('Hapus promo ini secara permanen?')) return;

        fetch(`/promo/${id}`, {
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
            showToast(data.message || 'Promo berhasil dihapus.');
            fetchPromoList();
        })
        .catch(err => showToast(err.message || 'Gagal menghapus data.', 'error'));
    }
</script>
@endsection