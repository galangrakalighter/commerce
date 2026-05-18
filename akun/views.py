from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth import login, authenticate, logout
from django.contrib import messages
from django.contrib.auth.models import User
from django.contrib.auth.decorators import login_required
from django.http import HttpResponseForbidden, JsonResponse
from toko.models import Pesanan
from akun.models import User, Profile

def register_view(request):
    if request.method == 'POST':
        # Deteksi jika request dikirim via AJAX Fetch
        is_ajax = request.headers.get('X-Requested-With') == 'XMLHttpRequest'
        
        # === 1. TANGKAP INPUT NAMA LENGKAP ===
        nama_lengkap = request.POST.get('nama_lengkap', '').strip()
        
        username = request.POST.get('username')
        email = request.POST.get('email')
        password = request.POST.get('password')
        konfirmasi_password = request.POST.get('konfirmasi_password')
        
        # Validasi sederhana
        if User.objects.filter(username=username).exists():
            msg = 'Username sudah digunakan.'
            if is_ajax:
                return JsonResponse({'status': 'error', 'message': msg}, status=400)
            else:
                messages.error(request, msg) # Menyimpan pesan error agar bisa tampil setelah redirect
                return redirect(request.META.get('HTTP_REFERER', 'home'))
            
        if password != konfirmasi_password:
            msg = 'Konfirmasi password tidak cocok.'
            if is_ajax:
                return JsonResponse({'status': 'error', 'message': msg}, status=400)
            else:
                messages.error(request, msg)
                return redirect(request.META.get('HTTP_REFERER', 'home'))
            
        # === 2. PECAH NAMA MENJADI FIRST_NAME & LAST_NAME ===
        # split(' ', 1) memotong string berdasarkan spasi pertama saja
        if nama_lengkap:
            nama_parts = nama_lengkap.split(' ', 1)
            first_name = nama_parts[0]
            last_name = nama_parts[1] if len(nama_parts) > 1 else ''
        else:
            first_name = ''
            last_name = ''
            
        # === 3. MASUKKAN KE PROSES PEMBUATAN USER ===
        user = User.objects.create_user(
            username=username, 
            email=email, 
            password=password,
            first_name=first_name, # Menyimpan nama depan
            last_name=last_name    # Menyimpan nama belakang/sisa nama
        )
        
        login(request, user)
        
        if is_ajax:
            return JsonResponse({'status': 'success', 'message': 'Registrasi berhasil!'})
        
        return redirect('home')
    

@login_required
def edit_profil_view(request):
    if request.method == 'POST':
        nama_lengkap = request.POST.get('nama_lengkap', '').strip()
        email = request.POST.get('email', '').strip()
        foto_baru = request.FILES.get('foto_profil') # Ambil file gambar
        
        user = request.user
        
        # 1. Update Email & Nama
        if email:
            user.email = email
            
        if nama_lengkap:
            nama_parts = nama_lengkap.split(' ', 1)
            user.first_name = nama_parts[0]
            user.last_name = nama_parts[1] if len(nama_parts) > 1 else ''
        else:
            user.first_name = ''
            user.last_name = ''
        user.save()
            
        # 2. Update atau Buat Foto Profil
        profile, created = Profile.objects.get_or_create(user=user)
        if foto_baru:
            profile.foto = foto_baru
            profile.save()
            
        messages.success(request, 'Profil dan foto Anda berhasil diperbarui!')
        return redirect('user_dashboard', username=user.username)
        
    return redirect('home')


def login_view(request):
    if request.method == 'POST':
        username = request.POST.get('username')
        password = request.POST.get('password')

        # Proses autentikasi mencocokkan data di database
        user = authenticate(request, username=username, password=password)

        if user is not None:
            login(request, user)
            
            # Cek Hak Akses menggunakan is_staff
            if user.is_staff:
                messages.success(request, f'Halo Staff {user.username}, selamat bekerja!')
            else:
                messages.success(request, f'Selamat datang kembali, {user.username}!')
            return redirect('home')
        else:
            messages.error(request, 'Username atau password salah.')
            return redirect('home') # Dikembalikan ke home tempat modal berada

def logout_view(request):
    logout(request)
    messages.success(request, 'Anda telah berhasil keluar.')
    return redirect('home')

def tambah_staff_view(request):
    # Proteksi backend: Jika bukan superuser, kunci aksesnya!
    if not request.user.is_superuser:
        return HttpResponseForbidden("Anda tidak memiliki akses untuk membuat akun staff.")

    if request.method == 'POST':
        username = request.POST.get('username')
        email = request.POST.get('email')
        password = request.POST.get('password')
        konfirmasi_password = request.POST.get('konfirmasi_password')

        if password != konfirmasi_password:
            messages.error(request, 'Password staff tidak cocok!')
            return redirect('home')

        if User.objects.filter(username=username).exists():
            messages.error(request, f'Username "{username}" sudah terdaftar!')
            return redirect('home')

        # Buat user baru dan setel status is_staff menjadi True
        user = User.objects.create_user(username=username, email=email, password=password)
        user.is_staff = True
        user.save()

        messages.success(request, f'Sukses mendaftarkan Staff baru: {username}!')
        return redirect('home')

    return redirect('home')

@login_required
def kelola_staff_view(request):
    if not request.user.is_superuser:
        return HttpResponseForbidden("Hanya Superadmin yang diizinkan mengakses halaman ini.")
    
    # Ambil semua user yang memiliki status is_staff=True, urutkan dari yang terbaru bergabung
    daftar_staff = User.objects.filter(is_staff=True).order_by('-date_joined')
    return render(request, 'kelola_staff.html', {'daftar_staff': daftar_staff})

@login_required
def tambah_staff_proses(request):
    if not request.user.is_superuser:
        return HttpResponseForbidden()

    if request.method == 'POST':
        username = request.POST.get('username')
        email = request.POST.get('email')
        password = request.POST.get('password')

        if User.objects.filter(username=username).exists():
            messages.error(request, f'Username "{username}" sudah terdaftar!')
            return redirect('kelola_staff')

        # Buat user baru dan setel atribut is_staff menjadi True
        user = User.objects.create_user(username=username, email=email, password=password)
        user.is_staff = True 
        user.save()

        messages.success(request, f'Akun Staff "{username}" berhasil didaftarkan!')
        return redirect('kelola_staff')


@login_required
def pecat_staff_proses(request, staff_id):
    if not request.user.is_superuser:
        return HttpResponseForbidden()
        
    staff = get_object_or_404(User, id=staff_id)
    
    # Keamanan: Jangan biarkan superadmin menonaktifkan akunnya sendiri secara tidak sengaja
    if staff.id == request.user.id:
        messages.error(request, "Anda tidak bisa mencabut hak akses akun Anda sendiri!")
        return redirect('kelola_staff')
        
    nama_staff = staff.username
    staff.is_staff = False  # Turunkan pangkat menjadi user biasa
    staff.save()
    
    messages.success(request, f'Hak akses Staff untuk "{nama_staff}" telah resmi dicabut.')
    return redirect('kelola_staff')

@login_required
def user_dashboard(request, username):
    # Keamanan: Pastikan user yang login tidak mengintip dashboard username lain
    if request.user.username != username:
        return redirect('user_dashboard', username=request.user.username)
        
    pesanan_saya = Pesanan.objects.filter(user=request.user).order_by('-tanggal_dibuat')
    pesanan_aktif = pesanan_saya.exclude(status__in=['SELESAI', 'BATAL'])
    
    context = {
        'pesanan_saya': pesanan_saya[:5],
        'total_pesanan_aktif': pesanan_aktif.count(),
        'user': request.user
    }
    return render(request, 'dashboard/user_dashboard.html', context)