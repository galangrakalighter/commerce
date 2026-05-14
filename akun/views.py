from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth import login, authenticate, logout
from django.contrib import messages
from django.contrib.auth.models import User
from django.contrib.auth.decorators import user_passes_test, login_required
from django.http import HttpResponseForbidden

def register_view(request):
    if request.method == 'POST':
        username = request.POST.get('username')
        email = request.POST.get('email')
        password = request.POST.get('password')
        konfirmasi_password = request.POST.get('konfirmasi_password')

        # Validasi Kecocokan Password
        if password != konfirmasi_password:
            messages.error(request, 'Password dan Konfirmasi Password tidak cocok!')
            return render(request, 'register.html')

        # Validasi Duplikasi Username
        if User.objects.filter(username=username).exists():
            messages.error(request, 'Username sudah digunakan!')
            return render(request, 'register.html')

        # Buat user baru (Secara default is_staff = False, otomatis jadi Pelanggan)
        user = User.objects.create_user(username=username, email=email, password=password)
        user.save()

        # Langsung otomatis login setelah sukses daftar
        login(request, user)
        messages.success(request, 'Registrasi berhasil! Selamat datang di LauraDerma.')
        return redirect('home')

    return render(request, 'register.html')


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