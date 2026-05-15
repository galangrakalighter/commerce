from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.http import JsonResponse
from django.contrib import messages
from django.http import HttpResponseForbidden
from django.utils.text import slugify
from .models import Produk, Kategori, Like, Wishlist, ProdukGambar, Review
from django.db.models import Avg

def halaman_utama(request):
    produk_spesial = Produk.objects.filter(is_flash_sale=True)
    produk_biasa = Produk.objects.filter(is_flash_sale=False)
    daftar_kategori = Kategori.objects.all()

    user_wishlists = []
    user_likes = []

    if request.user.is_authenticated:
        user_wishlists = Wishlist.objects.filter(user=request.user).values_list('produk_id', flat=True)
        user_likes = Like.objects.filter(user=request.user).values_list('produk_id', flat=True)

    context = {
        'produk_spesial': produk_spesial,
        'produk_biasa': produk_biasa,
        'daftar_kategori': daftar_kategori,
        'user_wishlists': list(user_wishlists), # Diubah ke list biasa agar Django template mudah membacanya
        'user_likes': list(user_likes),
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
        is_flash_sale = request.POST.get('is_flash_sale') == 'on'
        deskripsi = request.POST.get('deskripsi')
        gambar = request.FILES.get('gambar')

        from django.utils.text import slugify
        slug = slugify(nama)

        if Produk.objects.filter(slug=slug).exists():
            messages.error(request, f'Produk dengan nama "{nama}" sudah ada!')
            return redirect('kelola_produk')

        kategori = get_object_or_404(Kategori, id=kategori_id)

        # 1. Simpan ke database produk utama
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
        # produk.save()  # create() sudah otomatis memanggil save()

        # 2. TAMBAHKAN BAGIAN INI: Simpan Galeri Tambahan
        # 'galeri_produk' harus sesuai dengan name="galeri_produk" di modal HTML
        gambar_galeri = request.FILES.getlist('galeri_produk')
        
        for foto in gambar_galeri:
            ProdukGambar.objects.create(
                produk=produk, 
                gambar=foto
            )
        
        messages.success(request, f'Produk "{nama}" dan galerinya berhasil ditambahkan!')
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

@login_required
def toggle_like_view(request, produk_id):
    if request.method == 'POST':
        produk = get_object_or_404(Produk, id=produk_id)
        like_obj, created = Like.objects.get_or_create(user=request.user, produk=produk)
        
        if not created:
            # Jika sudah ada, artinya user klik untuk membatalkan (Unlike)
            like_obj.delete()
            status = 'unliked'
        else:
            status = 'liked'
            
        return JsonResponse({'status': status, 'total_likes': produk.likes.count()})
    return JsonResponse({'error': 'Invalid request'}, status=400)

@login_required
def toggle_wishlist_view(request, produk_id):
    if request.method == 'POST':
        produk = get_object_or_404(Produk, id=produk_id)
        wishlist_obj, created = Wishlist.objects.get_or_create(user=request.user, produk=produk)
        
        if not created:
            # Jika sudah ada, hapus dari wishlist
            wishlist_obj.delete()
            status = 'removed'
        else:
            status = 'added'
            
        return JsonResponse({'status': status})
    return JsonResponse({'error': 'Invalid request'}, status=400)

@login_required
def halaman_wishlist(request):
    wishlist_items = Wishlist.objects.filter(user=request.user).select_related('produk')
    
    user_wishlists = list(Wishlist.objects.filter(user=request.user).values_list('produk_id', flat=True))

    context = {
        'wishlist_items': wishlist_items,
        'user_wishlists': user_wishlists,
        'judul': 'Wishlist Saya',
    }
    return render(request, 'wishlist.html', context)

@login_required
def halaman_like(request):
    like_items = Like.objects.filter(user=request.user).select_related('produk')
    
    user_likes = list(Like.objects.filter(user=request.user).values_list('produk_id', flat=True))

    context = {
        'like_items': like_items,
        'user_likes': user_likes,
        'judul': 'Produk Favorit',
    }
    return render(request, 'likes.html', context)

def detail_produk(request, id):
    # Mengambil produk atau return 404 jika tidak ada
    produk = get_object_or_404(Produk, id=id)
    
    # Rekomendasi produk terkait (dari kategori yang sama, exclude produk ini sendiri)
    produk_terkait = Produk.objects.filter(kategori=produk.kategori).exclude(id=id)[:4]
    
    # Cek status wishlist & like untuk user yang login
    is_wishlisted = False
    is_liked = False
    reviews = produk.reviews.all().order_by('-tanggal')
    rata_rata_rating = reviews.aggregate(Avg('rating'))['rating__avg'] or 0
    user_sudah_review = False
    
    if request.user.is_authenticated:
        is_wishlisted = Wishlist.objects.filter(user=request.user, produk=produk).exists()
        is_liked = Like.objects.filter(user=request.user, produk=produk).exists()
        user_sudah_review = Review.objects.filter(produk=produk, user=request.user).exists()

    context = {
        'produk': produk,
        'produk_terkait': produk_terkait,
        'reviews': reviews,
        'is_wishlisted': is_wishlisted,
        'is_liked': is_liked,
        'rata_rata_rating': round(rata_rata_rating, 1),
        'jumlah_review': reviews.count(),
        'user_sudah_review': user_sudah_review,
    }
    return render(request, 'detail_produk.html', context)

def kelola_simpan_produk(request):
    if request.method == 'POST':
        # 1. Simpan data produk utama
        nama = request.POST.get('nama')
        harga = request.POST.get('harga')
        gambar_utama = request.FILES.get('gambar_utama')
        
        produk = Produk.objects.create(
            nama=nama,
            harga=harga,
            gambar=gambar_utama
        )

        # 2. Ambil list gambar dari input galeri (multiple)
        gambar_galeri = request.FILES.getlist('galeri_produk')
        
        for f in gambar_galeri:
            ProdukGambar.objects.create(produk=produk, gambar=f)
            
        return redirect('daftar_produk')

def kirim_review(request, produk_id):
    if request.method == "POST":
        if not request.user.is_authenticated:
            return JsonResponse({'status': 'error', 'message': 'Silakan login terlebih dahulu.'}, status=403)

        produk = get_object_or_404(Produk, id=produk_id)
        
        # CEK: Apakah user sudah pernah mereview produk ini?
        sudah_review = Review.objects.filter(produk=produk, user=request.user).exists()
        
        if sudah_review:
            return JsonResponse({
                'status': 'error', 
                'message': 'Anda sudah memberikan ulasan untuk produk ini.'
            }, status=400)

        rating = request.POST.get('rating')
        komentar = request.POST.get('komentar')

        # Simpan review jika belum pernah
        review = Review.objects.create(
            produk=produk,
            user=request.user,
            rating=int(rating),
            komentar=komentar
        )

        return JsonResponse({
            'status': 'success',
            'username': review.user.username,
            'rating': review.rating,
            'komentar': review.komentar,
            'tanggal': review.tanggal.strftime('%d %B %Y'),
            'avatar': review.user.username[0].upper()
        })
    
    return JsonResponse({'status': 'error', 'message': 'Invalid request'}, status=400)