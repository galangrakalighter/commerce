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
from toko.views import halaman_utama, dashboard_utama_view, kelola_kategori_view, daftar_produk_internal_view, tambah_produk_proses, hapus_produk_proses, tambah_kategori_proses, hapus_kategori_proses, toggle_like_view, toggle_wishlist_view, halaman_wishlist, halaman_like, detail_produk, kirim_review, tambah_ke_keranjang, detail_keranjang, hapus_dari_keranjang, checkout_view, bersihkan_keranjang_ajax, update_kuantitas_keranjang
from akun.views import register_view, login_view, logout_view, kelola_staff_view, tambah_staff_proses, pecat_staff_proses, user_dashboard, edit_profil_view

urlpatterns = [
    path('admin/', admin.site.urls),
    path('', halaman_utama, name='home'),
    path('register/', register_view, name='register'),
    path('login/', login_view, name='login'),
    path('logout/', logout_view, name='logout'),
    path('dashboard/', dashboard_utama_view, name='kelola_produk'),
    path('dashboard/kategori/', kelola_kategori_view, name='kelola_kategori'),
    path('dashboard/produk-list/', daftar_produk_internal_view, name='daftar_produk_internal'),
    path('dashboard/produk/tambah/', tambah_produk_proses, name='tambah_produk'),
    path('dashboard/produk/hapus/<int:produk_id>/', hapus_produk_proses, name='hapus_produk'),
    path('dashboard/kategori/tambah/', tambah_kategori_proses, name='tambah_kategori'),
    path('dashboard/kategori/hapus/<int:kategori_id>/', hapus_kategori_proses, name='hapus_kategori'),
    path('dashboard/staff/', kelola_staff_view, name='kelola_staff'),
    path('dashboard/staff/tambah/', tambah_staff_proses, name='tambah_staff'),
    path('dashboard/staff/pecat/<int:staff_id>/', pecat_staff_proses, name='pecat_staff'),
    path('produk/like/<int:produk_id>/', toggle_like_view, name='toggle_like'),
    path('produk/wishlist/<int:produk_id>/', toggle_wishlist_view, name='toggle_wishlist'),
    path('wishlist/', halaman_wishlist, name='halaman_wishlist'),
    path('favorit/', halaman_like, name='halaman_like'),
    path('produk/<int:id>/', detail_produk, name='detail_produk'),
    path('produk/<int:produk_id>/review/', kirim_review, name='kirim_review'),
    path('cart/add/<int:produk_id>/', tambah_ke_keranjang, name='tambah_ke_keranjang'),
    path('cart/buka/', detail_keranjang, name='detail_keranjang'),
    path('cart/delete/<int:produk_id>/', hapus_dari_keranjang, name='hapus_dari_keranjang'),
    path('cart/checkout/', checkout_view, name='checkout'),
    path('cart/clear-ajax/', bersihkan_keranjang_ajax, name='bersihkan_keranjang_ajax'),
    path('profile/edit/', edit_profil_view, name='edit_profil'),
    path('<str:username>/', user_dashboard, name='user_dashboard'),
    path('cart/update/<int:produk_id>/<str:aksi>/', update_kuantitas_keranjang, name='update_kuantitas_keranjang'),
]

if settings.DEBUG:
    urlpatterns += static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)