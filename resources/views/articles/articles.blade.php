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
            
            <div id="article-actions" class="w-full sm:w-auto flex flex-col xs:flex-row sm:flex-row gap-2">
                <button onclick="openGenerateModal()" class="w-full sm:w-auto bg-[#F2B705] text-white px-5 py-2 rounded font-bold text-xs md:text-sm hover:bg-opacity-90 transition shrink-0 flex items-center justify-center shadow-md">
                    <span>Generate Berita AI</span>
                </button>

                <button onclick="openArtikelModal()" class="w-full sm:w-auto bg-[#F2B705] text-white px-5 py-2 rounded font-bold text-xs md:text-sm hover:bg-opacity-90 transition shrink-0 flex items-center justify-center shadow-md">
                    <span>Tambah Artikel Baru</span>
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-8">
        <div class="mb-6 flex gap-2 border-b border-gray-200" role="tablist" aria-label="Menu kelola artikel">
            <button id="tab-articles" type="button" onclick="switchArticleTab('articles')" class="border-b-2 border-[#24420A] px-4 py-3 text-sm font-bold text-[#24420A]" role="tab" aria-selected="true">
                Daftar Artikel
            </button>
            <button id="tab-keyword" type="button" onclick="switchArticleTab('keyword')" class="border-b-2 border-transparent px-4 py-3 text-sm font-bold text-gray-500 hover:text-[#24420A]" role="tab" aria-selected="false">
                Kata Kunci Generate per Hari
            </button>
        </div>

        <div id="content-articles" role="tabpanel" aria-labelledby="tab-articles">
        <div class="bg-[#F8F9F5] p-4 rounded-xl border border-[#EAEFD6] flex flex-col sm:flex-row items-center gap-3 mb-6 shadow-sm">
            <div class="relative flex-1 w-full">
                <input
                    type="text"
                    id="artikel-search"
                    placeholder="Cari judul artikel..."
                    class="w-full pl-4 pr-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#24420A] focus:border-transparent outline-none text-sm transition">
            </div>
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

        <div id="content-keyword" class="hidden" role="tabpanel" aria-labelledby="tab-keyword">
            <div class="mb-6 rounded-lg border border-blue-100 bg-blue-50 p-4 text-xs text-blue-800">
                Sistem memakai keyword planning apabila tanggalnya sama dengan hari berjalan. Jika tidak ada planning untuk hari tersebut, sistem otomatis memakai keyword biasa.
            </div>

            <div class="mb-8">
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Keyword Biasa</h2>
                        <p class="mt-1 text-xs text-gray-500">Konfigurasi utama yang digunakan sehari-hari sebagai fallback.</p>
                    </div>
                    <button id="add-keyword-button" type="button" onclick="openKeywordModal(null, 'regular')" class="{{ $generateKeyword ? 'hidden ' : '' }}w-full rounded bg-[#F2B705] px-5 py-2 text-xs font-bold text-white shadow-md transition hover:bg-opacity-90 sm:w-auto">Tambah Keyword Biasa</button>
                </div>
                <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead><tr class="border-b border-gray-100 bg-gray-50/50 text-[11px] font-bold uppercase tracking-widest text-gray-400"><th class="px-5 py-4">Keyword</th><th class="px-5 py-4">Prompt Artikel</th><th class="px-5 py-4">Prompt Gambar</th><th class="px-5 py-4 text-center">Aksi</th></tr></thead>
                            <tbody id="keyword-table-body">
                                @if($generateKeyword)
                                    <tr id="keyword-row" class="align-top">
                                        <td id="keyword-value" class="px-5 py-4 text-sm font-medium text-gray-700">{{ $generateKeyword->keyword }}</td>
                                        <td class="max-w-xs px-5 py-4 text-xs text-gray-600"><p class="whitespace-pre-wrap">{{ Str::limit($generateKeyword->article_prompt ?: '-', 120) }}</p>@if(Str::length($generateKeyword->article_prompt ?? '') > 120)<button type="button" onclick="openPromptDetail('Prompt Artikel', currentKeyword.article_prompt)" class="mt-2 font-bold text-[#24420A] hover:underline">Selengkapnya</button>@endif</td>
                                        <td class="max-w-xs px-5 py-4 text-xs text-gray-600"><p class="whitespace-pre-wrap">{{ Str::limit($generateKeyword->image_prompt ?: '-', 120) }}</p>@if(Str::length($generateKeyword->image_prompt ?? '') > 120)<button type="button" onclick="openPromptDetail('Prompt Gambar Artikel', currentKeyword.image_prompt)" class="mt-2 font-bold text-[#24420A] hover:underline">Selengkapnya</button>@endif</td>
                                        <td class="px-5 py-4 text-center"><div class="flex justify-center gap-2"><button type="button" onclick="openKeywordModal(currentKeyword, 'regular')" class="rounded bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100">Edit</button><button type="button" onclick="deleteKeyword()" class="rounded bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 hover:bg-red-100">Hapus</button></div></td>
                                    </tr>
                                @else
                                    <tr><td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">Belum ada keyword biasa.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div>
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Keyword Planning</h2>
                        <p class="mt-1 text-xs text-gray-500">Konfigurasi khusus berdasarkan tanggal, maksimal satu planning per tanggal.</p>
                    </div>
                    <button type="button" onclick="openKeywordModal(null, 'planning')" class="w-full rounded bg-[#24420A] px-5 py-2 text-xs font-bold text-white shadow-md transition hover:bg-[#355e0e] sm:w-auto">Tambah Keyword Planning</button>
                </div>
                <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead><tr class="border-b border-gray-100 bg-gray-50/50 text-[11px] font-bold uppercase tracking-widest text-gray-400"><th class="px-5 py-4">Tanggal</th><th class="px-5 py-4">Keyword</th><th class="px-5 py-4">Prompt Artikel</th><th class="px-5 py-4">Prompt Gambar</th><th class="px-5 py-4 text-center">Aksi</th></tr></thead>
                        <tbody id="keyword-plan-table-body">
                            @forelse($generateKeywordPlans as $plan)
                                <tr class="align-top border-t border-gray-50" data-plan-id="{{ $plan->id }}"><td class="whitespace-nowrap px-5 py-4 text-xs font-bold text-gray-700">{{ $plan->planned_date->format('d-m-Y') }}</td><td class="px-5 py-4 text-sm font-medium text-gray-700">{{ $plan->keyword }}</td><td class="max-w-xs px-5 py-4 text-xs text-gray-600"><p class="whitespace-pre-wrap">{{ Str::limit($plan->article_prompt, 120) }}</p>@if(Str::length($plan->article_prompt) > 120)<button type="button" onclick="openPlanPromptDetail({{ $plan->id }}, 'article_prompt')" class="mt-2 font-bold text-[#24420A] hover:underline">Selengkapnya</button>@endif</td><td class="max-w-xs px-5 py-4 text-xs text-gray-600"><p class="whitespace-pre-wrap">{{ Str::limit($plan->image_prompt, 120) }}</p>@if(Str::length($plan->image_prompt) > 120)<button type="button" onclick="openPlanPromptDetail({{ $plan->id }}, 'image_prompt')" class="mt-2 font-bold text-[#24420A] hover:underline">Selengkapnya</button>@endif</td><td class="px-5 py-4 text-center"><div class="flex justify-center gap-2"><button type="button" onclick="editKeywordPlan({{ $plan->id }})" class="rounded bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100">Edit</button><button type="button" onclick="deleteKeywordPlan({{ $plan->id }})" class="rounded bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 hover:bg-red-100">Hapus</button></div></td></tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">Belum ada keyword planning.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<div id="keyword-modal" class="fixed inset-0 z-[70] hidden overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="keyword-modal-title">
    <div class="fixed inset-0 bg-black bg-opacity-60" onclick="closeKeywordModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl overflow-hidden rounded-lg bg-white text-xs shadow-2xl">
            <div class="flex items-center justify-between bg-[#24420A] px-4 py-3.5 text-white">
                <h3 id="keyword-modal-title" class="text-sm font-bold uppercase tracking-wider">Tambah Kata Kunci</h3>
                <button type="button" onclick="closeKeywordModal()" class="text-lg font-bold hover:text-gray-200" aria-label="Tutup modal">&times;</button>
            </div>
            <form id="keyword-form" onsubmit="handleKeywordSubmit(event)" class="space-y-4 p-5 text-gray-700">
                <div id="keyword-date-field" class="hidden">
                    <label for="keyword-date-input" class="mb-1.5 block font-bold">Tanggal Planning *</label>
                    <input id="keyword-date-input" name="planned_date" type="date" class="w-full rounded border px-3 py-2.5 outline-none focus:border-transparent focus:ring-2 focus:ring-[#24420A]">
                </div>
                <div>
                    <label for="keyword-input" class="mb-1.5 block font-bold">Kata Kunci *</label>
                    <input id="keyword-input" name="keyword" type="text" required maxlength="255" autocomplete="off" placeholder="Contoh: tren rempah Indonesia" class="w-full rounded border px-3 py-2.5 outline-none focus:border-transparent focus:ring-2 focus:ring-[#24420A]">
                </div>
                <div>
                    <label for="keyword-article-prompt-input" class="mb-1.5 block font-bold">Prompt Artikel *</label>
                    <textarea id="keyword-article-prompt-input" name="article_prompt" required rows="5" placeholder="Instruksi untuk membuat isi artikel..." class="w-full rounded border px-3 py-2.5 outline-none focus:border-transparent focus:ring-2 focus:ring-[#24420A]"></textarea>
                </div>
                <div>
                    <label for="keyword-image-prompt-input" class="mb-1.5 block font-bold">Prompt Gambar Artikel *</label>
                    <textarea id="keyword-image-prompt-input" name="image_prompt" required rows="4" placeholder="Instruksi untuk membuat gambar artikel..." class="w-full rounded border px-3 py-2.5 outline-none focus:border-transparent focus:ring-2 focus:ring-[#24420A]"></textarea>
                </div>
                <div class="flex justify-end gap-2 border-t pt-3">
                    <button type="button" onclick="closeKeywordModal()" class="rounded bg-gray-100 px-4 py-2 font-bold hover:bg-gray-200">Batal</button>
                    <button type="submit" class="rounded bg-[#24420A] px-5 py-2 font-bold text-white shadow-sm hover:bg-[#355e0e]">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="prompt-detail-modal" class="fixed inset-0 z-[80] hidden overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="prompt-detail-title">
    <div class="fixed inset-0 bg-black bg-opacity-60" onclick="closePromptDetail()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl overflow-hidden rounded-lg bg-white shadow-2xl">
            <div class="flex items-center justify-between bg-[#24420A] px-5 py-4 text-white">
                <h3 id="prompt-detail-title" class="text-sm font-bold uppercase tracking-wider">Detail Prompt</h3>
                <button type="button" onclick="closePromptDetail()" class="text-lg font-bold hover:text-gray-200" aria-label="Tutup modal">&times;</button>
            </div>
            <div class="max-h-[70vh] overflow-y-auto p-5">
                <p id="prompt-detail-content" class="whitespace-pre-wrap break-words text-sm leading-6 text-gray-700"></p>
            </div>
            <div class="flex justify-end border-t px-5 py-3">
                <button type="button" onclick="closePromptDetail()" class="rounded bg-[#24420A] px-5 py-2 text-xs font-bold text-white hover:bg-[#355e0e]">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div id="generate-modal" class="fixed inset-0 z-[70] overflow-y-auto hidden" role="dialog" aria-modal="true" aria-labelledby="generate-modal-title">
    <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity" onclick="closeGenerateModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-md text-xs overflow-hidden">
            <div class="bg-[#24420A] px-4 py-3.5 text-white flex items-center justify-between">
                <div>
                    <h3 id="generate-modal-title" class="text-sm font-bold uppercase tracking-wider">Generate Berita AI</h3>
                    <p class="mt-0.5 text-[11px] text-white/75">Kirim permintaan pembuatan berita ke n8n</p>
                </div>
                <button type="button" onclick="closeGenerateModal()" class="text-white text-lg font-bold hover:text-gray-200" aria-label="Tutup modal">&times;</button>
            </div>

            <form id="generate-form" onsubmit="handleGenerateSubmit(event)" class="p-5 space-y-4 text-gray-700">
                <div>
                    <label for="generate-topic" class="block font-bold mb-1.5">Nama Topik *</label>
                    <input
                        type="text"
                        id="generate-topic"
                        name="topic"
                        required
                        minlength="3"
                        maxlength="150"
                        autocomplete="off"
                        placeholder="Contoh: Berita terbaru tentang bumbu dan rempah"
                        class="w-full px-3 py-2.5 border rounded focus:ring-2 focus:ring-[#24420A] focus:border-transparent outline-none">
                    <p class="mt-1 text-[10px] text-gray-400">Masukkan topik yang spesifik agar hasil berita lebih relevan.</p>
                </div>

                <div>
                    <label for="generate-count" class="block font-bold mb-1.5">Jumlah Berita *</label>
                    <input
                        type="number"
                        id="generate-count"
                        name="count"
                        required
                        min="1"
                        max="20"
                        value="5"
                        inputmode="numeric"
                        class="w-full px-3 py-2.5 border rounded focus:ring-2 focus:ring-[#24420A] focus:border-transparent outline-none">
                    <p class="mt-1 text-[10px] text-gray-400">Minimal 1 dan maksimal 20 berita per permintaan.</p>
                </div>

                <div id="generate-error" class="hidden rounded border border-red-200 bg-red-50 px-3 py-2 text-red-700" role="alert"></div>
                <div
                    id="generate-progress"
                    class="hidden rounded-lg border border-green-200 bg-green-50 p-3"
                >
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <span
                            id="generate-progress-message"
                            class="font-bold text-green-800"
                        >
                            Menyiapkan proses...
                        </span>

                        <span
                            id="generate-progress-count"
                            class="shrink-0 font-bold text-green-700"
                        >
                            0/0
                        </span>
                    </div>

                    <div class="h-2 overflow-hidden rounded-full bg-green-100">
                        <div
                            id="generate-progress-bar"
                            class="h-full bg-green-600 transition-all duration-300"
                            style="width: 0%"
                        ></div>
                    </div>

                    <div
                        id="generate-progress-list"
                        class="mt-3 max-h-40 space-y-1 overflow-y-auto"
                    ></div>
                </div>

                <div class="pt-3 border-t flex justify-end gap-2">
                    <button type="button" onclick="closeGenerateModal()" class="bg-gray-100 px-4 py-2 rounded font-bold hover:bg-gray-200">Batal</button>
                    <button id="generate-submit-button" type="submit" class="bg-[#24420A] text-white px-5 py-2 rounded font-bold shadow-sm hover:bg-[#355e0e] disabled:opacity-60 disabled:cursor-not-allowed">
                        <span id="generate-submit-text">Generate Berita</span>
                    </button>
                </div>
            </form>
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
                        
                        <div id="image-preview-container" class="mb-2 hidden">
                            <p class="text-[10px] text-gray-500 italic">Gambar saat ini:</p>
                            <img id="current-image" src="" class="w-20 h-20 object-cover rounded shadow-sm border">
                        </div>

                        <input type="file" id="input-image" name="image" accept="image/*" class="w-full px-2 py-1.5 border rounded">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-bold mb-1.5 text-[#24420A]">Isi Konten *</label>
                        
                        <textarea id="editor" name="content" class="w-full"></textarea>
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
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
    const N8N_GENERATE_WEBHOOK_URL =
        'https://bmglbl3.n8n.bocindonesia.com/webhook/generate-berita';

    let generateProgressTimer = null;
    let generationTimeoutTimer = null;
    let generationIsRunning = false;
    let generatePollIsRunning = false;
    let generatedArticleCount = 0;
    let requestedGenerateCount = 0;
    let initialArticleKeys = new Set();
    let detectedArticleKeys = new Set();

    window.showToast = function (message, type = 'success') {
        const container =
            document.getElementById('toast-container');

        if (!container) {
            console.warn(
                'Elemen #toast-container tidak ditemukan.'
            );
            return;
        }

        const toast =
            document.createElement('div');

        const messageElement =
            document.createElement('span');

        let backgroundClass = 'bg-green-600';

        if (type === 'error') {
            backgroundClass = 'bg-red-600';
        } else if (type === 'warning') {
            backgroundClass = 'bg-yellow-500';
        }

        toast.className = [
            'pointer-events-auto',
            'flex',
            'items-center',
            'gap-2',
            'max-w-md',
            'rounded',
            'px-4',
            'py-3',
            'text-xs',
            'font-semibold',
            'text-white',
            'shadow-lg',
            'transition-all',
            'duration-300',
            backgroundClass
        ].join(' ');

        messageElement.textContent =
            String(message || 'Proses berhasil.');

        toast.appendChild(messageElement);
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform =
                'translateX(20px)';

            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    };

    function wait(milliseconds) {
        return new Promise(resolve => {
            setTimeout(resolve, milliseconds);
        });
    }

    function normalizeText(value) {
        return String(value || '')
            .replace(/\s+/g, ' ')
            .trim()
            .toLowerCase();
    }

    function getRowInformation(row, index = 0) {
        const articleId =
            row.dataset.articleId ||
            row.dataset.id ||
            row.querySelector('[data-article-id]')
                ?.dataset.articleId ||
            '';

        const firstCell =
            row.querySelector('td');

        const titleElement =
            row.querySelector(
                '[data-article-title], ' +
                '.article-title, ' +
                'h2, h3, h4'
            );

        let title =
            titleElement?.textContent?.trim() ||
            firstCell?.textContent?.trim() ||
            `Artikel ${index + 1}`;

        title = title
            .replace(/\s+/g, ' ')
            .trim();

        const normalizedRow =
            normalizeText(row.textContent);

        const key = articleId
            ? `id:${articleId}`
            : `row:${normalizedRow}`;

        return {
            key,
            title
        };
    }

    function extractRowsFromElement(container) {
        if (!container) {
            return [];
        }

        return Array.from(
            container.querySelectorAll('tr')
        )
            .filter(row => row.querySelector('td'))
            .map((row, index) =>
                getRowInformation(row, index)
            );
    }

    function extractRowsFromHtml(html) {
        const temporaryBody =
            document.createElement('tbody');

        temporaryBody.innerHTML =
            String(html || '');

        return extractRowsFromElement(
            temporaryBody
        );
    }

    function captureInitialArticles() {
        const tableBody =
            document.getElementById(
                'artikel-table-body'
            );

        const rows =
            extractRowsFromElement(tableBody);

        initialArticleKeys = new Set(
            rows.map(row => row.key)
        );

        detectedArticleKeys = new Set();
        generatedArticleCount = 0;
    }

    function updateGenerateProgress(
        message = null
    ) {
        const progressMessage =
            document.getElementById(
                'generate-progress-message'
            );

        const progressCount =
            document.getElementById(
                'generate-progress-count'
            );

        const progressBar =
            document.getElementById(
                'generate-progress-bar'
            );

        const percentage =
            requestedGenerateCount > 0
                ? Math.min(
                    100,
                    Math.round(
                        (
                            generatedArticleCount /
                            requestedGenerateCount
                        ) * 100
                    )
                )
                : 0;

        if (progressCount) {
            progressCount.textContent =
                `${generatedArticleCount}/${requestedGenerateCount}`;
        }

        if (progressBar) {
            progressBar.style.width =
                `${percentage}%`;
        }

        if (
            message &&
            progressMessage
        ) {
            progressMessage.textContent =
                message;
        }
    }

    function resetGenerateProgress(total) {
        const progressBox =
            document.getElementById(
                'generate-progress'
            );

        const progressMessage =
            document.getElementById(
                'generate-progress-message'
            );

        const progressCount =
            document.getElementById(
                'generate-progress-count'
            );

        const progressBar =
            document.getElementById(
                'generate-progress-bar'
            );

        const progressList =
            document.getElementById(
                'generate-progress-list'
            );

        if (progressBox) {
            progressBox.classList.remove(
                'hidden'
            );
        }

        if (progressMessage) {
            progressMessage.textContent =
                'Menyiapkan proses generate...';
        }

        if (progressCount) {
            progressCount.textContent =
                `0/${total}`;
        }

        if (progressBar) {
            progressBar.style.width = '0%';
        }

        if (progressList) {
            progressList.innerHTML = '';
        }
    }

    function addGenerateProgressItem(
        message,
        type = 'success'
    ) {
        const progressList =
            document.getElementById(
                'generate-progress-list'
            );

        if (!progressList) {
            return;
        }

        const item =
            document.createElement('div');

        if (type === 'error') {
            item.className =
                'rounded border border-red-200 ' +
                'bg-white px-2 py-1.5 text-[11px] ' +
                'text-red-700';
        } else if (type === 'warning') {
            item.className =
                'rounded border border-yellow-200 ' +
                'bg-white px-2 py-1.5 text-[11px] ' +
                'text-yellow-700';
        } else {
            item.className =
                'rounded border border-green-200 ' +
                'bg-white px-2 py-1.5 text-[11px] ' +
                'text-green-800';
        }

        item.textContent = message;
        progressList.prepend(item);
    }

    async function fetchLatestArticleTable() {
        const url = new URL(
            '/articles',
            window.location.origin
        );

        url.searchParams.set(
            '_generate_check',
            Date.now().toString()
        );

        const response = await fetch(
            url.toString(),
            {
                method: 'GET',
                headers: {
                    'X-Requested-With':
                        'XMLHttpRequest',
                    'Accept':
                        'application/json',
                    'Cache-Control':
                        'no-cache'
                },
                cache: 'no-store'
            }
        );

        if (!response.ok) {
            throw new Error(
                `Gagal memuat artikel: ${response.status}`
            );
        }

        const data =
            await response.json();

        if (
            !data ||
            typeof data.html !== 'string'
        ) {
            throw new Error(
                'Respons daftar artikel tidak memiliki field html.'
            );
        }

        return data.html;
    }

    async function checkGeneratedArticles() {
        if (
            !generationIsRunning ||
            generatePollIsRunning
        ) {
            return;
        }

        generatePollIsRunning = true;

        try {
            const html =
                await fetchLatestArticleTable();

            const currentRows =
                extractRowsFromHtml(html);

            const newRows =
                currentRows.filter(row => {
                    return (
                        !initialArticleKeys.has(row.key) &&
                        !detectedArticleKeys.has(row.key)
                    );
                });

            for (const row of newRows) {
                if (
                    generatedArticleCount >=
                    requestedGenerateCount
                ) {
                    break;
                }

                detectedArticleKeys.add(
                    row.key
                );

                generatedArticleCount++;

                const message =
                    `Berita ${generatedArticleCount} ` +
                    `berhasil dibuat: ${row.title}`;

                addGenerateProgressItem(
                    message,
                    'success'
                );

                showToast(
                    message,
                    'success'
                );

                updateGenerateProgress(
                    `Berhasil membuat ` +
                    `${generatedArticleCount} dari ` +
                    `${requestedGenerateCount} berita.`
                );
            }

            const tableBody =
                document.getElementById(
                    'artikel-table-body'
                );

            if (tableBody) {
                tableBody.innerHTML = html;
                tableBody.style.opacity = '1';
            }

            if (
                generatedArticleCount >=
                requestedGenerateCount
            ) {
                finishGenerateProcess(
                    `Semua ${requestedGenerateCount} ` +
                    `berita berhasil dibuat.`
                );
            }
        } catch (error) {
            console.error(
                'Gagal memantau artikel:',
                error
            );
        } finally {
            generatePollIsRunning = false;
        }
    }

    function startGenerateProgressPolling() {
        stopGenerateProgressPolling();

        checkGeneratedArticles();

        generateProgressTimer =
            setInterval(
                checkGeneratedArticles,
                2500
            );

        generationTimeoutTimer =
            setTimeout(() => {
                if (!generationIsRunning) {
                    return;
                }

                stopGenerateProgressPolling();
                generationIsRunning = false;

                const submitButton =
                    document.getElementById(
                        'generate-submit-button'
                    );

                const submitText =
                    document.getElementById(
                        'generate-submit-text'
                    );

                if (submitButton) {
                    submitButton.disabled = false;
                }

                if (submitText) {
                    submitText.textContent =
                        'Generate Berita';
                }

                const remaining = Math.max(
                    0,
                    requestedGenerateCount -
                    generatedArticleCount
                );

                let message;

                if (
                    generatedArticleCount > 0 &&
                    remaining > 0
                ) {
                    message =
                        `${generatedArticleCount} dari ` +
                        `${requestedGenerateCount} berita ` +
                        `berhasil dibuat. ${remaining} berita ` +
                        `lainnya belum sempat dibuat atau ` +
                        `belum dapat dikonfirmasi.`;
                } else if (
                    generatedArticleCount > 0
                ) {
                    message =
                        `${generatedArticleCount} berita ` +
                        `berhasil dibuat.`;
                } else {
                    message =
                        'Waktu pemantauan habis dan belum ada ' +
                        'artikel baru yang terdeteksi.';
                }

                const progressMessage =
                    document.getElementById(
                        'generate-progress-message'
                    );

                if (progressMessage) {
                    progressMessage.textContent =
                        message;
                }

                addGenerateProgressItem(
                    message,
                    generatedArticleCount > 0
                        ? 'warning'
                        : 'error'
                );

                showToast(
                    message,
                    generatedArticleCount > 0
                        ? 'warning'
                        : 'error'
                );
            }, 15 * 60 * 1000);
    }

    function stopGenerateProgressPolling() {
        if (generateProgressTimer) {
            clearInterval(
                generateProgressTimer
            );

            generateProgressTimer = null;
        }

        if (generationTimeoutTimer) {
            clearTimeout(
                generationTimeoutTimer
            );

            generationTimeoutTimer = null;
        }
    }

    function finishGenerateProcess(message) {
        if (!generationIsRunning) {
            return;
        }

        generationIsRunning = false;
        stopGenerateProgressPolling();

        const submitButton =
            document.getElementById(
                'generate-submit-button'
            );

        const submitText =
            document.getElementById(
                'generate-submit-text'
            );

        const progressMessage =
            document.getElementById(
                'generate-progress-message'
            );

        const progressBar =
            document.getElementById(
                'generate-progress-bar'
            );

        if (submitButton) {
            submitButton.disabled = false;
        }

        if (submitText) {
            submitText.textContent =
                'Generate Berita';
        }

        if (progressMessage) {
            progressMessage.textContent =
                message;
        }

        if (progressBar) {
            progressBar.style.width = '100%';
        }

        showToast(
            message,
            'success'
        );
    }

    function failGenerateProcess(message) {
        generationIsRunning = false;
        stopGenerateProgressPolling();

        const submitButton =
            document.getElementById(
                'generate-submit-button'
            );

        const submitText =
            document.getElementById(
                'generate-submit-text'
            );

        const errorBox =
            document.getElementById(
                'generate-error'
            );

        if (submitButton) {
            submitButton.disabled = false;
        }

        if (submitText) {
            submitText.textContent =
                'Generate Berita';
        }

        if (errorBox) {
            errorBox.textContent = message;
            errorBox.classList.remove(
                'hidden'
            );
        }

        addGenerateProgressItem(
            message,
            'error'
        );

        showToast(
            message,
            'error'
        );
    }

    async function handleWebhookFetchFailure(
        error
    ) {
        const progressMessage =
            document.getElementById(
                'generate-progress-message'
            );

        const submitButton =
            document.getElementById(
                'generate-submit-button'
            );

        const submitText =
            document.getElementById(
                'generate-submit-text'
            );

        if (progressMessage) {
            progressMessage.textContent =
                'Koneksi webhook terputus. ' +
                'Memeriksa artikel yang sudah berhasil dibuat...';
        }

        showToast(
            'Koneksi webhook terputus. ' +
            'Sedang memeriksa hasil yang sudah tersimpan.',
            'warning'
        );

        /*
         * Polling tidak langsung dihentikan.
         * Beri waktu agar artikel terakhir selesai disimpan.
         */
        await wait(3000);
        await checkGeneratedArticles();

        /*
         * checkGeneratedArticles dapat menyelesaikan proses
         * apabila semua artikel sudah terdeteksi.
         */
        if (!generationIsRunning) {
            return;
        }

        await wait(2000);
        await checkGeneratedArticles();

        if (!generationIsRunning) {
            return;
        }

        stopGenerateProgressPolling();
        generationIsRunning = false;

        if (submitButton) {
            submitButton.disabled = false;
        }

        if (submitText) {
            submitText.textContent =
                'Generate Berita';
        }

        const successful =
            generatedArticleCount;

        const remaining = Math.max(
            0,
            requestedGenerateCount -
            successful
        );

        let message;
        let messageType;

        if (
            successful > 0 &&
            remaining > 0
        ) {
            message =
                `${successful} dari ` +
                `${requestedGenerateCount} berita berhasil dibuat. ` +
                `${remaining} berita lainnya tidak sempat dibuat ` +
                `atau belum dapat dikonfirmasi karena koneksi terputus.`;

            messageType = 'warning';
        } else if (
            successful > 0 &&
            remaining === 0
        ) {
            message =
                `Semua ${successful} berita berhasil dibuat, ` +
                `tetapi respons akhir webhook tidak diterima.`;

            messageType = 'warning';
        } else {
            message =
                'Respons webhook terputus dan belum ada artikel ' +
                'baru yang berhasil terdeteksi. Proses n8n mungkin ' +
                'masih berjalan.';

            messageType = 'error';
        }

        if (progressMessage) {
            progressMessage.textContent =
                message;
        }

        updateGenerateProgress();

        addGenerateProgressItem(
            message,
            messageType
        );

        showToast(
            message,
            messageType
        );

        console.warn(
            'Webhook fetch gagal:',
            error
        );
    }

    function openGenerateModal() {
        const modal =
            document.getElementById(
                'generate-modal'
            );

        const form =
            document.getElementById(
                'generate-form'
            );

        const errorBox =
            document.getElementById(
                'generate-error'
            );

        if (!generationIsRunning) {
            if (form) {
                form.reset();
            }

            const countInput =
                document.getElementById(
                    'generate-count'
                );

            if (countInput) {
                countInput.value = 5;
            }

            if (errorBox) {
                errorBox.textContent = '';
                errorBox.classList.add(
                    'hidden'
                );
            }

            const progressBox =
                document.getElementById(
                    'generate-progress'
                );

            const progressMessage =
                document.getElementById(
                    'generate-progress-message'
                );

            const progressCount =
                document.getElementById(
                    'generate-progress-count'
                );

            const progressBar =
                document.getElementById(
                    'generate-progress-bar'
                );

            const progressList =
                document.getElementById(
                    'generate-progress-list'
                );

            const submitButton =
                document.getElementById(
                    'generate-submit-button'
                );

            const submitText =
                document.getElementById(
                    'generate-submit-text'
                );

            if (progressBox) {
                progressBox.classList.add(
                    'hidden'
                );
            }

            if (progressMessage) {
                progressMessage.textContent =
                    'Menyiapkan proses...';
            }

            if (progressCount) {
                progressCount.textContent =
                    '0/0';
            }

            if (progressBar) {
                progressBar.style.width =
                    '0%';
            }

            if (progressList) {
                progressList.innerHTML = '';
            }

            if (submitButton) {
                submitButton.disabled = false;
            }

            if (submitText) {
                submitText.textContent =
                    'Generate Berita';
            }
        }

        if (modal) {
            modal.classList.remove(
                'hidden'
            );
        }

        setTimeout(() => {
            const topicInput =
                document.getElementById(
                    'generate-topic'
                );

            if (topicInput) {
                topicInput.focus();
            }
        }, 50);
    }

    function closeGenerateModal() {
        if (generationIsRunning) {
            showToast(
                'Proses generate masih berjalan.',
                'warning'
            );

            return;
        }

        const modal =
            document.getElementById(
                'generate-modal'
            );

        if (modal) {
            modal.classList.add('hidden');
        }
    }

    async function handleGenerateSubmit(event) {
        event.preventDefault();

        if (generationIsRunning) {
            return;
        }

        const topicInput =
            document.getElementById(
                'generate-topic'
            );

        const countInput =
            document.getElementById(
                'generate-count'
            );

        const submitButton =
            document.getElementById(
                'generate-submit-button'
            );

        const submitText =
            document.getElementById(
                'generate-submit-text'
            );

        const errorBox =
            document.getElementById(
                'generate-error'
            );

        const topic =
            topicInput?.value?.trim() || '';

        const count =
            Number.parseInt(
                countInput?.value || '',
                10
            );

        if (errorBox) {
            errorBox.textContent = '';
            errorBox.classList.add(
                'hidden'
            );
        }

        if (topic.length < 3) {
            if (errorBox) {
                errorBox.textContent =
                    'Nama topik minimal 3 karakter.';

                errorBox.classList.remove(
                    'hidden'
                );
            }

            topicInput?.focus();
            return;
        }

        if (
            !Number.isInteger(count) ||
            count < 1 ||
            count > 20
        ) {
            if (errorBox) {
                errorBox.textContent =
                    'Jumlah berita harus antara 1 sampai 20.';

                errorBox.classList.remove(
                    'hidden'
                );
            }

            countInput?.focus();
            return;
        }

        if (
            !N8N_GENERATE_WEBHOOK_URL
                .startsWith('http')
        ) {
            if (errorBox) {
                errorBox.textContent =
                    'URL webhook n8n belum dikonfigurasi.';

                errorBox.classList.remove(
                    'hidden'
                );
            }

            return;
        }

        requestedGenerateCount = count;
        generationIsRunning = true;

        captureInitialArticles();
        resetGenerateProgress(count);

        if (submitButton) {
            submitButton.disabled = true;
        }

        if (submitText) {
            submitText.textContent =
                'Sedang memproses...';
        }

        startGenerateProgressPolling();

        try {
            const response = await fetch(
                N8N_GENERATE_WEBHOOK_URL,
                {
                    method: 'POST',
                    headers: {
                        'Content-Type':
                            'application/json',
                        'Accept':
                            'application/json'
                    },
                    body: JSON.stringify({
                        topic,
                        count,
                        requested_at:
                            new Date()
                                .toISOString(),
                        source:
                            'article-admin'
                    })
                }
            );

            const responseText =
                await response.text();

            let responseData = {};

            if (responseText) {
                try {
                    responseData =
                        JSON.parse(
                            responseText
                        );
                } catch (_) {
                    responseData = {
                        message:
                            responseText
                    };
                }
            }

            if (!response.ok) {
                throw new Error(
                    responseData.message ||
                    `Webhook merespons dengan status ` +
                    `${response.status}.`
                );
            }

            await checkGeneratedArticles();

            if (
                generatedArticleCount >=
                requestedGenerateCount
            ) {
                return;
            }

            const completedFromResponse =
                Number(
                    responseData.completed ??
                    responseData.success_count ??
                    0
                );

            if (
                Number.isInteger(
                    completedFromResponse
                ) &&
                completedFromResponse >
                    generatedArticleCount
            ) {
                generatedArticleCount =
                    Math.min(
                        completedFromResponse,
                        requestedGenerateCount
                    );

                updateGenerateProgress(
                    responseData.message ||
                    `${generatedArticleCount} berita ` +
                    `berhasil dibuat.`
                );
            }

            if (
                responseData.status ===
                    'completed' ||
                responseData.status ===
                    'success'
            ) {
                const remaining = Math.max(
                    0,
                    requestedGenerateCount -
                    generatedArticleCount
                );

                let finalMessage;

                if (
                    generatedArticleCount >=
                    requestedGenerateCount
                ) {
                    finalMessage =
                        responseData.message ||
                        `Semua ${requestedGenerateCount} ` +
                        `berita berhasil dibuat.`;
                } else if (
                    generatedArticleCount > 0
                ) {
                    finalMessage =
                        responseData.message ||
                        `${generatedArticleCount} dari ` +
                        `${requestedGenerateCount} berita ` +
                        `berhasil dibuat. ${remaining} berita ` +
                        `lainnya tidak dibuat atau tidak terdeteksi.`;
                } else {
                    finalMessage =
                        responseData.message ||
                        'Workflow selesai, tetapi belum ada ' +
                        'artikel baru yang terdeteksi.';
                }

                finishGenerateProcess(
                    finalMessage
                );

                return;
            }

            const progressMessage =
                document.getElementById(
                    'generate-progress-message'
                );

            if (progressMessage) {
                progressMessage.textContent =
                    responseData.message ||
                    'Workflow telah merespons. ' +
                    'Menunggu artikel baru...';
            }
        } catch (error) {
            console.error(
                'Gagal mengirim ke webhook n8n:',
                error
            );

            const errorMessage =
                String(
                    error?.message || ''
                );

            const isFetchError =
                error instanceof TypeError ||
                /failed to fetch/i.test(
                    errorMessage
                ) ||
                /networkerror/i.test(
                    errorMessage
                ) ||
                /network request failed/i.test(
                    errorMessage
                ) ||
                /load failed/i.test(
                    errorMessage
                );

            if (isFetchError) {
                await handleWebhookFetchFailure(
                    error
                );

                return;
            }

            /*
             * Error HTTP juga tetap memeriksa artikel yang
             * mungkin sudah berhasil disimpan sebelum error.
             */
            await wait(2000);
            await checkGeneratedArticles();

            if (!generationIsRunning) {
                return;
            }

            if (generatedArticleCount > 0) {
                const remaining = Math.max(
                    0,
                    requestedGenerateCount -
                    generatedArticleCount
                );

                const message =
                    `${generatedArticleCount} dari ` +
                    `${requestedGenerateCount} berita berhasil dibuat. ` +
                    `${remaining} berita lainnya tidak sempat dibuat ` +
                    `atau belum dapat dikonfirmasi.`;

                stopGenerateProgressPolling();
                generationIsRunning = false;

                if (submitButton) {
                    submitButton.disabled =
                        false;
                }

                if (submitText) {
                    submitText.textContent =
                        'Generate Berita';
                }

                const progressMessage =
                    document.getElementById(
                        'generate-progress-message'
                    );

                if (progressMessage) {
                    progressMessage.textContent =
                        message;
                }

                addGenerateProgressItem(
                    message,
                    'warning'
                );

                showToast(
                    message,
                    'warning'
                );

                return;
            }

            failGenerateProcess(
                errorMessage ||
                'Gagal menghubungi webhook n8n.'
            );
        }
    }


    // CRUD Artikel
    let currentEditId = null;
    let myEditor = null;

    ClassicEditor
        .create(document.querySelector('#editor'), {
            ckfinder: {
                uploadUrl: "{{ route('article.upload_image') . '?_token=' . csrf_token() }}"
            },
            toolbar: [
                'heading', '|', 'bold', 'italic', 'link',
                'bulletedList', 'numberedList', '|',
                'blockQuote', 'insertTable', 'undo', 'redo', '|',
                'imageUpload'
            ]
        })
        .then(editor => {
            myEditor = editor;
        })
        .catch(error => console.error('CKEditor gagal dimuat:', error));

    function openCategoryModal() {
        document.getElementById('category-modal')?.classList.remove('hidden');
    }

    function closeCategoryModal() {
        document.getElementById('category-modal')?.classList.add('hidden');
    }

    function closeArtikelModal() {
        const modal = document.getElementById('artikel-modal');
        const form = document.getElementById('artikel-form');

        modal?.classList.add('hidden');
        form?.reset();
        myEditor?.setData('');
        document.getElementById('image-preview-container')?.classList.add('hidden');
        currentEditId = null;
    }

    function openArtikelModal(data = null) {
        const modal = document.getElementById('artikel-modal');
        const form = document.getElementById('artikel-form');
        const previewContainer = document.getElementById('image-preview-container');
        const currentImage = document.getElementById('current-image');

        if (!modal || !form) return;

        form.reset();
        previewContainer?.classList.add('hidden');
        if (currentImage) currentImage.removeAttribute('src');

        if (data) {
            currentEditId = data.id;
            document.getElementById('modal-title-text').textContent = 'Edit Artikel';
            document.getElementById('form-method').value = 'PUT';
            document.getElementById('input-title').value = data.title || '';
            document.getElementById('input-category').value = data.category_id || '';
            document.getElementById('input-status').value = data.status || 'draft';

            if (data.image && previewContainer && currentImage) {
                currentImage.src = data.image.startsWith('http')
                    ? data.image
                    : `/storage/${data.image}`;
                previewContainer.classList.remove('hidden');
            }

            myEditor?.setData(data.content || '');
        } else {
            currentEditId = null;
            document.getElementById('modal-title-text').textContent = 'Buat Artikel Baru';
            document.getElementById('form-method').value = 'POST';
            myEditor?.setData('');
        }

        modal.classList.remove('hidden');
    }

    async function handleArtikelSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('artikel-form');
        const method = document.getElementById('form-method').value;

        if (myEditor) {
            document.getElementById('editor').value = myEditor.getData();
        }

        const formData = new FormData(form);
        const url = method === 'PUT' && currentEditId
            ? `/articles/${currentEditId}`
            : '/articles';

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                const errors = data.errors || data.message;
                const message = typeof errors === 'object'
                    ? Object.values(errors).flat().join('\n')
                    : (errors || 'Artikel gagal disimpan.');
                throw new Error(message);
            }

            showToast(data.message || 'Artikel berhasil disimpan.');
            closeArtikelModal();
            await fetchArtikelList();
        } catch (error) {
            showToast(error.message || 'Terjadi kesalahan saat menyimpan artikel.', 'error');
        }
    }

    async function fetchArtikelList(keyword = '') {
        const tableBody = document.getElementById('artikel-table-body');
        if (!tableBody) return;

        const url = new URL('/articles/fetch', window.location.origin);
        if (keyword) url.searchParams.set('search', keyword);

        try {
            tableBody.style.opacity = '0.5';
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) throw new Error('Daftar artikel gagal dimuat.');
            tableBody.innerHTML = await response.text();
        } catch (error) {
            console.error(error);
            showToast(error.message, 'error');
        } finally {
            tableBody.style.opacity = '1';
        }
    }

    async function deleteArtikel(id) {
        if (!confirm('Yakin ingin menghapus artikel ini?')) return;

        try {
            const response = await fetch(`/articles/${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ _method: 'DELETE' })
            });

            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Artikel gagal dihapus.');

            showToast(data.message || 'Artikel berhasil dihapus.');
            await fetchArtikelList(document.getElementById('artikel-search')?.value.trim() || '');
        } catch (error) {
            showToast(error.message || 'Terjadi kesalahan saat menghapus artikel.', 'error');
        }
    }

    document.getElementById('category-form')?.addEventListener('submit', async event => {
        event.preventDefault();

        try {
            const response = await fetch('/categories', {
                method: 'POST',
                body: new FormData(event.currentTarget),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (!response.ok) {
                const errors = data.errors || data.message;
                const message = typeof errors === 'object'
                    ? Object.values(errors).flat().join('\n')
                    : (errors || 'Kategori gagal disimpan.');
                throw new Error(message);
            }

            showToast(data.message || 'Kategori berhasil ditambahkan.');
            closeCategoryModal();
            window.location.reload();
        } catch (error) {
            showToast(error.message || 'Terjadi kesalahan saat menyimpan kategori.', 'error');
        }
    });

    let currentKeyword = @json($generateKeyword);
    let keywordPlans = @json($generateKeywordPlans);
    let keywordFormType = 'regular';
    let currentPlanId = null;

    function switchArticleTab(tab) {
        const showArticles = tab === 'articles';
        document.getElementById('content-articles')?.classList.toggle('hidden', !showArticles);
        document.getElementById('content-keyword')?.classList.toggle('hidden', showArticles);
        document.getElementById('article-actions')?.classList.toggle('hidden', !showArticles);

        const articleTab = document.getElementById('tab-articles');
        const keywordTab = document.getElementById('tab-keyword');
        articleTab?.classList.toggle('border-[#24420A]', showArticles);
        articleTab?.classList.toggle('text-[#24420A]', showArticles);
        articleTab?.classList.toggle('border-transparent', !showArticles);
        articleTab?.classList.toggle('text-gray-500', !showArticles);
        keywordTab?.classList.toggle('border-[#24420A]', !showArticles);
        keywordTab?.classList.toggle('text-[#24420A]', !showArticles);
        keywordTab?.classList.toggle('border-transparent', showArticles);
        keywordTab?.classList.toggle('text-gray-500', showArticles);
        articleTab?.setAttribute('aria-selected', showArticles ? 'true' : 'false');
        keywordTab?.setAttribute('aria-selected', showArticles ? 'false' : 'true');
    }

    function openKeywordModal(data = null, type = 'regular') {
        if (type === 'regular' && !data && currentKeyword) {
            showToast('Kata kunci sudah tersedia. Silakan edit data yang ada.', 'warning');
            return;
        }

        keywordFormType = type;
        currentPlanId = type === 'planning' ? data?.id || null : null;
        const label = type === 'planning' ? 'Keyword Planning' : 'Keyword Biasa';
        document.getElementById('keyword-modal-title').textContent = `${data ? 'Edit' : 'Tambah'} ${label}`;
        document.getElementById('keyword-date-field')?.classList.toggle('hidden', type !== 'planning');
        document.getElementById('keyword-date-input').required = type === 'planning';
        document.getElementById('keyword-date-input').value = data?.planned_date || '';
        document.getElementById('keyword-input').value = data?.keyword || '';
        document.getElementById('keyword-article-prompt-input').value = data?.article_prompt || '';
        document.getElementById('keyword-image-prompt-input').value = data?.image_prompt || '';
        document.getElementById('keyword-modal')?.classList.remove('hidden');
        setTimeout(() => document.getElementById('keyword-input')?.focus(), 0);
    }

    function closeKeywordModal() {
        document.getElementById('keyword-modal')?.classList.add('hidden');
        document.getElementById('keyword-form')?.reset();
        currentPlanId = null;
    }

    function escapeHtml(value) {
        const element = document.createElement('div');
        element.textContent = value ?? '';
        return element.innerHTML;
    }

    function promptPreview(value, detailAction) {
        const prompt = String(value || '-');
        const summary = prompt.length > 120 ? `${prompt.slice(0, 120).trimEnd()}...` : prompt;
        const detailButton = prompt.length > 120
            ? `<button type="button" onclick="${detailAction}" class="mt-2 font-bold text-[#24420A] hover:underline">Selengkapnya</button>`
            : '';

        return `<p class="whitespace-pre-wrap">${escapeHtml(summary)}</p>${detailButton}`;
    }

    function openPromptDetail(title, content) {
        document.getElementById('prompt-detail-title').textContent = title;
        document.getElementById('prompt-detail-content').textContent = content || '-';
        document.getElementById('prompt-detail-modal')?.classList.remove('hidden');
    }

    function openPlanPromptDetail(id, field) {
        const plan = keywordPlans.find(item => Number(item.id) === Number(id));
        if (!plan) return;

        const title = field === 'image_prompt' ? 'Prompt Gambar Artikel' : 'Prompt Artikel';
        openPromptDetail(title, plan[field]);
    }

    function closePromptDetail() {
        document.getElementById('prompt-detail-modal')?.classList.add('hidden');
    }

    function renderKeyword() {
        const tableBody = document.getElementById('keyword-table-body');
        const addButton = document.getElementById('add-keyword-button');
        if (!tableBody || !addButton) return;

        addButton.classList.toggle('hidden', Boolean(currentKeyword));

        if (!currentKeyword) {
            tableBody.innerHTML = '<tr><td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">Belum ada keyword biasa.</td></tr>';
            return;
        }

        tableBody.innerHTML = `
            <tr id="keyword-row" class="align-top">
                <td class="px-5 py-4 text-sm font-medium text-gray-700">${escapeHtml(currentKeyword.keyword)}</td>
                <td class="max-w-xs px-5 py-4 text-xs text-gray-600">${promptPreview(currentKeyword.article_prompt, "openPromptDetail('Prompt Artikel', currentKeyword.article_prompt)")}</td>
                <td class="max-w-xs px-5 py-4 text-xs text-gray-600">${promptPreview(currentKeyword.image_prompt, "openPromptDetail('Prompt Gambar Artikel', currentKeyword.image_prompt)")}</td>
                <td class="px-5 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <button type="button" onclick="openKeywordModal(currentKeyword, 'regular')" class="rounded bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100">Edit</button>
                        <button type="button" onclick="deleteKeyword()" class="rounded bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 hover:bg-red-100">Hapus</button>
                    </div>
                </td>
            </tr>`;
    }

    function renderKeywordPlans() {
        const tableBody = document.getElementById('keyword-plan-table-body');
        if (!tableBody) return;

        keywordPlans.sort((a, b) => a.planned_date.localeCompare(b.planned_date));
        if (!keywordPlans.length) {
            tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">Belum ada keyword planning.</td></tr>';
            return;
        }

        tableBody.innerHTML = keywordPlans.map(plan => {
            const formattedDate = new Intl.DateTimeFormat('id-ID', {
                day: '2-digit', month: '2-digit', year: 'numeric', timeZone: 'UTC'
            }).format(new Date(`${plan.planned_date}T00:00:00Z`));

            return `<tr class="align-top border-t border-gray-50" data-plan-id="${plan.id}">
                <td class="whitespace-nowrap px-5 py-4 text-xs font-bold text-gray-700">${formattedDate}</td>
                <td class="px-5 py-4 text-sm font-medium text-gray-700">${escapeHtml(plan.keyword)}</td>
                <td class="max-w-xs px-5 py-4 text-xs text-gray-600">${promptPreview(plan.article_prompt, `openPlanPromptDetail(${plan.id}, 'article_prompt')`)}</td>
                <td class="max-w-xs px-5 py-4 text-xs text-gray-600">${promptPreview(plan.image_prompt, `openPlanPromptDetail(${plan.id}, 'image_prompt')`)}</td>
                <td class="px-5 py-4 text-center"><div class="flex justify-center gap-2">
                    <button type="button" onclick="editKeywordPlan(${plan.id})" class="rounded bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100">Edit</button>
                    <button type="button" onclick="deleteKeywordPlan(${plan.id})" class="rounded bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 hover:bg-red-100">Hapus</button>
                </div></td>
            </tr>`;
        }).join('');
    }

    function editKeywordPlan(id) {
        const plan = keywordPlans.find(item => Number(item.id) === Number(id));
        if (plan) openKeywordModal(plan, 'planning');
    }

    async function handleKeywordSubmit(event) {
        event.preventDefault();

        const isPlanning = keywordFormType === 'planning';
        const editingId = isPlanning ? currentPlanId : currentKeyword?.id;
        const baseUrl = isPlanning ? '/article-generate-keyword-plan' : '/article-generate-keyword';
        const url = editingId ? `${baseUrl}/${editingId}` : baseUrl;
        const method = editingId ? 'PUT' : 'POST';
        const payload = {
            keyword: document.getElementById('keyword-input').value.trim(),
            article_prompt: document.getElementById('keyword-article-prompt-input').value.trim(),
            image_prompt: document.getElementById('keyword-image-prompt-input').value.trim()
        };
        if (isPlanning) payload.planned_date = document.getElementById('keyword-date-input').value;

        try {
            const response = await fetch(url, {
                method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            const data = await response.json();

            if (!response.ok) {
                const errors = data.errors || data.message;
                throw new Error(typeof errors === 'object'
                    ? Object.values(errors).flat().join('\n')
                    : (errors || 'Kata kunci gagal disimpan.'));
            }

            if (isPlanning) {
                const index = keywordPlans.findIndex(item => Number(item.id) === Number(data.plan.id));
                if (index >= 0) keywordPlans[index] = data.plan;
                else keywordPlans.push(data.plan);
                renderKeywordPlans();
            } else {
                currentKeyword = data.keyword;
                renderKeyword();
            }
            closeKeywordModal();
            showToast(data.message || 'Kata kunci berhasil disimpan.');
        } catch (error) {
            showToast(error.message || 'Terjadi kesalahan saat menyimpan kata kunci.', 'error');
        }
    }

    async function deleteKeyword() {
        if (!currentKeyword || !confirm('Yakin ingin menghapus kata kunci ini?')) return;

        try {
            const response = await fetch(`/article-generate-keyword/${currentKeyword.id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Kata kunci gagal dihapus.');

            currentKeyword = null;
            renderKeyword();
            showToast(data.message || 'Kata kunci berhasil dihapus.');
        } catch (error) {
            showToast(error.message || 'Terjadi kesalahan saat menghapus kata kunci.', 'error');
        }
    }

    async function deleteKeywordPlan(id) {
        if (!confirm('Yakin ingin menghapus keyword planning ini?')) return;

        try {
            const response = await fetch(`/article-generate-keyword-plan/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Keyword planning gagal dihapus.');

            keywordPlans = keywordPlans.filter(item => Number(item.id) !== Number(id));
            renderKeywordPlans();
            showToast(data.message || 'Keyword planning berhasil dihapus.');
        } catch (error) {
            showToast(error.message || 'Terjadi kesalahan saat menghapus keyword planning.', 'error');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('artikel-search');
        let debounceTimer = null;

        searchInput?.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchArtikelList(searchInput.value.trim());
            }, 300);
        });
    });


</script>
@endsection
