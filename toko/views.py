from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from django.http import HttpResponseForbidden
from django.utils.text import slugify
from .models import Produk, Kategori

def halaman_utama(request):
    daftar_kategori = Kategori.objects.all()
    
    produk_spesial = Produk.objects.filter(is_flash_sale=True).order_by('-dibuat_pada')
    
    produk_biasa = Produk.objects.filter(is_flash_sale=False).order_by('-dibuat_pada')
    
    context = {
        'daftar_kategori': daftar_kategori,
        'produk_spesial': produk_spesial,
        'produk_biasa': produk_biasa,
    }
    
    return render(request, 'index.html', context)

@login_required
def kelola_produk_view(request):
    # Proteksi: Hanya Staff atau Superadmin yang boleh masuk
    if not request.user.is_staff and not request.user.is_superuser:
        return HttpResponseForbidden("Anda tidak memiliki hak akses ke halaman ini.")
        
    # Ambil semua data untuk ditampilkan di tabel manajemen
    daftar_produk = Produk.objects.all().order_by('-dibuat_pada')
    daftar_kategori = Kategori.objects.all()
    
    context = {
        'daftar_produk': daftar_produk,
        'daftar_kategori': daftar_kategori,
    }
    return render(request, 'kelola_produk.html', context)


@login_required
def tambah_produk_proses(request):
    if not request.user.is_staff and not request.user.is_superuser:
        return HttpResponseForbidden()

    if request.method == 'POST':
        nama = request.POST.get('nama')
        kategori_id = request.POST.get('kategori')
        harga = request.POST.get('harga')
        stok = request.POST.get('stok')
        # Jika checkbox dicentang nilainya 'on', jika tidak nilainya None
        is_flash_sale = request.POST.get('is_flash_sale') == 'on'
        deskripsi = request.POST.get('deskripsi')
        gambar = request.FILES.get('gambar')

        # Buat slug otomatis sederhana dari nama produk
        from django.utils.text import slugify
        slug = slugify(nama)

        # Validasi duplikasi slug agar tidak crash di database
        if Produk.objects.filter(slug=slug).exists():
            messages.error(request, f'Produk dengan nama "{nama}" sudah ada!')
            return redirect('kelola_produk')

        # Ambil objek kategori pilihan
        kategori = get_object_or_404(Kategori, id=kategori_id)

        # Simpan ke database
        produk = Produk.objects.create(
            kategori=kategori,
            nama=nama,
            slug=slug,
            gambar=gambar,
            harga=harga,
            stok=stok,
            is_flash_sale=is_flash_sale,
            deskripsi=deskripsi
        )
        produk.save()
        
        messages.success(request, f'Produk "{nama}" berhasil ditambahkan!')
        return redirect('daftar_produk_internal')


@login_required
def hapus_produk_proses(request, produk_id):
    if not request.user.is_staff and not request.user.is_superuser:
        return HttpResponseForbidden()
        
    produk = get_object_or_404(Produk, id=produk_id)
    nama_produk = produk.nama
    produk.delete()
    
    messages.success(request, f'Produk "{nama_produk}" berhasil dihapus!')
    return redirect('daftar_produk_internal')

@login_required
def dashboard_utama_view(request):
    if not request.user.is_staff and not request.user.is_superuser:
        return HttpResponseForbidden()
    return render(request, 'kelola_produk.html') # Menampilkan 2 kotak di atas

@login_required
def kelola_kategori_view(request):
    if not request.user.is_staff and not request.user.is_superuser:
        return HttpResponseForbidden()
    daftar_kategori = Kategori.objects.all()
    return render(request, 'kelola_kategori.html', {'daftar_kategori': daftar_kategori})

@login_required
def daftar_produk_internal_view(request):
    if not request.user.is_staff and not request.user.is_superuser:
        return HttpResponseForbidden()
    daftar_produk = Produk.objects.all().order_by('-dibuat_pada')
    daftar_kategori = Kategori.objects.all()
    return render(request, 'daftar_produk_internal.html', {
        'daftar_produk': daftar_produk,
        'daftar_kategori': daftar_kategori
    })

@login_required
def tambah_kategori_proses(request):
    if not request.user.is_staff and not request.user.is_superuser:
        return HttpResponseForbidden()

    if request.method == 'POST':
        nama = request.POST.get('nama')
        slug = slugify(nama)

        # Validasi: Cek apakah kategori dengan nama/slug ini sudah terdaftar
        if Kategori.objects.filter(slug=slug).exists():
            messages.error(request, f'Kategori "{nama}" sudah ada!')
            return redirect('kelola_kategori')

        # Simpan ke database
        kategori = Kategori.objects.create(nama=nama, slug=slug)
        kategori.save()

        messages.success(request, f'Kategori "{nama}" berhasil ditambahkan!')
        return redirect('kelola_kategori')


@login_required
def hapus_kategori_proses(request, kategori_id):
    if not request.user.is_staff and not request.user.is_superuser:
        return HttpResponseForbidden()

    kategori = get_object_or_404(Kategori, id=kategori_id)
    if kategori.produk.exists():
        messages.error(
            request, 
            f'Gagal menghapus! Kategori "{kategori.nama}" masih digunakan oleh '
            f'{kategori.produk.count()} produk. Pindahkan atau hapus produknya terlebih dahulu.'
        )
        return redirect('kelola_kategori')
    

    nama_kategori = kategori.nama
    
    # Catatan: Jika kategori dihapus, produk di dalamnya akan ikut terhapus (CASCADE)
    kategori.delete()

    messages.success(request, f'Kategori "{nama_kategori}" dan seluruh produk di dalamnya berhasil dihapus!')
    return redirect('kelola_kategori')