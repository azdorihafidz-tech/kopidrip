# Kopi Drip Sidikalang Official Website

Fondasi resmi untuk website, toko online, dan panel admin Kopi Drip Sidikalang. Phase 1 hanya menyiapkan kerangka aplikasi; fitur toko dan desain final akan dibangun pada phase berikutnya.

## Teknologi

- Laravel 12
- PHP 8.2+
- MariaDB (XAMPP)
- Blade, Livewire 3, Tailwind CSS 4, Alpine.js, Vite
- Filament 3 untuk panel admin
- Spatie Laravel Permission untuk role dasar

## Instalasi lokal

1. Pasang PHP 8.2+ beserta ekstensi `intl`, `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, dan `xml`, Composer, Node.js/npm, Git, serta XAMPP MariaDB.
2. Jalankan `composer install` dan `npm install`.
3. Salin `.env.example` menjadi `.env`, lalu isi `APP_KEY` dengan `php artisan key:generate`.
4. Atur `APP_ENV=local`, `APP_DEBUG=true`, `APP_URL=http://localhost/kopidrip`, dan koneksi `DB_*` sesuai MariaDB lokal. Jangan commit `.env`.
5. Buat database `kopidrip` (utf8mb4) bila belum ada.
6. Jalankan `php artisan migrate --seed`.
7. Jalankan `npm run build` untuk asset produksi lokal, atau `npm run dev` saat pengembangan.
8. Untuk server lokal gunakan konfigurasi Apache XAMPP dengan document root ke folder `public`, atau jalankan `php artisan serve`.

## Menjalankan aplikasi

- Website: `http://localhost/kopidrip` (tergantung konfigurasi Apache/XAMPP).
- Admin: `http://localhost/kopidrip/admin`.
- Buat akun development dengan `php artisan make:filament-user`, lalu beri role yang sesuai melalui seeder/Tinker. Akun tanpa role tidak dapat membuka panel.
- Role dasar: `super-admin`, `admin`, `editor`.

## Verifikasi dan test

- Test PHP: `php artisan test`
- Status migration: `php artisan migrate:status`
- Daftar route: `php artisan route:list`
- Bersihkan cache: `php artisan optimize:clear`
- Build frontend: `npm run build`

## Git workflow

Buat branch per pekerjaan, jalankan test/build, tinjau `git status` dan diff, lalu commit perubahan dengan pesan yang jelas. Jangan menambahkan `.env`, `vendor`, atau `node_modules`. Remote GitHub belum dikonfigurasi.

## Deployment

Production belum di-deploy. Untuk cPanel Git Version Control, siapkan repository dan document root yang menunjuk ke `public`, konfigurasi environment production di server, pasang dependencies/build asset sesuai kapabilitas hosting, lalu jalankan migration dengan prosedur backup. `.cpanel.yml` ditunda sampai path deployment dan perintah hosting dikonfirmasi agar tidak menjalankan langkah yang salah.

## Aturan project

Baca `codex.md` sebelum mengubah aplikasi. File lokal saat ini berisi ringkasan instruksi Phase 1 yang tersedia di sesi dan perlu disinkronkan dengan versi resmi project owner bila berbeda.