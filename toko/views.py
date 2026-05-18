import midtransclient, json
from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.http import JsonResponse
from django.contrib import messages
from django.http import HttpResponseForbidden
from django.conf import settings
from django.utils.text import slugify
from toko.models import Produk, Kategori, Like, Wishlist, ProdukGambar, Review, Pesanan, DetailPesanan
from django.db.models import Avg
from django.urls import reverse

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
    
    # 2. PERBAIKAN DI SINI: Harus pakai 'dashboard/user_dashboard.html'
    # Karena di gambar, filenya dibungkus folder 'dashboard'
    return render(request, 'dashboard/user_dashboard.html', context)

def tambah_ke_keranjang(request, produk_id):
    produk = get_object_or_404(Produk, id=produk_id)
    id_str = str(produk_id)
    
    if request.method == 'POST':
        try:
            jumlah_input = int(request.POST.get('jumlah', 1))
            if jumlah_input < 1:
                jumlah_input = 1
        except (ValueError, TypeError):
            jumlah_input = 1
    else:
        jumlah_input = 1

    # JALUR 1: Beli Langsung (Checkout Langsung)
    if request.method == 'POST' and request.POST.get('action') == 'checkout_langsung':
        if 'buy_now' in request.session:
            del request.session['buy_now']
            
        request.session['buy_now'] = {
            id_str: {
                'jumlah': jumlah_input,
                'harga': produk.harga
            }
        }
        request.session.modified = True
        
        # Menggunakan reverse agar rute URL akurat sesuai urls.py (/cart/checkout/)
        url_checkout = reverse('checkout') + '?source=buy_now'
        return redirect(url_checkout)

    # JALUR 2: Tambah ke Keranjang Biasa
    cart = request.session.get('cart', {})
    
    if id_str in cart:
        cart[id_str]['jumlah'] += jumlah_input
    else:
        cart[id_str] = {
            'jumlah': jumlah_input,
            'harga': produk.harga
        }
        
    request.session['cart'] = cart
    request.session.modified = True
    
    return redirect('detail_keranjang')

def detail_keranjang(request):
    cart = request.session.get('cart', {})
    item_keranjang = []
    total_belanja = 0
    
    # Format data session agar mudah dibaca oleh template HTML
    for produk_id, item in cart.items():
        produk = get_object_or_404(Produk, id=int(produk_id))
        subtotal = item['jumlah'] * item['harga']
        total_belanja += subtotal
        
        item_keranjang.append({
            'produk': produk,
            'jumlah': item['jumlah'],
            'subtotal': subtotal
        })
        
    context = {
        'item_keranjang': item_keranjang,
        'total_belanja': total_belanja
    }
    return render(request, 'cart/detail_keranjang.html', context)

def hapus_dari_keranjang(request, produk_id):
    cart = request.session.get('cart', {})
    id_str = str(produk_id)
    
    # Jika produk ditemukan di dalam keranjang, hapus
    if id_str in cart:
        cart.pop(id_str)
        
    request.session['cart'] = cart
    request.session.modified = True
    
    return redirect('detail_keranjang')

@login_required
def checkout_view(request):
    # Cek parameter penanda di URL (?source=buy_now)
    is_buy_now = request.GET.get('source') == 'buy_now'
    
    if is_buy_now:
        # Hanya ambil produk dari session buy_now
        items_to_checkout = request.session.get('buy_now', {})
    else:
        # Ambil semua produk dari keranjang biasa
        items_to_checkout = request.session.get('cart', {})
        
    if not items_to_checkout:
        return redirect('detail_keranjang')
        
    # Hitung total belanja hanya dari item yang sedang di-checkout
    total_belanja = sum(item['jumlah'] * item['harga'] for item in items_to_checkout.values())
    
    if request.method == 'POST':
        nama_penerima = request.POST.get('nama_penerima')
        telepon = request.POST.get('telepon')
        alamat_lengkap = request.POST.get('alamat_lengkap')
        catatan = request.POST.get('catatan', '')
        
        # 1. Simpan data induk Pesanan
        pesanan = Pesanan.objects.create(
            user=request.user,
            total_harga=total_belanja,
            status='MENUNGGU',
            nama_penerima=nama_penerima,
            telepon=telepon,
            alamat_lengkap=alamat_lengkap,
            catatan=catatan
        )
        
        # 2. Simpan item produk ke DetailPesanan
        for produk_id, item in items_to_checkout.items():
            DetailPesanan.objects.create(
                pesanan=pesanan,
                produk_id=int(produk_id),
                jumlah=item['jumlah'],
                harga_saat_beli=item['harga']
            )
            
        # 3. Inisialisasi Midtrans
        snap = midtransclient.Snap(
            is_production=settings.MIDTRANS_IS_PRODUCTION,
            server_key=settings.MIDTRANS_SERVER_KEY
        )
        
        # 4. Set parameter data Midtrans
        transaction_details = {
            'order_id': f"INV-{pesanan.id}", 
            'gross_amount': int(total_belanja)
        }
        customer_details = {
            'first_name': nama_penerima,
            'phone': telepon,
            'email': request.user.email
        }
        
        param = {
            'transaction_details': transaction_details,
            'customer_details': customer_details
        }
        
        try:
            transaction = snap.create_transaction(param)
            snap_token = transaction['token']
            
            # ================== PERBAIKAN DI BAGIAN INI ==================
            if is_buy_now:
                # JIKA BELI LANGSUNG: Hanya hapus session 'buy_now'. 
                # Session 'cart' (keranjang lama) sengaja DIBIARKAN UTUH agar tidak hilang.
                if 'buy_now' in request.session:
                    del request.session['buy_now']
            else:
                # JIKA CHECKOUT BIASA: Baru hapus seluruh isi keranjang belanja belanjaan
                if 'cart' in request.session:
                    del request.session['cart']
            
            request.session.modified = True
            # =============================================================
            
            return render(request, 'cart/bayar.html', {
                'pesanan': pesanan, 
                'snap_token': snap_token,
                'client_key': settings.MIDTRANS_CLIENT_KEY
            })
            
        except Exception as e:
            error_msg = f"Midtrans API Error: {str(e)}"
            print(error_msg)
            
            return render(request, 'cart/checkout.html', {
                'total_belanja': total_belanja,
                'error_api': error_msg
            })
            
    return render(request, 'cart/checkout.html', {'total_belanja': total_belanja})

def bersihkan_keranjang_ajax(request):
    if request.method == 'POST':
        # 1. Kosongkan session keranjang
        request.session['cart'] = {}
        request.session.modified = True
        
        # 2. Ambil data pesanan_id dari JavaScript jika ada
        try:
            data = json.loads(request.body)
            pesanan_id = data.get('pesanan_id')
            if pesanan_id:
                pesanan = Pesanan.objects.get(id=pesanan_id)
                pesanan.status = 'SELESAI' # Ubah status menjadi Selesai
                pesanan.save()
        except Exception as e:
            print(f"Gagal mengupdate status pesanan: {e}")
            
        return JsonResponse({'status': 'success', 'message': 'Keranjang dibersihkan dan status diperbarui'})
    return JsonResponse({'status': 'error', 'message': 'Metode tidak diizinkan'}, status=405)

def update_kuantitas_keranjang(request, produk_id, aksi):
    cart = request.session.get('cart', {})
    id_str = str(produk_id)
    
    if id_str in cart:
        if aksi == 'tambah':
            cart[id_str]['jumlah'] += 1
        elif aksi == 'kurang':
            cart[id_str]['jumlah'] -= 1
            
            # Jika jumlah menjadi kurang dari 1, otomatis hapus item dari keranjang
            if cart[id_str]['jumlah'] < 1:
                del cart[id_str]
                
        request.session['cart'] = cart
        request.session.modified = True
        
    return redirect('detail_keranjang')