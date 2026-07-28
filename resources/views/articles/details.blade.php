@extends('app')
@section('content')
@section('meta_title', $article->meta_title)
@section('meta_description', $article->meta_description)
@section('meta_keywords', $article->meta_keywords)
@section('og_image', asset('storage/' . $article->image))
<article class="bg-white min-h-screen py-16">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-12">
            
            {{-- KOLOM KIRI: KONTEN UTAMA (Span 2) --}}
            <div class="lg:col-span-2">
                <header class="mb-10">
                    <div class="text-sm font-semibold text-[#F2B705] uppercase tracking-wider mb-3">
                        {{ $article->category->name ?? 'Uncategorized' }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-bold text-[#24420A] leading-tight mb-6">
                        {{ $article->title }}
                    </h1>
                    <div class="text-gray-500 text-sm italic">
                        Diterbitkan pada {{ $article->created_at?->format('F d, Y') ?? 'Tanggal tidak tersedia' }}
                    </div>
                </header>

                <div class="mb-10">
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" 
                         class="w-full h-auto rounded-3xl shadow-lg object-cover">
                </div>

                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                    {!! $article->content !!}
                </div>
            </div>

            {{-- KOLOM KANAN: SIDEBAR (Trending/Rekomendasi) --}}
            <aside class="lg:col-span-1">
                <div class="sticky top-24">
                    <h3 class="text-xl font-bold text-[#24420A] mb-6 border-b pb-2">Rekomendasi Lainnya</h3>
                    
                    <div class="space-y-6">
                        @foreach($relatedArticles as $related)
                        <a href="{{ route('articles.detailArtikel', $related->slug) }}" class="group flex gap-4 items-start">
                            {{-- Gambar --}}
                            <img src="{{ asset('storage/' . $related->image) }}" class="w-20 h-20 object-cover rounded-lg flex-shrink-0" alt="{{ $related->title }}">
                            
                            {{-- Kontainer Teks --}}
                            <div class="flex-1 overflow-hidden">
                                <h4 class="text-sm font-bold text-gray-800 group-hover:text-[#24420A] transition line-clamp-2">
                                    {{ $related->title }}
                                </h4>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $related->created_at?->format('M d, Y') ?? 'N/A' }}</p>
                                
                                {{-- Excerpt diletakkan di dalam div, bukan di luar --}}
                                <p class="text-[11px] text-gray-600 mt-2 line-clamp-2 italic">
                                    {{ Str::limit($related->excerpt, 70) }}
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    {{-- Banner Newsletter atau CTA bisa ditaruh di sini --}}
                    <div class="mt-10 p-6 bg-[#24420A] rounded-2xl text-white text-center">
                        <h4 class="font-bold mb-2">Butuh Bumbu Berkualitas?</h4>
                        <p class="text-xs opacity-80 mb-4">Dapatkan penawaran terbaik untuk bisnis makanan Anda.</p>
                        <a href="#footer" class="block bg-[#F2B705] text-[#24420A] py-2 rounded-lg font-bold text-xs hover:bg-yellow-400 transition">Hubungi Kami</a>
                    </div>
                </div>
            </aside>
            
        </div>

        <footer class="mt-16 pt-8 border-t">
            <a href="{{ route('articles.all') }}" class="text-[#24420A] font-bold hover:underline">
                &larr; Kembali ke Beranda
            </a>
        </footer>
    </div>
</article>

@endsection