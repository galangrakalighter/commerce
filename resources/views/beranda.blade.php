@extends('app')

@section('content')
@php
    $bannerPath = public_path('images/banner.jpg');
    $hasBanner = file_exists($bannerPath);
@endphp
<div class="bg-white min-h-screen">

    {{-- SECTION: HERO BANNER --}}
    <section class="relative pb-16">
        <!-- BANNER UTAMA -->
        <div class="relative w-full h-[500px] sm:h-[550px] md:h-[600px] lg:h-[650px] overflow-hidden">
            <img src="{{ asset('images/banner_beranda_Awal.png') }}" 
                alt="Banner Utama" 
                class="w-full h-full object-cover object-center">

            <!-- Overlay Text -->
            <div class="absolute inset-0 flex items-center justify-center text-center px-4 sm:px-6">
                <div class="text-white max-w-5xl mx-auto">
                    <h1 class="text-2xl sm:text-3xl md:text-5xl font-bold uppercase leading-tight drop-shadow-md font-['Platypi']">
                        Supplier Bumbu Makanan <br class="hidden sm:inline"> & Bubuk Minuman Halal
                    </h1>
                    <h2 class="text-lg sm:text-xl md:text-3xl font-bold mt-2 sm:mt-3 drop-shadow-md font-['Platypi']">
                        Untuk Bisnis Anda
                    </h2>
                    <p class="max-w-5xl mx-auto mt-6 sm:mt-8 md:mt-10 font-semibold text-sm sm:text-base md:text-lg leading-relaxed text-gray-100 font-['Inter']">
                        Golden Aroma Food Indonesia menyediakan bumbu tabur, bubuk minuman, cabe bubuk, rempah-rempah, essen flavor, dan <br class="hidden md:inline"> bahan baku F&B berkualitas untuk UMKM, distributor, reseller, hingga industri makanan dan minuman di seluruh Indonesia.
                    </p>
                </div>
            </div>
        </div>

        <!-- CARD BUTTON -->
        <div class="relative z-20 max-w-4xl mx-auto flex flex-col sm:flex-row justify-center items-center gap-4 sm:gap-6 -mt-12 sm:-mt-16 px-4">
            
            <!-- Card 1: Lihat Produk -->
            <a href="/" class="group relative w-full sm:w-[317px] h-[130px] sm:h-[143px] bg-[#FFBE32] rounded-[15px] overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl flex items-center px-5 sm:px-6">
                <div class="z-10 text-[#2C4A04] text-xl sm:text-[27px] font-bold font-['Platypi'] transition-all duration-300 group-hover:scale-105">
                    Lihat Produk
                </div>
                <div class="absolute -right-2 bottom-0 w-[150px] sm:w-[200px] h-full pointer-events-none">
                    <img src="{{ asset('images/bubuk.png') }}" 
                        class="w-full h-full object-contain object-right-bottom transition-transform duration-500 group-hover:-translate-x-2 group-hover:-translate-y-2 group-hover:rotate-[-5deg] group-hover:scale-110" 
                        alt="Lihat Produk" />
                </div>
            </a>

            <!-- Card 2: Konsultasi Custom Bumbu (Diperbaiki) -->
            <a href="#footer" class="group relative w-full sm:w-[317px] min-h-[130px] sm:h-[143px] bg-[#24420A] rounded-[15px] overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl flex items-center justify-end px-5 sm:px-6 py-4 sm:py-0">
                <div class="absolute -left-2 bottom-0 w-[140px] sm:w-[180px] h-full pointer-events-none">
                    <img src="{{ asset('images/buah-baru.png') }}" 
                        class="w-full h-full object-contain object-left-bottom transition-transform duration-500 group-hover:translate-x-2 group-hover:-translate-y-2 group-hover:rotate-[5deg] group-hover:scale-110" 
                        alt="Konsultasi Bumbu" />
                </div>
                <!-- Menambahkan class -translate-y-1 atau sm:-translate-y-1.5 agar teks naik dan tidak tertutup -->
                <div class="z-10 text-yellow-400 text-xl sm:text-[27px] font-bold font-['Platypi'] text-right transition-all duration-300 group-hover:scale-105 -translate-y-1 sm:-translate-y-1.5 leading-tight">
                    Konsultasi Custom Bumbu
                </div>
            </a>

        </div>
    </section>

    @php
        $imagePath = public_path('images/gafiku.jpg');
        $imageExists = file_exists($imagePath);
    @endphp

    {{-- SECTION: MENGAPA MEMILIH --}}
    <section class="bg-white py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 sm:gap-12 lg:gap-20 items-center">
                
                <!-- Kolom Teks -->
                <div class="order-2 md:order-1 text-center md:text-left flex flex-col items-center md:items-start">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#24420A] leading-tight">
                        Mengapa Memilih <br class="hidden md:block">
                        Golden Aroma Food <br class="hidden md:block">
                        Indonesia?
                    </h2>
                    
                    <!-- Garis Aksen (Centered di Mobile, Left di Desktop) -->
                    <div class="w-20 h-1.5 bg-[#F2B705] mt-5 mb-6 sm:mt-6 sm:mb-8 rounded-full"></div>
                    
                    <p class="text-gray-600 text-base sm:text-lg leading-relaxed max-w-lg">
                        Golden Aroma Food Indonesia hadir sebagai supplier bahan baku makanan 
                        dan minuman yang mengutamakan kualitas, inovasi rasa, dan kebutuhan 
                        bisnis pelanggan Anda.
                    </p>
                </div>

                <!-- Kolom Gambar / Placeholder -->
                <div class="order-1 md:order-2 w-full">
                    @if($imageExists)
                        <img src="{{ asset('images/tentang.png') }}" 
                            class="rounded-2xl sm:rounded-3xl shadow-xl w-full h-auto object-cover aspect-[4/3] max-h-[450px] md:max-h-none mx-auto" 
                            alt="Golden Aroma Food">
                    @else
                        <div class="w-full aspect-[4/3] bg-gray-100 border-2 border-dashed border-gray-300 rounded-2xl sm:rounded-3xl flex flex-col items-center justify-center text-gray-400 p-8">
                            <span class="font-medium text-sm sm:text-base">No Image</span>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION: STATISTIK --}}
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Menggunakan CSS Grid agar penataan item jauh lebih rapi & otomatis responsif -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 sm:gap-8 lg:gap-10 items-start justify-center">
                
                <!-- Item 1: Halal -->
                <div class="flex flex-col items-center text-center text-[#24420A] w-full">
                    <div class="h-20 sm:h-24 flex items-center justify-center mb-3 sm:mb-5">
                        <img src="{{ asset('images/halal.png') }}" class="w-20 h-20 sm:w-24 sm:h-24 object-contain" alt="Halal">
                    </div>
                    <p class="text-sm sm:text-base font-bold leading-tight">Produk Bersertifikat<br>Halal</p>
                </div>

                <!-- Item 2: Varian Rasa -->
                <div class="flex flex-col items-center text-center text-[#24420A] w-full">
                    <div class="h-20 sm:h-24 flex items-center justify-center mb-3 sm:mb-5">
                        <span class="text-4xl sm:text-5xl font-bold">150+</span>
                    </div>
                    <p class="text-sm sm:text-base font-bold leading-tight">Lebih dari 150<br>Varian Rasa</p>
                </div>

                <!-- Item 3: Custom Bumbu -->
                <div class="flex flex-col items-center text-center text-[#24420A] w-full">
                    <div class="h-20 sm:h-24 flex items-center justify-center mb-3 sm:mb-5">
                        <img src="{{ asset('images/loading.png') }}" class="w-20 h-20 sm:w-24 sm:h-24 object-contain" alt="Custom">
                    </div>
                    <p class="text-sm sm:text-base font-bold leading-tight">Custom Pembuatan<br>Bumbu</p>
                </div>

                <!-- Item 4: UMKM hingga Industri -->
                <div class="flex flex-col items-center text-center text-[#24420A] w-full">
                    <div class="h-20 sm:h-24 flex items-center justify-center mb-3 sm:mb-5">
                        <img src="{{ asset('images/shop.png') }}" class="w-20 h-20 sm:w-24 sm:h-24 object-contain" alt="UMKM">
                    </div>
                    <p class="text-sm sm:text-base font-bold leading-tight">Melayani UMKM<br>hingga Industri</p>
                </div>

                <!-- Item 5: Pengiriman -->
                <div class="flex flex-col items-center text-center text-[#24420A] w-full col-span-2 sm:col-span-1">
                    <div class="h-20 sm:h-24 flex items-center justify-center mb-3 sm:mb-5">
                        <img src="{{ asset('images/package_car.png') }}" class="w-20 h-20 sm:w-24 sm:h-24 object-contain" alt="Pengiriman">
                    </div>
                    <p class="text-sm sm:text-base font-bold leading-tight">Pengiriman ke<br>Seluruh Indonesia</p>
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION: KATEGORI PRODUK --}}
    <section class="py-20 overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <!-- Heading -->
            <h2 class="text-3xl md:text-5xl font-extrabold text-[#24420A] mb-4 font-['Platypi']">
                Solusi Bahan Baku Kuliner
            </h2>
            <p class="text-gray-600 mb-16 text-base md:text-lg">Pilih kategori terbaik untuk mendukung bisnis Anda</p>

            @php
                $kategoriProduk = [
                    ['nama' => 'Bumbu Tabur', 'img' => 'card-1.png', 'desc' => 'Bumbu tabur aneka rasa untuk keripik, makaroni, basreng, kentang, popcorn, dan berbagai produk camilan.'],
                    ['nama' => 'Bubuk Minuman', 'img' => 'bumbu-minuman.png', 'desc' => 'Bubuk minuman dengan berbagai varian rasa untuk usaha minuman kekinian, kafe, restoran dan bisnis franchise.'],
                    ['nama' => 'Cabe Bubuk', 'img' => 'bubuk-cabe.png', 'desc' => 'Cabe bubuk dengan berbagai level kepedasan untuk kebutuhan snack pedas, sambal bubuk, seblak dan industri makanan.'],
                    ['nama' => 'Bubuk Rempah', 'img' => 'bubuk-rempah.png', 'desc' => 'Bumbu rempah berkualitas untuk kebutuhan masakan, olahan, dan formulasi makanan.'],
                    ['nama' => 'Essen Flavor', 'img' => 'essen-flavor.png', 'desc' => 'Essen flavor untuk kebutuhan makanan, minuman, bakery, dessert dan pengembangan produk F&B'],
                ];
            @endphp

            <!-- Grid Kategori -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 md:gap-8 mb-16">
                @foreach($kategoriProduk as $item)
                    <div class="group relative flex flex-col items-center">
                        
                        <div class="relative w-full aspect-[3/4] overflow-hidden rounded-[1.5rem] md:rounded-[2rem] shadow-xl hover:shadow-2xl transition-all duration-500 cursor-pointer group-hover:-translate-y-2">
                            
                            <img src="{{ asset('images/' . trim($item['img'])) }}" 
                                alt="{{ $item['nama'] }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            
                            <!-- Gradient background -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[#24420A]/95 via-[#24420A]/40 to-transparent opacity-85 group-hover:opacity-95 transition-opacity duration-500"></div>
                            
                            <div class="absolute inset-0 p-4 md:p-6 flex flex-col justify-end text-center">
                                
                                <!-- NAMA KATEGORI: Ukuran teks disesuaikan responsif agar tidak kekecilan di layar HP kecil -->
                                <h3 class="font-bold text-sm sm:text-base md:text-lg text-white transition-all duration-500 transform group-hover:-translate-y-28 sm:group-hover:-translate-y-32 md:group-hover:-translate-y-36">
                                    {{ $item['nama'] }}
                                </h3>

                                <!-- DESKRIPSI: Menggunakan text-xs sampai text-sm dengan line-height yang nyaman dibaca -->
                                <div class="absolute inset-x-4 sm:inset-x-5 md:inset-x-6 bottom-4 sm:bottom-5 md:bottom-6 opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-6 group-hover:translate-y-0 text-white">
                                    <p class="text-[11px] sm:text-xs md:text-sm font-normal leading-relaxed text-gray-100">
                                        {{ $item['desc'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tombol CTA -->
            <a href="{{ route('home') }}" class="group inline-flex items-center gap-2 bg-[#24420A] text-white px-8 md:px-10 py-3.5 md:py-4 rounded-full font-bold text-sm md:text-base hover:bg-[#3A5A1F] transition-all duration-300 hover:shadow-lg hover:shadow-green-900/20">
                Lihat Semua Produk 
                <span class="group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>
    </section>

    {{-- SECTION: CUSTOM --}}
    <section class="py-20 bg-gradient-to-b from-white from-30% to-[#24420A] to-30%">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <!-- Heading -->
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#24420A] mb-4 font-['Platypi']">
                Ciptakan Rasa Khas untuk Produk Anda
            </h2>
            <p class="text-gray-600 mb-12 text-base md:text-lg">
                Ingin memiliki produk makanan dengan cita rasa yang berbeda dari kompetitor?
            </p>

            @php
                $customItems = [
                    ['nama' => 'Bumbu Snack', 'img' => 'bumbu-snack.png'], 
                    ['nama' => 'Bumbu Tabur', 'img' => 'bumbu-tabur.png'], 
                    ['nama' => 'Bumbu Pedas', 'img' => 'bumbu-pedas.png'], 
                    ['nama' => 'Bumbu Gurih', 'img' => 'bumbu-gurih.png'], 
                    ['nama' => 'Bumbu Rempah', 'img' => 'bumbu-rempah.png'], 
                    ['nama' => 'Formula Rasa Khusus', 'img' => 'formula-rasa-khusus.png']
                ];
            @endphp

            <!-- Grid Items -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8 mb-16">
                @foreach($customItems as $item)
                    <div class="group bg-[#F2B705] hover:bg-[#DDFADF] p-4 rounded-t-[32px] rounded-b-2xl shadow-lg flex flex-col items-center transition-all duration-300 transform hover:-translate-y-2 hover:scale-[1.02] cursor-pointer">
                        <div class="w-full h-48 overflow-hidden rounded-t-[24px] mb-4">
                            <img src="{{ asset('images/' . $item['img']) }}" alt="{{ $item['nama'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>
                        <h3 class="text-[#24420A] font-bold text-lg md:text-xl font-['Platypi'] transition-colors">
                            {{ $item['nama'] }}
                        </h3>
                    </div>
                @endforeach
            </div>

            <!-- Banner CTA Bawah -->
            <div class="bg-[#1C3508]/40 backdrop-blur-sm p-6 md:p-8 rounded-2xl flex flex-col md:flex-row items-center justify-between text-left gap-6 border border-white/10 shadow-xl">
                <p class="text-white font-normal text-sm md:text-base leading-relaxed" style="font-family: 'Inter', sans-serif;">
                    Layanan custom pembuatan bumbu untuk membantu bisnis Anda <br class="hidden md:block">
                    menciptakan rasa yang unik, konsisten, dan sesuai dengan target pasar.
                </p>
                <a href="#footer" class="bg-[#F2B705] text-[#24420A] px-8 py-3.5 rounded-xl font-bold text-sm md:text-base hover:bg-white transition duration-300 shadow-md shrink-0">
                    Konsultasi Custom Bumbu
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION: TENTANG KAMI --}}
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
            
            <!-- Judul Responsif -->
            <h2 class="text-2xl sm:text-3xl md:text-5xl font-bold text-[#24420A] mb-6 sm:mb-8 leading-tight font-['Platypi']">
                Tentang Golden Aroma Food <br class="hidden md:inline"> Indonesia
            </h2>

            <!-- Gambar Responsif dengan Tinggi Terkontrol -->
            <div class="mb-6 sm:mb-8 overflow-hidden rounded-2xl sm:rounded-3xl shadow-lg bg-gray-50 flex items-center justify-center">
                <img src="{{ asset('images/tentang-kami.png') }}" 
                    alt="Tim Golden Aroma Food" 
                    class="w-full h-auto max-h-[300px] sm:max-h-[450px] md:max-h-[550px] object-contain transition-transform duration-500 hover:scale-105">
            </div>

            <!-- Teks Deskripsi (Rata Kiri, Nyaman Dibaca di Semua Layar) -->
            <div class="text-left mb-8 sm:mb-10">
                <p class="text-gray-700 text-sm sm:text-base md:text-lg leading-relaxed font-sans">
                    Golden Aroma Food Indonesia adalah supplier dan produsen bahan baku makanan dan  minuman yang berfokus menyediakan produk berkualitas untuk kebutuhan bisnis kuliner di  Indonesia. Kami menghadirkan berbagai pilihan bumbu makanan Halal, bubuk minuman, cabe bubuk,  rempah-rempah, essen flavor, dan layanan custom pembuatan bumbu untuk mendukung  pertumbuhan UMKM, distributor, reseller, hingga industri makanan dan minuman. Dengan komitmen terhadap kualitas, inovasi, dan pelayanan profesional, kami siap menjadi  mitra terpercaya dalam pengembangan produk F&B Anda.
                </p>
            </div>
            
            <!-- Tombol Responsif -->
            <div class="text-center md:text-left">
                <a href="{{ route('tentang') }}" 
                class="inline-flex items-center justify-center bg-[#24420A] text-white px-8 py-3.5 rounded-xl font-bold text-sm sm:text-base hover:bg-[#3a5a1f] transition-all duration-300 shadow-md hover:shadow-lg">
                    Selengkapnya Tentang Kami
                </a>
            </div>
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
    <section class="relative py-16 sm:py-20 md:py-24 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/spices.png') }}');">
        <!-- Overlay gelap transparan agar teks di atas background gambar semakin kontras dan mudah dibaca -->
        <div class="absolute inset-0 md:bg-black/40"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <!-- Judul Responsif -->
                <h2 class="text-2xl sm:text-3xl md:text-5xl font-bold text-[#F2B705] mb-4 sm:mb-6 leading-tight font-['Platypi']">
                    Siap Mengembangkan Produk Kuliner Anda?
                </h2>

                <!-- Deskripsi Responsif (Tanpa pemenggalan br manual yang kaku di mobile) -->
                <p class="text-white text-sm sm:text-base md:text-xl mb-6 sm:mb-8 leading-relaxed font-['Inter']">
                    Temukan pilihan bumbu makanan, bubuk <br> minuman, dan bahan baku F&B berkualitas <br> bersama 
                    <span class="font-bold text-[#F2B705]">Golden Aroma Food Indonesia</span>. <br><br> Konsultasikan kebutuhan produk Anda <br> sekarang 
                    dan <span class="font-bold text-[#F2B705]">dapatkan solusi terbaik</span> untuk <br> bisnis makanan dan minuman Anda.
                </p>

                <!-- Tombol Aksi (Responsif & Rapi saat di HP) -->
                <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
                    <a href="/" class="bg-[#24420A] text-white px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl font-bold text-center text-sm sm:text-base hover:bg-[#3a5a1f] transition shadow-md font-['Inter']">
                        Lihat Katalog Produk
                    </a>
                    <a href="#footer" class="bg-[#FFBE32] text-[#24420A] px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl font-bold text-center text-sm sm:text-base hover:bg-[#ffc857] transition shadow-md font-['Inter']">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection