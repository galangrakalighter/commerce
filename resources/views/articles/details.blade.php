@extends('app')

@section('meta_title', $article->meta_title ?? $article->title)
@section('meta_description', $article->meta_description ?? Str::limit(strip_tags($article->content), 150))
@section('meta_keywords', $article->meta_keywords ?? '')
@section('og_image', $article->image ? asset('storage/' . $article->image) : asset('images/default.jpg'))

@section('content')
<article class="bg-white min-h-screen py-8 sm:py-12 lg:py-16 overflow-x-hidden">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
            
            <!-- KOLOM KIRI: KONTEN UTAMA (Span 2) -->
            <div class="lg:col-span-2 w-full min-w-0">
                <header class="mb-6 sm:mb-8 lg:mb-10">
                    <div class="text-xs sm:text-sm font-semibold text-[#F2B705] uppercase tracking-wider mb-2">
                        {{ $article->category->name ?? 'Uncategorized' }}
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#24420A] leading-tight sm:leading-snug mb-4">
                        {{ $article->title }}
                    </h1>
                    <div class="text-gray-500 text-xs sm:text-sm italic">
                        Diterbitkan pada {{ $article->created_at ? $article->created_at->format('F d, Y') : 'Tanggal tidak tersedia' }}
                    </div>
                </header>

                @if($article->image)
                    <div class="mb-8 sm:mb-10 overflow-hidden rounded-2xl sm:rounded-3xl shadow-md bg-gray-100">
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" 
                             class="w-full h-auto max-h-[320px] sm:max-h-[450px] object-cover">
                    </div>
                @endif

                <!-- Konten Artikel (Responsif terhadap elemen gambar/tabel dari editor) -->
                <div class="prose prose-base sm:prose-lg max-w-none text-gray-700 leading-relaxed break-words">
                    {!! $article->content !!}
                </div>

                <!-- Navigasi Bawah / Kembali -->
                <div class="mt-10 sm:mt-12 pt-6 border-t border-gray-100">
                    <a href="{{ route('articles.all') }}" class="inline-flex items-center gap-2 text-[#24420A] font-bold text-sm sm:text-base hover:underline">
                        &larr; Kembali ke Daftar Artikel
                    </a>
                </div>
            </div>

            <!-- KOLOM KANAN: SIDEBAR (Trending / Rekomendasi) -->
            <aside class="lg:col-span-1 w-full min-w-0 mt-8 lg:mt-0">
                <div class="lg:sticky lg:top-24 space-y-6 sm:space-y-8">
                    
                    <!-- Kotak Rekomendasi -->
                    <div class="bg-gray-50 p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="text-base sm:text-lg font-bold text-[#24420A] mb-4 sm:mb-6 border-b border-gray-200 pb-2">Rekomendasi Lainnya</h3>
                        
                        <div class="space-y-4 sm:space-y-5">
                            @forelse($relatedArticles ?? [] as $related)
                                <a href="{{ route('articles.detailArtikel', $related->slug) }}" class="group flex gap-3 sm:gap-4 items-start">
                                    {{-- Gambar Artikel Terkait --}}
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden bg-gray-200 shrink-0 border border-gray-200 shadow-sm">
                                        <img src="{{ $related->image ? asset('storage/' . $related->image) : asset('images/default.jpg') }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $related->title }}">
                                    </div>
                                    
                                    {{-- Kontainer Teks --}}
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs sm:text-sm font-bold text-gray-800 group-hover:text-[#24420A] transition line-clamp-2 leading-snug">
                                            {{ $related->title }}
                                        </h4>
                                        <p class="text-[10px] text-gray-400 mt-1">{{ $related->created_at ? $related->created_at->format('M d, Y') : '' }}</p>
                                        
                                        @if($related->excerpt)
                                            <p class="text-[11px] text-gray-600 mt-1 line-clamp-2 italic leading-relaxed">
                                                {{ Str::limit($related->excerpt, 60) }}
                                            </p>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <p class="text-xs text-gray-500 text-center py-4">Belum ada artikel rekomendasi.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Banner CTA Bisnis -->
                    <div class="p-6 bg-[#24420A] rounded-2xl text-white text-center shadow-sm">
                        <h4 class="font-bold text-base mb-2">Butuh Bumbu Berkualitas?</h4>
                        <p class="text-xs opacity-90 mb-4 leading-relaxed">Dapatkan penawaran terbaik untuk kebutuhan bisnis makanan dan minuman Anda.</p>
                        <a href="https://wa.me/6289612821257" target="_blank" class="inline-block bg-[#F2B705] text-[#24420A] px-5 py-3 rounded-xl font-bold text-xs sm:text-sm hover:bg-yellow-400 transition shadow-sm w-full sm:w-auto">Hubungi Kami via WhatsApp</a>
                    </div>

                </div>
            </aside>
            
        </div>
    </div>
</article>
@endsection