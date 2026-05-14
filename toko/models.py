from django.db import models

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
    gambar = models.ImageField(upload_to='produk/', blank=True, null=True)
    deskripsi = models.TextField(blank=True, null=True)
    harga = models.IntegerField()  # Menggunakan integer agar pas untuk mata uang Rupiah
    stok = models.IntegerField(default=0)
    is_flash_sale = models.BooleanField(default=False) # Penanda untuk masuk bagian diskon
    dibuat_pada = models.DateTimeField(auto_now_add=True)

    class Meta:
        verbose_name_plural = "Produk"

    def __str__(self):
        return self.nama