# Buku Panduan TitikAman

**Sistem Informasi Mitigasi Banjir dan Navigasi Jalur Evakuasi**
*Platform Manajemen Kebencanaan Banjir Terpadu — Kota Bekasi*

---

> **PETUNJUK PEMBUATAN PDF:**
> - Gunakan **font**: Judul bab = **Poppins Bold 18pt**, Sub-judul = **Poppins SemiBold 14pt**, Isi = **Inter Regular 11pt**, Caption/kode = **JetBrains Mono 9pt**
> - Warna aksen: `#006A60` (teal TitikAman) untuk judul bab dan garis pemisah
> - Setiap `<!-- PAGEBREAK -->` = pindah halaman baru
> - Tabel menggunakan header dengan background `#006A60` (putih untuk teks), baris genap `#F5F5F5`

---

<!-- PAGEBREAK -->

## DAFTAR ISI

1. [Apa Itu TitikAman?](#1-apa-itu-titikaman)
2. [Tech Stack](#2-tech-stack)
3. [Struktur Tim & Peran](#3-struktur-tim--peran)
4. [Panduan Instalasi Lengkap](#4-panduan-instalasi-lengkap)
5. [Menjalankan Aplikasi](#5-menjalankan-aplikasi)
6. [Alur Sistem & Cara Pakai](#6-alur-sistem--cara-pakai)
7. [Fitur per Role](#7-fitur-per-role)
8. [Database & Struktur Data](#8-database--struktur-data)
9. [FAQ & Troubleshooting](#9-faq--troubleshooting)

---

<!-- PAGEBREAK -->

## 1. Apa Itu TitikAman?

TitikAman adalah **sistem informasi kebencanaan berbasis web** yang dirancang untuk membantu proses mitigasi, respons, dan evakuasi bencana banjir di wilayah **Kota Bekasi dan sekitarnya**. Platform ini menghubungkan warga terdampak, relawan kebencanaan, pengelola posko pengungsian, dan aparatur dinas (BPBD) dalam satu sistem terpadu.

### Fitur Utama

| Fitur | Manfaat |
|-------|---------|
| **SOS Darurat** | Warga terjebak banjir bisa mengirim sinyal evakuasi dengan lokasi GPS otomatis |
| **Peta Genangan** | Visualisasi titik banjir berdasarkan laporan partisipatif dari warga (crowdsourcing) |
| **Dashboard TMA** | Data tinggi muka air pintu air (Sungai Cikeas, Bekasi, Cakung) real-time |
| **Manajemen Posko** | Update kapasitas pengungsi, kebutuhan logistik, dan donasi |
| **Misi Penyelamatan** | Admin Relawan menugaskan tim evakuasi ke lokasi SOS dengan koordinasi WhatsApp |
| **Integrasi WhatsApp** | Koordinasi tim dan pengiriman instruksi evakuasi via WhatsApp group |
| **Donasi Publik** | Warga bisa donasi logistik langsung ke posko tujuan |

### Tujuan

- Mempercepat respons evakuasi korban banjir
- Menyediakan data tinggi muka air secara real-time
- Memudahkan koordinasi antara warga, relawan, dan BPBD
- Meningkatkan transparansi distribusi bantuan logistik

### Batasan Wilayah

Sistem **hanya melayani wilayah Kota Bekasi dan sekitarnya** dengan batas koordinat:

| Batas | Latitude | Longitude |
|-------|----------|-----------|
| Selatan | -6.350 | - |
| Utara | -6.100 | - |
| Barat | - | 106.800 |
| Timur | - | 107.100 |

Validasi diterapkan di semua form: SOS, lapor banjir, registrasi posko, dan update lokasi.

---

<!-- PAGEBREAK -->

## 2. Tech Stack

| Kategori | Teknologi |
|---|---|
| **Framework** | Laravel 12 |
| **Database** | MySQL / MariaDB / SQLite |
| **Bahasa Pemrograman** | PHP 8.3, JavaScript, CSS |
| **Text Editor** | Visual Studio Code |
| **Server** | Apache / Nginx (Production), `php artisan serve` (Local Development) |
| **Real-time** | Laravel Reverb (WebSocket) |
| **Peta Interaktif** | Leaflet.js + OpenStreetMap / CartoDB |
| **CSS Framework** | Tailwind CSS v4 |
| **Ikon** | Lucide Icons |
| **Font** | Inter, Plus Jakarta Sans |

---

<!-- PAGEBREAK -->

## 3. Struktur Tim & Peran

Sistem ini memiliki **5 peran (role)** yang saling terintegrasi:

| # | Role Database | Deskripsi |
|---|---------------|-----------|
| 1 | **Warga** | Masyarakat umum — lapor banjir, kirim SOS, donasi, lihat peta & TMA |
| 2 | **Admin_Relawan** | Koordinator tim (dispatcher) — lihat antrian SOS, tugaskan misi ke tim, kelola anggota |
| 3 | **Relawan** | Label data anggota tim (tidak punya dashboard khusus, hanya data) |
| 4 | **Pengelola_Posko** | Manajemen posko — update kapasitas, kebutuhan logistik, verifikasi donasi |
| 5 | **Admin_BPBD** | Super user — verifikasi laporan banjir, kelola TMA, setujui/tolak akun baru |

### 3.1 Warga

Warga adalah pengguna yang mendaftar sebagai masyarakat umum. Mereka bisa melaporkan banjir, mengirim sinyal SOS, dan berdonasi.

| Aktivitas | Cara |
|-----------|------|
| **Daftar Akun** | Buka /register → pilih "Warga" → isi data diri → submit (auto-login, langsung ke dashboard) |
| **Login** | Masuk dengan email & password |
| **Lapor Banjir** | Menu "Lapor Banjir" → isi tinggi air → upload foto → pilih akses jalan & listrik → submit |
| **Kirim SOS** | Menu "SOS Darurat" → deteksi GPS otomatis → isi jumlah korban & kelompok rentan → submit |
| **Donasi** | Halaman Posko(/posko) → form donasi → pilih posko & barang → isi jumlah → upload bukti kirim |
| **Lihat Peta** | Dashboard atau menu "Peta Evakuasi" → klik marker untuk detail |
| **Cek TMA** | Menu "Data Pintu Air" → lihat status tinggi muka air real-time |

### 3.2 Admin Relawan (Komandan Tim / Dispatcher)

Admin Relawan adalah koordinator tim evakuasi. Mereka bisa melihat antrian SOS, menugaskan misi ke tim, dan mengelola anggota.

| Aktivitas | Cara |
|-----------|------|
| **Login** | Login sebagai `relawan@example.com` / `password` |
| **Lihat Antrian SOS** | Dashboard → panel kiri "Antrian SOS Terbaru" |
| **Tugaskan Tim** | Klik **TUGASKAN KE TIM** pada kartu SOS → pilih tim dari dropdown → klik Tugaskan Misi |
| **Kirim WA Instruksi** | Setelah tugaskan, banner muncul → klik **WA ke Relawan** → redirect ke WhatsApp dengan pesan pre-filled |
| **Review Anggota Baru** | Card "Pendaftar Baru" → klik **Review** → lihat pratinjau dokumen → Terima atau Tolak |
| **Kirim WA Grup ke Anggota** | Setelah Terima anggota → klik **Kirim WA** → anggota dapat link grup tim |
| **Kelola Anggota Tim** | Card tim (per kecamatan) → klik → modal daftar anggota → Edit / Pindah / Hapus |
| **Selesaikan Misi** | Kartu misi aktif → klik **SELESAI** → konfirmasi → status berubah completed |
| **Lihat Riwayat Misi** | Tabel "Riwayat Misi" → 10 data terbaru → klik **Detail** → **Export CSV** |
| **Monitor Peta** | Peta operasional → marker SOS (warna prioritas) → fullscreen / refresh |

### 3.3 Pengelola Posko (Petugas Shelter)

Pengelola Posko adalah petugas yang mengelola posko pengungsian, kapasitas, dan logistik.

| Aktivitas | Cara |
|-----------|------|
| **Login** | Login sebagai `pengelola@example.com` / `password` |
| **Update Kapasitas** | Dashboard → ubah jumlah pengungsi → ubah status posko (Aktif/Penuh/Tutup) |
| **Tambah Kebutuhan** | Klik **Tambah Kebutuhan** → isi nama barang, jumlah, urgensi → submit |
| **Verifikasi Donasi** | Dashboard → tabel donasi → klik **Verifikasi** → cek fisik barang → set status "delivered" |
| **Edit/Hapus Kebutuhan** | Klik ikon pensil untuk edit → ikon sampah untuk hapus |

### 3.4 Admin BPBD (Super User)

Admin BPBD adalah administrator tertinggi yang memverifikasi laporan banjir, mengelola TMA, dan menyetujui akun pengguna baru.

| Aktivitas | Cara |
|-----------|------|
| **Login** | Login sebagai `admin@example.com` / `password` |
| **Verifikasi Laporan Banjir** | Dashboard admin → lihat laporan pending + foto → klik **Verifikasi** atau **Tolak** |
| **Set Surut** | Klik **Set Surut** pada laporan yang sudah diverifikasi → tinggi air direset ke 0 |
| **Update TMA** | Menu "Kelola TMA" → pilih pintu air → input tinggi air (cm) → sistem otomatis hitung status bahaya |
| **Verifikasi Pengguna** | Menu "Verifikasi Pengguna" → lihat data lengkap + dokumen → **Setujui** atau **Tolak** akun baru |

---

<!-- PAGEBREAK -->

## 4. Panduan Instalasi Lengkap

### Spesifikasi Minimal

| Komponen | Versi |
|----------|-------|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 18+ |
| MySQL / MariaDB | 8.0+ / 10.5+ |
| Web Server | Apache / Nginx |

### Persiapan Awal

Sebelum memulai instalasi, pastikan Anda sudah menginstall:

1. **PHP** (>= 8.2) — <https://www.php.net/downloads>
2. **Composer** — <https://getcomposer.org/download/>
3. **Node.js** (>= 18) — <https://nodejs.org/>
4. **Database** — MySQL/MariaDB atau SQLite

### Langkah Instalasi

#### Langkah 1: Clone Repositori

```bash
git clone https://github.com/Rangga11268/titikAman.git
cd titikAman
```

#### Langkah 2: Install Dependencies PHP

```bash
composer install
```

#### Langkah 3: Install Dependencies Node.js

```bash
npm install
```

#### Langkah 4: Copy File Environment

```bash
copy .env.example .env
```

> **Untuk pengguna Linux/Mac:** gunakan `cp .env.example .env`

#### Langkah 5: Generate Key Aplikasi

```bash
php artisan key:generate
```

#### Langkah 6: Setup Database

**Opsi A: SQLite (Mudah, tanpa install database)**

Buka file `.env` dan ubah:

```
DB_CONNECTION=sqlite
```

Hapus atau komentari baris `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

**Opsi B: MySQL (Disarankan untuk produksi)**

- Buat database baru (contoh: `db_titik_aman`)
- Buka file `.env` dan sesuaikan:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_titik_aman
DB_USERNAME=root
DB_PASSWORD=
```

#### Langkah 7: Setup Reverb (WebSocket)

Buka file `.env` dan sesuaikan konfigurasi Reverb:

```
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=302023
REVERB_APP_KEY=g38qjq4htihjyn8qiswa
REVERB_APP_SECRET=rahasia
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http
```

#### Langkah 8: Jalankan Migrasi

```bash
php artisan migrate
```

#### Langkah 9: Isi Data Contoh (Seeder)

```bash
php artisan db:seed
```

Perintah ini akan mengisi database dengan:

- 15 akun demo (5 role berbeda + anggota tim)
- 3 posko pengungsian
- 5 kebutuhan logistik
- 2 pintu air
- 2 laporan banjir (sudah terverifikasi)
- 2 SOS request (1 waiting, 1 completed)
- 1 misi penyelamatan

#### Langkah 10: Setup Storage Link

```bash
php artisan storage:link
```

#### Langkah 11: Compile Asset Frontend

```bash
npm run build
```

#### Langkah 12: Jalankan Server

```bash
php artisan serve
```

Buka browser dan akses: **http://localhost:8000**

#### Langkah 13: Jalankan Reverb (Untuk fitur real-time)

Buka terminal baru:

```bash
php artisan reverb:start
```

### Verifikasi Instalasi

1. Buka `http://localhost:8000` — landing page TitikAman muncul
2. Login dengan `admin@example.com` / `password` — dashboard admin terbuka
3. Cek menu "Verifikasi Pengguna" → data terisi

### Perintah Penting Setelah Pull / Update

```bash
git pull origin main
composer install
npm run build
php artisan migrate
php artisan db:seed
```

### Menjalankan Test Suite

```bash
php artisan test
```

**Total: 59 test cases (Unit + Feature)** — mencakup autentikasi, admin portal, relawan portal, donasi, dan export data.

---

<!-- PAGEBREAK -->

## 5. Menjalankan Aplikasi

### Development Mode

Buka **dua terminal** secara bersamaan:

**Terminal 1 — Web Server:**

```bash
php artisan serve
```

**Terminal 2 — Reverb (WebSocket untuk real-time):**

```bash
php artisan reverb:start
```

### Production Mode

```bash
npm run build
php artisan serve --host=0.0.0.0 --port=8000
```

### Akses Aplikasi

| URL | Keterangan |
|-----|------------|
| `http://localhost:8000` | Landing page / Halaman utama |
| `http://localhost:8000/login` | Halaman login |
| `http://localhost:8000/register` | Halaman registrasi (pilih role) |

### Akun Testing

| Role | Email | Password |
|------|-------|----------|
| Admin BPBD | `admin@example.com` | `password` |
| Admin Relawan | `relawan@example.com` | `password` |
| Pengelola Posko | `pengelola@example.com` | `password` |
| Warga | `warga@example.com` | `password` |
| Lead Tim Bekasi Timur | `lead.bekasitimur@example.com` | `password` |
| Lead Tim Jatiasih | `lead.jatiasih@example.com` | `password` |
| Lead Tim Rawalumbu | `lead.rawalumbu@example.com` | `password` |
| Lead Tim Bekasi Utara | `lead.bekasiutara@example.com` | `password` |
| Anggota Tim (Relawan label) | `anggota.bekasitimur1@example.com` | `password` |

---

<!-- PAGEBREAK -->

## 6. Alur Sistem & Cara Pakai

### Alur Utama End-to-End

```
Registrasi → Login → Dashboard → Aktivitas Sesuai Role → Logout
```

---

### 6.1 Alur Warga

#### 6.1.1 Registrasi & Login

> **PENTING:** Setelah registrasi, Warga **langsung auto-login** dan diarahkan ke dashboard (tidak perlu login ulang).

```
Buka /register → Pilih "Warga"
  → Isi Nama, No HP, Email, Password
  → Pilih Kecamatan & Kelurahan domisili (wajib wilayah Bekasi)
  → Submit
  → Auto-login → Redirect ke Dashboard Warga (/dashboard)
```

**Skenario Login Kembali (setelah logout):**

```
Buka /login
  → Masukkan email atau no HP
  → Masukkan password
  → Klik "Masuk"
  → Redirect ke Dashboard (/dashboard)
```

#### 6.1.2 Melaporkan Banjir

```
Dashboard → Klik "Lapor Banjir" atau buka /warga/lapor
  Langkah 1: Isi tinggi genangan (slider 0-200 cm)
  Langkah 2: Upload foto bukti genangan
  Langkah 3: Pilih status akses jalan & listrik
  Langkah 4: Konfirmasi lokasi (deteksi GPS otomatis, bounding box Bekasi)
  Langkah 5: Submit → status "pending" menunggu verifikasi Admin BPBD
```

#### 6.1.3 Mengirim SOS Darurat

```
Dashboard → Klik "SOS Darurat" atau buka /warga/sos
  → Deteksi lokasi GPS otomatis (allow permission)
  → Isi jumlah orang terjebak
  → Isi jumlah kelompok rentan (lansia, balita, ibu hamil, disabilitas)
  → Sistem otomatis hitung prioritas:
      • HIGH:   ada kelompok rentan ATAU >= 5 orang terjebak
      • MEDIUM: 3-4 orang terjebak (tanpa rentan)
      • LOW:    < 3 orang terjebak (tanpa rentan)
  → Submit → masuk antrian Admin Relawan
  → Status: waiting → assigned (ada tim ditugaskan) → completed (selamat)
```

#### 6.1.4 Donasi Logistik

```
Buka halaman /posko
  → Lihat daftar posko aktif + peta sebaran
  → Scroll ke bawah ke form "Kirim Bantuan Logistik"
  → Pilih posko & barang kebutuhan dari dropdown
  → Isi jumlah donasi (maks = sisa kebutuhan)
  → Upload foto bukti pengiriman
  → Submit → tunggu verifikasi pengelola posko
```

#### 6.1.5 Lihat Informasi

| Halaman | Konten |
|---------|--------|
| Dashboard (/dashboard) | Statistik posko, pengungsi, laporan banjir, berita terbaru, log aktivitas |
| Peta Evakuasi (/peta-evakuasi) | Peta interaktif marker posko & titik banjir |
| Data Pintu Air (/data-pintu-air) | TMA real-time + peta lokasi pintu air |
| Posko (/posko) | Daftar posko dengan kapasitas, status, fasilitas + form donasi |

**Data Pintu Air — Status Siaga:**

| Tinggi Air | Status | Warna |
|------------|--------|-------|
| < 80 cm | Normal | Hijau |
| 80 - 150 cm | Siaga 3 (Waspada) | Kuning |
| 150 - 250 cm | Siaga 2 (Siaga) | Oranye |
| > 250 cm | Siaga 1 (Bahaya) | Merah |

---

### 6.2 Alur Admin Relawan

#### 6.2.1 Login & Dashboard

```
Login sebagai relawan@example.com / password
→ Dashboard Mission Control dengan layout 3 panel:
  Kiri:   Antrian SOS Terbaru + Kartu misi aktif
  Tengah: Peta operasional interaktif
  Kanan:  Info tim + Riwayat misi
```

#### 6.2.2 Menangani SOS

```
Panel Kiri — Antrian SOS Terbaru:
  → Lihat daftar SOS: nama pelapor, lokasi, jumlah korban, prioritas
  → SOS baru (status waiting) → klik "TUGASKAN KE TIM"
      → Modal pilih tim → dropdown tim per kecamatan
      → Klik "Tugaskan Misi"
      → Muncul banner hijau dengan tombol:
          • WA ke Relawan: kirim instruksi ke nomor ketua tim
          • Share Grup [Nama Tim]: bagikan ke grup WhatsApp tim
          • Minta Bantuan: bagikan ke grup gabungan
          • Buka Google Maps: navigasi ke lokasi SOS

  → SOS butuh backup (status assigned, sudah ada tim) → klik "KIRIM BANTUAN TIM"
      → Proses sama seperti tugaskan misi baru (tim kedua sebagai backup)
```

#### 6.2.3 Menyelesaikan Misi

```
Panel Tengah — Misi Aktif:
  → Kartu misi yang sedang berjalan
  → Klik "SELESAI" → konfirmasi → misi selesai
  → Masuk ke Riwayat Misi (tabel)
```

#### 6.2.4 Review Anggota Baru

```
Panel Kanan — Pendaftar Baru:
  → Card "Pendaftar Baru" (jumlah pending)
  → Lihat data: nama, email, no HP, keahlian, organisasi
  → Pratinjau dokumen KTP (klik link)
  → Klik "Terima" → anggota approved
      → Banner muncul: "Kirim WA" → otomatis buka WA dengan link grup tim
  → Klik "Tolak" → anggota rejected
```

#### 6.2.5 Kelola Anggota Tim

```
Panel Kanan — Card Tim (per kecamatan):
  → "Tim Bekasi Timur (Lead: Budi Santoso)" + jumlah anggota
  → Klik card → modal daftar anggota:
      • Nama + No HP
      • Tombol Edit: ubah keahlian, organisasi, kecamatan/kelurahan
      • Tombol Pindah: pindahkan anggota ke tim lain
      • Tombol Hapus: keluarkan dari tim
```

#### 6.2.6 Riwayat & Ekspor

```
Tabel "Riwayat Misi":
  → 10 data terbaru (urutan waktu)
  → Kolom: Tanggal, Lokasi, Korban, Prioritas, Tim/Lead, Status, Aksi
  → Klik "Detail" → modal info lengkap misi
  → Klik "Export CSV" → download semua riwayat
```

#### 6.2.7 Statistik

Kartu statistik di atas dashboard:

| Statistik | Sumber Data |
|-----------|-------------|
| SOS Antri | Jumlah SOS status "waiting" |
| Prioritas Tinggi | Jumlah SOS prioritas "high" |
| Misi Aktif | Misi yang sedang berjalan |
| Misi Selesai | Total misi selesai hari ini |
| Relawan Terdaftar | Total anggota relawan terverifikasi |
| Rata-rata Respon | Waktu respon rata-rata (menit) |

---

### 6.3 Alur Pengelola Posko

#### 6.3.1 Login & Dashboard

```
Login sebagai pengelola@example.com / password
  → Dashboard Kelola Posko (/pengelola/dashboard)
  → Lihat: informasi posko, daftar kebutuhan, donasi masuk
```

#### 6.3.2 Update Kapasitas Posko

```
Dashboard:
  → Informasi posko (nama, kapasitas, pengungsi saat ini)
  → Ubah jumlah pengungsi (input number)
  → Ubah status posko:
      • Aktif: normal, masih bisa menerima pengungsi
      • Penuh: kapasitas maksimum tercapai
      • Tutup: posko tidak beroperasi (hilang dari peta publik)
  → Update fasilitas MCK (Ya/Tidak)
```

#### 6.3.3 Kelola Kebutuhan Logistik

```
Dashboard → Tombol "Tambah Kebutuhan":
  → Nama barang (contoh: Makanan Siap Saji, Susu Formula, Selimut)
  → Jumlah yang dibutuhkan
  → Tingkat urgensi (High/Medium/Low)
  → Submit → masuk ke daftar kebutuhan

Tabel Kebutuhan:
  → Edit: klik ikon pensil
  → Hapus: klik ikon sampah
```

#### 6.3.4 Verifikasi Donasi

```
Dashboard → Tabel Donasi Masuk:
  → Kolom: donatur, barang, jumlah, bukti foto, status
  → Klik "Verifikasi" → cek fisik barang
  → Set status "delivered" bila sudah sesuai
```

---

### 6.4 Alur Admin BPBD

#### 6.4.1 Login & Dashboard

```
Login sebagai admin@example.com / password
  → Admin Dashboard (/admin/dashboard) dengan:
      • Statistik: total laporan, posko, pengungsi, SOS
      • Peta ringkas: marker laporan banjir terverifikasi
      • Log aktivitas: riwayat tindakan admin
      • Laporan pending: kartu laporan yang butuh verifikasi
```

#### 6.4.2 Verifikasi Laporan Banjir

```
Dashboard → Card laporan pending:
  → Lihat detail: nama pelapor, tinggi air, foto, lokasi
  → Klik "Verifikasi" → laporan muncul di peta publik
  → Klik "Tolak" → laporan dihapus
  → Klik "Set Surut" → laporan yang sudah surut (water_height = 0)
```

#### 6.4.3 Kelola TMA (Tinggi Muka Air)

```
Menu "Kelola TMA" (/admin/tma):
  → Tabel pintu air: nama, tinggi air (cm), status bahaya, update terakhir
  → Klik "Update" → modal input tinggi air baru (cm)
  → Sistem otomatis menghitung status:
      • < 80 cm: Normal
      • 80 - 150 cm: Siaga 3 (Waspada)
      • 150 - 250 cm: Siaga 2 (Siaga)
      • > 250 cm: Siaga 1 (Bahaya)
  → Notifikasi peringatan dini berdasarkan DAS (Daerah Aliran Sungai)
```

#### 6.4.4 Verifikasi Pengguna

```
Menu "Verifikasi Pengguna" (/admin/verifikasi-pengguna):
  → Antrean pengajuan akun baru (role: Pengelola Posko)
  → Klik nama → lihat data lengkap:
      • Profil: nama, email, no HP
      • Detail posko: nama, kapasitas, alamat, fasilitas, foto
      • Lokasi: latitude, longitude (tampilan peta)
  → Klik "Setujui" → akun aktif
  → Klik "Tolak" → akun ditolak
```

---

### 6.5 Alur Relawan (Calon Anggota Tim)

#### 6.5.1 Registrasi

```
Buka /register → Pilih "Relawan / SAR"
  → Isi data diri: nama, NIK, no HP, email
  → Pilih domisili: kecamatan & kelurahan (wajib wilayah Bekasi)
  → Pilih keahlian: Water Rescue, Medis, Logistik
  → Upload dokumen KTP/sertifikat
  → Buat password
  → Submit → status "pending"

Akun relawan = hanya label data, tidak memiliki dashboard khusus.
Setelah diverifikasi Admin Relawan, data anggota muncul di tim.
```

#### 6.5.2 Cek Status Verifikasi

```
Login dengan akun yang sudah didaftarkan:
  → Sistem deteksi status "pending/rejected"
  → Redirect ke /status-verifikasi

Jika status "pending":
  → Ikon jam pasir
  → Info: "Akun Anda sedang dalam proses verifikasi"
  → Tombol: Hubungi Admin BPBD via WA
  → Tombol: Keluar (logout)

Jika status "approved":
  → Ikon centang hijau
  → Info: "Akun Anda telah diverifikasi"
  → Tampilkan link grup WhatsApp tim
  → Tombol: Masuk ke Dashboard (/dashboard)

Jika status "rejected":
  → Ikon silang merah
  → Info: "Pendaftaran ditolak"
  → Tombol: Hubungi Admin BPBD via WA
```

---

<!-- PAGEBREAK -->

## 7. Fitur per Role

### 7.1 Fitur Warga

| Halaman | URL | Fungsi |
|---------|-----|--------|
| Dashboard | `/dashboard` | Statistik banjir, berita, log aktivitas |
| Peta Evakuasi | `/peta-evakuasi` | Peta interaktif marker posko & banjir |
| Form Laporan Banjir | `/warga/lapor` | Lapor genangan banjir |
| SOS Darurat | `/warga/sos` | Kirim sinyal evakuasi darurat |
| Donasi Logistik | `/posko` | Donasi logistik ke posko |
| Data Pintu Air | `/data-pintu-air` | Status TMA real-time |
| Daftar Posko | `/posko` | Cari & filter posko aktif |

### 7.2 Fitur Admin Relawan

| Halaman | URL | Fungsi |
|---------|-----|--------|
| Dashboard Mission Control | `/relawan/dashboard` | Panel utama: antrian SOS, peta, misi, tim |
| Data SOS (AJAX) | `/relawan/sos-data` | Endpoint JSON untuk refresh peta |
| Export Riwayat Misi | `/relawan/mission/export` | Download CSV riwayat misi |

### 7.3 Fitur Pengelola Posko

| Halaman | URL | Fungsi |
|---------|-----|--------|
| Dashboard Kelola Posko | `/pengelola/dashboard` | Update kapasitas, kebutuhan, donasi |
| Donasi Masuk | `/donasi` | Lihat & verifikasi donasi (tampilan berbeda untuk pengelola) |

### 7.4 Fitur Admin BPBD

| Halaman | URL | Fungsi |
|---------|-----|--------|
| Dashboard Admin | `/admin/dashboard` | Verifikasi laporan, peta, log aktivitas |
| Kelola TMA | `/admin/tma` | Input tinggi air pintu air |
| Verifikasi Pengguna | `/admin/verifikasi-pengguna` | Setujui/tolak akun baru |

---

<!-- PAGEBREAK -->

## 8. Database & Struktur Data

### 8 Tabel Utama

| # | Nama Tabel | Fungsi |
|---|-----------|--------|
| 1 | `users` | Data pengguna (semua role) |
| 2 | `water_gates` | Data pintu air & tinggi muka air (TMA) |
| 3 | `shelters` | Data posko pengungsian (kapasitas, fasilitas, koordinat) |
| 4 | `flood_reports` | Laporan genangan banjir dari warga |
| 5 | `shelter_needs` | Kebutuhan logistik posko |
| 6 | `donations` | Transaksi donasi logistik |
| 7 | `sos_requests` | Permintaan evakuasi darurat (SOS) |
| 8 | `rescue_missions` | Misi penyelamatan (penugasan ke tim) |

### Relasi Antar Tabel

```
users ──┬── flood_reports   (1:N — warga melaporkan banjir)
         ├── sos_requests     (1:N — warga mengirim SOS)
         ├── donations        (1:N — warga menyumbang)
         └── rescue_missions  (1:N — relawan menangani misi)

shelters ──┬── shelter_needs  (1:N — posko punya kebutuhan logistik)
           └── shelter_needs ── donations (1:N — kebutuhan dipenuhi donasi)

sos_requests ── rescue_missions (1:N — 1 SOS bisa ditugaskan ke banyak tim)
```

### Status Workflow

```
SOS Request:     waiting → assigned → completed
Flood Report:    pending → verified / rejected (bisa Set Surut)
Shelter:         active → full → closed
Donation:        pending → accepted → delivered
User Akun:       pending → approved / rejected
```

---

<!-- PAGEBREAK -->

## 9. FAQ & Troubleshooting

### Error Umum & Solusi

| Error | Penyebab | Solusi |
|-------|----------|--------|
| `Target class [controller] does not exist` | Cache autoload | `composer dump-autoload` |
| `Base table or view not found` | Migrasi belum jalan | `php artisan migrate` |
| `No application encryption key` | Key belum digenerate | `php artisan key:generate` |
| Foto/File tidak muncul | Storage link belum dibuat | `php artisan storage:link` |
| `Call to a member function format() on null` | Data seeder belum jalan | `php artisan db:seed` |
| Error WebSocket/Pusher | Reverb tidak jalan | `php artisan reverb:start` |
| Halaman 403 | Role tidak punya akses | Login dengan akun yang sesuai |
| Halaman 419 | Sesi habis | Login ulang |
| Halaman 500 | Error server | Cek log: `storage/logs/laravel.log` |

### Tips Developer

**Reset Database (data testing baru):**

```bash
php artisan migrate:fresh --seed
```

**Lihat daftar route:**

```bash
php artisan route:list
```

**Lihat log error terbaru:**

```bash
# Windows (PowerShell)
Get-Content storage/logs/laravel.log -Tail 20

# Linux/Mac
tail -20 storage/logs/laravel.log
```

**Clear cache:**

```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

**Cek data via Tinker:**

```bash
php artisan tinker
> \App\Models\User::count()
> \App\Models\User::where('role', 'Admin_Relawan')->pluck('email')
> \App\Models\SosRequest::where('status', 'waiting')->count()
```

### Kontak & Dukungan

Jika mengalami kendala teknis, hubungi:

- **Developer**: <https://github.com/Rangga11268>
- **Repositori**: <https://github.com/Rangga11268/titikAman>