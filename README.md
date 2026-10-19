# Commerce Gafi

Commerce Gafi adalah aplikasi e-commerce berbasis Laravel untuk katalog produk, transaksi pelanggan, promosi, voucher, dan publikasi artikel. Aplikasi memiliki halaman publik dan dashboard admin.

## Teknologi

- PHP 8.3+
- Laravel 13 dan Eloquent ORM
- Blade, Vite, Tailwind CSS
- CKEditor 5 untuk artikel
- MySQL/MariaDB (atau database yang didukung Laravel)
- n8n webhook untuk generate artikel otomatis

## Fitur aplikasi

### Halaman publik

- Halaman utama, Beranda, Tentang Kami, dan dashboard promo.
- Katalog, pencarian, filter kategori, dan detail produk.
- Keranjang belanja.
- Favorit dan wishlist.
- Rating/review produk.
- Daftar artikel terbit dan detail artikel berdasarkan slug.
- Banner aktif pada halaman beranda/artikel.
- Notifikasi pengguna dan status sudah dibaca.

### Autentikasi dan otorisasi

- Registrasi, login, logout, dan session pengguna.
- Fitur pengguna dilindungi middleware `auth`.
- Dashboard pengelolaan dilindungi middleware `auth` dan `admin`.

### Dashboard admin

Admin memiliki CRUD untuk promo, kategori produk, produk, voucher, banner, artikel, dan kategori artikel. Artikel mendukung draft/published, kategori, slug, excerpt, metadata, gambar cover, pencarian, dan upload gambar melalui CKEditor.

### Keyword generate artikel

Menu Kelola Artikel memiliki dua konfigurasi:

- **Keyword biasa:** tepat satu data, berisi keyword, prompt artikel, dan prompt gambar.
- **Keyword planning:** banyak data berdasarkan tanggal; satu tanggal hanya boleh memiliki satu planning.

Saat generate, planning pada tanggal hari ini dipilih lebih dahulu. Jika tidak ada, sistem memakai keyword biasa. Prompt panjang dapat dilihat melalui tombol Selengkapnya dan modal detail.

## Struktur proyek

```text
app/Http/Controllers/    Controller HTTP, dashboard, dan AJAX
app/Models/              Model Eloquent
app/Services/             Layanan aplikasi, termasuk respons n8n
app/Console/Commands/     Command generate artikel
database/migrations/      Struktur database
resources/views/          Template Blade
routes/web.php            Route HTTP
routes/console.php        Jadwal command
public/                   Document root dan aset publik
storage/app/public/       Disk publik Laravel lokal
```

## Instalasi lokal

Persyaratan: PHP 8.3+, Composer, Node.js/npm, dan database.

```bash
git clone <url-repository>
cd commerce_gafi
composer install
copy .env.example .env
php artisan key:generate
```

Atur koneksi database di `.env`, lalu jalankan:

```bash
php artisan migrate
php artisan storage:link
npm install
npm run build
php artisan serve
```

Untuk pengembangan aset frontend gunakan `npm run dev`. Script gabungan tersedia melalui `composer run dev`.

## Konfigurasi environment

Contoh konfigurasi production/local:

```dotenv
APP_NAME="Commerce Gafi"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password

FILESYSTEM_DISK=public
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

Production harus menggunakan `APP_DEBUG=false`, HTTPS pada `APP_URL`, dan kredensial database yang aman.

## Database

Migration membuat tabel pengguna/session, `promo`, `kategori`, `produk`, `voucher`, `banner`, `articles`, `articles_categories`, `ratings`, `wishlist`, `keranjang`, `like`, `user_product_actions`, dan `user_notification_reads`.

Migration artikel generate menambahkan:

- `article_generate_keywords`: keyword biasa tunggal, prompt artikel, dan prompt gambar.
- `article_generate_keyword_plans`: banyak planning dengan tanggal unik, keyword, prompt artikel, dan prompt gambar.

Tabel `artikel` dan `kategori_artikel` adalah struktur legacy. Fitur artikel aktif memakai `articles` dan `articles_categories`.

## Route publik

| Method | URL | Keterangan |
|---|---|---|
| GET | `/` | Halaman utama |
| GET | `/beranda` | Beranda |
| GET | `/tentang-kami` | Tentang Kami |
| GET | `/promo-dashboard` | Promo publik |
| GET | `/articles-all` | Daftar artikel terbit |
| GET | `/articles/detail/{slug}` | Detail artikel |
| GET | `/articles/fetch` | Baris tabel artikel untuk AJAX |
| GET | `/search` | Pencarian |
| GET | `/filter-produk/{kategori_id?}` | Filter produk |
| GET | `/produk/detail/{id}` | Detail produk |

## Route pengguna login

- `POST /logout`
- `POST /notifications/mark-as-read`
- `POST /produk/{id}/review`
- `POST /product/{product}/toggle-favorite`
- `POST /product/{product}/toggle-wishlist`
- `GET /keranjang` dan `GET /product-favorit`
- `DELETE /keranjang/{id}/delete` dan `POST /keranjang/delete-batch`
- `POST /artikel/upload-image` untuk CKEditor

## Route admin

Resource CRUD tersedia pada `/promo`, `/kategori`, `/produk`, `/voucher`, `/banner`, dan `/articles`. Kategori artikel menggunakan `POST /categories`, `PUT /categories-edit/{id}`, dan `DELETE /categories-delete/{id}`.

Keyword biasa menggunakan:

- `POST /article-generate-keyword`
- `PUT /article-generate-keyword/{id}`
- `DELETE /article-generate-keyword/{id}`

Keyword planning menggunakan:

- `POST /article-generate-keyword-plan`
- `PUT /article-generate-keyword-plan/{id}`
- `DELETE /article-generate-keyword-plan/{id}`

Semua route admin membutuhkan autentikasi dan hak admin.

## Generate artikel otomatis

`routes/console.php` menjadwalkan command `app:trigger-n8n-webhook` setiap menit. Command hanya memproses jam 08.00–16.00 waktu Asia/Jakarta, memilih konfigurasi planning hari ini atau fallback keyword biasa, kemudian mengirim kategori, jam, keyword, prompt artikel, prompt gambar, dan sumber konfigurasi ke webhook n8n.

Respons n8n disimpan melalui `App\Services\ArticleService::storeFromN8n()`.

Scheduler server harus menjalankan:

```bash
php artisan schedule:run
```

Pada shared hosting, gunakan Cron Jobs panel hosting. URL webhook n8n sebaiknya dipindahkan ke environment variable sebelum production.

## Penyimpanan gambar

Pada instalasi Laravel standar, disk publik berada di `storage/app/public` dan diakses melalui `public/storage` setelah `php artisan storage:link`.

Untuk shared hosting tanpa terminal/symbolic link, gambar artikel disimpan langsung ke:

```text
public_html/storage/articles
```

Nilai kolom database tetap berbentuk `articles/nama-file.ext`, sehingga URL menjadi `/storage/articles/nama-file.ext`. Pastikan folder tersebut ada, dapat ditulis PHP, dan memiliki permission yang sesuai (umumnya `755`).

## Deployment shared hosting

1. Upload source aplikasi dan `vendor` atau install dependency melalui fasilitas hosting.
2. Pastikan document root domain mengarah ke folder `public`, atau `index.php` sudah mengarah ke folder aplikasi yang benar.
3. Isi `.env` production dan jalankan migration. Jika tidak ada terminal, gunakan phpMyAdmin/SQL sesuai migration.
4. Buat `public_html/storage/articles` dengan permission `755`.
5. Pastikan `storage`, `bootstrap/cache`, dan folder upload dapat ditulis PHP.
6. Jangan mengunggah cache lokal dari `bootstrap/cache`, terutama cache yang berisi path Windows seperti `D:\laragon\...`.
7. Setelah perubahan konfigurasi, bersihkan cache dengan `php artisan optimize:clear`. Tanpa terminal, hapus cache route/config yang dibuat di server melalui File Manager.

## Troubleshooting

### Daftar artikel gagal dimuat

Periksa `/articles/fetch`, method `ArticleController::fetch()`, status response, cache route, dan `storage/logs/laravel.log`. Endpoint tersebut harus mengembalikan HTML baris tabel.

### Gambar tidak tampil

Pastikan file ada di `public_html/storage/articles`, nilai database diawali `articles/`, permission folder benar, dan URL `/storage/articles/...` dapat diakses. Bersihkan cache browser setelah deployment.

### Error JSON diawali `<!DOCTYPE`

Frontend mengharapkan JSON tetapi menerima HTML error atau redirect. Periksa status HTTP, session login, CSRF, route cache, dan log Laravel.

### Muncul folder path Windows di server

Hapus cache konfigurasi lokal yang ikut terunggah dari `bootstrap/cache`, lalu buat cache baru di server. Folder seperti `D:\laragon\www\...` tidak boleh menjadi path production.

## Pemeriksaan setelah instalasi

- Registrasi, login, logout, dan akses admin.
- CRUD produk, kategori, promo, voucher, banner, artikel, dan kategori artikel.
- Keranjang, favorit, wishlist, review, dan notifikasi.
- Upload cover artikel dan gambar CKEditor.
- Refresh daftar artikel setelah tambah/edit/hapus.
- Keyword biasa tunggal dan keyword planning multi-tanggal.
- Fallback planning ke keyword biasa.
- Scheduler dan webhook n8n.

## Lisensi

Framework Laravel menggunakan lisensi MIT. Lisensi bisnis, konten, dan aset Commerce Gafi mengikuti kebijakan pemilik aplikasi.
