# 📘 Buku Panduan TitikAman

**Sistem Informasi Mitigasi Banjir dan Navigasi Jalur Evakuasi**  
*Platform Manajemen Kebencanaan Banjir Terpadu — Kota Bekasi*

---

## 📌 Daftar Isi
1. [Apa Itu TitikAman?](#-1-apa-itu-titikaman)
2. [Struktur Tim & Peran](#-2-struktur-tim--peran)
3. [Panduan Instalasi Lengkap](#-3-panduan-instalasi-lengkap)
4. [Menjalankan Aplikasi](#-4-menjalankan-aplikasi)
5. [Alur Sistem & Cara Pakai](#-5-alur-sistem--cara-pakai)
6. [Fitur per Role](#-6-fitur-per-role)
7. [Database & Struktur Data](#-7-database--struktur-data)
8. [FAQ & Troubleshooting](#-8-faq--troubleshooting)

---

## 🏠 1. Apa Itu TitikAman?

TitikAman adalah **sistem informasi kebencanaan berbasis web** yang dirancang untuk membantu proses mitigasi, respons, dan evakuasi bencana banjir di wilayah **Kota Bekasi dan sekitarnya**.

### ✨ Fitur Utama
| Fitur | Manfaat |
|-------|---------|
| 🚨 **SOS Darurat** | Warga terjebak banjir bisa mengirim sinyal evakuasi dengan lokasi GPS |
| 🗺️ **Peta Genangan** | Visualisasi titik banjir berdasarkan laporan partisipatif dari warga |
| 📊 **Dashboard TMA** | Data tinggi muka air pintu air (Sungai Cikeas, Bekasi, Cakung) |
| 🏕️ **Manajemen Posko** | Update kapasitas pengungsi, kebutuhan logistik, dan donasi |
| 🆘 **Misi Penyelamatan** | Admin Relawan menugaskan tim evakuasi ke lokasi SOS |
| 📱 **Integrasi WhatsApp** | Koordinasi tim dan pengiriman instruksi evakuasi via WhatsApp |

---

## 👥 2. Struktur Tim & Peran

Sistem ini memiliki **4 aktor utama** yang saling terintegrasi:

### 1️⃣ Warga (Masyarakat / Korban / Donatur)
| Aktivitas | Cara |
|-----------|------|
| **Daftar Akun** | Register pilih peran "Warga" |
| **Lapor Banjir** | Buka menu Form Laporan → isi tinggi air + foto + lokasi |
| **Kirim SOS** | Buka menu SOS Darurat → isi jumlah korban + kelompok rentan |
| **Donasi** | Buka Hub Donasi → pilih posko & barang → upload bukti kirim |
| **Lihat Peta** | Dashboard atau Peta Evakuasi → klik marker untuk detail |

### 2️⃣ Admin Relawan (Komandan Tim / Dispatcher)
| Aktivitas | Cara |
|-----------|------|
| **Login** | Login sebagai `relawan@example.com` / `password` |
| **Lihat Antrian SOS** | Dashboard → panel kiri "Antrian SOS Terbaru" |
| **Tugaskan Tim** | Klik **TUGASKAN KE TIM** → pilih tim → kirim WA |
| **Review Anggota** | Card "Pendaftar Baru" → Review → Terima/Tolak |
| **Export Riwayat** | Tabel "Riwayat Misi" → Export CSV |

### 3️⃣ Pengelola Posko (Petugas Shelter)
| Aktivitas | Cara |
|-----------|------|
| **Login** | Login sebagai `pengelola@example.com` / `password` |
| **Update Kapasitas** | Dashboard → Update jumlah pengungsi + status posko |
| **Tambah Kebutuhan** | Tambah barang logistik yang dibutuhkan |
| **Verifikasi Donasi** | Cek donasi masuk → verifikasi fisik → set delivered |

### 4️⃣ Admin BPBD (Super User)
| Aktivitas | Cara |
|-----------|------|
| **Login** | Login sebagai `admin@example.com` / `password` |
| **Verifikasi Laporan** | Dashboard admin → Verifikasi/Tolak laporan banjir |
| **Set Surut** | Tandai laporan yang sudah surut (water_height = 0) |
| **Update TMA** | Kelola TMA → input tinggi air baru |
| **Verifikasi Pengguna** | /admin/verifikasi-pengguna → Setujui/Tolak akun baru |

---

## 💻 3. Panduan Instalasi Lengkap

### 📋 Spesifikasi Minimal
| Komponen | Versi |
|----------|-------|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 18+ |
| MySQL / MariaDB | 8.0+ / 10.5+ |
| Web Server | Apache / Nginx |

### 🚀 Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/Rangga11268/titikAman.git
cd titikAman

# 2. Install dependencies PHP
composer install

# 3. Install dependencies Node.js
npm install

# 4. Copy file environment
copy .env.example .env

# 5. Generate key aplikasi
php artisan key:generate

# 6. Setup database (pilih salah satu)

## Opsi A: SQLite (tanpa install database)
## Buka file .env, ubah:
DB_CONNECTION=sqlite

## Opsi B: MySQL
## Buka file .env, sesuaikan:
DB_DATABASE=db_titik_aman
DB_USERNAME=root
DB_PASSWORD=

# 7. Buat database (MySQL saja)
php artisan db:create

# 8. Jalankan migrasi
php artisan migrate

# 9. Isi data contoh
php artisan db:seed

# 10. Setup storage link (untuk upload file)
php artisan storage:link

# 11. Jalankan server development
php artisan serve
```

Buka browser: **http://localhost:8000**

### 🔄 Perintah Penting Setelah Pull / Update
```bash
git pull origin main
composer install
php artisan migrate
php artisan db:seed
npm run build
```

### 🧪 Menjalankan Test Suite
```bash
php artisan test
```
Total: **59 test cases** (Unit + Feature) — mencakup autentikasi, admin portal, relawan portal, donasi, dan export data.

---

## ▶️ 4. Menjalankan Aplikasi

### Development Mode
```bash
php artisan serve
```

### Production Mode
```bash
# Compile asset
npm run build

# Jalankan dengan supervisor / pm2
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 🔄 5. Alur Sistem & Cara Pakai

### 🔁 Alur Utama End-to-End
```
Registrasi → Login → Dashboard → Aktivitas Sesuai Role → Logout
```

### 🟢 Alur Warga
```
Register Warga → Login (auto) → Dashboard
    ├── Laporkan Banjir (form wizard)
    │   ├── Isi tinggi air (slider)
    │   ├── Upload foto
    │   ├── Pilih status akses jalan & listrik
    │   └── Submit → status pending
    │
    ├── Kirim SOS Darurat
    │   ├── Deteksi GPS otomatis
    │   ├── Isi jumlah korban + kelompok rentan
    │   └── Submit → masuk antrian Admin Relawan
    │
    ├── Donasi Logistik
    │   ├── Pilih posko & barang
    │   ├── Isi jumlah (maks = sisa kebutuhan)
    │   ├── Upload foto bukti kirim
    │   └── Submit → tunggu verifikasi pengelola
    │
    └── Lihat Dashboard
        ├── Statistik banjir & SOS
        ├── Berita laporan banjir (klik untuk detail)
        └── Log aktivitas terbaru
```

### 🔵 Alur Admin Relawan
```
Login (relawan@example.com / password) → Dashboard Mission Control
    ├── Monitor Antrian SOS (panel kiri)
    │   ├── SOS baru (status waiting) → tombol "TUGASKAN KE TIM"
    │   ├── SOS butuh backup (status assigned) → tombol "KIRIM BANTUAN TIM"
    │
    ├── Tugaskan Misi
    │   ├── Pilih tim dari dropdown → Tugaskan Misi
    │   ├── Banner muncul dengan 4 tombol:
    │   │   ├── WA ke Relawan (kirim instruksi ke lead tim)
    │   │   ├── Share Grup [Tim] (bagikan ke grup WA tim)
    │   │   ├── Minta Bantuan (grup gabungan)
    │   │   └── Buka Google Maps
    │   └── WA berisi: info SOS + Maps + prioritas
    │
    ├── Review Anggota Baru
    │   ├── Card "Pendaftar Baru" → klik → Review
    │   ├── Lihat pratinjau dokumen KTP
    │   └── Terima → otomatis kirim WA link grup ke anggota
    │
    ├── Kelola Anggota Tim
    │   ├── Card tim (Jatiasih, Bekasi Timur, dll.)
    │   ├── Klik → modal daftar anggota (nama + no HP)
    │   ├── Edit anggota (ubah keahlian, organisasi, pindah tim)
    │   └── Hapus anggota
    │
    ├── Selesaikan Misi
    │   ├── Kartu misi aktif → klik SELESAI
    │   ├── Konfirmasi → status jadi completed
    │   └── Masuk ke Riwayat Misi
    │
    ├── Riwayat Misi
    │   ├── Tabel semua misi (10 terbaru)
    │   ├── Detail (klik tombol Detail)
    │   ├── WA ke relawan (langsung dari tabel)
    │   └── Export CSV
    │
    ├── Peta Operasional
    │   ├── Marker SOS (lingkaran warna prioritas)
    │   ├── Marker misi aktif (pulsing)
    │   ├── Fullscreen (klik tombol ⛶)
    │   └── Refresh (klik tombol 🔁)
    │
    └── Statistik
        ├── SOS Antri, Prioritas Tinggi
        ├── Misi Aktif, Misi Selesai
        └── Rata-rata respon (menit)
```

### 🟡 Alur Pengelola Posko
```
Register → Login → Dashboard Kelola Posko
    ├── Pilih Posko (jika baru daftar)
    ├── Update Kapasitas (jumlah pengungsi, MCK, status)
    ├── Tambah Kebutuhan Logistik (nama barang, jumlah, urgensi)
    ├── Lihat Donasi Masuk → Verifikasi (delivered)
    └── Status Posko: Aktif / Penuh / Tutup
        └── Jika Tutup → posko hilang dari peta publik
```

### 🔴 Alur Admin BPBD
```
Login (admin@example.com / password) → Admin Dashboard
    ├── Dashboard (statistik, peta, log, laporan pending)
    ├── Verifikasi Laporan Banjir
    │   ├── Lihat laporan pending + foto
    │   ├── Verifikasi (muncul di peta) / Tolak / Set Surut
    │
    ├── Kelola TMA (Pintu Air)
    │   ├── Input tinggi air (cm)
    │   ├── Sistem otomatis hitung status bahaya
    │   └── Notifikasi peringatan dini (DAS mapping + throttling)
    │
    └── Verifikasi Pengguna
        ├── Filter: Relawan / Pengelola Posko
        ├── Lihat data lengkap + dokumen
        └── Setujui / Tolak
```

### 🟣 Alur Relawan (Anggota Tim)
```
Register → Upload KTP → Pending → Menunggu Review
    ├── Admin Relawan Review → Terima
    │   ├── WA masuk: link grup tim
    │   └── Login → /status-verifikasi → approved
    │       ├── Gabung grup WA tim
    │       └── Lanjut ke Dashboard umum
    │
    └── Admin Relawan Review → Tolak
        └── Login → /status-verifikasi → rejected
```

---

## 🎯 6. Fitur per Role

### ✅ Fitur Warga
| Halaman | URL | Fungsi |
|---------|-----|--------|
| Dashboard | `/dashboard` | Statistik & berita banjir |
| Peta Evakuasi | `/peta-evakuasi` | Peta interaktif marker posko & banjir |
| Form Laporan | `/warga/lapor` | Lapor genangan banjir (wizard multi-step) |
| SOS Darurat | `/warga/sos` | Kirim sinyal evakuasi |
| Hub Donasi | `/donasi` | Donasi logistik ke posko |
| Data Pintu Air | `/data-pintu-air` | Status TMA real-time |
| Posko | `/posko` | Daftar posko aktif + donasi |

### ✅ Fitur Admin Relawan
| Halaman | URL | Fungsi |
|---------|-----|--------|
| Dashboard | `/relawan/dashboard` | Mission Control lengkap |
| SOS Data (JSON) | `/relawan/sos-data` | Data SOS untuk AJAX refresh |
| Export Misi | `/relawan/mission/export` | Download CSV riwayat misi |

### ✅ Fitur Pengelola Posko
| Halaman | URL | Fungsi |
|---------|-----|--------|
| Dashboard | `/pengelola/dashboard` | Kelola kapasitas, logistik & donasi |

### ✅ Fitur Admin BPBD
| Halaman | URL | Fungsi |
|---------|-----|--------|
| Dashboard | `/admin/dashboard` | Verifikasi laporan, peta, log |
| Kelola TMA | `/admin/tma` | Input tinggi air pintu air |
| Verifikasi Pengguna | `/admin/verifikasi-pengguna` | Setujui/tolak akun baru |

---

## 🗄️ 7. Database & Struktur Data

### 🧩 8 Tabel Utama

| # | Nama Tabel | Fungsi |
|---|-----------|--------|
| 1 | `users` | Data pengguna (Warga, Relawan, Admin_Relawan, Pengelola_Posko, Admin_BPBD) |
| 2 | `water_gates` | Data pintu air & tinggi muka air (TMA) |
| 3 | `shelters` | Data posko pengungsian (kapasitas, fasilitas, foto, koordinat) |
| 4 | `flood_reports` | Laporan genangan banjir dari warga (crowdsourcing) |
| 5 | `shelter_needs` | Kebutuhan logistik posko |
| 6 | `donations` | Transaksi donasi logistik |
| 7 | `sos_requests` | Permintaan evakuasi darurat (SOS) |
| 8 | `rescue_missions` | Misi penyelamatan (penugasan ke tim) |

### 🔗 Relasi Antar Tabel
```
users ──┬── flood_reports (1:N melaporkan)
        ├── sos_requests (1:N mengirim)
        ├── donations (1:N menyumbang)
        └── rescue_missions (1:N menangani)

shelters ──┬── shelter_needs (1:N membutuhkan)
           └── shelter_needs ── donations (1:N terpenuhi)

sos_requests ── rescue_missions (1:1 memicu)
```

### 👤 Role di Database
| Role | Deskripsi |
|------|-----------|
| `Warga` | Masyarakat umum, punya akses ke dashboard umum |
| `Admin_Relawan` | Dispatcher, punya akses ke Mission Control |
| `Relawan` | Label data registrasi saja, tidak punya akses dashboard khusus |
| `Pengelola_Posko` | Manajemen posko dan logistik |
| `Admin_BPBD` | Super user, verifikasi laporan dan TMA |

### 🎨 Status Workflow

**SOS Request:** `waiting` → `assigned` → `completed`

**Flood Report:** `pending` → `verified` / `rejected` (bisa Set Surut)

**Shelter:** `active` → `full` → `closed` (closed = arsip abu-abu)

**Donation:** `pending` → `accepted` → `delivered`

**User Akun:** `pending` → `approved` / `rejected`

---

## ❓ 8. FAQ & Troubleshooting

### Error umum & Solusi

❌ **Error: Target class [controller] does not exist**
➡️ Jalankan `composer dump-autoload`

❌ **Error: Base table or view not found**
➡️ Jalankan `php artisan migrate`

❌ **Error: No application encryption key**
➡️ Jalankan `php artisan key:generate`

❌ **Foto/File tidak muncul**
➡️ Jalankan `php artisan storage:link`

❌ **Error: Pusher/WebSocket connection failed**
➡️ Abaikan — tidak mempengaruhi fungsi aplikasi, hanya notifikasi real-time

❌ **Halaman error 500 | Call to a member function format() on null**
➡️ Pastikan data seeder sudah dijalankan: `php artisan db:seed`

### Akun Testing

| Role | Email | Password |
|------|-------|----------|
| Admin BPBD | `admin@example.com` | `password` |
| Admin Relawan | `relawan@example.com` | `password` |
| Pengelola Posko | `pengelola@example.com` | `password` |
| Lead Tim Bekasi Timur | `lead.bekasitimur@example.com` | `password` |
| Lead Tim Jatiasih | `lead.jatiasih@example.com` | `password` |
| Lead Tim Rawalumbu | `lead.rawalumbu@example.com` | `password` |
| Lead Tim Bekasi Utara | `lead.bekasiutara@example.com` | `password` |

### 🐳 Tips Developer

**Reset Database (data testing baru):**
```bash
php artisan migrate:fresh --seed
```

**Lihat daftar route:**
```bash
php artisan route:list
```

**Clear cache:**
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

**Lihat data di database langsung via tinker:**
```bash
php artisan tinker
> \App\Models\User::count()
> \App\Models\User::where('role', 'Admin_Relawan')->pluck('email')
```
