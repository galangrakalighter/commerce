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
from django.views.decorators.csrf import csrf_exempt

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
        video = request.FILES.get('video_produk')

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
            deskripsi=deskripsi,
            video_produk=video
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
    # Cek parameter penanda di URL (?source=...)
    source = request.GET.get('source')
    
    # === STEP 1: TANGKAP DATA CHECKBOX JIKA DATANG DARI FORM KERANJANG ===
    if source == 'selected' and request.method == 'POST' and 'pilihan_item' in request.POST:
        produk_terpilih_ids = request.POST.getlist('pilihan_item')
        cart = request.session.get('cart', {})
        
        # Saring isi cart lama, ambil yang id-nya dicentang saja
        checkout_selected = {}
        for prod_id in produk_terpilih_ids:
            if str(prod_id) in cart:
                checkout_selected[str(prod_id)] = cart[str(prod_id)]
                
        # Amankan ke session temporary khusus checkout ini
        request.session['checkout_selected'] = checkout_selected
        request.session.modified = True
    
    # === STEP 2: TENTUKAN SUMBER DATA PRODUK YANG AKAN DI-CHECKOUT ===
    if source == 'buy_now':
        # Kondisi 1: Langsung beli dari halaman katalog / detail produk
        items_to_checkout = request.session.get('buy_now', {})
    elif source == 'selected':
        # Kondisi 2: Menggunakan produk hasil pilihan centang di keranjang
        items_to_checkout = request.session.get('checkout_selected', {})
    else:
        # Kondisi 3: Fallback jika klik checkout biasa tanpa parameter (Beli Semua)
        items_to_checkout = request.session.get('cart', {})
        
    # Jika tidak ada item yang siap diproses, kembalikan ke keranjang
    if not items_to_checkout:
        return redirect('detail_keranjang')
        
    # Hitung total belanja hanya dari item yang sedang lolos seleksi checkout
    total_belanja = sum(item['jumlah'] * item['harga'] for item in items_to_checkout.values())
    
    # === STEP 3: PROSES SUBMIT ALAMAT & INREMENT KE MIDTRANS ===
    # Pengecekan 'nama_penerima' memastikan ini adalah POST dari form alamat checkout, bukan POST dari checkbox keranjang
    if request.method == 'POST' and request.POST.get('nama_penerima'):
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
            
            # === STEP 4: REDISTRIBUSI & PEMBERSIHAN DATA KERANJANG ===
            if source == 'buy_now':
                # JIKA BELI LANGSUNG: Hanya hapus session flash buy_now
                if 'buy_now' in request.session:
                    del request.session['buy_now']
                    
            elif source == 'selected':
                # JIKA CHECKOUT SELEKSI:
                cart = request.session.get('cart', {})
                # Hapus HANYA produk yang tadi dicentang dari keranjang belanja utama
                for produk_id in items_to_checkout.keys():
                    if produk_id in cart:
                        del cart[produk_id]
                
                # Simpan sisa barang yang tidak dicentang agar tetap awet di dalam cart
                request.session['cart'] = cart
                # Hapus data sampah temporary pilihan
                if 'checkout_selected' in request.session:
                    del request.session['checkout_selected']
            else:
                # JIKA CHECKOUT ALL (Tanpa Filter): Bersihkan seluruh isi keranjang biasa
                if 'cart' in request.session:
                    del request.session['cart']
            
            request.session.modified = True
            
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
        try:
            data = json.loads(request.body)
            pesanan_id = data.get('pesanan_id')
            
            cart = request.session.get('cart', {})
            
            # KONDISI 1: Jika pesanan_id dikirim (User Berhasil Bayar / Skenario Seleksi)
            if pesanan_id:
                # Ambil semua item produk yang dibeli di dalam pesanan ini
                item_terbeli = DetailPesanan.objects.filter(pesanan_id=pesanan_id)
                
                # Hapus HANYA produk yang ada di pesanan ini dari session cart
                for item in item_terbeli:
                    id_produk_str = str(item.produk_id)
                    if id_produk_str in cart:
                        del cart[id_produk_str]
                
                # Simpan sisa produk yang tidak dipilih kembali ke session
                request.session['cart'] = cart
                
            # KONDISI 2: Jika pesanan_id TIDAK dikirim (karena onPending / VA baru keluar)
            else:
                # Sesuai logika onPending di JS-mu, jika datang dari keranjang belanja seleksi (selected),
                # hapus item yang dicentang dari cart utama agar tidak double pas bayar nanti.
                checkout_selected = request.session.get('checkout_selected', {})
                
                if checkout_selected:
                    for prod_id in checkout_selected.keys():
                        if str(prod_id) in cart:
                            del cart[str(prod_id)]
                    request.session['cart'] = cart

            # Bersihkan session sampah temporary jika ada
            if 'checkout_selected' in request.session:
                del request.session['checkout_selected']
            if 'buy_now' in request.session:
                del request.session['buy_now']
                
            request.session.modified = True
            return JsonResponse({'status': 'success', 'message': 'Keranjang berhasil diperbarui'})
            
        except Exception as e:
            return JsonResponse({'status': 'error', 'message': str(e)}, status=400)
            
    return JsonResponse({'status': 'error', 'message': 'Invalid request'}, status=400)

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
        is_flash_sale = request.POST.get('is_flash_sale') == 'on' # Checkbox menghasilkan 'on' jika dicentang
        deskripsi = request.POST.get('deskripsi')
        
        try:
            # 3. Update data teks & relasi kategori
            kategori = get_object_or_404(Kategori, id=kategori_id)
            
            produk.nama = nama
            produk.kategori = kategori
            produk.harga = harga
            produk.stok = stok
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
                        GaleriProduk.objects.create(produk=produk, gambar=f)

            messages.success(request, f'Produk "{produk.nama}" berhasil diperbarui!')
        except Exception as e:
            messages.error(request, f'Gagal memperbarui produk: {str(e)}')
            
        return redirect('kelola_produk')
        
    # Jika diakses lewat GET (keamanan tambahan jika user iseng ketik URL langsung)
    return redirect('kelola_produk')