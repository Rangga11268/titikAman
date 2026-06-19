# 🎨 Analisis Desain Figma & Penyelarasan Skema — TitikAman

Dokumen ini berisi hasil analisis mendalam terhadap file desain Figma **TitikAman** dan evaluasi keselarasan antarmuka (UI/UX) dengan rancangan database (8 tabel utama) serta dokumen kebutuhan sistem.

---

## 📌 1. Daftar Halaman & Hirarki Node Figma

Melalui Figma Dev Mode MCP Server, kami mengidentifikasi struktur halaman berikut pada halaman **Mockup (`0:1`)** dan **Prototype (`211:268`)**:

### Halaman Utama & Alur Autentikasi
1. **Login Page** (`Node 211:271` / `Node 82:5`):
   - Halaman login dengan desain split-pane (kiri: status siaga banjir, kanan: form login).
2. **Register Tahap 1 (Pilih Peran)** (`Node 82:156`):
   - Kartu seleksi peran (`Warga`, `Relawan`, `Pengelola_Posko`, `Admin_BPBD`) dengan informasi hak akses masing-masing.
3. **Register Warga (Tahap 2 Data Diri)** (`Node 82:329`):
   - Form pengisian data identitas warga, domisili, dan checkbox persetujuan.
4. **Register Relawan** (`Node 248:1747`), **Register Pengelola** (`Node 248:2353`), & **Register Admin** (`Node 248:2020`):
   - Formulir pendaftaran khusus untuk peran non-warga.

### Halaman Aplikasi Utama (Dashboard & Fitur)
5. **Landing Page** (`Node 161:292`): Halaman depan untuk publik.
6. **Dashboard Utama** (`Node 163:1693`): Portal monitoring banjir terintegrasi.
7. **SOS - Warga** (`Node 190:2`): Antarmuka pengiriman sinyal darurat.
8. **Form Laporan Banjir - Warga** (`Node 163:1375`): Form input laporan genangan air (crowdsourcing).
9. **Kelola Laporan - Admin BPBD** (`Node 163:2189`): Dashboard validasi laporan genangan.
10. **Dashboard Relawan** (`Node 163:2528`): Manajemen misi penyelamatan korban SOS.
11. **Kelola Kebutuhan - Pengelola Posko** (`Node 163:2917`): Manajemen kapasitas posko & inventaris logistik.
12. **Hub Logistik & Donasi** (`Node 186:2`) & **Posko Pengungsian** (`Node 187:2`): Halaman publik untuk donatur.
13. **Data Pintu Air** (`Node 163:3941`): Informasi TMA pintu air dan grafik status siaga.
14. **Peta Evakuasi Interaktif** (`Node 174:2`): Peta shelter dan rute aman.

---

## 🎨 2. Design Tokens (Spesifikasi Visual UI/UX)

Berdasarkan ekstraksi CSS dan screenshot Figma, berikut adalah parameter visual wajib untuk frontend:

### A. Palet Warna (Color System)
- **Primary Navy (Left Panel Bg)**: `#1d3557` (Navy gelap yang solid untuk kesan formal dan terpercaya)
- **Brand Teal (Buttons & Links)**: `#006a60` (Warna utama untuk aksi primary, outline hover, dan link penting)
- **Accent Red (Emergency/SOS)**: `#e63946` (Digunakan pada tombol SOS dan status darurat ekstrem)
- **Neutral Light (Background)**: `#f8f9fa` (Abu-abu terang untuk panel kanan)
- **Neutral Dark (Heading/Text)**: `#031f41` (Navy sangat gelap untuk keterbacaan teks utama)
- **Border / Neutral Muted**: `#c4c6cf` (Garis pembatas input)

### B. Typography (Google Fonts)
- **Headings (H1, H2, H3)**: `'Plus Jakarta Sans'`, sans-serif (Font tebal/bold untuk penekanan status dan judul kartu)
- **Body & Inputs**: `'Inter'`, sans-serif (Untuk keterbacaan tinggi pada form dan keterangan status)
- **Subheadings & Role Cards**: `'Poppins'`, sans-serif (Memberikan kontras visual yang premium)

### C. Layout & Grid
- **Split-Pane Layout (Desktop)**: Pembagian kolom `45%` kiri (informasi informatif statis) dan `55%` kanan (interaksi form) pada halaman auth.
- **Form Card Layout**: Lebar kartu form `440px` dengan padding internal `33px` dan `border-radius: 16px` berwarna putih (`#ffffff`).
- **Responsive Behavior**: Pada lebar layar `< 768px` (mobile), panel kiri (45%) disembunyikan untuk memberikan ruang penuh bagi form input.

---

## 🔍 3. Penyelarasan Skema: UI Form vs. Kolom Database

Kami membandingkan elemen input form pada UI Figma dengan kolom tabel database yang dirancang dalam [docs/database.md](file:///d:/laragon/www/titikAman/docs/database.md):

| Halaman UI Figma | Input Field di UI | Kolom Database Terkait (`users`) | Status Keselarasan |
| :--- | :--- | :--- | :--- |
| **Login** | Email atau No. HP | `email` ATAU `phone` | **Selaras**. Autentikasi dapat dilakukan dengan pencarian ganda (pencocokan `email` atau `phone`). |
| **Login** | Kata Sandi | `password` | **Selaras**. |
| **Register Warga** | Nama Lengkap | `fullname` | **Selaras**. |
| **Register Warga** | Nomor HP | `phone` | **Selaras**. |
| **Register Warga** | Email (Opsional) | `email` | **Selaras**. Kolom email di DB diatur sebagai nullable jika opsional. |
| **Register Warga** | Kata Sandi | `password` | **Selaras**. |
| **Register Warga** | Kecamatan Domisili | *Tidak ada* | ⚠️ **Mismatch**. Tidak ditemukan kolom `kecamatan` pada tabel `users`. |
| **Register Warga** | Kelurahan Domisili | *Tidak ada* | ⚠️ **Mismatch**. Tidak ditemukan kolom `kelurahan` pada tabel `users`. |

---

## ⚠️ 4. Analisis Mismatch Domisili & Usulan Solusi

### Mengapa Kecamatan & Kelurahan Diperlukan di UI?
1. **Persyaratan Fungsional (Peringatan Dini)**: Berdasarkan `docs/requirements.md` Bagian 2.4, sistem wajib mengirimkan push notification siaga banjir *"kepada warga di kelurahan/kecamatan yang searah dengan aliran sungai"*.
2. **Kebutuhan Asesmen**: BPBD dan relawan memerlukan data agregat jumlah warga terdampak per kelurahan untuk memprioritaskan evakuasi dan logistik.

### Opsi Solusi yang Direkomendasikan
Untuk menyelaraskan UI pendaftaran dengan database tanpa merusak rancangan 8 tabel utama, kami mengusulkan dua alternatif:

#### Opsi A: Menambahkan Kolom Domisili Langsung ke Tabel `users` (Direkomendasikan - Paling Sederhana)
Menambahkan kolom `kecamatan` dan `kelurahan` langsung ke tabel `users` sebagai string nullable (karena admin/relawan tingkat kota mungkin tidak memiliki kelurahan domisili khusus).
* **Kelebihan**: Kueri cepat, tidak memerlukan tabel relasi baru, pendaftaran warga dapat langsung disimpan dalam satu query insert.
* **Skema**:
  ```sql
  ALTER TABLE users ADD COLUMN kecamatan VARCHAR(100) NULL AFTER role;
  ALTER TABLE users ADD COLUMN kelurahan VARCHAR(100) NULL AFTER kecamatan;
  ```

#### Opsi B: Membuat Tabel Relasi Baru `user_domiciles` (Lebih Normalisasi)
Membuat tabel baru untuk memisahkan data pribadi autentikasi dengan wilayah administratif tempat tinggal warga.
* **Kelebihan**: Struktur database lebih bersih dan normalisasi (3NF). Memungkinkan satu akun warga berpindah alamat atau memiliki lebih dari satu alamat pantau tanpa memodifikasi tabel `users`.
* **Skema**:
  - `user_id` (FK to `users.user_id`, Cascade)
  - `kecamatan` (String, 100)
  - `kelurahan` (String, 100)
  - `alamat_detail` (Text, Nullable)

---

## 🛠️ 5. Rencana Langkah Setup Lanjutan (Setelah Diskusi)

Setelah menyetujui arah penyelesaian masalah domisili di atas, langkah setup teknis berikutnya adalah:
1. **Penyelarasan Model & Database**:
   - Jika memilih **Opsi A**, kita buat file migrasi `_alter_users_add_domicile` dan tambahkan field ke `$fillable` model `User`.
   - Jika memilih **Opsi B**, kita buat migrasi tabel baru `user_domiciles` beserta model Eloquent-nya.
2. **Pembuatan Routing & Controllers**:
   - Membuat `AuthController` dan Form Request untuk validasi input pendaftaran.
3. **Penyusunan Blade Views**:
   - Membuat layout auth (`layouts/auth.blade.php`) dan mengimplementasikan UI login serta registrasi langkah 1 & 2 dengan CSS terkompilasi Vite.
