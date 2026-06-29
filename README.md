# TitikAman - Platform Manajemen Kebencanaan Banjir

**TitikAman** adalah platform manajemen kebencanaan banjir terpadu (Jabodetabek & Kota Bekasi) yang menghubungkan warga terdampak, relawan kebencanaan, pengelola posko pengungsian, dan aparatur dinas secara real-time.

Aplikasi ini dibangun menggunakan **Laravel 12** secara bersih (*from scratch*) tanpa scaffolding Breeze/Jetstream untuk memastikan kontrol penuh atas struktur data dan keamanan sistem.

---

## 🚀 Fitur Utama

1. **SOS Kedaruratan & Evakuasi Prioritas**
   - Mengirimkan sinyal SOS dengan deteksi lokasi GPS otomatis.
   - Penskalaan prioritas evakuasi otomatis bagi warga kelompok rentan (lansia, balita, ibu hamil).
   - Penugasan misi penyelamatan langsung ke relawan terdekat.

2. **Peta Genangan Banjir (Crowdsourcing)**
   - Warga dapat berkontribusi melaporkan genangan banjir beserta bukti foto, lokasi jalan, dan tinggi genangan air.
   - Verifikasi laporan secara real-time oleh Admin BPBD sebelum dipublikasikan ke peta interaktif.

3. **Manajemen Logistik & Donasi Transparan**
   - Pengelola posko pengungsian mendata kebutuhan logistik spesifik.
   - Donatur dapat melihat kebutuhan posko secara langsung dan melakukan donasi mandiri tanpa perantara birokrasi yang rumit.

4. **Peringatan Dini Pintu Air**
   - Integrasi status siaga Tinggi Muka Air (TMA) dari pintu air utama (Sungai Cikeas, Sungai Bekasi, Sungai Cakung).
   - Sistem otomatisasi peringatan dini berdasarkan aliran air.

---

## 🗄️ Rancangan Database

Database menggunakan 8 tabel utama yang sesuai dengan hasil analisis kebutuhan kebencanaan nasional:

1. **`users`**: Data autentikasi dan peran akses (warga, relawan, pengelola, admin).
2. **`water_gates`**: Kondisi tinggi muka air (TMA) dan status siaga pintu air.
3. **`shelters`**: Posko pengungsian, kapasitas tampung, fasilitas kamar mandi, dan status operasional.
4. **`flood_reports`**: Laporan genangan kontribusi masyarakat (*crowdsourcing*).
5. **`shelter_needs`**: Kebutuhan logistik darurat dari tiap posko.
6. **`donations`**: Log pengiriman donasi logistik dari donatur ke posko secara langsung.
7. **`sos_requests`**: Permintaan evakuasi darurat beserta jumlah kelompok rentan terdampak.
8. **`rescue_missions`**: Misi koordinasi penyelamatan korban SOS oleh relawan di lapangan.

---

## 📂 Dokumentasi Proyek

Semua berkas rancangan, riset, dan kebutuhan sistem diletakkan di dalam folder `docs/`:
- [TAHAP RISET DAN ANALIS PERMASALAHAN BANJIR.md](file:///d:/laragon/www/titikAman/docs/TAHAP%20RISET%20DAN%20ANALIS%20PERMASALAHAN%20BANJIR.md)
- [Requirements Analisis](file:///d:/laragon/www/titikAman/docs/requirements.md)
- [Rancangan Database Lengkap](file:///d:/laragon/www/titikAman/docs/database.md)

---

## 👥 Akun Demo

Semua akun seeder memiliki password: **`password`**

| Email | Role | Kecamatan | Keterangan |
|---|---|---|---|
| `admin@example.com` | Admin_BPBD | — | Super admin, akses penuh |
| `relawan@example.com` | Admin_Relawan | Bekasi Selatan | Koordinator tim relawan |
| `lead.bekasitimur@example.com` | Admin_Relawan | Bekasi Timur | Ketua Tim Bekasi Timur |
| `lead.jatiasih@example.com` | Admin_Relawan | Jatiasih | Ketua Tim Jatiasih |
| `lead.rawalumbu@example.com` | Admin_Relawan | Rawalumbu | Ketua Tim Rawalumbu |
| `lead.bekasiutara@example.com` | Admin_Relawan | Bekasi Utara | Ketua Tim Bekasi Utara |
| `pengelola@example.com` | Pengelola_Posko | — | Mengelola Posko Masjid Agung Al-Barkah |
| `warga@example.com` | Warga | Bekasi Timur | Warga biasa, akses lapor & SOS |

> **Catatan:** Jalankan `php artisan db:seed` setelah migrasi untuk mengisi data demo.

---

## ⚙️ Persyaratan Sistem

- **PHP** >= 8.2
- **Composer**
- Database Engine (MySQL / MariaDB / SQLite)

---

## 🛠️ Langkah Instalasi

1. **Clone Repositori**
   ```bash
   git clone https://github.com/Rangga11268/titikaman.git
   cd titikaman
   ```

2. **Instal Dependensi**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Salin berkas `.env.example` ke `.env` dan sesuaikan pengaturan database Anda:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Jalankan Migrasi Database**
   ```bash
   php artisan migrate
   ```

5. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```
