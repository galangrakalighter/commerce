"""
URL configuration for commerce project.

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/6.0/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  path('', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  path('', Home.as_view(), name='home')
Including another URLconf
    1. Import the include() function: from django.urls import include, path
    2. Add a URL to urlpatterns:  path('blog/', include('blog.urls'))
"""
from django.contrib import admin
from django.urls import path
from django.conf import settings
from django.conf.urls.static import static
from toko.views import halaman_utama, dashboard_utama_view, kelola_banner, toggle_status_banner, hapus_banner, kelola_kategori_view, edit_kategori, create_shipping_order, daftar_produk_internal_view, cek_status_kurir_api, tambah_produk_proses, lacak_paket, hapus_produk_proses, bayar_ulang_pesanan_view, lanjut_pembayaran_doku, doku_payment_notification, matikan_flash_sale_ajax, tambah_kategori_proses, hapus_kategori_proses, toggle_like_view, toggle_wishlist_view, halaman_wishlist, halaman_like, detail_produk, kirim_review, tambah_ke_keranjang, detail_keranjang, hapus_dari_keranjang, checkout_view, bersihkan_keranjang_ajax, update_kuantitas_keranjang, live_search_view, edit_produk
from akun.views import register_view, login_view, logout_view, kelola_staff_view, tambah_staff_proses, pecat_staff_proses, user_dashboard, edit_profil_view, terms_view
from website.views import halaman_web, halaman_produk, halaman_artikel, detail_artikel
urlpatterns = [
    path('admin/', admin.site.urls),
    path('', halaman_web, name='home_lfd'),
    path('produk', halaman_produk, name='produk'),
    path('artikel', halaman_artikel, name='artikel'),
    path('artikel/<slug:slug>/', detail_artikel, name='detail_artikel'),
    path('commerce/', halaman_utama, name='home'),
    path('commerce/register/', register_view, name='register'),
    path('commerce/terms/', terms_view, name='terms_view'),
    path('commerce/login/', login_view, name='login'),
    path('commerce/logout/', logout_view, name='logout'),
    path('commerce/dashboard/', dashboard_utama_view, name='kelola_produk'),
    path('commerce/dashboard/kategori/', kelola_kategori_view, name='kelola_kategori'),
    path('commerce/dashboard/kategori/edit/<int:id>/', edit_kategori, name='edit_kategori'),
    path('commerce/dashboard/produk-list/', daftar_produk_internal_view, name='daftar_produk_internal'),
    path('commerce/dashboard/produk/tambah/', tambah_produk_proses, name='tambah_produk'),
    path('commerce/dashboard/produk/hapus/<int:produk_id>/', hapus_produk_proses, name='hapus_produk'),
    path('commerce/dashboard/produk/edit/<int:id>/', edit_produk, name='edit_produk'),
    path('commerce/dashboard/kategori/tambah/', tambah_kategori_proses, name='tambah_kategori'),
    path('commerce/dashboard/kategori/hapus/<int:kategori_id>/', hapus_kategori_proses, name='hapus_kategori'),
    path('commerce/dashboard/staff/', kelola_staff_view, name='kelola_staff'),
    path('commerce/dashboard/staff/tambah/', tambah_staff_proses, name='tambah_staff'),
    path('commerce/dashboard/staff/pecat/<int:staff_id>/', pecat_staff_proses, name='pecat_staff'),
    path('commerce/produk/like/<int:produk_id>/', toggle_like_view, name='toggle_like'),
    path('commerce/produk/wishlist/<int:produk_id>/', toggle_wishlist_view, name='toggle_wishlist'),
    path('commerce/wishlist/', halaman_wishlist, name='halaman_wishlist'),
    path('commerce/favorit/', halaman_like, name='halaman_like'),
    path('commerce/produk/<int:id>/', detail_produk, name='detail_produk'),
    path('commerce/produk/<int:produk_id>/review/', kirim_review, name='kirim_review'),
    path('commerce/cart/add/<int:produk_id>/', tambah_ke_keranjang, name='tambah_ke_keranjang'),
    path('commerce/cart/buka/', detail_keranjang, name='detail_keranjang'),
    path('commerce/cart/delete/<int:produk_id>/', hapus_dari_keranjang, name='hapus_dari_keranjang'),
    path('commerce/cart/checkout/', checkout_view, name='checkout'),
    path('commerce/cart/checkout/<int:pesanan_id>/', bayar_ulang_pesanan_view, name='bayar_ulang_pesanan'),
    path('commerce/pembayaran/doku/<int:pesanan_id>/', lanjut_pembayaran_doku, name='lanjut_pembayaran_doku'),
    path('api/doku/notification/', doku_payment_notification, name='doku_payment_notification'),
    path('commerce/cart/clear-ajax/', bersihkan_keranjang_ajax, name='bersihkan_keranjang_ajax'),
    path('commerce/profile/edit/', edit_profil_view, name='edit_profil'),
    path('commerce/dashboard/produk/matikan-flash-sale-ajax/<int:produk_id>/', matikan_flash_sale_ajax, name='matikan_flash_sale_ajax'),
    path('commerce/cart/update/<int:produk_id>/<str:aksi>/', update_kuantitas_keranjang, name='update_kuantitas_keranjang'),
    path('api/search-live/', live_search_view, name='live_search_view'),
    path('commerce/<str:username>/', user_dashboard, name='user_dashboard'),
    path('commerce/pesanan/lacak/<int:pesanan_id>/', lacak_paket, name='lacak_paket'),
    path('api/cek-status-pesanan/<int:pesanan_id>/', cek_status_kurir_api, name='cek_status_kurir_api'),
    path('commerce/dashboard/banner/', kelola_banner, name='kelola_banner'),
    path('commerce/dashboard/banner/toggle/<int:banner_id>/', toggle_status_banner, name='toggle_status_banner'),
    path('commerce/dashboard/banner/hapus/<int:banner_id>/', hapus_banner, name='hapus_banner'),
    path('api/create-resi/', create_shipping_order, name='create_shipping_order'),
]

if settings.DEBUG:
    urlpatterns += static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
