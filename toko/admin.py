from django.contrib import admin
from .models import Kategori, Produk, ProdukGambar

@admin.register(Kategori)
class KategoriAdmin(admin.ModelAdmin):
    # Otomatis mengisi kolom slug ketika kita mengetik Nama Kategori
    prepopulated_fields = {'slug': ('nama',)}

class ProdukGambarInline(admin.TabularInline):
    model = ProdukGambar
    extra = 3
@admin.register(Produk)
class ProdukAdmin(admin.ModelAdmin):
    # Kolom apa saja yang mau ditampilkan di daftar tabel admin
    list_display = ['nama', 'kategori', 'harga', 'stok', 'is_flash_sale']
    # Fitur filter di sebelah kanan halaman admin
    list_filter = ['is_flash_sale', 'kategori']
    # Otomatis mengisi kolom slug ketika kita mengetik Nama Produk
    prepopulated_fields = {'slug': ('nama',)}
    inlines = [ProdukGambarInline]