from django.db import models
from django.contrib.auth.models import User

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
    deskripsi = models.TextField(blank=True, null=True)
    harga = models.IntegerField()  # Menggunakan integer agar pas untuk mata uang Rupiah
    stok = models.IntegerField(default=0)
    is_flash_sale = models.BooleanField(default=False) # Penanda untuk masuk bagian diskon
    dibuat_pada = models.DateTimeField(auto_now_add=True)

    class Meta:
        verbose_name_plural = "Produk"

    def __str__(self):
        return self.nama

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