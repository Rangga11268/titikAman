# Fase 7: Deployment and Maintenance + Kata Pengantar + Kesimpulan

> Copy-paste ke makalah.

---

## KATA PENGANTAR

Puji syukur kami panjatkan kehadirat Allah SWT yang telah melimpahkan rahmat dan karunia-Nya sehingga kami dapat menyelesaikan tugas proyek perangkat lunak ini dengan baik sebagai bagian dari kegiatan IT Bootcamp. Dalam makalah ini kami membahas **"TitikAman"** — Sistem Informasi Mitigasi Banjir dan Navigasi Jalur Evakuasi Berbasis Web di Kota Bekasi.

Terima kasih kami haturkan kepada Bapak/Ibu dosen pembimbing yang telah memberikan arahan serta bimbingan, juga semua pihak yang turut memberikan kontribusi dalam penyusunan makalah ini.

Harapan kami makalah ini dapat memberikan manfaat dan inspirasi positif bagi kegiatan IT Bootcamp serta menjadi bahan evaluasi ke depannya. Namun demikian, kami menyadari bahwa masih terdapat kekurangan dalam penyusunan makalah ini. Oleh karena itu, saran dan masukan sangat kami harapkan untuk kesempurnaan sistem di masa yang akan datang.

Jakarta, ... Juni 2026

Penulis

---

## FASE 7
## DEPLOYMENT AND MAINTENANCE
## (PENERAPAN DAN PEMELIHARAAN)

### 7.1 Deployment

Deployment adalah proses penerapan aplikasi ke server agar dapat diakses oleh pengguna secara online.

#### 7.1.1 Kebutuhan Server

| Komponen | Spesifikasi |
|----------|-------------|
| PHP | 8.2 atau lebih tinggi |
| Database | MySQL 8.0+ / MariaDB 10.5+ |
| Web Server | Apache / Nginx |
| Node.js | 18+ (untuk build asset frontend) |

#### 7.1.2 Langkah Deployment

| Langkah | Perintah | Keterangan |
|---------|----------|------------|
| 1 | `composer install --no-dev` | Install dependency PHP (produksi) |
| 2 | `npm install && npm run build` | Build asset CSS/JS |
| 3 | `cp .env.example .env` | Copy file konfigurasi |
| 4 | `php artisan key:generate` | Generate key aplikasi |
| 5 | `php artisan migrate --force` | Migrasi database |
| 6 | `php artisan db:seed --class=DatabaseSeeder` | Isi data awal |
| 7 | `php artisan storage:link` | Link storage untuk file upload |
| 8 | `php artisan config:cache` | Optimasi cache |

Setelah itu, arahkan domain ke folder `/public` dan aplikasi siap diakses.

#### 7.1.3 Kebutuhan File .env

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=titikaman
DB_USERNAME=root
DB_PASSWORD=password

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=302023
REVERB_APP_KEY=app_key
REVERB_APP_SECRET=app_secret
```

### 7.2 Maintenance

Pemeliharaan sistem dilakukan untuk menjaga aplikasi tetap berjalan optimal dan aman.

#### 7.2.1 Backup Database

```bash
# Backup harian (manual)
mysqldump -u root -p titikaman > backup_$(date +%Y%m%d).sql

# Restore jika diperlukan
mysql -u root -p titikaman < backup_file.sql
```

#### 7.2.2 Prosedur Update Aplikasi

```bash
git pull origin main
composer install --no-dev
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 7.2.3 Monitoring

| Yang Dipantau | Cara | Frekuensi |
|---------------|------|-----------|
| Error aplikasi | Cek `storage/logs/laravel.log` | Harian |
| Koneksi database | Cek response halaman | Harian |
| Ruang disk | `df -h` | Mingguan |
| SSL certificate | Cek masa berlaku | Bulanan |
| Dependency keamanan | `composer audit` | Bulanan |

---

## KESIMPULAN

TitikAman berhasil dikembangkan sebagai sistem informasi mitigasi banjir yang menghubungkan empat aktor utama — **Warga, Admin Relawan, Pengelola Posko, dan Admin BPBD** — dalam satu platform terpadu.

Sistem ini mampu memberikan solusi atas permasalahan evakuasi manual, koordinasi SAR yang tumpang tindih, serta kurangnya transparansi data logistik dan banjir di Kota Bekasi. Beberapa pencapaian utama dalam pengembangan sistem ini meliputi:

1. **SOS Darurat** dengan prioritas otomatis berdasarkan jumlah korban dan kelompok rentan, serta bounding box GPS yang membatasi wilayah operasional hanya di Kota Bekasi.
2. **Mission Control** yang memungkinkan Admin Relawan melihat antrian SOS, menugaskan misi ke tim, dan berkoordinasi melalui WhatsApp dalam satu dashboard.
3. **Manajemen Posko dan Donasi** yang transparan — warga dapat melihat kebutuhan riil posko dan berdonasi secara terverifikasi.
4. **Testing dan Quality Assurance** dengan 59 test case PHPUnit (200 assertions) dan 54 black box test case yang seluruhnya berhasil lolos uji.
5. **Batasan Wilayah (Bounding Box)** yang diterapkan di semua form input (SOS, lapor banjir, registrasi posko) sehingga sistem hanya melayani wilayah Kota Bekasi.

Pengujian menunjukkan bahwa sistem berjalan stabil dan siap digunakan. Meskipun demikian, masih terdapat ruang untuk pengembangan seperti integrasi sensor IoT untuk data TMA otomatis, notifikasi push berbasis FCM, serta perluasan wilayah ke kota/kabupaten lain.

Proyek ini diselesaikan sesuai metodologi yang direncanakan dengan dokumentasi yang lengkap untuk pengembangan masa depan. TitikAman diharapkan dapat memberikan kontribusi dalam mitigasi bencana banjir di Kota Bekasi.
