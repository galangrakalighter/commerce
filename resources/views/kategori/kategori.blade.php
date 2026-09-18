@extends('app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 antialiased">

    <div class="bg-[#24420A] py-4 px-4 md:px-6 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center space-x-3 w-full sm:w-auto text-white">
                <span class="text-2xl">🗂️</span>
                <div>
                    <h1 class="text-md md:text-lg font-bold uppercase tracking-wide">Kelola Kategori </h1>
                </div>
            </div>
            
            <button onclick="openKategoriModal()" class="w-full sm:w-auto bg-[#F2B705] text-white px-5 py-2 rounded font-bold text-xs md:text-sm hover:bg-opacity-90 transition shrink-0 flex items-center justify-center space-x-2 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Kategori Baru</span>
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-6">
        <div class="flex mb-6 border-b border-gray-200">
            <button
                id="tab-produk"
                onclick="switchTab('produk')"
                class="tab-btn px-5 py-3 font-semibold text-sm border-b-2 border-[#24420A] text-[#24420A]">
                📦 Kategori Produk
            </button>

            <button
                id="tab-artikel"
                onclick="switchTab('artikel')"
                class="tab-btn px-5 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-500 hover:text-[#24420A]">
                📰 Kategori Artikel
            </button>
        </div>
        <div id="content-produk">
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-lg bg-green-100 text-2xl">
                    📦
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Total Kategori
                    </p>

                    <p id="stat-total" class="mt-1 text-2xl font-bold text-[#24420A]">
                        {{ $totalCategories }}
                    </p>
                </div>
            </div>

            <div class="w-full">
                <!-- Filter Live Search Kategori -->
                <div class="bg-[#EAEFD6] p-2 rounded flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-4 text-xs">
                    <div class="flex items-center gap-1.5 sm:gap-2 flex-1">
                        <span class="text-gray-600 font-medium pl-1 sm:pl-2 hidden sm:inline">Filter</span>
                        <!-- ID diubah menjadi kategori-search & tombol terapkan dihapus -->
                        <input type="text" id="kategori-search" placeholder="Cari nama kategori..." class="px-3 py-1.5 rounded border border-gray-200 focus:outline-none flex-1 sm:flex-none sm:w-48 text-gray-700 bg-white">
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-200">
                                    <th class="py-3.5 px-4 w-1/2">Nama Kategori</th>
                                    <th class="py-3.5 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="kategori-table-body" class="divide-y divide-gray-100 font-medium text-gray-700">
                                @include('kategori.table_rows', ['categories' => $categories])
                            </tbody>
                        </table>
                        <div class="p-4 border-t border-gray-200" id="pagination-container">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="content-artikel" class="hidden">
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-lg bg-green-100 text-2xl">
                    📦
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Total Kategori
                    </p>

                    <p id="stat-total" class="mt-1 text-2xl font-bold text-[#24420A]">
                        {{ $totalArticleCategories }}
                    </p>
                </div>
            </div>

            <div class="w-full">
                <!-- Filter Live Search Kategori -->
                <div class="bg-[#EAEFD6] p-2 rounded flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-4 text-xs">
                    <div class="flex items-center gap-1.5 sm:gap-2 flex-1">
                        <span class="text-gray-600 font-medium pl-1 sm:pl-2 hidden sm:inline">Filter</span>
                        <!-- ID diubah menjadi kategori-search & tombol terapkan dihapus -->
                        <input type="text" id="article-search" placeholder="Cari nama kategori..." class="px-3 py-1.5 rounded border border-gray-200 focus:outline-none flex-1 sm:flex-none sm:w-48 text-gray-700 bg-white">
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-200">
                                    <th class="py-3.5 px-4 w-1/2">Nama Kategori</th>
                                    <th class="py-3.5 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="article-table-body" class="divide-y divide-gray-100 font-medium text-gray-700">
                                @include('articles.categori_table_rows', ['articleCategories' => $articleCategories])
                            </tbody>
                        </table>
                        <div class="p-4 border-t border-gray-200" id="pagination-container">
                            {{ $articleCategories->links() }}
                        </div>
                    </div>
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

                <div class="pt-3 border-t border-gray-100 flex justify-end space-x-2">
                    <button type="button" onclick="closeKategoriModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold px-4 py-2 rounded transition">Batalkan</button>
                    <button type="submit" id="btn-submit-kategori" class="bg-[#24420A] hover:bg-opacity-95 text-white font-bold px-5 py-2 rounded shadow-sm transition">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="article-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeArticleModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-lg">
            <div class="bg-[#24420A] px-4 py-3.5 text-white flex items-center justify-between">
                <h3 class="text-sm font-bold uppercase tracking-wider flex items-center">
                    <span>🗂️</span> <span class="ml-2" id="modal-title-article-text">Formulir Article</span>
                </h3>
                <button onclick="closeArticleModal()" class="text-white/70 hover:text-white font-bold text-lg focus:outline-none">&times;</button>
            </div>

            <form id="article-form" onsubmit="handleFormSubmit(event)" class="p-5 space-y-4 text-xs text-gray-700">
                @csrf
                <input type="hidden" id="form-method-article" name="_method" value="POST">
                
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Kategori Artikel *</label>
                    <input type="text" id="input-nama_article" name="name" required placeholder="Contoh: Bumbu Tabur Makaroni, Bubuk Minuman Premium" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-[#24420A]">
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-end space-x-2">
                    <button type="button" onclick="closeArticleModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold px-4 py-2 rounded transition">Batalkan</button>
                    <button type="submit" id="btn-submit-article" class="bg-[#24420A] hover:bg-opacity-95 text-white font-bold px-5 py-2 rounded shadow-sm transition">Simpan Article</button>
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

    function switchTab(tab) {

        const produk = document.getElementById('content-produk');
        const artikel = document.getElementById('content-artikel');

        const btnProduk = document.getElementById('tab-produk');
        const btnArtikel = document.getElementById('tab-artikel');

        if(tab === 'produk'){

            produk.classList.remove('hidden');
            artikel.classList.add('hidden');

            btnProduk.classList.add(
                'border-[#24420A]',
                'text-[#24420A]'
            );

            btnProduk.classList.remove(
                'border-transparent',
                'text-gray-500'
            );

            btnArtikel.classList.remove(
                'border-[#24420A]',
                'text-[#24420A]'
            );

            btnArtikel.classList.add(
                'border-transparent',
                'text-gray-500'
            );

        }else{

            artikel.classList.remove('hidden');
            produk.classList.add('hidden');

            btnArtikel.classList.add(
                'border-[#24420A]',
                'text-[#24420A]'
            );

            btnArtikel.classList.remove(
                'border-transparent',
                'text-gray-500'
            );

            btnProduk.classList.remove(
                'border-[#24420A]',
                'text-[#24420A]'
            );

            btnProduk.classList.add(
                'border-transparent',
                'text-gray-500'
            );
        }
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
        const modalArticle = document.getElementById('article-modal');
        const form = document.getElementById('kategori-form');
        const formArtikel = document.getElementById('article-form');
        const titleText = document.getElementById('modal-title-text');
        const titleTextArticle = document.getElementById('modal-title-article-text');
        const methodInput = document.getElementById('form-method');
        const methodInputArticle = document.getElementById('form-method-article');
        const category = document.getElementById('content-category');
        const produk = document.getElementById('content-produk');

        const adaHidden = produk.classList.contains('hidden');

        if (adaHidden) {
            modalArticle.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            if (data) {
                currentEditId = data.id;
                titleTextArticle.innerText = "Ubah Detail Article";
                methodInputArticle.value = "PUT";
                
                document.getElementById('input-nama_article').value = data.name;
            } else {
                currentEditId = null;
                titleTextArticle.innerText = "Tambah Article Baru";
                methodInputArticle.value = "POST";
                formArtikel.reset();
            }
        } else {
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

    }

    function closeKategoriModal() {
        document.getElementById('kategori-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function closeArticleModal() {
        document.getElementById('article-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function fetchKategoriList() {
        const search = document.getElementById('kategori-search').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/kategori?search-produk=${encodeURIComponent(search)}`, {
            method: 'GET',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json' 
            }
        })
        .then(res => {
            if (res.status === 403) throw new Error('Akses Ditolak: Sesi admin tidak valid.');
            console.log("masuk sini");
            return res.json();
        })
        .then(res => {
            document.getElementById('kategori-table-body').innerHTML = res.html;
            document.getElementById('stat-total').innerText = res.stats.total;
        })
        .catch((err) => showToast(err.message || 'Gagal sinkronisasi data.', 'error'));
    }

    function fetchArticleList() {
        const search = document.getElementById('article-search').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/kategori?search-artikel=${encodeURIComponent(search)}`, {
            method: 'GET',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json' 
            }
        })
        .then(res => {
            if (res.status === 403) throw new Error('Akses Ditolak: Sesi admin tidak valid.');
            // Perbaikan: Langsung return res.json() tanpa memanggilnya dua kali
            return res.json();
        })
        .then(res => {
            // Jika ingin melihat data JSON-nya, log variabel 'res' di sini:
            // console.log(res);

            document.getElementById('article-table-body').innerHTML = res.html;
            document.getElementById('stat-total').innerText = res.stats.total;
        })
        .catch((err) => {
            showToast(err.message || 'Gagal sinkronisasi data.', 'error');
            console.log(err);
        });
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('kategori-form');
        const formArticle = document.getElementById('article-form');
        const btn = document.getElementById('btn-submit-kategori');
        const btnArticle = document.getElementById('btn-submit-article');
        const method = document.getElementById('form-method-article');
        const formData = new FormData(form);
        const formDataArticle = new FormData(formArticle);
        const url = currentEditId ? `/kategori/${currentEditId}` : '/kategori';
        const urlArticle = currentEditId ? `/categories-edit/${currentEditId}` : '/categories';
        const produk = document.getElementById('content-produk');

        const adaHidden = produk.classList.contains('hidden');

        if(adaHidden){
            btnArticle.disabled = true;
            btnArticle.innerText = "Menyimpan...";
            fetch(urlArticle, {
                method: method.value,
                body: formDataArticle,
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw data;
                showToast(data.message || 'Berhasil mengamankan data kategori!');
                closeArticleModal();
                fetchArticleList();
            })
            .catch(err => {
                let errorMsg = err.message || 'Gagal menyimpan perubahan.';
                if (err.errors) errorMsg = Object.values(err.errors)[0][0]; 
                showToast(errorMsg, 'error');
            })
            .finally(() => {
                btnArticle.disabled = false;
                btnArticle.innerText = "Simpan Kategori";
            });
        }else{
            btn.disabled = true;
            btn.innerText = "Menyimpan...";
            console.log(formData);
    
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

    function deleteArticle(id) {
        if (!confirm('Apakah Anda yakin? Menghapus kategori ini dapat berdampak pada article di dalamnya.')) return;

        fetch(`/categories-delete/${id}`, {
            method: 'DELETE',
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
            showToast(data.message || 'Article berhasil dimusnahkan.');
            fetchArticleList();
        })
        .catch(err => showToast(err.message || 'Gagal menghapus article.', 'error'));
    }

    document.addEventListener('DOMContentLoaded', function () {
        const kategoriSearchInput = document.getElementById('kategori-search');
        const kategoriTableBody = document.getElementById('kategori-table-body');
        
        const articleSearchInput = document.getElementById('article-search');
        const articleTableBody = document.getElementById('article-table-body');
        let kategoriDebounceTimer;
        let articleDebounceTimer;
        let kategoriController = null;
        let articleController = null;

        if (kategoriSearchInput && kategoriTableBody) {
            kategoriSearchInput.addEventListener('input', function () {
                clearTimeout(kategoriDebounceTimer);
                
                kategoriDebounceTimer = setTimeout(() => {
                    const keyword = kategoriSearchInput.value.trim();
                    fetchKategoriList(keyword);
                }, 300);
            });
        }

        if (articleSearchInput && articleTableBody) {
            articleSearchInput.addEventListener('input', function () {
                clearTimeout(articleDebounceTimer);
                
                articleDebounceTimer = setTimeout(() => {
                    const keyword = articleSearchInput.value.trim();
                    fetchArticleList(keyword);
                }, 300);
            });
        }

        function fetchArticleList(keyword) {
            if (articleController) {
                articleController.abort();
            }
            articleController = new AbortController();

            articleTableBody.style.opacity = '0.5';

            const url = new URL(window.location.href);
            url.searchParams.set('search-artikel', keyword);

            fetch(url, {
                signal: articleController.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                articleTableBody.innerHTML = data.html;
                articleTableBody.style.opacity = '1';
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Terjadi kesalahan:', error);
                    articleTableBody.style.opacity = '1';
                }
            });
        }

        function fetchKategoriList(keyword) {
            if (kategoriController) {
                kategoriController.abort();
            }
            kategoriController = new AbortController();

            kategoriTableBody.style.opacity = '0.5';

            const url = new URL(window.location.href);
            if (keyword !== '') {
                url.searchParams.set('search-produk', keyword); // Menggunakan parameter khusus kategori agar tidak bentrok jika dalam 1 halaman
            } else {
                url.searchParams.delete('search-produk');
            }

            fetch(url, {
                signal: kategoriController.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                kategoriTableBody.innerHTML = data.html;
                kategoriTableBody.style.opacity = '1';
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Terjadi kesalahan:', error);
                    kategoriTableBody.style.opacity = '1';
                }
            });
        }
    });
</script>
@endsection