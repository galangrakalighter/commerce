@extends('app')
@section('content')

<section class="w-full max-w-6xl mx-auto px-4 py-8">
    {{-- Banner Promo --}}
    <div class="relative overflow-hidden rounded-2xl shadow-lg border border-gray-100">
        <img 
            src="{{ asset('images/mobil-promo.png') }}" 
            alt="Promo Spesial GAFIKU - Gratis Subsidi Ongkir Seluruh Indonesia" 
            class="w-full h-auto object-cover block"
        >
    </div>

    {{-- Info Box / Badge Subsidi Ongkir --}}
    <div class="mt-6 flex justify-center">
        <div class="w-full max-w-xl bg-[#284807] text-amber-400 rounded-2xl p-5 md:p-6 shadow-md flex items-center justify-center gap-4 border border-[#1e3605]">
            {{-- Icon Truk Delivery --}}
            <div class="shrink-0">
                <svg class="w-12 h-12 md:w-16 md:h-16 fill-current" viewBox="0 0 24 24">
                    <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                </svg>
            </div>

            {{-- Text Pesan --}}
            <div class="text-center md:text-left">
                <p class="text-sm md:text-lg font-semibold leading-snug">
                    Dapatkan subsidi ongkir untuk setiap pembelian minimal 20 kg ke seluruh Indonesia.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="w-full max-w-6xl mx-auto px-4 py-12">
    {{-- Judul Section --}}
    <div class="text-center mb-10">
        <h2 class="text-3xl md:text-4xl font-extrabold text-[#284807] leading-tight">
            Keuntungan Berbelanja <br class="hidden sm:inline"> di GAFIKU
        </h2>
    </div>

    {{-- Grid Keuntungan (4 Kolom) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Card 1 --}}
        <div class="bg-[#eaf4e1] border border-[#d2e8be] rounded-2xl p-6 text-center flex flex-col items-center justify-center shadow-sm transition hover:shadow-md">
            <div class="mb-4">
                <img src="{{ asset('images/package.png') }}" alt="Subsidi Ongkir" class="w-20 h-20 object-contain">
            </div>
            <h3 class="text-[#1e3605] font-bold text-lg leading-snug">
                Subsidi ongkir <br> minimal 20 kg
            </h3>
        </div>

        {{-- Card 2: Berbagai Varian Bumbu --}}
        <div class="bg-[#eaf4e1] border border-[#d2e8be] rounded-2xl p-6 text-center flex flex-col items-center justify-center shadow-sm transition hover:shadow-md">
            <div class="mb-4">
                <img src="{{ asset('images/Turbine.png') }}" alt="Varian Bumbu" class="w-20 h-20 object-contain">
            </div>
            <h3 class="text-[#1e3605] font-bold text-lg leading-snug">
                Berbagai <br> varian bumbu <br> berkualitas
            </h3>
        </div>

        {{-- Card 3: Cocok untuk Kebutuhan Usaha --}}
        <div class="bg-[#eaf4e1] border border-[#d2e8be] rounded-2xl p-6 text-center flex flex-col items-center justify-center shadow-sm transition hover:shadow-md">
            <div class="mb-4">
                <img src="{{ asset('images/Shop.png') }}" alt="Kebutuhan Usaha" class="w-20 h-20 object-contain">
            </div>
            <h3 class="text-[#1e3605] font-bold text-lg leading-snug">
                Cocok untuk <br> kebutuhan usaha
            </h3>
        </div>

        {{-- Card 4: Pembelian Besar Lebih Hemat --}}
        <div class="bg-[#eaf4e1] border border-[#d2e8be] rounded-2xl p-6 text-center flex flex-col items-center justify-center shadow-sm transition hover:shadow-md">
            <div class="mb-4">
                <img src="{{ asset('images/Basket_alt_1.png') }}" alt="Pembelian Hemat" class="w-20 h-20 object-contain">
            </div>
            <h3 class="text-[#1e3605] font-bold text-lg leading-snug">
                Pembelian <br> besar harga <br> lebih hemat
            </h3>
        </div>
    </div>
</section>

<section class="w-full max-w-6xl mx-auto px-4 py-12">
    {{-- Banner Utama Call to Action (CTA) --}}
    <div class="relative overflow-hidden rounded-2xl shadow-lg border border-gray-100">
        {{-- Gunakan 'items-stretch' agar tinggi kiri dan kanan selalu sama --}}
        <div class="relative bg-white flex flex-col md:flex-row items-stretch">
            
            {{-- Bagian Kiri (Gambar Bumbu) --}}
            <div class="w-full md:w-1/2 relative min-h-[250px] md:min-h-full">
                <img src="{{ asset('storage/' . $gambar_promo->image_path) }}" alt="Varian Bumbu GAFI" class="absolute inset-0 w-full h-full object-cover">
            </div>

            {{-- Bagian Kanan (Background Hijau & Teks) --}}
            <div class="w-full md:w-1/2 bg-[#284807] text-white p-8 md:p-12 flex flex-col justify-center text-center md:text-left">
                <h2 class="text-2xl md:text-3xl font-extrabold mb-3 text-center">Dapatkan Promo Terbaru</h2>
                <p class="text-sm md:text-base text-center text-gray-200 mb-6 leading-relaxed">
                    Hubungi Customer Service kami untuk <br> mendapatkan informasi promo terbaru dan <br> penawaran terbaik sesuai kebutuhan Anda.
                </p>
                <div class="flex justify-center md:justify-center">
                    <a href="https://wa.me/6289612821257" target="_blank" class="inline-flex items-center gap-2 bg-white text-[#284807] font-bold px-6 py-3 rounded-full shadow hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.03-.42 1.98-1.07 2.75z"/></svg>
                        Hubungi CS
                    </a>
                </div>
                <p class="text-lg md:text-xl text-center mt-6">Belanja lebih hemat, untung lebih banyak bersama GAFI.</p>
            </div>

        </div>
    </div>
</section>

@endsection