# Kopi Drip Sidikalang Official Website

Website resmi dan fondasi toko online Kopi Drip Sidikalang. Aplikasi ini menyediakan halaman publik berbasis CMS, panel admin Filament, katalog produk, keranjang, checkout tamu, status pesanan privat, dan fondasi pembayaran serta ongkir manual.

## Teknologi

- Laravel 12, PHP 8.2+, MariaDB, dan XAMPP
- Blade, Livewire 3, Tailwind CSS 4, Alpine.js, dan Vite
- Filament 3 dan Spatie Laravel Permission
- Git untuk version control

## Instalasi lokal

1. Pastikan PHP 8.2+ dan ekstensi `intl`, `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, dan `xml`, Composer, Node.js/npm, Git, serta MariaDB XAMPP tersedia.
2. Jalankan `composer install` dan `npm install` dari root project.
3. Salin `.env.example` ke `.env`, lalu jalankan `php artisan key:generate`.
4. Atur `APP_ENV=local`, `APP_DEBUG=true`, `APP_URL=http://localhost/kopidrip`, serta `DB_*` pada `.env`. Buat database `kopidrip` jika belum ada. Jangan commit `.env`.
5. Jalankan `php artisan migrate --seed` untuk membuat tabel, role/permission, artikel edukasi, FAQ, galeri editorial, dan konten awal.
6. Jalankan `php artisan storage:link` untuk menyediakan file publik dari `storage/app/public`.
7. Jalankan `npm run build` untuk kompilasi asset, atau `npm run dev` saat mengembangkan frontend.
8. Untuk XAMPP, aktifkan Apache `mod_rewrite` dan gunakan front controller lokal. Alternatifnya, jalankan `php artisan serve`.

## Website dan admin

- Website lokal: `http://localhost/kopidrip` (bergantung konfigurasi Apache) atau URL dari `php artisan serve`.
- Admin: `/admin`.
- Buat akun melalui `php artisan make:filament-user`, lalu beri role melalui Tinker. Akun baru tidak otomatis memperoleh akses admin.
- Role awal: `super-admin`, `admin`, dan `editor`. Akses toko/pelanggan/analitik diberikan kepada admin dan super-admin.

Konten awal berisi panduan umum tentang kopi dan ilustrasi editorial. Produk, menu, harga, alamat, nomor kontak, jam operasional, testimoni, dan promo hanya ditampilkan setelah informasinya dikonfirmasi melalui admin. Atur visibilitas mesin pencari di **Pengaturan website**.

## CMS, toko, dan keamanan

Filament mengelola branding, homepage, halaman, navigasi, produk/kategori/varian, menu, galeri, jurnal, pesanan, dan pelanggan. Unggahan publik disimpan di disk Laravel dan divalidasi. Harga serta stok selalu dibaca ulang dari database saat checkout; stok dipesan secara transaksional. Checkout belum memungut pembayaran online dan ongkir belum dikalkulasi otomatis. Admin memasukkan penawaran kurir secara manual. Pesanan tamu hanya dapat dilihat lewat token privat pada tautan pesanan.

Webhook pembayaran manual hanya menerima signature HMAC dan memeriksa waktu, idempotensi, jumlah, serta status pesanan. Secret tidak disediakan; konfigurasi hanya diperlukan setelah integrasi tepercaya dipilih. Analytics menyimpan tayangan halaman publik, host referrer, serta parameter UTM yang dibatasi. Data formulir, alamat IP, user-agent, query bebas, halaman checkout, dan tautan token pesanan tidak dicatat sebagai analytics. GA4 tidak aktif tanpa measurement ID.

## Pengujian dan build

```powershell
php artisan test
npm run build
```

Periksa juga migrasi dengan `php artisan migrate:status`, route dengan `php artisan route:list`, dan cache dengan `php artisan optimize:clear`.

## Git workflow

Periksa `git status` dan diff sebelum membuat perubahan. Jalankan tes dan build sebelum commit. Jangan tambahkan `.env`, `vendor`, atau `node_modules`. Jangan push sebelum remote GitHub disepakati.

## Deployment

Production menggunakan GitHub dan cPanel Git Version Control. Lihat `.cpanel.yml` serta `docs/deployment/cpanel.md` untuk alur deploy. Siapkan environment/APP_KEY dan secret terpisah, build asset, tautkan storage, backup database, lalu jalankan migration secara terencana. Jangan menyalin `.env` lokal ke production.

## Panduan project

Baca `codex.md` sebelum mengubah aplikasi. Sinkronkan file itu dengan versi resmi project owner jika kontennya berbeda.
