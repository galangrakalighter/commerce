document.addEventListener('DOMContentLoaded', function () {
    
    // =========================================================================
    // 1. REGISTRASI ELEMEN DOM
    // =========================================================================
    
    // Elemen Dropdown Profil (Hanya ada jika user sudah login)
    const dropdownBtn = document.getElementById('dropdown-btn');
    const dropdownMenu = document.getElementById('dropdown-menu');
    const dropdownWrapper = document.getElementById('user-dropdown-wrapper');

    // Elemen Modal Utama (Hanya ada jika user belum login / guest)
    const openModalBtn = document.getElementById('open-login-btn');
    const closeModalBtn = document.getElementById('close-login-btn');
    const modalOverlay = document.getElementById('modal-overlay');
    const loginModal = document.getElementById('login-modal');
    const modalBox = document.getElementById('modal-box');
    const errorBox = document.getElementById('modal-error-message');

    // Elemen Pengalih Form Internal di dalam Modal
    const formLogin = document.getElementById('form-login-container');
    const formRegister = document.getElementById('form-register-container');
    const switchToRegisterBtn = document.getElementById('switch-to-register');
    const switchToLoginBtn = document.getElementById('switch-to-login');

    // Elemen Form Form AJAX
    const loginForm = document.getElementById('ajax-login-form');
    const registerForm = document.getElementById('ajax-register-form');

    // =========================================================================
    // 2. LOGIKA DROPDOWN PROFILE (USER MODE)
    // =========================================================================
    if (dropdownBtn && dropdownMenu) {
        // Toggle dropdown saat ikon profil diklik
        dropdownBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isHidden = dropdownMenu.classList.contains('invisible');
            if (isHidden) {
                dropdownMenu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none', 'invisible');
                dropdownMenu.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
            } else {
                hideDropdown();
            }
        });

        // Tutup dropdown jika mengklik area di luar menu dropdown
        document.addEventListener('click', function (e) {
            if (dropdownWrapper && !dropdownWrapper.contains(e.target)) {
                hideDropdown();
            }
        });

        function hideDropdown() {
            dropdownMenu.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
            dropdownMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none', 'invisible');
        }
    }

    // =========================================================================
    // 3. LOGIKA KONTROL MODAL UTAMA & SWITCH FORM (GUEST MODE)
    // =========================================================================
    if (openModalBtn && loginModal) {
        
        // Buka Modal (Otomatis menampilkan form login terlebih dahulu)
        openModalBtn.addEventListener('click', function () {
            loginModal.classList.remove('opacity-0', 'pointer-events-none', 'invisible');
            loginModal.classList.add('opacity-100', 'pointer-events-auto');
            
            modalBox.classList.remove('scale-95');
            modalBox.classList.add('scale-100');
            
            showLoginForm(); // Pastikan default awal adalah form login
        });

        // Akses Tutup Modal via Tombol Close (X) dan Overlay Backdrop
        closeModalBtn.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', closeModal);

        function closeModal() {
            loginModal.classList.remove('opacity-100', 'pointer-events-auto');
            loginModal.classList.add('opacity-0', 'pointer-events-none', 'invisible');
            
            modalBox.classList.remove('scale-100');
            modalBox.classList.add('scale-95');
            
            if (errorBox) errorBox.classList.add('hidden'); // Reset alert error
        }

        // Pemicu Animasi Pindah ke Form Register
        if (switchToRegisterBtn) {
            switchToRegisterBtn.addEventListener('click', showRegisterForm);
        }

        // Pemicu Animasi Pindah ke Form Login
        if (switchToLoginBtn) {
            switchToLoginBtn.addEventListener('click', showLoginForm);
        }

        // FUNGSI ANIMASI: Menampilkan Form Login (Menyembunyikan Register)
        function showLoginForm() {
            if (errorBox) errorBox.classList.add('hidden');

            // Form Login Aktif (Masuk dari kiri atas)
            formLogin.classList.remove('opacity-0', 'scale-95', '-translate-y-4', 'pointer-events-none', 'invisible');
            formLogin.classList.add('opacity-100', 'scale-100', 'translate-y-0', 'pointer-events-auto');

            // Form Register Non-Aktif (Keluar bergeser ke bawah & mengecil halus)
            formRegister.classList.remove('opacity-100', 'scale-100', 'translate-y-0', 'pointer-events-auto');
            formRegister.classList.add('opacity-0', 'scale-105', 'translate-y-4', 'pointer-events-none', 'invisible');
        }

        // FUNGSI ANIMASI: Menampilkan Form Register (Menyembunyikan Login)
        function showRegisterForm() {
            if (errorBox) errorBox.classList.add('hidden');

            // Form Login Non-Aktif (Keluar bergeser naik ke atas)
            formLogin.classList.remove('opacity-100', 'scale-100', 'translate-y-0', 'pointer-events-auto');
            formLogin.classList.add('opacity-0', 'scale-95', '-translate-y-4', 'pointer-events-none', 'invisible');

            // Form Register Aktif (Masuk naik dari arah bawah)
            formRegister.classList.remove('opacity-0', 'scale-105', 'translate-y-4', 'pointer-events-none', 'invisible');
            formRegister.classList.add('opacity-100', 'scale-100', 'translate-y-0', 'pointer-events-auto');
        }
    }

    // =========================================================================
    // 4. PROSES SUBMIT FORM VIA AJAX (FETCH API)
    // =========================================================================
    
    // Handler AJAX untuk Form Login
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault(); 
            if (errorBox) errorBox.classList.add('hidden'); 

            const formData = new FormData(loginForm);

            fetch(loginForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                if (res.status === 200 && res.body.success) {
                    // Refresh halaman instan untuk memproses session auth Laravel
                    window.location.reload(); 
                } else {
                    // Tampilkan pesan error di dalam alert box modal
                    if (errorBox) {
                        errorBox.innerText = res.body.message || 'Email atau password salah.';
                        errorBox.classList.remove('hidden');
                    }
                }
            })
            .catch(err => {
                if (errorBox) {
                    errorBox.innerText = 'Gagal terhubung ke server. Sila coba beberapa saat lagi.';
                    errorBox.classList.remove('hidden');
                }
            });
        });
    }

    // Handler AJAX untuk Form Register
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (errorBox) errorBox.classList.add('hidden');

            const formData = new FormData(registerForm);

            fetch(registerForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                if (res.status === 200 && res.body.success) {
                    // Refresh halaman instan setelah user berhasil terdaftar
                    window.location.reload();
                } else {
                    // Tampilkan pesan error validasi (misal: email sudah terdaftar)
                    if (errorBox) {
                        errorBox.innerText = res.body.message || 'Pendaftaran gagal.';
                        errorBox.classList.remove('hidden');
                    }
                }
            })
            .catch(err => {
                if (errorBox) {
                    errorBox.innerText = 'Gagal memproses pendaftaran ke server.';
                    errorBox.classList.remove('hidden');
                }
            });
        });
    }

    // Navbar Mobile
    const mobileTrigger = document.getElementById('admin-mobile-trigger');
    const mobileDropdown = document.getElementById('admin-mobile-dropdown');
    const mobileArrow = document.getElementById('admin-mobile-arrow');

    if (mobileTrigger && mobileDropdown) {
        mobileTrigger.addEventListener('click', function(e) {
            e.preventDefault();

            // Memeriksa apakah dropdown sedang tertutup
            const isCollapsed = mobileDropdown.style.maxHeight === '0px' || !mobileDropdown.style.maxHeight;

            if (isCollapsed) {
                // Proses Membuka: Set tinggi maksimal sesuai isi tinggi asli konten
                mobileDropdown.style.maxHeight = mobileDropdown.scrollHeight + "px";
                
                // Putar ikon panah ke atas
                if (mobileArrow) mobileArrow.classList.add('rotate-180');
            } else {
                // Proses Menutup: Kembalikan tinggi maksimal ke nol rupiah/pixel
                mobileDropdown.style.maxHeight = "0px";
                
                // Kembalikan orientasi panah ke posisi awal
                if (mobileArrow) mobileArrow.classList.remove('rotate-180');
            }
        });
    }

    // Reset otomatis jika menu burger utama ditutup oleh user
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            // Jika menu burger ditutup, pastikan internal dropdown sub-cms ikut merapat kembali
            if (mobileMenu.classList.contains('hidden') && mobileDropdown) {
                mobileDropdown.style.maxHeight = "0px";
                if (mobileArrow) mobileArrow.classList.remove('rotate-180');
            }
        });
    }

    // Button Admin
    const adminBtn = document.getElementById('admin-cms-btn');
    const adminMenu = document.getElementById('admin-cms-menu');
    const adminArrow = document.getElementById('admin-arrow');
    const adminWrapper = document.getElementById('admin-cms-wrapper');

    if (adminBtn && adminMenu) {
        adminBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            // Toggle classes untuk memunculkan dropdown
            adminMenu.classList.toggle('opacity-0');
            adminMenu.classList.toggle('scale-95');
            adminMenu.classList.toggle('pointer-events-none');
            adminMenu.classList.toggle('invisible');
            
            // Efek memutar panah kecil saat dropdown aktif
            if(adminArrow) adminArrow.classList.toggle('rotate-180');
        });

        // Tutup dropdown otomatis jika admin mengklik area di luar menu
        document.addEventListener('click', function(e) {
            if (!adminWrapper.contains(e.target)) {
                adminMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none', 'invisible');
                if(adminArrow) adminArrow.classList.remove('rotate-180');
            }
        });
    }
});