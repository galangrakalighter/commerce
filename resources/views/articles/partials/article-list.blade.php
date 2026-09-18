@forelse($articles as $article)
    <div class="relative flex flex-col sm:flex-row gap-4 sm:gap-5 border-b border-gray-100 pb-6 hover:bg-gray-50/80 transition p-3 rounded-xl group">

        <a
            href="{{ route('articles.detailArtikel', $article->slug) }}"
            class="absolute inset-0 z-10"
            aria-label="Baca artikel {{ $article->title }}"
        ></a>

        <div class="w-full sm:w-1/3 h-48 sm:h-32 flex-shrink-0 relative rounded-lg overflow-hidden bg-gray-100">
            <img
                src="{{ asset('storage/' . $article->image) }}"
                class="w-full h-full object-cover shadow-sm group-hover:scale-105 transition duration-300"
                alt="{{ $article->title }}"
            >

            <span class="absolute top-2 left-2 bg-[#24420A] text-white text-[9px] px-2 py-1 rounded uppercase font-bold shadow-md z-20">
                {{ $article->category->name ?? 'Umum' }}
            </span>
        </div>

        <div class="flex-1 flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-gray-800 text-base sm:text-lg leading-snug mt-1 group-hover:text-[#24420A] transition line-clamp-2">
                    {{ $article->title }}
                </h3>

                <p class="text-[10px] sm:text-xs text-gray-400 mt-1">
                    {{ $article->created_at->format('F d, Y') }}
                </p>

                <p class="text-xs text-gray-600 mt-2 line-clamp-2 leading-relaxed">
                    {{ $article->excerpt }}
                </p>
            </div>

            <div>
                <span class="inline-block bg-[#24420A] text-white text-[10px] px-4 py-1.5 mt-3 w-max rounded shadow-sm group-hover:bg-[#355e0e] transition relative z-20 pointer-events-none font-semibold">
                    Read More
                </span>
            </div>
        </div>
    </div>
@empty
    <div class="flex flex-col items-center justify-center py-16 px-6 bg-gray-50 border border-dashed border-gray-300 rounded-2xl text-center">
        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-[#24420A]/10 mb-4">
            <i class="fas fa-newspaper text-2xl text-[#24420A]"></i>
        </div>

        <h3 class="text-lg font-bold text-gray-800">
            Belum Ada Artikel
        </h3>

        <p class="mt-2 text-sm text-gray-500 max-w-md">
            Belum ada artikel yang dipublikasikan dalam kategori ini.
        </p>
    </div>
@endforelse

@if($articles->hasPages())
    <div class="mt-8 article-pagination">
        {{ $articles->links() }}
    </div>
@endif