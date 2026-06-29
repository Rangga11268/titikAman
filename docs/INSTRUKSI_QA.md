# 📦 Instruksi QA — TitikAman

File `titikaman-qa.zip` adalah versi lengkap sistem TitikAman untuk tim QA.

---

## 🚀 Cara Menjalankan

### Prasyarat
Pastikan komputer sudah terinstall:
- **PHP 8.2+** (cek: `php -v`)
- **Composer** (cek: `composer --version`)
- **Node.js 18+** (cek: `node -v`)
- **MySQL / MariaDB** (atau bisa pakai SQLite tanpa instalasi database)

---

### Langkah 1: Ekstrak File
Ekstrak `titikaman-qa.zip` ke folder tujuan, misalnya `C:\titikaman-qa` atau `D:\titikaman-qa`.

---

### Langkah 2: Setup Database (Pilih Salah Satu)

#### Opsi A: SQLite (Mudah, Tanpa Install Database)
```bash
# Di dalam folder project:
copy .env.example .env
# Buka file .env, cari dan ubah:
DB_CONNECTION=sqlite
# Lalu jalankan:
php artisan key:generate
php artisan migrate
php artisan db:seed
```

#### Opsi B: MySQL (Jika sudah punya MySQL)
```bash
copy .env.example .env
# Buka file .env, sesuaikan:
DB_DATABASE=db_titik_aman
DB_USERNAME=root
DB_PASSWORD=
# Lalu jalankan:
php artisan key:generate
php artisan migrate
php artisan db:seed
```

---

### Langkah 3: Install Dependencies
```bash
composer install
npm install
```

---

### Langkah 4: Setup Storage Link
```bash
php artisan storage:link
```

---

### Langkah 5: Jalankan Server
```bash
php artisan serve
```
Buka browser: **http://localhost:8000**

---

## 🔑 Akun Testing

| Role | Email | Password |
|------|-------|----------|
| **Admin BPBD** | `admin@example.com` | `password` |
| **Admin Relawan** | `relawan@example.com` | `password` |
| **Warga** | `warga@example.com` | `password` |
| **Pengelola Posko** | `pengelola@example.com` | `password` |

### Data Tim & Lead (Seeder)
| Kecamatan | Lead (role: Admin_Relawan) | Anggota Tim (role: Relawan) |
|-----------|---------------------------|---------------------------|
| Bekasi Timur | Budi Santoso (lead.bekasitimur@example.com / password) | Siti Rahmawati, Ahmad Fauzi |
| Jatiasih | Ani Wijaya (lead.jatiasih@example.com / password) | Rina Marlina, Hendra Gunawan |
| Rawalumbu | Dodi Pratama (lead.rawalumbu@example.com / password) | Fitri Handayani, Agus Permadi |
| Bekasi Utara | Rudi Hermawan (lead.bekasiutara@example.com / password) | Dewi Sartika, Jamet |
| Bekasi Selatan | Admin Relawan (relawan@example.com) | Budi |

---

## 🧪 Menjalankan Test Suite
```bash
php artisan test
```

Test yang tersedia:
- **59 Test Cases** (Unit + Feature)
- Mencakup: Auth, Login, Registrasi, Admin Portal, Relawan Portal, Donasi, Export CSV

---

## 📚 Dokumentasi Testing QA
Dokumen panduan testing ada di folder `docs/`:

| File | Isi |
|------|-----|
| **`QA_TESTING_PLAN.md`** | 38 Black Box Test Cases, SUS Kuesioner, Heuristic UX Evaluation |
| **`UC_SCENARIO.md`** | Use Case Skenario lengkap (16 skenario) |
| **`requirements.md`** | User persona & alur sistem |

### Untuk AI (ChatGPT / Claude / Gemini)
Jika tim QA menggunakan AI untuk membantu testing, berikan prompt ini:

> *"Kamu adalah QA Engineer untuk sistem TitikAman, platform manajemen kebencanaan banjir. Lakukan Black Box Testing berdasarkan dokumen QA_TESTING_PLAN.md dan UC_SCENARIO.md yang ada di folder docs/. Laporkan hasil testing dalam format: TC-ID, Status (✅ Pass / ❌ Fail), Catatan."*

---

## ⚙️ Stack Teknologi
| Komponen | Versi |
|----------|-------|
| Laravel | 12.x |
| PHP | 8.3+ |
| Database | MySQL / SQLite |
| Frontend | Blade + Tailwind CSS 4 |
| Leaflet.js | 1.9.4 (OpenStreetMap) |
| Icons | Lucide Icons |
