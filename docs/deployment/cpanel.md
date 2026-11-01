# Catatan deployment cPanel

Belum ada deployment production yang dilakukan. Sebelum membuat `.cpanel.yml`, project owner/hosting perlu menetapkan:

- direktori checkout Git Version Control;
- direktori release dan document root domain;
- lokasi `public` Laravel yang dapat ditetapkan sebagai document root;
- versi PHP dan ekstensi yang tersedia;
- lokasi Composer/Node atau strategi asset build;
- prosedur backup dan waktu migration;
- kredensial database, `APP_KEY`, dan secret webhook yang disimpan di environment hosting.

Jangan menjalankan `php artisan migrate --seed` pada production karena seeder menambahkan konten demo. Jangan menaruh `.env`, `vendor`, atau direktori source Laravel di dalam area publik yang dapat diakses web. Sesudah detail target disetujui, `.cpanel.yml` harus hanya berisi perintah deploy yang cocok dengan path akun tersebut.
