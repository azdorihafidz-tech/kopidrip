# Aturan Project Kopi Drip Sidikalang

> Dokumen ini adalah placeholder berdasarkan instruksi Phase 1 yang diterima pada sesi pembangunan. Sinkronkan dengan file `codex.md` resmi dari project owner sebelum pekerjaan selanjutnya bila ada versi resmi yang berbeda.

## Identitas dan stack

- Project: Kopi Drip Sidikalang Official Website + Online Store + Admin Panel.
- Domain produksi: https://kopidripsidikalang.com. Jangan mengubah domain atau melakukan deployment tanpa instruksi.
- Local project: `D:\xampp\htdocs\kopidrip`.
- Wajib menggunakan Laravel 12, PHP 8.2+, MariaDB/XAMPP, Blade, Livewire, Tailwind CSS, Alpine.js, Vite, Filament, Git, dan GitHub. Jangan upgrade ke Laravel 13 atau mengganti stack.

## Cara bekerja

1. Periksa file dan kondisi project sebelum mengubahnya.
2. Kerjakan satu phase sesuai permintaan. Jangan masuk ke phase berikutnya sebelum phase aktif selesai dan terverifikasi.
3. Jangan menghapus/merusak folder lain di `D:\xampp\htdocs` atau mengubah project Laravel lain.
4. Tangani error dengan mencari root cause, memperbaiki, dan menjalankan ulang verifikasi terkait. Jangan menyembunyikan error atau mengklaim sukses bila masih ada known error.
5. Jangan menyimpan credential produksi atau credential database di source code. `.env` tidak boleh di-commit.
6. Jangan mengisi data bisnis yang belum diberikan pemilik.
7. Jangan push ke remote sebelum repository dan remote dikonfirmasi.

## Batas Phase 1

Phase 1 hanya fondasi Laravel, environment, database, Git, Filament/auth, role/permission dasar, Website Settings, README, deployment foundation, frontend foundation, dan verifikasi. Jangan membuat homepage final, katalog/produk lengkap, checkout, pembayaran, shipping API, blog lengkap, analytics atau SEO lengkap.

## Verifikasi wajib

Jalankan dan laporkan command yang benar-benar dieksekusi: `php artisan --version`, `php artisan about`, `php artisan migrate:status`, `php artisan route:list`, `php artisan optimize:clear`, `php artisan test`, dan `npm run build`. Periksa juga koneksi database, Filament/auth, assets, dan `git status`. Jangan mengabaikan kegagalan.