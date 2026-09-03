from django.db import models
from django.contrib.auth.models import User
from django.utils import timezone
class Kategori(models.Model):
    nama = models.CharField(max_length=100)
    # Slug digunakan untuk membuat URL ramah SEO (misal: /produk/serum-wajah)
    slug = models.SlugField(unique=True, max_length=150)

    class Meta:
        verbose_name_plural = "Kategori" # Agar di halaman admin tulisannya rapi

    def __str__(self):
        return self.nama

class Produk(models.Model):
    # Menghubungkan produk ke tabel Kategori (One-to-Many)
    kategori = models.ForeignKey(Kategori, on_delete=models.SET_NULL, null=True, blank=True, related_name='produk')
    nama = models.CharField(max_length=200)
    slug = models.SlugField(unique=True, max_length=250)
    gambar = models.ImageField(upload_to='produk/thumbnails/', blank=True, null=True)
    video_produk = models.FileField(upload_to='produk/videos/', null=True, blank=True)
    deskripsi = models.TextField(blank=True, null=True)
    harga = models.IntegerField()  # Menggunakan integer agar pas untuk mata uang Rupiah
    stok = models.IntegerField(default=0)
    is_flash_sale = models.BooleanField(default=False) # Penanda untuk masuk bagian diskon
    dibuat_pada = models.DateTimeField(auto_now_add=True)
    flash_sale_end = models.DateTimeField(null=True, blank=True)

    class Meta:
        verbose_name_plural = "Produk"

    def __str__(self):
        return self.nama
    
    @property
    def status_flash_aktif(self):
        if self.is_flash_sale and self.flash_sale_end:
            return timezone.now() < self.flash_sale_end
        return False

class Like(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='produk_disukai')
    produk = models.ForeignKey(Produk, on_delete=models.CASCADE, related_name='likes')
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        # Memastikan user hanya bisa me-like 1 produk sebanyak 1 kali (tidak duplikat)
        unique_together = ('user', 'produk')

class Wishlist(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='produk_wishlist')
    produk = models.ForeignKey(Produk, on_delete=models.CASCADE, related_name='wishlists')
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        unique_together = ('user', 'produk')
        
class ProdukGambar(models.Model):
    produk = models.ForeignKey(Produk, on_delete=models.CASCADE, related_name='galeri')
    gambar = models.ImageField(upload_to='produk/galeri/')
    alt_text = models.CharField(max_length=255, blank=True, null=True)

    def __str__(self):
        return f"Gambar tambahan untuk {self.produk.nama}"
    
class Review(models.Model):
    produk = models.ForeignKey(Produk, on_delete=models.CASCADE, related_name='reviews')
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    rating = models.IntegerField(default=5) # 1 sampai 5
    komentar = models.TextField()
    tanggal = models.DateTimeField(auto_now_add=True)

    def __str__(self):
        return f"{self.user.username} - {self.produk.nama} ({self.rating} Bintang)"

class Pesanan(models.Model):
    STATUS_CHOICES = [
        ('MENUNGGU', 'Menunggu Pembayaran'),
        ('PROSES', 'Sedang Diproses'),
        ('KIRIM', 'Sedang Dikirim'),
        ('SELESAI', 'Selesai'),
        ('BATAL', 'Dibatalkan'),
    ]
    
    KURIR_CHOICES = [
        ('jne', 'JNE Express'),
        ('jnt', 'J&T Express'),
        ('sicepat', 'SiCepat'),
        ('pos', 'POS Indonesia'),
        ('anteraja', 'AnterAja'),
        ('tiki', 'TIKI'),
    ]

    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='pesanan')
    total_harga = models.IntegerField()
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default='MENUNGGU')
    catatan = models.TextField(blank=True, null=True, help_text="Catatan tambahan dari pembeli")
    
    # Data Pengiriman Sederhana
    nama_penerima = models.CharField(max_length=100)
    telepon = models.CharField(max_length=20)
    alamat_lengkap = models.TextField()
    
    status_kurir = models.CharField(max_length=20, default='gudang')
    kurir_lat = models.FloatField(null=True, blank=True)
    kurir_lon = models.FloatField(null=True, blank=True)
    no_resi = models.CharField(max_length=100, blank=True, null=True, verbose_name="Nomor Resi")
    kurir = models.CharField(max_length=20, choices=KURIR_CHOICES, blank=True, null=True, help_text="Pilih ekspedisi pengiriman")
    kode_pos = models.IntegerField(null=True, blank=True)
    
    lokasi_lat = models.FloatField(blank=True, null=True)
    lokasi_lon = models.FloatField(blank=True, null=True)
    
    # === TAMBAHAN FIELD UNTUK DOKU ===
    doku_invoice_number = models.CharField(max_length=100, blank=True, null=True)
    doku_payment_url = models.URLField(max_length=500, blank=True, null=True)
    stok_dikurangi = models.BooleanField(default=False)

    # Tracking otomatis waktu
    tanggal_dibuat = models.DateTimeField(auto_now_add=True)
    tanggal_diperbarui = models.DateTimeField(auto_now=True)

    class Meta:
        verbose_name_plural = "Daftar Pesanan"

    def __str__(self):
        return f"Invoice #INV-{self.id} - {self.user.username}"


class DetailPesanan(models.Model):
    pesanan = models.ForeignKey(Pesanan, on_delete=models.CASCADE, related_name='items')
    produk = models.ForeignKey(Produk, on_delete=models.SET_NULL, null=True)
    jumlah = models.PositiveIntegerField(default=1)
    harga_saat_beli = models.IntegerField() # Mengunci harga agar laporan keuangan tidak berantakan jika harga produk naik/turun

    class Meta:
        verbose_name_plural = "Detail Item Pesanan"

    def __str__(self):
        return f"{self.jumlah}x {self.produk.nama if self.produk else 'Produk Dihapus'} (INV-{self.pesanan.id})"
    
    @property
    def subtotal(self):
        return self.jumlah * self.harga_saat_beli

class BannerPromo(models.Model):
    judul = models.CharField(max_length=150, help_text="Nama promo (contoh: Concert Season Deal)")
    gambar = models.ImageField(upload_to='banners/', help_text="Rekomendasi ukuran banner e-commerce: 1200x450 piksel")
    url_tujuan = models.CharField(max_length=255, blank=True, null=True, help_text="Link tujuan saat diklik (contoh: /kategori/serum/ atau link eksternal)")
    is_aktif = models.BooleanField(default=True, help_text="Centang untuk menampilkan di halaman utama")
    diperbarui_pada = models.DateTimeField(auto_now=True)

    class Meta:
        verbose_name_plural = "Banner Promo Beranda"

    def __str__(self):
        return self.judul
