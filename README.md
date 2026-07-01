<div align="center">
  <img src="public/assets/logo-titikaman.png" alt="TitikAman Logo" width="180"/>
  <h1 align="center" style="margin-top: 12px; font-size: 2.5em; color: #006A60;">TitikAman</h1>
  <p align="center">
    <strong>Sistem Informasi Mitigasi Banjir dan Navigasi Jalur Evakuasi</strong>
    <br>
    <em>Platform Manajemen Kebencanaan Banjir Terpadu — Kota Bekasi</em>
  </p>
  <p align="center">
    <a href="https://github.com/Rangga11268/titikAman/blob/main/BUKU_PANDUAN.md"><img src="https://img.shields.io/badge/Buku%20Panduan-Lengkap-006A60?style=for-the-badge" alt="Buku Panduan"></a>
    <a href="https://github.com/Rangga11268/titikAman/blob/main/docs/QA_TESTING_PLAN.md"><img src="https://img.shields.io/badge/QA%20Testing-54%20TC%20%E2%9C%85-006A60?style=for-the-badge" alt="QA Testing"></a>
    <a href="https://github.com/Rangga11268/titikAman/blob/main/docs/SLIDE_PRESENTASI.md"><img src="https://img.shields.io/badge/Slide%20Presentasi-Siap-006A60?style=for-the-badge" alt="Slide Presentasi"></a>
  </p>
  <p align="center">
    <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel" alt="Laravel 12">
    <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php" alt="PHP 8.3">
    <img src="https://img.shields.io/badge/Tailwind%20CSS-v4-38B2AC?style=flat-square&logo=tailwind-css" alt="Tailwind CSS">
    <img src="https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql" alt="MySQL">
    <img src="https://img.shields.io/badge/Leaflet.js-199900?style=flat-square&logo=leaflet" alt="Leaflet.js">
    <img src="https://img.shields.io/badge/59%20Tests-Passing-10B981?style=flat-square" alt="Tests">
  </p>
</div>

<br>

---

## 📋 Daftar Isi

- [Tentang TitikAman](#-tentang-titikaman)
- [Fitur Unggulan](#-fitur-unggulan)
- [Aktor & Peran](#-aktor--peran)
- [Tech Stack](#-tech-stack)
- [Alur Sistem](#-alur-sistem)
- [Preview Halaman](#-preview-halaman)
- [Panduan Instalasi](#-panduan-instalasi)
- [Akun Demo](#-akun-demo)
- [Testing](#-testing)
- [Struktur Database](#-struktur-database)
- [Dokumentasi](#-dokumentasi)
- [Lisensi](#-lisensi)

---

## 🏠 Tentang TitikAman

**TitikAman** adalah platform manajemen kebencanaan banjir berbasis web yang dirancang untuk membantu mitigasi, respons, dan evakuasi bencana banjir di **Kota Bekasi dan sekitarnya**. Platform ini menghubungkan **4 aktor utama** — warga terdampak, relawan kebencanaan, pengelola posko pengungsian, dan aparatur BPBD — dalam satu sistem real-time.

<p align="center">
</p>

### 🎯 Latar Belakang

Berdasarkan data BNPB per Maret 2025, banjir di Jabodetabek merendam **37.058 KK**, di mana **Kota Bekasi menjadi wilayah terdampak paling masif dengan 18.738 KK (61.233 jiwa)** di 25 kelurahan. Sayangnya, koordinasi evakuasi masih dilakukan secara manual melalui telepon dan WhatsApp tanpa sistem terstruktur, menyebabkan respons yang lambat dan tumpang tindih di lapangan.

**TitikAman hadir sebagai solusi untuk:** 
- Mempercepat respons evakuasi korban banjir
- Menyediakan data tinggi muka air secara real-time
- Memudahkan koordinasi antara warga, relawan, dan BPBD
- Meningkatkan transparansi distribusi bantuan logistik

---

## 🚀 Fitur Unggulan

<div align="center">
  <table>
    <tr>
      <td align="center" width="33%">
        <br>
        <strong>🚨 SOS Darurat</strong><br>
        <sub>1 klik kirim sinyal evakuasi<br>dengan GPS otomatis & prioritas<br>berdasarkan jumlah korban</sub>
      </td>
      <td align="center" width="33%">
        <br>
        <strong>🗺️ Mission Control</strong><br>
        <sub>Dashboard relawan dengan peta,<br>antrian SOS, & penugasan tim<br>dalam 1 layar</sub>
      </td>
      <td align="center" width="33%">
        <br>
        <strong>🏕️ Manajemen Posko</strong><br>
        <sub>Update kapasitas, kebutuhan<br>logistik, & verifikasi donasi<br>real-time</sub>
      </td>
    </tr>
    <tr>
      <td align="center" width="33%">
        <br>
        <strong>🌊 TMA Real-time</strong><br>
        <sub>Data tinggi muka air dari<br>pintu air (Cikeas, Bekasi,<br>Cakung) + status siaga</sub>
      </td>
      <td align="center" width="33%">
        <br>
        <strong>📱 Donasi Publik</strong><br>
        <sub>Donasi langsung ke posko,<br>transparan, & terverifikasi<br>oleh pengelola</sub>
      </td>
      <td align="center" width="33%">
        <br>
        <strong>📍 Scope Bekasi</strong><br>
        <sub>Bounding box GPS membatasi<br>wilayah operasional hanya<br>Kota Bekasi</sub>
      </td>
    </tr>
  </table>
</div>

---

## 👥 Aktor & Peran

| Role | Deskripsi | Akses Utama |
|------|-----------|-------------|
| **Warga** | Masyarakat umum / korban / donatur | Lapor banjir, SOS, donasi, lihat peta & TMA |
| **Admin Relawan** | Koordinator tim evakuasi (dispatcher) | Mission control, tugaskan misi, kelola anggota, kirim WA |
| **Pengelola Posko** | Petugas shelter pengungsian | Update kapasitas, kebutuhan logistik, verifikasi donasi |
| **Admin BPBD** | Super user / administrator | Verifikasi laporan, kelola TMA, verifikasi pengguna |

---

## 🧰 Tech Stack

| Kategori | Teknologi |
|----------|-----------|
| **Framework** | Laravel 12 |
| **Database** | MySQL / MariaDB / SQLite |
| **Backend** | PHP 8.3 |
| **Frontend** | Tailwind CSS v4, JavaScript |
| **Real-time** | Laravel Reverb (WebSocket) |
| **Peta Interaktif** | Leaflet.js + OpenStreetMap / CartoDB |
| **Ikon** | Lucide Icons |
| **Font** | Inter, Plus Jakarta Sans |

---

## 🔄 Alur Sistem

```
    Warga                    Relawan                  Posko                 BPBD
      │                        │                        │                    │
      ├─ Lapor Banjir ─────────┤                        │                    │
      │                        │                        │     ┌─ Verifikasi ─┤
      ├─ Kirim SOS ────────────┤                        │     │              │
      │                        ├─ Tugaskan Tim ─────────┤     │              │
      │                        │                        │     │              │
      │                        ├─ Kirim WA ke Relawan   │     │              │
      │                        │                        │     │              │
      ├─ Donasi ───────────────┤────────────────────────┤     │              │
      │                        │                        ├─ Verifikasi Donasi │
      │                        │                        ├─ Update Kapasitas  │
      │                        │                        │                    │
      │                        │                        │                    ├─ Update TMA
      │                        │                        │                    ├─ Verifikasi User
```


---

## ⚙️ Panduan Instalasi

### Persyaratan Sistem

| Komponen | Versi |
|----------|-------|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 18+ |
| MySQL / MariaDB | 8.0+ / 10.5+ |

### Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/Rangga11268/titikAman.git
cd titikAman

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Copy file environment
cp .env.example .env

# 5. Generate key aplikasi
php artisan key:generate

# 6. Setup database di file .env (MySQL/SQLite)

# 7. Jalankan migrasi
php artisan migrate

# 8. Isi data demo
php artisan db:seed

# 9. Setup storage link
php artisan storage:link

# 10. Build asset frontend
npm run build

# 11. Jalankan server
php artisan serve
```

Buka **http://localhost:8000** di browser.

### Fitur Real-time (Opsional)

```bash
# Terminal terpisah
php artisan reverb:start
```

---

## 👤 Akun Demo

Semua password: **`password`**

| Role | Email | Wilayah |
|------|-------|---------|
| **Admin BPBD** | `admin@example.com` | — |
| **Admin Relawan** | `relawan@example.com` | Bekasi Selatan |
| **Lead Tim** | `lead.bekasitimur@example.com` | Bekasi Timur |
| **Lead Tim** | `lead.jatiasih@example.com` | Jatiasih |
| **Lead Tim** | `lead.rawalumbu@example.com` | Rawalumbu |
| **Lead Tim** | `lead.bekasiutara@example.com` | Bekasi Utara |
| **Pengelola Posko** | `pengelola@example.com` | Posko Masjid Al-Barkah |
| **Warga** | `warga@example.com` | Bekasi Timur |

---

## 🧪 Testing

| Jenis | Jumlah | Status |
|-------|--------|--------|
| PHPUnit (Unit + Feature) | 59 test cases, 200 assertions | ✅ Pass |
| Black Box Testing | 54 test case — 7 area fitur | ✅ Pass |
| Cakupan Role | Warga, Admin Relawan, Pengelola Posko, Admin BPBD | ✅ Tercover |

```bash
# Jalankan semua test
php artisan test
```

---

## 🗄️ Struktur Database

| # | Tabel | Fungsi |
|---|-------|--------|
| 1 | `users` | Data pengguna (5 role) |
| 2 | `water_gates` | Data pintu air & TMA |
| 3 | `shelters` | Data posko pengungsian |
| 4 | `flood_reports` | Laporan banjir crowdsource |
| 5 | `shelter_needs` | Kebutuhan logistik posko |
| 6 | `donations` | Transaksi donasi |
| 7 | `sos_requests` | Permintaan evakuasi SOS |
| 8 | `rescue_missions` | Misi penyelamatan |

---

## 📂 Dokumentasi

| Dokumen | Deskripsi |
|---------|-----------|
| [📘 Buku Panduan](BUKU_PANDUAN.md) | Panduan lengkap penggunaan sistem |
| [🧪 QA Testing Plan](docs/QA_TESTING_PLAN.md) | 54 black box test + usability + UX |
| [🎯 Slide Presentasi](docs/SLIDE_PRESENTASI.md) | Konten PPT presentasi |
| [📄 Q&A Presentasi](docs/QA_PRESENTASI.md) | 32 pertanyaan + jawaban |
| [🎬 Script Demo](docs/SCRIPT_DEMO.md) | Skrip demo 6 menit |
| [📊 Use Case Scenario](docs/UC_SCENARIO.md) | 17 skenario use case |
| [📐 Analisis Database](docs/database.md) | Struktur & relasi database |

---

## 📜 Lisensi

Hak Cipta © 2026 Tim TitikAman — IT Bootcamp UBSI

---

<div align="center">
  <p>
    <sub>Dibuat dengan ❤️ oleh Tim TitikAman</sub>
    <br>
    <a href="https://github.com/Rangga11268/titikAman">GitHub</a> •
    <a href="BUKU_PANDUAN.md">Buku Panduan</a> •
    <a href="docs/SLIDE_PRESENTASI.md">Slide Presentasi</a>
  </p>
  <br>
  <img src="https://img.shields.io/github/last-commit/Rangga11268/titikAman?color=006A60&style=flat-square" alt="Last Commit">
  <img src="https://img.shields.io/github/repo-size/Rangga11268/titikAman?color=006A60&style=flat-square" alt="Repo Size">
  <img src="https://img.shields.io/github/license/Rangga11268/titikAman?color=006A60&style=flat-square" alt="License">
</div>
