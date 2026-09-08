import logging
import json, time, requests
from urllib.parse import urlparse
from django.shortcuts import render, redirect, get_object_or_404
from django.utils.dateparse import parse_datetime
from django.utils import timezone
from django.db import transaction
from django.contrib.auth.decorators import login_required
from django.core.exceptions import PermissionDenied
from django.views.decorators.http import require_GET, require_POST
from django.http import JsonResponse
from django.contrib import messages
from .forms import BannerPromoForm
from django.http import HttpResponseForbidden
from django.conf import settings
from django.utils.text import slugify
from toko.models import Produk, Kategori, Like, Wishlist, ProdukGambar, Review, Pesanan, DetailPesanan, BannerPromo
from django.db.models import Avg, Count
from django.urls import reverse
from django.views.decorators.csrf import csrf_exempt
from .shipping_service import BiteshipService, DokuService

logger = logging.getLogger(__name__)

def _checkout_items(request, source):
    if source == 'buy_now':
        raw_items = request.session.get('buy_now', {})
    elif source == 'selected':
        raw_items = request.session.get('checkout_selected', {})
    else:
        raw_items = request.session.get('cart', {})

    product_ids = []
    for product_id in raw_items:
        try:
            product_ids.append(int(product_id))
        except (TypeError, ValueError):
            continue
    products = {p.id: p for p in Produk.objects.filter(id__in=product_ids)}
    items = {}
    total = 0
    for product_id, raw_item in raw_items.items():
        try:
            product = products[int(product_id)]
            quantity = max(1, int(raw_item.get('jumlah', 1)))
        except (KeyError, TypeError, ValueError):
            continue
        items[str(product.id)] = {
            'jumlah': quantity,
            'harga': product.harga,
            'nama': product.nama,
            'berat_gram': product.berat_gram,
            'panjang_cm': product.panjang_cm,
            'lebar_cm': product.lebar_cm,
            'tinggi_cm': product.tinggi_cm,
            'subtotal': product.harga * quantity,
        }
        total += product.harga * quantity
    return items, total


def _biteship_rate_items(items):
    return [
        {
            'name': item['nama'],
            'description': 'Produk dari toko online',
            'value': int(item['harga']),
            'length': int(item['panjang_cm']),
            'width': int(item['lebar_cm']),
            'height': int(item['tinggi_cm']),
            'weight': int(item['berat_gram']),
            'quantity': int(item['jumlah']),
        }
        for item in items.values()
    ]


@require_POST
def cek_ongkir_biteship(request):
    if not request.user.is_authenticated:
        return JsonResponse({'error': 'Sesi login telah berakhir. Silakan login kembali.'}, status=401)
    try:
        data = json.loads(request.body.decode('utf-8'))
        latitude = float(data.get('destination_latitude'))
        longitude = float(data.get('destination_longitude'))
        source = str(data.get('source', '')).strip()
        items, subtotal = _checkout_items(request, source)
        if not (-11 <= latitude <= 6 and 95 <= longitude <= 141) or not items:
            return JsonResponse({'error': 'Alamat dan produk harus dipilih.'}, status=400)
        rates = BiteshipService().get_rates(
            latitude,
            longitude,
            _biteship_rate_items(items),
            couriers='jne',
        )
        options = []
        for rate in rates:
            courier = rate.get('courier_code') or rate.get('company')
            service = rate.get('courier_service_code') or rate.get('type')
            if courier == 'jne' and service and rate.get('price') is not None:
                options.append({
                    'courier': courier,
                    'service': service,
                    'name': rate.get('courier_service_name') or service.upper(),
                    'duration': rate.get('duration') or '',
                    'price': int(rate['price']),
                })
        options.sort(key=lambda option: option['price'])
        if not options:
            return JsonResponse({'error': 'JNE tidak tersedia untuk alamat ini.'}, status=422)
        return JsonResponse({'subtotal': subtotal, 'options': options})
    except (UnicodeDecodeError, json.JSONDecodeError, TypeError, ValueError):
        return JsonResponse({'error': 'Permintaan tidak valid.'}, status=400)
    except Exception as exc:
        logger.warning('Pengecekan ongkir Biteship gagal: %s', exc)
        return JsonResponse({'error': str(exc)}, status=502)

def halaman_utama(request):
    # 1. AMBIL DATA MASTER BANNER & KATEGORI
    daftar_banner = BannerPromo.objects.filter(is_aktif=True).order_by('-diperbarui_pada')
    daftar_kategori = Kategori.objects.all()
    
    # 2. QUERY SEMUA PRODUK UNTUK GRID UTAMA (Mendukung filter JavaScript)
    # Kita gabungkan produk flash sale dan reguler di sini, diurutkan agar flash sale muncul paling atas
    daftar_produk = Produk.objects.all().order_by('-is_flash_sale', '-dibuat_pada')

    # 3. GENERATE PRODUK POPULER (BACKUP ALGORITMA REKOMENDASI)
    produk_populer = Produk.objects.annotate(
        total_likes=Count('likes')
    ).order_by('-total_likes', '-dibuat_pada')[:9]
    
    user_wishlists = []
    user_likes = []
    produk_rekomendasi = None

    # 4. PROSES ALGORITMA "YANG MUNGKIN ANDA SUKA" (BERDASARKAN AKTIVITAS USER)
    if request.user.is_authenticated:
        user_likes = Like.objects.filter(user=request.user).values_list('produk_id', flat=True)
        user_wishlists = Wishlist.objects.filter(user=request.user).values_list('produk_id', flat=True)
        
        user_pembelian = DetailPesanan.objects.filter(
            pesanan__user=request.user
        ).values_list('produk_id', flat=True)
        
        # Gabungkan semua ID produk yang berinteraksi dengan user
        produk_terkait_user = list(user_likes) + list(user_wishlists) + list(user_pembelian)
        
        if produk_terkait_user:
            kategori_tertarik = Produk.objects.filter(
                id__in=produk_terkait_user
            ).values_list('kategori_id', flat=True).distinct()
            
            # Ambil produk rekomendasi acak berdasarkan ketertarikan kategori
            produk_rekomendasi = Produk.objects.filter(
                kategori_id__in=kategori_tertarik
            ).exclude(id__in=produk_terkait_user).order_by('?')[:9]

    # 5. FALLBACK SAFETY NET
    if not produk_rekomendasi or not produk_rekomendasi.exists():
        produk_rekomendasi = produk_populer

    # 6. KIRIMKAN DATA KE TEMPLATE
    context = {
        'daftar_banner': daftar_banner,
        'daftar_kategori': daftar_kategori,
        'daftar_produk': daftar_produk, # Menggantikan produk_spesial dan produk_biasa
        'produk_rekomendasi': produk_rekomendasi,
        'user_wishlists': list(user_wishlists), 
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
        try:
            berat_gram = int(request.POST.get('berat_gram', ''))
            panjang_cm = int(request.POST.get('panjang_cm', ''))
            lebar_cm = int(request.POST.get('lebar_cm', ''))
            tinggi_cm = int(request.POST.get('tinggi_cm', ''))
            if min(berat_gram, panjang_cm, lebar_cm, tinggi_cm) <= 0:
                raise ValueError
        except (TypeError, ValueError):
            messages.error(request, 'Berat dan seluruh dimensi produk harus berupa angka lebih dari 0.')
            return redirect('daftar_produk_internal')
        is_flash_sale = 'is_flash_sale' in request.POST
        deskripsi = request.POST.get('deskripsi')
        gambar = request.FILES.get('gambar')
        video = request.FILES.get('video_produk')
        
        # Tangkap data string dari datetime-local HTML
        flash_sale_end_raw = request.POST.get('flash_sale_end')
        
        # 2. PERBAIKAN LOGIKA: Jika ikut flash sale dan inputnya ada, ubah ke objek datetime
        if is_flash_sale and flash_sale_end_raw:
            flash_sale_end = parse_datetime(flash_sale_end_raw)
        else:
            flash_sale_end = None

        from django.utils.text import slugify
        slug = slugify(nama)

        if Produk.objects.filter(slug=slug).exists():
            messages.error(request, f'Produk dengan nama "{nama}" sudah ada!')
            return redirect('kelola_produk')

        kategori = get_object_or_404(Kategori, id=kategori_id)

        # 3. MASUKKAN FIELD KE DATABASE DI SINI
        produk = Produk.objects.create(
            kategori=kategori,
            nama=nama,
            slug=slug,
            gambar=gambar,
            harga=harga,
            stok=stok,
            berat_gram=berat_gram,
            panjang_cm=panjang_cm,
            lebar_cm=lebar_cm,
            tinggi_cm=tinggi_cm,
            is_flash_sale=is_flash_sale,
            flash_sale_end=flash_sale_end,  # <-- Tambahkan baris ini agar tersimpan
            deskripsi=deskripsi,
            video_produk=video
        )

        # 2. Simpan Galeri Tambahan
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
    source = request.GET.get('source')
    
    # === STEP 1: TANGKAP DATA CHECKBOX JIKA DATANG DARI FORM KERANJANG ===
    if source == 'selected' and request.method == 'POST' and 'pilihan_item' in request.POST:
        produk_terpilih_ids = request.POST.getlist('pilihan_item')
        cart = request.session.get('cart', {})
        
        checkout_selected = {}
        for prod_id in produk_terpilih_ids:
            if str(prod_id) in cart:
                checkout_selected[str(prod_id)] = cart[str(prod_id)]
                
        request.session['checkout_selected'] = checkout_selected
        request.session.modified = True
    
    # Harga selalu diambil ulang dari database, bukan dari nilai di browser/session.
    items_to_checkout, total_belanja = _checkout_items(request, source)
        
    if not items_to_checkout:
        return redirect('detail_keranjang')
        
    # === STEP 3: PROSES SUBMIT ALAMAT & INTEGRASI KE DOKU ===
    if request.method == 'POST' and request.POST.get('nama_penerima'):
        nama_penerima = request.POST.get('nama_penerima', '').strip()
        telepon = request.POST.get('telepon', '').strip()
        alamat_lengkap = request.POST.get('alamat_lengkap', '').strip()
        area_name = request.POST.get('area_search', '').strip()
        kode_pos = request.POST.get('kode_pos', '').strip()
        catatan = request.POST.get('catatan', '').strip()
        lat = request.POST.get('lat', '').strip()
        lon = request.POST.get('lon', '').strip()
        google_place_id = request.POST.get('google_place_id', '').strip()
        selected_courier = request.POST.get('selected_courier', '').strip()
        selected_service = request.POST.get('selected_service', '').strip()

        try:
            latitude = float(lat)
            longitude = float(lon)
            postal_code = int(kode_pos)
            if not (-11 <= latitude <= 6 and 95 <= longitude <= 141):
                raise ValueError
            if not alamat_lengkap or not area_name or not google_place_id or selected_courier != 'jne' or not selected_service:
                raise ValueError

            # Hitung ulang pada server agar ongkir dari browser tidak dapat dipalsukan.
            rates = BiteshipService().get_rates(
                latitude,
                longitude,
                _biteship_rate_items(items_to_checkout),
                couriers=selected_courier,
            )
            selected_rate = next(
                rate for rate in rates
                if (rate.get('courier_code') or rate.get('company')) == selected_courier
                and (rate.get('courier_service_code') or rate.get('type')) == selected_service
            )
            biaya_ongkir = int(selected_rate['price'])
        except (ValueError, TypeError, KeyError, StopIteration):
            return render(request, 'cart/checkout.html', {
                'total_belanja': total_belanja,
                'error_api': 'Pilih alamat dari saran dan pilih layanan pengiriman kembali.',
                'form_data': request.POST,
                'checkout_items': items_to_checkout.values(),
                'google_maps_api_key': settings.GOOGLE_MAPS_API_KEY,
            })
        except Exception as exc:
            logger.warning('Validasi ongkir checkout gagal: %s', exc)
            return render(request, 'cart/checkout.html', {
                'total_belanja': total_belanja,
                'error_api': f'Ongkir tidak dapat diverifikasi: {exc}',
                'form_data': request.POST,
                'checkout_items': items_to_checkout.values(),
                'google_maps_api_key': settings.GOOGLE_MAPS_API_KEY,
            })
        
        # 1. Simpan data induk Pesanan
        pesanan = Pesanan.objects.create(
            user=request.user,
            subtotal_harga=total_belanja,
            biaya_ongkir=biaya_ongkir,
            total_harga=total_belanja + biaya_ongkir,
            status='MENUNGGU',
            nama_penerima=nama_penerima,
            telepon=telepon,
            alamat_lengkap=f'{alamat_lengkap}, {area_name}',
            catatan=catatan,
            lokasi_lat=latitude,
            lokasi_lon=longitude,
            kode_pos=postal_code,
            destination_area_id=None,
            kurir=selected_courier,
            kurir_layanan=selected_service,
        )
        
        # 2. Simpan item produk ke DetailPesanan
        for produk_id, item in items_to_checkout.items():
            DetailPesanan.objects.create(
                pesanan=pesanan,
                produk_id=int(produk_id),
                jumlah=item['jumlah'],
                harga_saat_beli=item['harga']
            )
            
        # 3. Inisialisasi dan buat URL DOKU Checkout
        try:
            doku = DokuService()
            doku_result = doku.create_checkout_url(pesanan)
            
            if doku_result.get('success'):
                pesanan.doku_payment_url = doku_result.get('payment_url')
                pesanan.doku_invoice_number = doku_result.get('invoice_number')
                pesanan.save()
                kurangi_stok_pesanan(pesanan.id)
            else:
                raise Exception(doku_result.get('message', 'Gagal membuat transaksi DOKU'))
            
            # === STEP 4: REDISTRIBUSI & PEMBERSIHAN DATA KERANJANG ===
            if source == 'buy_now':
                if 'buy_now' in request.session:
                    del request.session['buy_now']
            elif source == 'selected':
                cart = request.session.get('cart', {})
                for produk_id in items_to_checkout.keys():
                    if produk_id in cart:
                        del cart[produk_id]
                request.session['cart'] = cart
                if 'checkout_selected' in request.session:
                    del request.session['checkout_selected']
            else:
                if 'cart' in request.session:
                    del request.session['cart']
            
            request.session.modified = True
            
            # Render ke halaman bayar dengan URL DOKU
            return render(request, 'cart/bayar.html', {
                'pesanan': pesanan, 
                'doku_payment_url': pesanan.doku_payment_url
            })
            
        except Exception as e:
            error_msg = f"Gagal membuat pembayaran DOKU: {str(e)}"
            print(error_msg)
            # Hapus pesanan yang terlanjur dibuat jika gagal koneksi payment gateway
            pesanan.delete()
            
            return render(request, 'cart/checkout.html', {
                'total_belanja': total_belanja,
                'error_api': error_msg,
                'form_data': request.POST,
                'checkout_items': items_to_checkout.values(),
                'google_maps_api_key': settings.GOOGLE_MAPS_API_KEY,
            })
            
    return render(request, 'cart/checkout.html', {
        'total_belanja': total_belanja,
        'checkout_items': items_to_checkout.values(),
        'google_maps_api_key': settings.GOOGLE_MAPS_API_KEY,
    })

@login_required
def bayar_ulang_pesanan_view(request, pesanan_id):
    pesanan = get_object_or_404(Pesanan, id=pesanan_id, user=request.user)

    if pesanan.status != 'MENUNGGU':
        messages.info(request, 'Pesanan ini tidak lagi menunggu pembayaran.')
        return redirect('user_dashboard', username=request.user.username)

    try:
        doku_result = DokuService().create_checkout_url(pesanan)
        if not doku_result.get('success') or not doku_result.get('payment_url'):
            raise RuntimeError(
                doku_result.get('message') or 'DOKU tidak memberikan tautan pembayaran.'
            )

        return render(request, 'cart/bayar.html', {
            'pesanan': pesanan,
            'doku_payment_url': doku_result['payment_url'],
        })

    except Exception as e:
        error_msg = f"DOKU API Error: {str(e)}"
        logger.exception('Gagal membuat pembayaran ulang DOKU: %s', e)
        return render(request, 'cart/bayar.html', {
            'pesanan': pesanan,
            'error_api': error_msg
        })


@login_required
def lanjut_pembayaran_doku(request, pesanan_id):
    pesanan = get_object_or_404(Pesanan, id=pesanan_id, user=request.user)
    payment_url = (pesanan.doku_payment_url or '').strip()

    if not payment_url:
        messages.error(request, 'Tautan pembayaran DOKU belum tersedia.')
        return redirect('user_dashboard', username=request.user.username)

    parsed_url = urlparse(payment_url)
    if parsed_url.scheme != 'https' or parsed_url.hostname != 'checkout.doku.com':
        messages.error(request, 'Tautan pembayaran DOKU tidak valid.')
        return redirect('user_dashboard', username=request.user.username)

    return redirect(payment_url)


def buat_pengiriman_biteship(pesanan):
    """Buat satu pengiriman dan simpan AWB/resi dari Biteship."""
    if pesanan.no_resi:
        return pesanan.no_resi

    result = BiteshipService().create_order(pesanan)
    if not result.get('success'):
        raise RuntimeError(
            'Biteship gagal membuat pengiriman: '
            + str(result.get('error') or result.get('message') or 'Unknown error')
        )

    courier_data = result.get('courier', {})
    nomor_resi = courier_data.get('waybill_id') or courier_data.get('tracking_id')
    if not nomor_resi:
        raise RuntimeError('Respons Biteship tidak berisi nomor resi.')

    coordinate = result.get('origin', {}).get('coordinate', {})
    pesanan.no_resi = nomor_resi
    pesanan.biteship_order_id = result.get('id')
    pesanan.biteship_tracking_id = courier_data.get('tracking_id')
    pesanan.kurir = courier_data.get('company') or pesanan.kurir or 'jne'
    pesanan.kurir_lat = coordinate.get('latitude')
    pesanan.kurir_lon = coordinate.get('longitude')
    pesanan.status = 'KIRIM'
    pesanan.save(update_fields=[
        'no_resi', 'biteship_order_id', 'biteship_tracking_id',
        'kurir', 'kurir_lat', 'kurir_lon', 'status',
        'tanggal_diperbarui',
    ])
    logger.info(
        'Biteship berhasil: pesanan_id=%s resi=%s kurir=%s',
        pesanan.id,
        nomor_resi,
        pesanan.kurir,
    )
    return nomor_resi


@csrf_exempt
@require_POST
def doku_payment_notification(request):
    target_path = request.path
    request_body = request.body.decode('utf-8')

    try:
        doku = DokuService()
        if not doku.verify_notification(request.headers, request_body, target_path):
            logger.warning(
                'Webhook DOKU ditolak: signature tidak valid '
                '(client_id=%s request_id=%s path=%s)',
                request.headers.get('Client-Id', ''),
                request.headers.get('Request-Id', ''),
                target_path,
            )
            return JsonResponse(
                {'status': 'error', 'message': 'Signature DOKU tidak valid.'},
                status=401,
            )

        payload = json.loads(request_body)
        invoice_number = payload.get('order', {}).get('invoice_number')
        transaction_status = payload.get('transaction', {}).get('status', '').upper()
        logger.info(
            'Webhook DOKU diterima: invoice=%s status=%s request_id=%s',
            invoice_number,
            transaction_status,
            request.headers.get('Request-Id', ''),
        )

        if not invoice_number:
            return JsonResponse(
                {'status': 'error', 'message': 'Nomor invoice tidak ditemukan.'},
                status=400,
            )

        if transaction_status == 'SUCCESS':
            with transaction.atomic():
                pesanan = Pesanan.objects.select_for_update().get(
                    doku_invoice_number=invoice_number
                )
                if pesanan.status == 'MENUNGGU':
                    pesanan.status = 'PROSES'
                    pesanan.save(update_fields=['status', 'tanggal_diperbarui'])

            # Lock pesanan mencegah notifikasi DOKU berulang membuat dua resi.
            with transaction.atomic():
                pesanan = Pesanan.objects.select_for_update().get(
                    doku_invoice_number=invoice_number
                )
                buat_pengiriman_biteship(pesanan)

        return JsonResponse({'status': 'success'})
    except Pesanan.DoesNotExist:
        return JsonResponse(
            {'status': 'error', 'message': 'Pesanan tidak ditemukan.'},
            status=404,
        )
    except (UnicodeDecodeError, json.JSONDecodeError):
        return JsonResponse(
            {'status': 'error', 'message': 'Payload tidak valid.'},
            status=400,
        )
    except Exception as exc:
        logger.exception('Gagal memproses notifikasi DOKU: %s', exc)
        return JsonResponse(
            {'status': 'error', 'message': 'Notifikasi gagal diproses.'},
            status=500,
        )

def bersihkan_keranjang_ajax(request):
    if request.method == 'POST':
        try:
            data = json.loads(request.body)
            pesanan_id = data.get('pesanan_id')
            
            cart = request.session.get('cart', {})
            
            # KONDISI 1: Jika pesanan_id dikirim (User Berhasil Bayar)
            if pesanan_id:
                try:
                    clean_id = int(str(pesanan_id).split('-')[0])
                    
                    # Gunakan transaction.atomic agar jika salah satu proses gagal, database dibatalkan (rollback)
                    with transaction.atomic():
                        # 1. Cari pesanan dan ubah status menjadi SELESAI
                        pesanan = Pesanan.objects.select_for_update().get(id=clean_id, user=request.user)
                        
                        # Status boleh diperbarui berkali-kali tanpa mengurangi stok dua kali.
                        if pesanan.status != 'SELESAI':
                            pesanan.status = 'SELESAI'
                            pesanan.save()

                        kurangi_stok_pesanan(clean_id)

                        item_terbeli = DetailPesanan.objects.filter(pesanan_id=clean_id)
                        for item in item_terbeli:
                                # === HAPUS DARI SESSION CART ===
                            id_produk_str = str(item.produk_id)
                            if id_produk_str in cart:
                                del cart[id_produk_str]
                                    
                            request.session['cart'] = cart
                    
                except (Pesanan.DoesNotExist, ValueError):
                    pass
                
            # KONDISI 2: Jika pesanan_id TIDAK dikirim (karena onPending)
            else:
                checkout_selected = request.session.get('checkout_selected', {})
                if checkout_selected:
                    for prod_id in checkout_selected.keys():
                        if str(prod_id) in cart:
                            del cart[str(prod_id)]
                    request.session['cart'] = cart

            # Bersihkan session sampah temporary
            if 'checkout_selected' in request.session:
                del request.session['checkout_selected']
            if 'buy_now' in request.session:
                del request.session['buy_now']
                
            request.session.modified = True
            return JsonResponse({'status': 'success', 'message': 'Status diperbarui dan stok berhasil dikurangi'})
            
        except Exception as e:
            return JsonResponse({'status': 'error', 'message': str(e)}, status=400)
            
    return JsonResponse({'status': 'error', 'message': 'Invalid request'}, status=400)


def kurangi_stok_pesanan(pesanan_id):
    """Kurangi stok seluruh item pesanan tepat satu kali."""
    with transaction.atomic():
        pesanan = Pesanan.objects.select_for_update().get(id=pesanan_id)
        if pesanan.stok_dikurangi:
            return

        items = list(
            DetailPesanan.objects.filter(pesanan=pesanan).select_related('produk')
        )
        for item in items:
            if item.produk_id is None:
                continue
            produk = Produk.objects.select_for_update().get(id=item.produk_id)
            if produk.stok < item.jumlah:
                raise ValueError(
                    f"Stok {produk.nama} tidak mencukupi. Tersedia {produk.stok}, "
                    f"dibutuhkan {item.jumlah}."
                )
            produk.stok -= item.jumlah
            produk.save(update_fields=['stok'])

        pesanan.stok_dikurangi = True
        pesanan.save(update_fields=['stok_dikurangi'])

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

def live_search_view(request):
    query = request.GET.get('q', '').strip()
    results = []
    
    if len(query) >= 2:
        produks = Produk.objects.filter(nama__icontains=query)[:6]
        
        for p in produks:
            gambar_url = p.gambar.url if p.gambar else '/static/images/default-avatar.png'
            
            detail_url = reverse('detail_produk', kwargs={'id': p.id}) 
            
            results.append({
                'id': p.id,
                'nama': p.nama,
                'harga': f"Rp {p.harga:,}".replace(",", "."),
                'gambar': gambar_url,
                'url': detail_url
            })
            
    return JsonResponse({'results': results})

def edit_produk(request, id):
    # 1. Ambil objek produk berdasarkan ID atau return 404 jika tidak ketemu
    produk = get_object_or_404(Produk, id=id)
    
    if request.method == 'POST':
        # 2. Ambil data teks dari form text/number/select
        nama = request.POST.get('nama')
        kategori_id = request.POST.get('kategori')
        harga = request.POST.get('harga')
        stok = request.POST.get('stok')
        berat_gram = request.POST.get('berat_gram')
        panjang_cm = request.POST.get('panjang_cm')
        lebar_cm = request.POST.get('lebar_cm')
        tinggi_cm = request.POST.get('tinggi_cm')
        is_flash_sale = 'is_flash_sale' in request.POST # Checkbox menghasilkan 'on' jika dicentang
        deskripsi = request.POST.get('deskripsi')
        flash_sale_end = request.POST.get('flash_sale_end')
        
        if not is_flash_sale or not flash_sale_end:
            flash_sale_end = None
        
        try:
            berat_gram = int(berat_gram)
            panjang_cm = int(panjang_cm)
            lebar_cm = int(lebar_cm)
            tinggi_cm = int(tinggi_cm)
            if min(berat_gram, panjang_cm, lebar_cm, tinggi_cm) <= 0:
                raise ValueError('Berat dan dimensi harus lebih dari 0.')

            # 3. Update data teks & relasi kategori
            kategori = get_object_or_404(Kategori, id=kategori_id)
            
            produk.nama = nama
            produk.kategori = kategori
            produk.harga = harga
            produk.stok = stok
            produk.berat_gram = berat_gram
            produk.panjang_cm = panjang_cm
            produk.lebar_cm = lebar_cm
            produk.tinggi_cm = tinggi_cm
            produk.is_flash_sale = is_flash_sale
            produk.deskripsi = deskripsi
            
            # 4. Handle Gambar Sampul Utama (Hanya ganti jika user upload file baru)
            if 'gambar' in request.FILES:
                produk.gambar = request.FILES['gambar']
                
            # 5. Handle Video Produk (Hanya ganti jika user upload file baru)
            if 'video_produk' in request.FILES:
                produk.video_produk = request.FILES['video_produk']
            
            # Simpan perubahan produk utama ke database
            produk.save()
            
            # 6. Handle Galeri Foto Tambahan (Multiple Files)
            # Jika user mengunggah foto baru di galeri, biasanya galeri lama diganti atau ditambah.
            # Di sini kita asumsikan mengganti galeri lama dengan yang baru jika ada inputan baru:
            if 'galeri_produk' in request.FILES:
                files = request.FILES.getlist('galeri_produk')
                if files:
                    # Hapus rekam jejak galeri foto lama jika ingin overwrite total
                    produk.galeri.all().delete() 
                    
                    # Simpan barisan foto galeri baru satu per satu
                    for f in files:
                        ProdukGambar.objects.create(produk=produk, gambar=f)

            messages.success(request, f'Produk "{produk.nama}" berhasil diperbarui!')
        except Exception as e:
            messages.error(request, f'Gagal memperbarui produk: {str(e)}')
            
        return redirect('daftar_produk_internal')
        
    # Jika diakses lewat GET (keamanan tambahan jika user iseng ketik URL langsung)
    return redirect('daftar_produk_internal')

def edit_kategori(request, id):
    kategori = get_object_or_404(Kategori, id=id)
    
    if request.method == 'POST':
        nama_baru = request.POST.get('nama')
        if nama_baru:
            kategori.nama = nama_baru
            kategori.slug = slugify(nama_baru) # Otomatis generate ulang slug url biar bersih
            kategori.save()
            messages.success(request, f'Kategori berhasil diubah menjadi "{nama_baru}"!')
        else:
            messages.error(request, 'Nama kategori tidak boleh kosong.')
            
    return redirect('kelola_kategori')

def matikan_flash_sale_ajax(request, produk_id):
    if request.method == 'POST':
        try:
            produk = Produk.objects.get(id=produk_id)
            produk.is_flash_sale = False
            produk.flash_sale_end = None
            produk.save()
            return JsonResponse({'status': 'success', 'message': 'Flash sale berakhir'})
        except Produk.DoesNotExist:
            return JsonResponse({'status': 'error', 'message': 'Produk tidak ditemukan'}, status=404)
    return JsonResponse({'status': 'error', 'message': 'Metode tidak diizinkan'}, status=400)

STATUS_BITESHIP_LABELS = {
    'confirmed': 'Pesanan dikonfirmasi',
    'allocated': 'Kurir dialokasikan',
    'picking_up': 'Kurir menuju lokasi pengambilan',
    'picked': 'Paket telah diambil kurir',
    'dropping_off': 'Paket menuju penerima',
    'delivered': 'Paket telah diterima',
    'cancelled': 'Pengiriman dibatalkan',
    'on_hold': 'Pengiriman ditahan sementara',
    'return_in_transit': 'Paket sedang dikembalikan',
    'returned': 'Paket telah dikembalikan',
}


@login_required
def lacak_paket(request, pesanan_id):
    pesanan = get_object_or_404(Pesanan, id=pesanan_id, user=request.user)
    tracking_data = []
    summary_data = {
        'courier': pesanan.kurir or '-',
        'awb': pesanan.no_resi or '-',
        'status': pesanan.get_status_display(),
    }
    error_msg = None

    if pesanan.no_resi and pesanan.kurir:
        try:
            cache_key = f'biteship_tracking_{pesanan.id}'
            cached = request.session.get(cache_key, {})
            if cached.get('data') and time.time() - cached.get('timestamp', 0) < 300:
                result = cached['data']
            else:
                result = BiteshipService().retrieve_tracking(
                    tracking_id=pesanan.biteship_tracking_id,
                    waybill_id=pesanan.no_resi,
                    courier_code=pesanan.kurir,
                )
                request.session[cache_key] = {
                    'timestamp': time.time(),
                    'data': result,
                }
                request.session.modified = True
            biteship_status = str(result.get('status') or '').lower()
            summary_data = {
                'courier': result.get('courier', {}).get('company') or pesanan.kurir,
                'awb': result.get('waybill_id') or pesanan.no_resi,
                'status': STATUS_BITESHIP_LABELS.get(
                    biteship_status,
                    biteship_status.replace('_', ' ').title() or 'Belum ada status',
                ),
            }
            histories = sorted(
                result.get('history') or [],
                key=lambda item: item.get('updated_at') or '',
                reverse=True,
            )
            for history in histories:
                updated_at = history.get('updated_at') or ''
                parsed_date = parse_datetime(updated_at) if updated_at else None
                if parsed_date:
                    updated_at = timezone.localtime(parsed_date).strftime('%d %b %Y, %H:%M WIB')
                history_status = str(history.get('status') or '').lower()
                tracking_data.append({
                    'desc': history.get('note') or STATUS_BITESHIP_LABELS.get(
                        history_status,
                        history_status.replace('_', ' ').title(),
                    ),
                    'date': updated_at,
                })

            update_fields = []
            if biteship_status and pesanan.status_kurir != biteship_status:
                pesanan.status_kurir = biteship_status
                update_fields.append('status_kurir')
            if biteship_status == 'delivered' and pesanan.status != 'SELESAI':
                pesanan.status = 'SELESAI'
                update_fields.append('status')
            elif biteship_status == 'cancelled' and pesanan.status != 'BATAL':
                pesanan.status = 'BATAL'
                update_fields.append('status')
            if update_fields:
                update_fields.append('tanggal_diperbarui')
                pesanan.save(update_fields=update_fields)
            logger.info(
                'Biteship Tracking berhasil: pesanan_id=%s resi=%s status=%s',
                pesanan.id,
                pesanan.no_resi,
                biteship_status,
            )
        except Exception as exc:
            logger.warning(
                'Biteship Tracking gagal: pesanan_id=%s resi=%s error=%s',
                pesanan.id,
                pesanan.no_resi,
                exc,
            )
            error_msg = f'Pelacakan Biteship belum tersedia: {exc}'
    else:
        error_msg = 'Nomor resi belum tersedia untuk pesanan ini.'
    
    # KITA TETAP MENGGUNAKAN KOORDINAT ORIGIN DARI BITESHIP (Database)
    # Jika origin_lat di database kosong (belum di-update), gunakan koordinat default toko
    origin_lat = pesanan.kurir_lat if pesanan.kurir_lat else -6.9175
    origin_lon = pesanan.kurir_lon if pesanan.kurir_lon else 107.6191

    return render(request, 'pesanan/lacak.html', {
        'pesanan': pesanan,
        'tracking_data': tracking_data,
        'summary_data': summary_data,
        'error_msg': error_msg,
        'origin_lat': origin_lat,
        'origin_lon': origin_lon
    })

@login_required
def cek_status_kurir_api(request, pesanan_id):
    try:
        pesanan = Pesanan.objects.get(id=pesanan_id, user=request.user)
        return JsonResponse({
            'status': 'success',
            'kurir_lat': float(pesanan.kurir_lat) if pesanan.kurir_lat else None,
            'kurir_lon': float(pesanan.kurir_lon) if pesanan.kurir_lon else None,
            'status_kurir': pesanan.status_kurir
        })
    except Exception as e:
        return JsonResponse({'status': 'error', 'message': str(e)}, status=400)

@login_required
def kelola_banner(request):
    if not request.user.is_staff:
        raise PermissionDenied
        
    # JIKA STAFF MENGIRIM DATA BANNER BARU (DARI MODAL)
    if request.method == 'POST':
        form = BannerPromoForm(request.POST, request.FILES)
        if form.is_valid():
            form.save()
            return redirect('kelola_banner') # Refresh halaman agar banner baru langsung muncul
            
    # JIKA STAFF HANYA MELIHAT HALAMAN (GET)
    else:
        form = BannerPromoForm()
        
    semua_banner = BannerPromo.objects.all().order_by('-diperbarui_pada')
    
    context = {
        'semua_banner': semua_banner,
        'form': form # Form dikirim ke template untuk dirender di dalam modal
    }
    return render(request, 'kelola_banner.html', context)

# 2. VIEW EDIT STATUS AKTIF (TOGGLE)
@login_required
def toggle_status_banner(request, banner_id):
    if not request.user.is_staff:
        raise PermissionDenied
    
    banner = get_object_or_404(BannerPromo, id=banner_id)
    banner.is_aktif = not banner.is_aktif  # Balikkan statusnya (True jadi False, atau sebaliknya)
    banner.save()
    return redirect('kelola_banner')

# 3. VIEW UNTUK HAPUS BANNER
@login_required
@require_POST # Mengamankan penghapusan hanya bisa lewat metode POST
def hapus_banner(request, banner_id):
    if not request.user.is_staff:
        raise PermissionDenied
        
    banner = get_object_or_404(BannerPromo, id=banner_id)
    # Hapus file gambar fisik dari penyimpanan server agar tidak menumpuk sampah data
    if banner.gambar:
        banner.gambar.delete()
        
    banner.delete()
    return redirect('kelola_banner')

@login_required
@csrf_exempt
def create_shipping_order(request):
    if request.method == 'POST':
        pesanan_id = request.POST.get('pesanan_id')
        try:
            with transaction.atomic():
                pesanan = Pesanan.objects.select_for_update().get(id=pesanan_id)
                nomor_resi = buat_pengiriman_biteship(pesanan)

            return JsonResponse({
                'status': 'success',
                'resi': nomor_resi,
                'lat': pesanan.kurir_lat,
                'lon': pesanan.kurir_lon,
            })
                
        except Pesanan.DoesNotExist:
            return JsonResponse({'status': 'error', 'message': 'Pesanan tidak ditemukan'})
        except Exception as e:
            return JsonResponse({'status': 'error', 'message': str(e)})
            
    return JsonResponse({'status': 'error', 'message': 'Invalid method'})
