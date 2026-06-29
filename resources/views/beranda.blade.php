@extends('app')

@section('content')
@php
    $bannerPath = public_path('images/banner.jpg');
    $hasBanner = file_exists($bannerPath);
@endphp
<div class="bg-white min-h-screen">

    {{-- SECTION: HERO BANNER --}}
    <section class="relative">
        <div class="relative w-full">
            <img src="{{ asset('images/banner_beranda_Awal.png') }}" 
                 alt="Banner Utama" 
                 class="w-full h-auto object-contain">

            <div class="absolute inset-0 bg-black/30 flex items-center justify-center text-center px-4">
                <div class="text-white">
                    <h1 class="text-3xl md:text-5xl font-bold uppercase leading-tight drop-shadow-md">
                        Supplier Bumbu Makanan <br> & Bubuk Minuman Halal
                    </h1>
                    <h2 class="text-xl md:text-3xl font-semibold mt-3 drop-shadow-md">
                        Untuk Bisnis Anda
                    </h2>
                    <p class="max-w-2xl mx-auto mt-6 text-sm md:text-base">
                        Golden Aroma Food Indonesia menyediakan bumbu tabur, bubuk minuman, 
                        cabe bubuk, rempah-rempah, essen flavor, dan bahan baku F&B berkualitas.
                    </p>
                </div>
            </div>
        </div>

        {{-- CARD BUTTON --}}
        <div class="relative z-20 flex flex-col md:flex-row justify-center gap-6 -mt-10 px-4">
    
            <a href="/" class="group block relative w-[317px] h-[143px] bg-[#FFBE32] rounded-[15px] overflow-hidden transition-all duration-300 hover:shadow-xl">
    
                <div class="absolute left-[18px] top-[12px] text-[#2C4A04] text-[27px] font-bold font-['Platypi'] transition-all duration-300 group-hover:text-[22px]">
                    Lihat Produk
                </div>

                <div class="absolute -right-2 top-10 w-[200px] h-[143px]">
                    <img src="{{ asset('images/bubuk.png') }}" 
                        class="w-full h-full object-contain transition-transform duration-500 group-hover:-translate-x-3 group-hover:-translate-y-3 group-hover:rotate-[-5deg] group-hover:scale-110" 
                        alt="Lihat Produk" />
                </div>
            </a>

            <a href="#footer" class="group block relative w-[317px] h-[143px] bg-[#24420A] rounded-[15px] overflow-hidden transition-all duration-300 hover:shadow-xl">
                
                <div class="absolute right-[18px] top-[12px] text-yellow-400 text-[27px] font-bold font-['Platypi'] text-right transition-all duration-300 group-hover:text-[22px]">
                    Konsultasi <br> Custom Bumbu
                </div>

                <div class="absolute -left-2 top-10 w-[180px] h-[143px]">
                    <img src="{{ asset('images/buah-baru.png') }}" 
                        class="w-full h-full object-contain transition-transform duration-500 group-hover:translate-x-3 group-hover:-translate-y-3 group-hover:rotate-[5deg] group-hover:scale-110" 
                        alt="Konsultasi Bumbu" />
                </div>
            </a>
        </div>
    </section>

    @php
        $imagePath = public_path('images/gafiku.jpg');
        $imageExists = file_exists($imagePath);
    @endphp

    {{-- SECTION: MENGAPA MEMILIH --}}
    <section class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 lg:gap-20 items-center">
                <div class="order-2 md:order-1">
                    <h2 class="text-4xl md:text-5xl font-bold text-[#24420A] leading-tight">
                        Mengapa Memilih <br class="hidden md:block">
                        Golden Aroma Food <br class="hidden md:block">
                        Indonesia?
                    </h2>
                    <div class="w-20 h-1.5 bg-[#F2B705] mt-6 mb-8 rounded-full"></div>
                    <p class="text-gray-600 text-lg leading-relaxed max-w-lg">
                        Golden Aroma Food Indonesia hadir sebagai supplier bahan baku makanan 
                        dan minuman yang mengutamakan kualitas, inovasi rasa, dan kebutuhan 
                        bisnis pelanggan Anda.
                    </p>
                </div>
                <div class="order-1 md:order-2">
                    @if($imageExists)
                        <img src="{{ asset('images/tentang.png') }}" 
                             class="rounded-3xl shadow-xl w-full h-auto object-cover aspect-[4/3]" 
                             alt="Golden Aroma Food">
                    @else
                        <div class="w-full aspect-[4/3] bg-gray-100 border-2 border-dashed border-gray-300 rounded-3xl flex flex-col items-center justify-center text-gray-400 p-8">
                            <span class="font-medium">No Image</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: STATISTIK --}}
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-10 md:gap-20 mb-16">
                <div class="flex flex-col items-center text-center text-[#24420A] w-40">
                    <img src="{{ asset('images/halal.png') }}" class="w-24 h-24 mb-5 object-contain" alt="Halal">
                    <p class="text-base font-bold leading-tight">Produk Bersertifikat<br>Halal</p>
                </div>
                <div class="flex flex-col items-center text-center text-[#24420A] w-40">
                    <span class="text-5xl font-bold mb-3 h-24 flex items-center justify-center">150+</span>
                    <p class="text-base font-bold leading-tight">Lebih dari 150<br>Varian Rasa</p>
                </div>
                <div class="flex flex-col items-center text-center text-[#24420A] w-40">
                    <img src="{{ asset('images/loading.png') }}" class="w-24 h-24 mb-5 object-contain" alt="Custom">
                    <p class="text-base font-bold leading-tight">Custom Pembuatan<br>Bumbu</p>
                </div>
            </div>
            <div class="flex flex-wrap justify-center gap-10 md:gap-20">
                <div class="flex flex-col items-center text-center text-[#24420A] w-40">
                    <img src="{{ asset('images/shop.png') }}" class="w-24 h-24 mb-5 object-contain" alt="UMKM">
                    <p class="text-base font-bold leading-tight">Melayani UMKM<br>hingga Industri</p>
                </div>
                <div class="flex flex-col items-center text-center text-[#24420A] w-40">
                    <img src="{{ asset('images/package_car.png') }}" class="w-24 h-24 mb-5 object-contain" alt="Pengiriman">
                    <p class="text-base font-bold leading-tight">Pengiriman ke<br>Seluruh Indonesia</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: KATEGORI PRODUK --}}
    <section class="py-20 overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <!-- Heading dengan aksen lebih halus -->
            <h2 class="text-3xl md:text-5xl font-extrabold text-[#24420A] mb-4">
                Solusi Bahan Baku Kuliner
            </h2>
            <p class="text-gray-600 mb-16 text-lg">Pilih kategori terbaik untuk mendukung bisnis Anda</p>

            @php
                $kategoriProduk = [
                    ['nama' => 'Bumbu Tabur', 'img' => 'card-1.png', 'desc' => 'Bumbu tabur aneka rasa untuk keripik, makaroni, basreng, kentang, popcorn, dan berbagai produk camilan.'],
                    ['nama' => 'Bubuk Minuman', 'img' => 'bumbu-minuman.png ', 'desc' => 'Bubuk minuman dengan berbagai varian rasa untuk usaha minuman kekinian, kafe, restoran dan bisnis franchise.'],
                    ['nama' => 'Cabe Bubuk', 'img' => 'bubuk-cabe.png', 'desc' => 'Cabe bubuk dengan berbagai level kepedasan untuk kebutuhan snack pedas, sambal bubuk, seblak dan industri makanan.'],
                    ['nama' => 'Bubuk Rempah', 'img' => 'bubuk-rempah.png', 'desc' => 'Bumbu rempah berkualitas untuk kebutuhan masakan, produk olahan dan formulasi makanan.'],
                    ['nama' => 'Essen Flavor', 'img' => 'essen-flavor.png', 'desc' => 'Essen flavor untuk kebutuhan makanan, minuman, bakery, dessert dan pengembangan produk F&B'],
                ];
            @endphp

            <!-- Grid yang lebih dinamis -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6 md:gap-8 mb-16">
                @foreach($kategoriProduk as $item)
                    <div class="group relative flex flex-col items-center">
                        
                        <div class="relative w-full aspect-[3/4] overflow-hidden rounded-[2rem] shadow-xl hover:shadow-2xl transition-all duration-500 cursor-pointer group-hover:-translate-y-2">
                            
                            <img src="{{ asset('images/' . $item['img']) }}" 
                                alt="{{ $item['nama'] }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-[#24420A]/90 via-[#24420A]/20 to-transparent opacity-80 transition-opacity duration-500"></div>
                            
                            <div class="absolute inset-0 p-6 flex flex-col justify-end text-center">
                                
                                <h3 class="text-white font-bold text-lg md:text-xl transition-all duration-500 group-hover:-translate-y-40">
                                    {{ $item['nama'] }}
                                </h3>

                                <div class="absolute inset-x-6 bottom-6 opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-4 group-hover:translate-y-0 text-white">
                                    <p class="text-xs md:text-sm font-medium leading-relaxed">
                                        {{ $item['desc'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tombol CTA dengan style yang lebih modern -->
            <a href="/" class="group inline-flex items-center gap-2 bg-[#24420A] text-white px-10 py-4 rounded-full font-bold hover:bg-[#3A5A1F] transition-all duration-300 hover:shadow-lg hover:shadow-green-900/20">
                Lihat Semua Produk 
                <span class="group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>
    </section>

    {{-- SECTION: CUSTOM --}}
    <section class="py-20 bg-gradient-to-b from-white from-30% to-[#24420A] to-30%">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-[#24420A] mb-4">Ciptakan Rasa Khas untuk Produk Anda</h2>
            <p class="text-gray-600 mb-8">Ingin memiliki produk makanan dengan cita rasa yang berbeda dari kompetitor?</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                @php
                    $customItems = [['nama' => 'Bumbu Snack', 'img' => 'bumbu-snack.png'], ['nama' => 'Bumbu Tabur', 'img' => 'bumbu-tabur.png'], ['nama' => 'Bumbu Pedas', 'img' => 'bumbu-pedas.png'], ['nama' => 'Bumbu Gurih', 'img' => 'bumbu-gurih.png'], ['nama' => 'Bumbu Rempah', 'img' => 'bumbu-rempah.png'], ['nama' => 'Formula Rasa Khusus', 'img' => 'formula-rasa-khusus.png']];
                @endphp
                @foreach($customItems as $item)
                    <div class="group bg-[#F2B705] hover:bg-[#DDFADF] p-4 rounded-t-[40px] rounded-b-2xl shadow-lg flex flex-col items-center transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 cursor-pointer">
                        <img src="{{ asset('images/' . $item['img']) }}" alt="{{ $item['nama'] }}" class="w-full h-48 object-cover rounded-t-[32px] mb-4">
                        <h3 class="text-[#24420A] font-bold text-lg">{{ $item['nama'] }}</h3>
                    </div>
                @endforeach
            </div>
            <div class="p-8 rounded-2xl flex flex-col md:flex-row items-center justify-between text-[#24420A] gap-6 border border-[#24420A]/20">
                <h1 class="text-left text-white font-medium text-sm md:text-base" style="font-family: 'Inter', sans-serif;">
                    Layanan custom pembuatan bumbu untuk membantu bisnis Anda <br> 
                    menciptakan rasa yang unik, konsisten, dan sesuai dengan target pasar
                </h1>
                <a href="#" class="bg-[#F2B705] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#3a5a1f] transition">Konsultasi Custom Bumbu</a>
            </div>
        </div>
    </section>

    {{-- SECTION: TENTANG KAMI --}}
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            
            <!-- Judul -->
            <h2 class="text-3xl md:text-5xl font-bold text-[#24420A] mb-8 leading-tight" 
                style="font-family: 'Platypi', serif;">
                Tentang Golden Aroma Food <br> Indonesia
            </h2>

            <!-- Gambar -->
            <div class="mb-8">
                <img src="{{ asset('images/tentang-kami.png') }}" 
                    alt="Tim Golden Aroma Food" 
                    class="w-full h-auto rounded-3xl object-cover shadow-lg">
            </div>

            <!-- Teks Deskripsi (Rata Kiri) -->
            <div class="text-left mb-8">
                <p class="text-gray-700 text-lg md:text-xl leading-relaxed font-sans">
                    Golden Aroma Food Indonesia adalah supplier dan produsen bahan baku makanan dan  minuman yang berfokus menyediakan produk berkualitas untuk kebutuhan bisnis kuliner di  Indonesia. Kami menghadirkan berbagai pilihan bumbu makanan Halal, bubuk minuman, cabe bubuk,  rempah-rempah, essen flavor, dan layanan custom pembuatan bumbu untuk mendukung  pertumbuhan UMKM, distributor, reseller, hingga industri makanan dan minuman. Dengan komitmen terhadap kualitas, inovasi, dan pelayanan profesional, kami siap menjadi  mitra terpercaya dalam pengembangan produk F&B Anda.
                </p>
            </div>
            
            <!-- Tombol -->
            <a href="#" 
            class="inline-block bg-[#24420A] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#3a5a1f] transition-all duration-300">
                Selengkapnya <br> Tentang Kami
            </a>
        </div>
    </section>

    {{-- SECTION: FITUR --}}
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-2 gap-8">
            
            <!-- Kartu 1: Dipercaya untuk Kebutuhan Bisnis F&B -->
            <div class="bg-[#FDF6E9] p-8 rounded-3xl transition-all duration-300 hover:bg-[#F5E6CC] hover:shadow-2xl hover:-translate-y-2 cursor-pointer border border-transparent hover:border-[#E8D4A8]">
                <h3 class="text-2xl font-bold text-[#24420A] text-center mb-8">Dipercaya untuk Kebutuhan Bisnis F&B</h3>
                <div class="grid grid-cols-2 gap-8 text-center text-[#24420A]">
                    <!-- Item 1 -->
                    <div>
                        <div class="text-4xl font-bold mb-2">150+</div>
                        <p class="text-sm font-semibold">Lebih dari 150 <br> Varian Rasa</p>
                    </div>
                    <!-- Item 2 -->
                    <div>
                        <img src="{{ asset('images/loading.png') }}" alt="Custom Bumbu" class="w-10 h-10 mx-auto mb-2">
                        <p class="text-sm font-semibold">Custom <br> Pembuatan Bumbu</p>
                    </div>
                    <!-- Item 3 -->
                    <div>
                        <img src="{{ asset('images/halal.png') }}" alt="Halal" class="w-10 h-10 mx-auto mb-2">
                        <p class="text-sm font-semibold">Produk <br> Bersertifikat Halal</p>
                    </div>
                    <!-- Item 4 -->
                    <div>
                        <img src="{{ asset('images/package_car.png') }}" alt="Pengiriman" class="w-10 h-10 mx-auto mb-2">
                        <p class="text-sm font-semibold">Pengiriman ke <br> Seluruh Indonesia</p>
                    </div>
                </div>
            </div>

            <!-- Kartu 2: Kami Melayani Berbagai Kebutuhan Usaha -->
            <div class="bg-[#FDF6E9] p-8 rounded-3xl transition-all duration-300 hover:bg-[#F5E6CC] hover:shadow-2xl hover:-translate-y-2 cursor-pointer border border-transparent hover:border-[#E8D4A8]">
                <h3 class="text-2xl font-bold text-[#24420A] text-center mb-8">Kami Melayani Berbagai <br> Kebutuhan Usaha</h3>
                <ul class="space-y-4 text-[#24420A] font-medium">
                    <li class="flex items-center gap-3"><img src="{{ asset('images/subtract.png') }}" class="w-5 h-5"> UMKM makanan dan minuman</li>
                    <li class="flex items-center gap-3"><img src="{{ asset('images/subtract.png') }}" class="w-5 h-5"> Produsen snack dan camilan</li>
                    <li class="flex items-center gap-3"><img src="{{ asset('images/subtract.png') }}" class="w-5 h-5"> Bisnis minuman kekinian</li>
                    <li class="flex items-center gap-3"><img src="{{ asset('images/subtract.png') }}" class="w-5 h-5"> Restoran, kafe, dan Catering</li>
                    <li class="flex items-center gap-3"><img src="{{ asset('images/subtract.png') }}" class="w-5 h-5"> Distributor dan reseller</li>
                    <li class="flex items-center gap-3"><img src="{{ asset('images/subtract.png') }}" class="w-5 h-5"> Industri pengolahan makanan</li>
                    <li class="flex items-center gap-3"><img src="{{ asset('images/subtract.png') }}" class="w-5 h-5"> Pemilik brand private label</li>
                </ul>
            </div>
            
        </div>
    </section>

    {{-- SECTION: FOOTER CTA --}}
    <section class="relative py-24 bg-cover bg-center" style="background-image: url('{{ asset('images/spices.png') }}');">

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <!-- Judul -->
                <h2 class="text-4xl md:text-5xl font-bold text-[#F2B705] mb-6 leading-tight">
                    Siap Mengembangkan Produk Kuliner Anda?
                </h2>

                <!-- Deskripsi -->
                <p class="text-white text-lg md:text-xl mb-8 leading-relaxed">
                    Temukan pilihan bumbu makanan, bubuk <br> minuman, dan bahan baku F&B berkualitas <br> bersama 
                    <span class="font-bold text-[#F2B705]">Golden Aroma Food Indonesia</span>. <br> <br>Konsultasikan kebutuhan produk Anda <br> sekarang
                    dan <span class="font-bold text-[#F2B705]">dapatkan solusi terbaik </span> untuk <br> bisnis makanan dan minuman Anda.
                </p>

                <!-- Tombol -->
                <div class="flex flex-wrap gap-4">
                    <a href="/" class="bg-[#24420A] text-white px-8 py-4 rounded-xl font-bold hover:bg-[#3a5a1f] transition">
                        Lihat Katalog Produk
                    </a>
                    <a href="#" class="bg-[#FFBE32] text-[#24420A] px-8 py-4 rounded-xl font-bold hover:bg-[#ffc857] transition">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection