# Deployment cPanel Kopi Drip

## Lokasi hosting

- Repository cPanel: `/home/azwc7142/repositories/kopidrip`
- Document root: `/home/azwc7142/public_html/kopidripsidikalang.com`
- Branch GitHub: `master`
- Deploy dijalankan melalui `.cpanel.yml` dan `deploy/cpanel-deploy.sh`.

Skrip deploy menyalin file publik Laravel dan gambar editorial ke document root. Source Laravel tetap berada di direktori repository di luar document root.

## Perubahan database

Untuk perubahan yang menambah migration, lakukan backup database terlebih dahulu. Hosting ini tidak menyediakan terminal atau Composer, jadi migration dijalankan memakai helper PHP sementara di browser. Helper menghapus dirinya sesudah dijalankan; jangan menyimpan helper itu di root website atau membagikan URL-nya.

Jangan jalankan seeder pada database production. Seeder dipakai saat membuat database baru di lokal; migration production hanya memperbarui schema dan konten awal yang disiapkan untuk rilis.

## Environment

Simpan `.env`, `APP_KEY`, kredensial database, dan secret hanya pada server. Jangan menyalin file environment lokal ke hosting atau menaruhnya di document root.
