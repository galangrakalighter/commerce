@extends('app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Hapus px-4 md:px-8 dari section agar bisa mentok ke pinggir layar -->
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    'playfair': ['Playfair Display', 'serif'],
                    'inter': ['Inter', 'sans-serif'],
                },
                colors: {
                    'brand-dark': '#1b3b2b', // Warna teks/icon utama
                    'brand-light': '#e7f2ec', // Warna background icon
                    'brand-accent': '#f9a826', // Warna garis penghubung (oranye)
                }
            }
        }
    }
</script>

<section class="relative bg-white py-10 sm:py-14 lg:py-20 overflow-hidden">
    <div class="w-full mx-auto text-center relative">

        <!-- GAMBAR HERO -->
        <div class="relative z-10 mb-[-50px] sm:mb-[-80px] lg:mb-[-120px] overflow-hidden rounded-xl sm:rounded-2xl max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <img src="{{ asset('images/tentang-kami-hero.png') }}"
                alt="Golden Aroma Food Indonesia - Bumbu & Seasoning"
                class="w-full h-auto object-contain block transition duration-500">
        </div>

        <!-- BACKGROUND HIJAU -->
        <div class="w-full bg-[#1b3b2b] text-white
                    pt-20 sm:pt-28 lg:pt-36
                    pb-10 sm:pb-14 lg:pb-20
                    px-4 sm:px-8 lg:px-12
                    rounded-t-[30%] sm:rounded-t-[40%] lg:rounded-t-[54%]
                    shadow-xl">

            <!-- KONTEN -->
            <div class="max-w-5xl mx-auto">

                <!-- JUDUL -->
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl
                           font-bold text-amber-400
                           mb-5 sm:mb-6
                           tracking-wide drop-shadow-md">
                    Golden Aroma Food Indonesia
                </h1>

                <!-- DESKRIPSI -->
                <div class="space-y-4 sm:space-y-6
                            text-gray-200
                            text-sm sm:text-base md:text-lg lg:text-xl
                            leading-relaxed
                            text-left
                            mb-6 sm:mb-8">

                    <p>
                        <strong>Golden Aroma Food Indonesia (GAFI)</strong>
                        adalah perusahaan yang bergerak di bidang produksi bumbu
                        dan seasoning berkualitas untuk kebutuhan rumah tangga,
                        UMKM, hingga industri makanan dan minuman. Dengan
                        pengalaman dalam pengembangan produk, GAFI menghadirkan
                        berbagai pilihan bumbu instan, bumbu tabur, dry rub,
                        dan bubuk minuman yang praktis, konsisten, serta sesuai
                        dengan selera pasar Indonesia.
                    </p>

                    <p>
                        Kami berkomitmen menjadi mitra terpercaya bagi para pelaku
                        usaha F&B dengan menghadirkan produk berkualitas,
                        inovatif, dan memiliki nilai terbaik untuk mendukung
                        pertumbuhan bisnis pelanggan.
                    </p>

                </div>

            </div>
        </div>

        <!-- GARIS KUNING -->
        <div class="w-full h-8 sm:h-10 lg:h-14 bg-amber-400"></div>

    </div>
</section>

<!-- Section Visi Kami -->
<section class="relative bg-white mt-8 sm:mt-0 md:-mt-20 pb-12 sm:pb-16 lg:pb-20 overflow-hidden z-20">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-10 lg:gap-16 items-center">

            <!-- Kolom Teks: Visi Kami -->
            <div class="text-left space-y-4 sm:space-y-5">

                <h2 class="text-3xl sm:text-4xl md:text-5xl
                           font-extrabold
                           font-['Platypi']
                           text-[#1b3b2b]
                           tracking-wide">
                    Visi Kami
                </h2>

                <p class="text-[#2C4A04]
                          font-medium
                          text-base sm:text-lg md:text-xl lg:text-2xl
                          leading-relaxed
                          text-left
                          font-['Inter']
                          max-w-2xl">
                    Menjadi partner utama dan barometer industri seasoning di
                    Indonesia yang dipercaya untuk membantu bisnis F&B berkembang
                    melalui produk yang berkualitas, konsisten, dan inovatif.
                </p>

            </div>

            <!-- Kolom Gambar -->
            <div class="flex justify-center lg:justify-end relative">

                <img src="{{ asset('images/visi-kami.png') }}"
                    alt="Visi Kami - Golden Aroma Food Indonesia"
                    class="w-full
                           max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg
                           h-auto
                           object-contain
                           transition duration-500
                           hover:scale-105">

            </div>

        </div>

    </div>

</section>

<!-- Section Misi Kami -->
<section class="relative bg-white py-16 md:py-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Judul Section -->
        <div class="text-center mb-12 md:mb-16">
            <h2 class="text-3xl md:text-5xl font-extrabold font-['Platypi'] text-[#1b3b2b] tracking-wide">
                Misi Kami
            </h2>
        </div>

        <!-- Grid 4 Kartu Misi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 max-w-5xl mx-auto">
            
            <!-- Kartu 1: Customer Value -->
            <div class="bg-[#E7F7DA] p-6 sm:p-8 md:p-10 rounded-3xl shadow-sm transition duration-300 hover:shadow-md relative flex flex-col justify-between">
                <!-- Logo di Atas Kanan -->
                <div class="absolute top-6 right-6 sm:top-8 sm:right-8">
                    <img src="{{ asset('images/User_add_alt.png') }}" alt="Customer Value" class="w-12 h-12 sm:w-16 sm:h-16 object-contain opacity-90">
                </div>

                <!-- Konten: Judul -->
                <div class="mt-2 sm:mt-4 mb-4 sm:mb-6 pr-14">
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-bold font-['Inter'] text-[#1b3b2b] leading-snug">
                        Customer<br>Value
                    </h3>
                </div>

                <p class="text-[#2C4A04] font-medium text-sm sm:text-base md:text-lg leading-relaxed font-['Inter']">
                    Memberikan kepuasan pelanggan secara berkelanjutan dengan menghadirkan solusi rasa yang relevan dengan kebutuhan pasar.
                </p>
            </div>

            <!-- Kartu 2: Product Excellence -->
            <div class="bg-[#FFE9BF] p-6 sm:p-8 md:p-10 rounded-3xl shadow-sm transition duration-300 hover:shadow-md relative flex flex-col justify-between">
                <!-- Logo di Atas Kanan -->
                <div class="absolute top-6 right-6 sm:top-8 sm:right-8">
                    <img src="{{ asset('images/Star_light.png') }}" alt="Product Excellence" class="w-12 h-12 sm:w-16 sm:h-16 object-contain opacity-90">
                </div>

                <!-- Konten: Judul -->
                <div class="mt-2 sm:mt-4 mb-4 sm:mb-6 pr-14">
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-bold font-['Inter'] text-[#1b3b2b] leading-snug">
                        Product<br>Excellence
                    </h3>
                </div>

                <!-- Diperbaiki: Menambahkan tag penutup </p> yang sebelumnya kurang -->
                <p class="text-[#2C4A04] font-medium text-sm sm:text-base md:text-lg leading-relaxed font-['Inter']">
                    Menghasilkan produk berkualitas tinggi dengan cita rasa yang konsisten serta terus mengembangkan inovasi melalui riset dan pengembangan produk.
                </p>
            </div>

            <!-- Kartu 3: Business Sustainability -->
            <div class="bg-[#FFE9BF] p-6 sm:p-8 md:p-10 rounded-3xl shadow-sm transition duration-300 hover:shadow-md relative flex flex-col justify-between">
                <!-- Logo di Atas Kanan -->
                <div class="absolute top-6 right-6 sm:top-8 sm:right-8">
                    <img src="{{ asset('images/Chart_light.png') }}" alt="Business Sustainability" class="w-12 h-12 sm:w-16 sm:h-16 object-contain opacity-90">
                </div>

                <!-- Konten: Judul -->
                <div class="mt-2 sm:mt-4 mb-4 sm:mb-6 pr-14">
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-bold font-['Inter'] text-[#1b3b2b] leading-snug">
                        Business<br>Sustainability
                    </h3>
                </div>

                <p class="text-[#2C4A04] font-medium text-sm sm:text-base md:text-lg leading-relaxed font-['Inter']">
                    Menciptakan nilai terbaik melalui produk yang berkualitas, harga yang kompetitif, dan mendukung pertumbuhan bisnis pelanggan secara berkelanjutan.
                </p>
            </div>

            <!-- Kartu 4: Organizational Growth -->
            <div class="bg-[#E7F7DA] p-6 sm:p-8 md:p-10 rounded-3xl shadow-sm transition duration-300 hover:shadow-md relative flex flex-col justify-between">
                <!-- Logo di Atas Kanan -->
                <div class="absolute top-6 right-6 sm:top-8 sm:right-8">
                    <img src="{{ asset('images/growth.png') }}" alt="Organizational Growth" class="w-12 h-12 sm:w-16 sm:h-16 object-contain opacity-90">
                </div>

                <!-- Konten: Judul -->
                <div class="mt-2 sm:mt-4 mb-4 sm:mb-6 pr-14">
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-bold font-['Inter'] text-[#1b3b2b] leading-snug">
                        Organizational<br>Growth
                    </h3>
                </div>

                <p class="text-[#2C4A04] font-medium text-sm sm:text-base md:text-lg leading-relaxed font-['Inter']">
                    Membangun organisasi yang sehat, produktif, dan terus berkembang dengan mendorong kreativitas, kolaborasi, serta inovasi di setiap lini.
                </p>
            </div>

        </div>

    </div>
</section>

{{-- Section Nilai Perusahaan --}}
<section class="relative bg-white py-16 md:py-24 overflow-hidden font-inter">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Judul Section -->
        <div class="text-center mb-16 md:mb-20">
            <h2 class="text-4xl md:text-6xl font-extrabold font-['Platypi'] text-brand-dark tracking-tight mb-4">
                Nilai Perusahaan
            </h2>
            <p class="text-lg md:text-xl font-['Inter'] text-gray-700 max-w-2xl mx-auto">
                Kami menjalankan setiap proses bisnis berdasarkan nilai-nilai berikut:
            </p>
        </div>

        <!-- Diagram Lingkaran -->
        <div class="relative w-full max-w-4xl mx-auto">

            <!-- MOBILE: Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 lg:hidden">

                <!-- Setiap item nilai perusahaan dibuat sebagai card biasa -->
                <div class="bg-[#E7F7DA] rounded-3xl p-6 text-center shadow-md">
                    <img src="{{ asset('images/thumb_up.png') }}"
                        class="w-12 h-12 mx-auto mb-3 object-contain">

                    <h3 class="font-semibold text-[#1b3b2b]">
                        Quality & Consistency
                    </h3>

                    <p class="text-sm text-[#2C4A04] mt-3">
                        Produk berkualitas dengan rasa yang konsisten.
                    </p>
                </div>

                <div class="bg-[#E7F7DA] rounded-3xl p-6 text-center shadow-md">
                    <img src="{{ asset('images/Done_ring_round.png') }}"
                        class="w-12 h-12 mx-auto mb-3 object-contain">

                    <h3 class="font-semibold text-[#1b3b2b]">
                        Ownership
                    </h3>

                    <p class="text-sm text-[#2C4A04] mt-3">
                        Mengutamakan kebutuhan dan kepuasan pelanggan.
                    </p>
                </div>

                <div class="bg-[#E7F7DA] rounded-3xl p-6 text-center shadow-md">
                    <img src="{{ asset('images/Reduce.png') }}"
                        class="w-12 h-12 mx-auto mb-3 object-contain">

                    <h3 class="font-semibold text-[#1b3b2b]">
                        Collaboration
                    </h3>

                    <p class="text-sm text-[#2C4A04] mt-3">
                        Membangun kerja sama yang kuat untuk mencapai tujuan bersama.
                    </p>
                </div>

                <div class="bg-[#E7F7DA] rounded-3xl p-6 text-center shadow-md">
                    <img src="{{ asset('images/Expand_right_double.png') }}"
                        class="w-12 h-12 mx-auto mb-3 object-contain">

                    <h3 class="font-semibold text-[#1b3b2b]">
                        Speed & Responsiveness
                    </h3>

                    <p class="text-sm text-[#2C4A04] mt-3">
                        Memberikan pelayanan yang cepat dan responsif.
                    </p>
                </div>

                <div class="bg-[#E7F7DA] rounded-3xl p-6 text-center shadow-md">
                    <img src="{{ asset('images/contineus.png') }}"
                        class="w-12 h-12 mx-auto mb-3 object-contain">

                    <h3 class="font-semibold text-[#1b3b2b]">
                        Continuous Improvement
                    </h3>

                    <p class="text-sm text-[#2C4A04] mt-3">
                        Terus melakukan perbaikan dan pengembangan.
                    </p>
                </div>

                <div class="bg-[#E7F7DA] rounded-3xl p-6 text-center shadow-md">
                    <img src="{{ asset('images/happy.png') }}"
                        class="w-12 h-12 mx-auto mb-3 object-contain">

                    <h3 class="font-semibold text-[#1b3b2b]">
                        Customer-Oriented
                    </h3>

                    <p class="text-sm text-[#2C4A04] mt-3">
                        Mengutamakan kebutuhan dan kepuasan pelanggan.
                    </p>
                </div>

            </div>

            <!-- DESKTOP: Diagram Lingkaran -->
            <div class="hidden lg:block relative w-full h-[700px]">

                <svg class="absolute inset-0 w-full h-full z-0" xmlns="http://www.w3.org/2000/svg">
                    <!-- Garis ke Atas (Quality & Consistency) -->
                    <line x1="50%" y1="50%" x2="50%" y2="12%" stroke="#FFB617" stroke-width="2" />
                    
                    <!-- Garis ke Kanan Atas (Ownership) -->
                    <line x1="50%" y1="50%" x2="82%" y2="28%" stroke="#FFB617" stroke-width="2" />
                    
                    <!-- Garis ke Kanan Bawah (Collaboration) -->
                    <line x1="50%" y1="50%" x2="82%" y2="72%" stroke="#FFB617" stroke-width="2" />
                    
                    <!-- Garis ke Bawah (Speed & Responsiveness) -->
                    <line x1="50%" y1="50%" x2="50%" y2="88%" stroke="#FFB617" stroke-width="2" />
                    
                    <!-- Garis ke Kiri Bawah (Continuous Improvement) -->
                    <line x1="50%" y1="50%" x2="18%" y2="72%" stroke="#FFB617" stroke-width="2" />
                    
                    <!-- Garis ke Kiri Atas (Customer-Oriented) -->
                    <line x1="50%" y1="50%" x2="18%" y2="28%" stroke="#FFB617" stroke-width="2" />
                </svg>

                <!-- Titik Pusat: Integrity -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20 flex flex-col items-center group cursor-pointer">
                    
                    <!-- Tampilan Asli -->
                    <div class="flex flex-col items-center z-10 transition-all duration-300 group-hover:opacity-0 group-hover:invisible">
                        <div class="bg-[#E7F7DA] p-8 rounded-full shadow-lg">
                            <img src="{{ asset('images/Diamon.png') }}" alt="Integrity" class="w-12 h-12 object-contain" />
                        </div>
                        <span class="mt-4 text-base md:text-lg font-semibold text-brand-dark whitespace-nowrap">
                            Integrity
                        </span>
                    </div>

                    <!-- Kotak Deskripsi -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-0 flex items-center bg-[#2D4424] text-white rounded-full shadow-2xl border-2 border-[#EED8A1] h-[90px] overflow-hidden w-0 opacity-0 group-hover:w-[380px] md:group-hover:w-[250px] group-hover:opacity-100 transition-all duration-500 ease-in-out whitespace-nowrap">
                        
                        <div class="flex items-center px-4 space-x-3 w-full">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-[#FFB617]"
                                    style="mask: url('{{ asset('images/Diamon.png') }}') center / contain no-repeat;
                                        -webkit-mask: url('{{ asset('images/Diamon.png') }}') center / contain no-repeat;">
                                </div>
                            </div>

                            <p class="text-xs font-medium leading-snug text-center whitespace-normal">
                                Menjunjung tinggi <br>
                                kejujuran, profesionalisme, <br>
                                dan kepercayaan.
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Item Lingkaran Luar -->
                <!-- 1. Quality & Consistency (Atas) -->
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 z-10 flex flex-col items-center group cursor-pointer">

                    <!-- Tampilan Asli -->
                    <div class="flex flex-col items-center z-10 transition-all duration-300 group-hover:opacity-0 group-hover:invisible">
                        <div class="bg-[#E7F7DA] p-8 rounded-full shadow-lg bg-gray-50">
                            <img src="{{ asset('images/thumb_up.png') }}"
                                alt="Quality & Consistency"
                                class="w-12 h-12 object-contain" />
                        </div>

                        <span class="mt-3 text-sm md:text-base font-medium text-brand-dark whitespace-nowrap">
                            Quality & Consistency
                        </span>
                    </div>

                    <!-- Kotak Deskripsi -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-0 flex items-center bg-[#2D4424] text-white rounded-full shadow-2xl border-2 border-[#EED8A1] h-[90px] overflow-hidden w-0 opacity-0 group-hover:w-[380px] md:group-hover:w-[200px] group-hover:opacity-100 transition-all duration-500 ease-in-out whitespace-nowrap">

                        <div class="flex items-center px-4 space-x-3 w-full">

                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-[#FFB617]"
                                    style="mask: url('{{ asset('images/thumb_up.png') }}') center / contain no-repeat;
                                        -webkit-mask: url('{{ asset('images/thumb_up.png') }}') center / contain no-repeat;">
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <p class="text-xs font-medium leading-snug text-center whitespace-normal">
                                Produk berkualitas <br>
                                dengan rasa <br>
                                yang konsisten
                            </p>

                        </div>

                    </div>

                </div>

                <!-- 2. Ownership (Kanan Atas) -->
                <div class="absolute top-[20%] right-[5%] md:right-[10%] z-10 flex flex-col items-center group cursor-pointer">

                    <!-- Tampilan Asli -->
                    <div class="flex flex-col items-center z-10 transition-all duration-300 group-hover:opacity-0 group-hover:invisible">
                        <div class="bg-[#E7F7DA] p-8 rounded-full shadow-lg bg-gray-50">
                            <img src="{{ asset('images/Done_ring_round.png') }}"
                                alt="Ownership"
                                class="w-12 h-12 object-contain" />
                        </div>

                        <span class="mt-3 text-sm md:text-base font-medium text-brand-dark whitespace-nowrap">
                            Ownership
                        </span>
                    </div>

                    <!-- Kotak Deskripsi -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-0 flex items-center bg-[#2D4424] text-white rounded-full shadow-2xl border-2 border-[#EED8A1] h-[90px] overflow-hidden w-0 opacity-0 group-hover:w-[380px] md:group-hover:w-[200px] group-hover:opacity-100 transition-all duration-500 ease-in-out whitespace-nowrap">

                        <div class="flex items-center px-4 space-x-3 w-full">

                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-[#FFB617]"
                                    style="mask: url('{{ asset('images/Done_ring_round.png') }}') center / contain no-repeat;
                                        -webkit-mask: url('{{ asset('images/Done_ring_round.png') }}') center / contain no-repeat;">
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <p class="text-xs font-medium leading-snug text-center whitespace-normal">
                                Mengutamakan <br>
                                kebutuhan dan <br>
                                kepuasan pelanggan
                            </p>

                        </div>

                    </div>

                </div>

                <!-- 3. Collaboration (Kanan Bawah) -->
                <div class="absolute bottom-[20%] right-[5%] md:right-[10%] top-[5%] md:top-[65%] z-10 flex flex-col items-center group cursor-pointer">

                    <!-- Tampilan Asli -->
                    <div class="flex flex-col items-center z-10 transition-all duration-300 group-hover:opacity-0 group-hover:invisible">
                        <div class="bg-[#E7F7DA] p-8 rounded-full shadow-lg bg-gray-50">
                            <img src="{{ asset('images/Reduce.png') }}"
                                alt="Collaboration"
                                class="w-12 h-12 object-contain" />
                        </div>

                        <span class="mt-3 text-sm md:text-base font-medium text-brand-dark whitespace-nowrap">
                            Collaboration
                        </span>
                    </div>

                    <!-- Kotak Deskripsi -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-0 flex items-center bg-[#2D4424] text-white rounded-full shadow-2xl border-2 border-[#EED8A1] h-[90px] overflow-hidden w-0 opacity-0 group-hover:w-[380px] md:group-hover:w-[250px] group-hover:opacity-100 transition-all duration-500 ease-in-out whitespace-nowrap">

                        <div class="flex items-center px-4 space-x-3 w-full">

                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-[#FFB617]"
                                    style="mask: url('{{ asset('images/Reduce.png') }}') center / contain no-repeat;
                                        -webkit-mask: url('{{ asset('images/Reduce.png') }}') center / contain no-repeat;">
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <p class="text-xs font-medium leading-snug text-center whitespace-normal">
                            Membangun kerja sama <br>
                            yang kuat untuk mencapai <br>
                            tujuan bersama
                            </p>

                        </div>

                    </div>

                </div>

                <!-- 4. Speed & Responsiveness (Bawah) -->
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 z-10 flex flex-col items-center group cursor-pointer">

                    <!-- Tampilan Asli -->
                    <div class="flex flex-col items-center z-10 transition-all duration-300 group-hover:opacity-0 group-hover:invisible">
                        <div class="bg-[#E7F7DA] p-8 rounded-full shadow-lg bg-gray-50">
                            <img src="{{ asset('images/Expand_right_double.png') }}"
                                alt="Speed & Responsiveness"
                                class="w-12 h-12 object-contain" />
                        </div>

                        <span class="mt-3 text-sm md:text-base font-medium text-brand-dark whitespace-nowrap">
                            Speed & Responsiveness
                        </span>
                    </div>

                    <!-- Kotak Deskripsi -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-0 flex items-center bg-[#2D4424] text-white rounded-full shadow-2xl border-2 border-[#EED8A1] h-[90px] overflow-hidden w-0 opacity-0 group-hover:w-[380px] md:group-hover:w-[250px] group-hover:opacity-100 transition-all duration-500 ease-in-out whitespace-nowrap">

                        <div class="flex items-center px-4 space-x-3 w-full">

                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-[#FFB617]"
                                    style="mask: url('{{ asset('images/Expand_right_double.png') }}') center / contain no-repeat;
                                        -webkit-mask: url('{{ asset('images/Expand_right_double.png') }}') center / contain no-repeat;">
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <p class="text-xs font-medium leading-snug text-center whitespace-normal">
                                Memberikan pelayanan <br>
                                yang cepat dan responsif
                            </p>

                        </div>

                    </div>

                </div>

                <!-- 5. Continuous Improvement (Kiri Bawah) -->
                <div class="absolute bottom-[20%] left-[5%] md:left-[10%] top-[5%] md:top-[65%] z-10 flex flex-col items-center group cursor-pointer">

                    <!-- Tampilan Asli -->
                    <div class="flex flex-col items-center z-10 transition-all duration-300 group-hover:opacity-0 group-hover:invisible">
                        <div class="bg-[#E7F7DA] p-8 rounded-full shadow-lg bg-gray-50">
                            <img src="{{ asset('images/contineus.png') }}"
                                alt="Continuous Improvement"
                                class="w-12 h-12 object-contain" />
                        </div>

                        <span class="mt-3 text-sm md:text-base font-medium text-brand-dark whitespace-nowrap">
                            Continuous Improvement
                        </span>
                    </div>

                    <!-- Kotak Deskripsi -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-0 flex items-center bg-[#2D4424] text-white rounded-full shadow-2xl border-2 border-[#EED8A1] h-[90px] overflow-hidden w-0 opacity-0 group-hover:w-[380px] md:group-hover:w-[200px] group-hover:opacity-100 transition-all duration-500 ease-in-out whitespace-nowrap">

                        <div class="flex items-center px-4 space-x-3 w-full">

                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-[#FFB617]"
                                    style="mask: url('{{ asset('images/contineus.png') }}') center / contain no-repeat;
                                        -webkit-mask: url('{{ asset('images/contineus.png') }}') center / contain no-repeat;">
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <p class="text-xs font-medium leading-snug text-center whitespace-normal">
                                Terus melakukan <br>
                                perbaikan dan <br>
                                pengembangan
                            </p>

                        </div>

                    </div>

                </div>

                <!-- 6. Customer-Oriented (Kiri Atas) -->
                <div class="absolute top-[20%] left-[5%] md:left-[10%] z-10 flex flex-col items-center group cursor-pointer">

                    <!-- Tampilan Asli -->
                    <div class="flex flex-col items-center z-10 transition-all duration-300 group-hover:opacity-0 group-hover:invisible">
                        <div class="bg-[#E7F7DA] p-8 rounded-full shadow-lg bg-gray-50">
                            <img src="{{ asset('images/happy.png') }}"
                                alt="Customer-Oriented"
                                class="w-12 h-12 object-contain" />
                        </div>

                        <span class="mt-3 text-sm md:text-base font-medium text-brand-dark whitespace-nowrap">
                            Customer-Oriented
                        </span>
                    </div>

                    <!-- Kotak Deskripsi -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-0 flex items-center bg-[#2D4424] text-white rounded-full shadow-2xl border-2 border-[#EED8A1] h-[90px] overflow-hidden w-0 opacity-0 group-hover:w-[380px] md:group-hover:w-[220px] group-hover:opacity-100 transition-all duration-500 ease-in-out whitespace-nowrap">

                        <div class="flex items-center px-4 space-x-3 w-full">

                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-[#FFB617]"
                                    style="mask: url('{{ asset('images/happy.png') }}') center / contain no-repeat;
                                        -webkit-mask: url('{{ asset('images/happy.png') }}') center / contain no-repeat;">
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <p class="text-xs font-medium leading-snug text-center whitespace-normal">
                                Mengutamakan <br> 
                                kebutuhan dan <br>
                                kepuasan pelanggan
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</section>

<!-- Section: Mengapa Memilih GAFI? -->
<section class="relative overflow-hidden bg-[#2D4424] py-12 text-white sm:py-16 md:py-20 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


        <!-- Judul Section -->
        <div class="mb-10 text-center sm:mb-12 md:mb-16">
            <h2 class="font-['Platypi'] text-3xl font-extrabold leading-tight tracking-tight text-[#FFBE32] sm:text-4xl md:text-5xl lg:text-6xl">
                Mengapa Memilih GAFI?
            </h2>
        </div>

        <!-- Gambar Utama -->
        <div class="mx-auto mb-10 w-full overflow-hidden rounded-xl sm:mb-12 sm:rounded-2xl">
            <img
                src="{{ asset('images/Banner-tentang.png') }}"
                alt="Mengapa Memilih GAFI"
                class="block h-auto w-full object-cover"
                loading="lazy"
            />
        </div>

        <!-- Grid Kartu Poin -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-5 lg:gap-6">

            <!-- Kartu 1 -->
            <div class="flex min-h-[180px] flex-col items-center justify-center rounded-2xl bg-[#EED8A1] p-5 text-gray-900 shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl sm:min-h-[200px] sm:justify-between sm:items-start sm:p-6">
                <p class="text-base font-medium leading-relaxed text-center sm:text-left sm:text-base">
                    Produk berkualitas dengan rasa yang konsisten.
                </p>

                <div class="mt-4 flex justify-center sm:mt-6 sm:w-full sm:justify-end">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#1F3018] text-base font-bold text-[#EED8A1] shadow sm:h-8 sm:w-8 sm:text-sm">
                        1
                    </span>
                </div>
            </div>

            <!-- Kartu 2 -->
            <div class="flex min-h-[180px] flex-col items-center justify-center rounded-2xl bg-[#FFBE32] p-5 text-gray-900 shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl sm:min-h-[200px] sm:justify-between sm:items-start sm:p-6">
                <p class="text-base font-medium leading-relaxed text-center sm:text-left sm:text-base">
                    Cocok untuk kebutuhan rumah tangga, UMKM, hingga industri F&B.
                </p>

                <div class="mt-4 flex justify-center sm:mt-6 sm:w-full sm:justify-end">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#1F3018] text-base font-bold text-[#EED8A1] shadow sm:h-8 sm:w-8 sm:text-sm">
                        2
                    </span>
                </div>
            </div>

            <!-- Kartu 3 -->
            <div class="flex min-h-[180px] flex-col items-center justify-center rounded-2xl bg-[#EED8A1] p-5 text-gray-900 shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl sm:min-h-[200px] sm:justify-between sm:items-start sm:p-6">
                <p class="text-base font-medium leading-relaxed text-center sm:text-left sm:text-base">
                    Tersedia berbagai pilihan varian bumbu dan bubuk minuman.
                </p>

                <div class="mt-4 flex justify-center sm:mt-6 sm:w-full sm:justify-end">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#1F3018] text-base font-bold text-[#EED8A1] shadow sm:h-8 sm:w-8 sm:text-sm">
                        3
                    </span>
                </div>
            </div>

            <!-- Kartu 4 -->
            <div class="flex min-h-[180px] flex-col items-center justify-center rounded-2xl bg-[#FFBE32] p-5 text-gray-900 shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl sm:min-h-[200px] sm:justify-between sm:items-start sm:p-6">
                <p class="text-base font-medium leading-relaxed text-center sm:text-left sm:text-base">
                    Terus berinovasi melalui riset dan pengembangan produk.
                </p>

                <div class="mt-4 flex justify-center sm:mt-6 sm:w-full sm:justify-end">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#1F3018] text-base font-bold text-[#EED8A1] shadow sm:h-8 sm:w-8 sm:text-sm">
                        4
                    </span>
                </div>
            </div>

            <!-- Kartu 5 -->
            <div class="flex min-h-[180px] flex-col items-center justify-center rounded-2xl bg-[#EED8A1] p-5 text-gray-900 shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl sm:min-h-[200px] sm:justify-between sm:items-start sm:p-6">
                <p class="text-base font-medium leading-relaxed text-center sm:text-left sm:text-base">
                    Menjadi mitra yang mendukung pertumbuhan bisnis pelanggan.
                </p>

                <div class="mt-4 flex justify-center sm:mt-6 sm:w-full sm:justify-end">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#1F3018] text-base font-bold text-[#EED8A1] shadow sm:h-8 sm:w-8 sm:text-sm">
                        5
                    </span>
                </div>
            </div>

</div>
    </div>
</section>


@endsection