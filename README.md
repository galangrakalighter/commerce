# La-Flyderm Commerce

Aplikasi marketplace dan toko online skincare La-Flyderm berbasis Django. Aplikasi ini menyediakan storefront publik, akun pelanggan, katalog produk, keranjang, checkout DOKU, pengiriman Biteship, pelacakan pesanan, serta dashboard operasional untuk mengelola produk, kategori, staf, banner, dan pesanan.

## Ringkasan fitur

### Storefront dan katalog

- Landing page, katalog produk, artikel, dan detail artikel.
- Produk dengan kategori, harga Rupiah, stok, gambar sampul, galeri foto, dan video promosi.
- Pencarian produk live melalui endpoint JSON.
- Pengurutan katalog berdasarkan harga.
- Flash sale dengan waktu berakhir.
- Detail produk dengan galeri gambar dan video portrait 9:16.
- Review produk.

### Akun pelanggan

- Registrasi, login, logout, dan syarat & ketentuan.
- Profil pengguna dan edit profil.
- Dashboard pelanggan berdasarkan username.
- Like dan wishlist produk.
- Riwayat pesanan, detail pembayaran, status pesanan, nomor resi, dan pelacakan paket.

### Keranjang dan checkout

- Tambah produk, ubah jumlah, hapus item, dan kosongkan keranjang.
- Checkout semua item atau item yang dipilih.
- Mode beli sekarang.
- Validasi stok dan pengambilan ulang harga dari database sebelum membuat pesanan.
- Perhitungan subtotal, ongkir, dan total pembayaran.
- Ongkir JNE melalui Biteship berdasarkan alamat dan koordinat Google Maps.
- Pembayaran melalui DOKU Checkout.
- Webhook pembayaran DOKU dengan validasi signature.
- Pengurangan stok setelah pembayaran berhasil, dengan perlindungan pemrosesan notifikasi ganda.
- Pembuatan pengiriman dan nomor resi melalui Biteship.

### Dashboard operasional

- Dashboard internal.
- CRUD produk: nama, kategori, harga, stok, dimensi pengiriman, gambar, galeri, video, deskripsi, dan flash sale.
- Video produk opsional maksimal 15 detik pada validasi antarmuka; pratinjau dan tampilan produk menggunakan rasio portrait.
- Format harga UI Rupiah, misalnya `Rp 189.000`; nilai tetap dikirim ke backend sebagai angka.
- CRUD kategori, staf, dan banner promo.
- Toggle status banner.
- Menonaktifkan flash sale melalui AJAX.

## Teknologi

- Python 3.9+
- Django 6
- PostgreSQL untuk production
- Gunicorn
- Nginx sebagai reverse proxy dan terminasi HTTPS
- Docker dan Docker Compose
- Tailwind CSS pada frontend
- DOKU Checkout untuk pembayaran
- Biteship untuk tarif, pengiriman, dan tracking
- Google Maps/Places untuk alamat checkout
- WhiteNoise untuk static files

## Struktur proyek

```text
commerce/
├── akun/                 # Registrasi, autentikasi, profil, staf, dashboard pelanggan
├── commerce/             # Settings, URL utama, WSGI/ASGI
├── toko/                 # Model, view, layanan pembayaran/pengiriman, admin
│   ├── migrations/
│   ├── templatetags/     # Filter tampilan, termasuk format Rupiah
│   ├── models.py
│   ├── views.py
│   └── shipping_service.py
├── templates/            # Template storefront, dashboard, checkout, dan partial
├── static/               # Asset statis
├── media/                # Upload gambar/video produk dan banner
├── Dockerfile
├── docker-compose.yml
├── requirements.txt
├── manage.py
└── .env.example
```

## Model data utama

- `Kategori`: nama dan slug kategori produk.
- `Produk`: katalog produk, harga integer dalam satuan Rupiah, stok, media, dimensi, dan flash sale.
- `ProdukGambar`: galeri foto tambahan produk.
- `Like` dan `Wishlist`: relasi pengguna dengan produk.
- `Review`: rating, komentar, pengguna, dan produk.
- `Pesanan`: pelanggan, alamat, subtotal, ongkir, total, status, data kurir, pembayaran DOKU, dan status stok.
- `DetailPesanan`: item pesanan dan harga saat pembelian untuk mengunci histori harga.
- `BannerPromo`: banner aktif/nonaktif dan URL tujuan.
- `akun.Profile`: data profil tambahan pengguna.

Harga produk tetap memakai `IntegerField` karena Rupiah disimpan tanpa pecahan. Format `Rp` dan pemisah ribuan hanya diterapkan di UI melalui filter `toko.templatetags.currency.rupiah`.

## URL utama

| Area | URL |
|---|---|
| Landing page | `/` |
| Katalog publik | `/produk` |
| Artikel | `/artikel`, `/artikel/<slug>/` |
| Storefront commerce | `/commerce/` |
| Login/registrasi | `/commerce/login/`, `/commerce/register/` |
| Detail produk | `/commerce/produk/<id>/` |
| Wishlist/like | `/commerce/wishlist/`, `/commerce/favorit/` |
| Keranjang | `/commerce/cart/buka/` |
| Checkout | `/commerce/cart/checkout/` |
| Dashboard operasional | `/commerce/dashboard/` |
| Daftar produk internal | `/commerce/dashboard/produk-list/` |
| Kategori internal | `/commerce/dashboard/kategori/` |
| Staf internal | `/commerce/dashboard/staff/` |
| Banner internal | `/commerce/dashboard/banner/` |
| Dashboard pelanggan | `/commerce/<username>/` |
| Tracking pesanan | `/commerce/pesanan/lacak/<pesanan_id>/` |
| Admin Django | `/admin/` |

Endpoint API/webhook utama:

- `GET /api/search-live/?q=...` — pencarian produk.
- `POST /api/biteship/rates/` — pilihan ongkir.
- `POST /api/doku/notification/` — webhook status pembayaran DOKU.
- `GET /api/cek-status-pesanan/<pesanan_id>/` — status tracking kurir.
- `POST /api/create-resi/` — membuat pengiriman/resi.

## Menjalankan secara lokal

### Prasyarat

- Python 3.9 atau lebih baru.
- PostgreSQL yang dapat diakses aplikasi.
- FFmpeg tidak diperlukan untuk validasi durasi karena durasi video dibaca browser.

### Instalasi

```powershell
git clone <alamat-repository>
cd commerce
python -m venv venv
.\venv\Scripts\Activate.ps1
pip install -r requirements.txt
Copy-Item .env.example .env
```

Isi `.env`, lalu jalankan:

```powershell
python manage.py migrate
python manage.py check
python manage.py runserver
```

Aplikasi tersedia di `http://127.0.0.1:8000/`.

Membuat administrator:

```powershell
python manage.py createsuperuser
```

## Konfigurasi environment

| Variabel | Fungsi |
|---|---|
| `DJANGO_SECRET_KEY` | Secret key Django production |
| `DJANGO_DEBUG` | Mode debug; gunakan `False` di production |
| `DJANGO_ALLOWED_HOSTS` | Daftar host yang diizinkan |
| `DJANGO_CSRF_TRUSTED_ORIGINS` | Origin HTTPS yang dipercaya CSRF |
| `DJANGO_SERVE_MEDIA` | Menyajikan media oleh Django bila diperlukan |
| `PUBLIC_BASE_URL` | URL publik aplikasi |
| `POSTGRES_DB` | Nama database PostgreSQL |
| `POSTGRES_USER` / `POSTGRES_PASSWORD` | Kredensial PostgreSQL |
| `POSTGRES_HOST` / `POSTGRES_PORT` | Lokasi PostgreSQL |
| `POSTGRES_SSLMODE` | Mode SSL koneksi PostgreSQL |
| `POSTGRES_CONN_MAX_AGE` | Durasi reuse koneksi |
| `BITESHIP_API_KEY` | API key production Biteship |
| `GOOGLE_MAPS_API_KEY` | API key Google Maps/Places frontend |
| `DOKU_CLIENT_ID` | Client ID DOKU |
| `DOKU_SECRET_KEY` | Secret key DOKU |

Jangan commit `.env`, secret key, password database, atau API key.

## Menjalankan dengan Docker

Docker Compose menjalankan Django/Gunicorn pada port `2026`, melakukan migrasi dan collectstatic saat container dimulai, serta menyimpan `media/` di host.

```bash
cp .env.example .env
# isi .env dengan nilai production
docker compose up -d --build web
docker compose logs -f web
```

Perintah operasional:

```bash
docker compose exec web python manage.py check
docker compose exec web python manage.py migrate
docker compose exec web python manage.py createsuperuser
docker compose restart web
docker compose down
```

Jika template atau kode berubah, gunakan build ulang:

```bash
docker compose up -d --build --force-recreate web
```

## Nginx production

Nginx meneruskan request domain ke Gunicorn pada `127.0.0.1:2026`. Untuk upload video dan beberapa gambar, virtual host perlu memiliki batas request yang cukup:

```nginx
server {
    server_name laflyderm.com www.laflyderm.com;
    client_max_body_size 50M;
}
```

Setelah perubahan:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

Error `413 Request Entity Too Large` berarti ukuran request melewati batas Nginx, bukan validasi durasi video.

## Alur pembayaran dan pengiriman

1. Pelanggan memilih produk dan item checkout.
2. Server mengambil ulang produk, harga, dan stok dari database.
3. Alamat tujuan dikirim ke Biteship untuk pilihan tarif JNE.
4. Pesanan disimpan dengan snapshot harga per item.
5. DOKU membuat URL checkout.
6. DOKU mengirim webhook status pembayaran.
7. Webhook valid mengubah status pesanan, mengurangi stok sekali, dan membuat pengiriman Biteship.
8. Nomor resi dan tracking dapat dilihat di dashboard pelanggan.

Jangan menandai pesanan lunas hanya berdasarkan data browser. Status pembayaran harus berasal dari webhook DOKU dengan signature valid.

## Media produk

- Gambar utama: `media/produk/thumbnails/`.
- Galeri: `media/produk/galeri/`.
- Video: `media/produk/videos/`.
- Banner: `media/banners/`.
- Video promosi divalidasi di browser maksimal 15 detik.
- UI menampilkan video dalam rasio portrait 9:16.
- Batas ukuran upload ditentukan Nginx (`client_max_body_size`) dan harus lebih besar dari total request.

## Pengujian dan pemeriksaan

```powershell
python manage.py check
python manage.py test
python manage.py showmigrations
```

Sebelum deploy, verifikasi login, tambah produk, upload media, keranjang, ongkir, checkout DOKU, webhook pembayaran, pengurangan stok, pembuatan resi, dan tracking.

## Catatan keamanan operasional

- Gunakan `DJANGO_DEBUG=False` di production.
- Gunakan HTTPS dan isi `CSRF_TRUSTED_ORIGINS` dengan origin resmi.
- Simpan kredensial hanya di environment atau secret manager.
- Batasi akses dashboard dan `/admin/` pada akun yang berwenang.
- Backup database PostgreSQL dan folder `media/` secara berkala.
- Monitor log Gunicorn, Nginx, DOKU, dan Biteship.
- Setelah deploy, lakukan hard refresh jika template lama masih terlihat karena cache atau container belum rebuild.

## Lisensi dan kontak

Informasi lisensi proyek belum didefinisikan di repository ini. Tambahkan file `LICENSE` dan alamat kontak resmi sebelum distribusi publik.

