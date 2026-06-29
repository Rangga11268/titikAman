# 🔄 Rencana Integrasi n8n — TitikAman

Dokumen ini berisi rencana implementasi **n8n** (Workflow Automation) untuk mengotomatiskan notifikasi WhatsApp, monitoring SOS, dan tugas operasional lainnya di TitikAman.

---

## 📋 Apa itu n8n?

**n8n** adalah platform *workflow automation* open-source yang memungkinkan kita menghubungkan berbagai layanan (WhatsApp API, database, Google Sheets, dll.) secara visual menggunakan node-based editor, tanpa perlu coding.

> Website: https://n8n.io  
> Lisensi: **Sustainable Use License** (gratis untuk penggunaan internal tim/komunitas)

---

## 🏗️ Arsitektur yang Direncanakan

Semua komponen berjalan di **satu PC/server lokal yang sama**:

```
┌─────────────────────────────────────────────────────────┐
│                    SERVER LOKAL                          │
│                                                           │
│  ┌──────────────┐     ┌──────────────┐                   │
│  │  TitikAman    │     │     n8n       │                   │
│  │  Laravel 12   │◄───►│  Automation   │                   │
│  │  localhost:   │     │  localhost:   │                   │
│  │    8000       │     │    5678       │                   │
│  └──────┬───────┘     └──────┬───────┘                   │
│         │                     │                            │
│  ┌──────┴───────┐     ┌──────┴───────┐                   │
│  │   MySQL DB   │     │  WhatsApp    │                   │
│  │   localhost  │     │  Cloud API   │                   │
│  └──────────────┘     └──────────────┘                   │
│                                                           │
│  ┌──────────────────────────────────────────────────┐    │
│  │         Google Drive / Sheets (Opsional)          │    │
│  └──────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────┘
```

### 🔗 Cara n8n Terhubung ke TitikAman

| Metode | Node n8n | Contoh |
|--------|----------|--------|
| **HTTP Request** | `HTTP Request` | `GET http://localhost:8000/api/sos/latest` |
| **Database langsung** | `MySQL` | `SELECT * FROM sos_requests WHERE status = 'waiting'` |
| **Webhook dari Laravel** | `Webhook` | Laravel kirim POST ke `http://localhost:5678/webhook/sos-baruh` |

---

## 📦 1. Setup & Instalasi n8n

### Prasyarat
- Node.js 18+ (sudah terinstall untuk project TitikAman)
- npm (bundled with Node.js)

### Langkah Instalasi

```bash
# Install n8n secara global
npm install -g n8n

# Atau install di dalam project (opsional)
npm install n8n

# Jalankan n8n
n8n start
```

Setelah berjalan, buka browser: **http://localhost:5678**

> **Catatan**: Pertama kali membuka n8n, Anda akan diminta membuat akun user lokal (gratis, offline).

### Cara Menghentikan / Menjalankan Ulang

```bash
# Hentikan
Ctrl + C

# Jalankan lagi
n8n start
```

---

## ⚡ 2. Workflow yang Direncanakan

### Workflow 1: Auto-Notify WA saat Relawan di-Approve

**Trigger**: Database MySQL → ada perubahan `users.status` dari `pending` ke `approved`

| Step | Node | Detail |
|------|------|--------|
| 1 | **MySQL Trigger** | `SELECT * FROM users WHERE status = 'approved' AND updated_at > NOW() - INTERVAL 1 MINUTE` |
| 2 | **IF** | Filter hanya yang role = `Relawan` |
| 3 | **HTTP Request** | `GET http://localhost:8000/api/team-link?kecamatan={{$json.kecamatan}}` → ambil link grup WA tim |
| 4 | **WhatsApp** | Kirim pesan ke nomor user: *"Halo [nama], akun Relawan Anda telah disetujui! Bergabung ke grup tim: [link grup]"* |

**Cara Aktivasi**: Jalankan setiap 1 menit (cron `*/1 * * * *`)

**Pesan WhatsApp yang Dikirim**:
```
Halo [fullname], akun Relawan Anda telah disetujui! 🎉

Anda terdaftar sebagai anggota [Tim Kecamatan].

Bergabung ke grup tim melalui link berikut:
[Link Grup WhatsApp]

Salam,
Tim Admin Relawan TitikAman
```

---

### Workflow 2: Monitoring SOS Lama (Auto-Alert Ke Grup)

**Trigger**: Cek setiap 5 menit untuk SOS yang status `waiting` lebih dari 30 menit

| Step | Node | Detail |
|------|------|--------|
| 1 | **MySQL** | `SELECT * FROM sos_requests WHERE status = 'waiting' AND created_at < NOW() - INTERVAL 30 MINUTE` |
| 2 | **IF** | Jika ada data → lanjut |
| 3 | **HTTP Request** | `POST http://localhost:8000/api/sos/urgent` → set flag `urgent = 1` |
| 4 | **WhatsApp** | Kirim notifikasi ke Grup Admin Relawan: *"⚠️ PERHATIAN! Ada SOS yang sudah menunggu > 30 menit!"* |

---

### Workflow 3: Backup Database Otomatis (Harian)

**Trigger**: Jadwal harian jam 00:00

| Step | Node | Detail |
|------|------|--------|
| 1 | **Schedule** | `0 0 * * *` (setiap tengah malam) |
| 2 | **MySQL** | `mysqldump -u root db_titik_aman > backup.sql` |
| 3 | **Google Drive** | Upload file `backup.sql` ke folder Google Drive "TitikAman Backup" |

---

### Workflow 4: Export Riwayat Misi Harian ke Google Sheets

**Trigger**: Jadwal harian jam 23:00

| Step | Node | Detail |
|------|------|--------|
| 1 | **Schedule** | `0 23 * * *` (setiap jam 23:00) |
| 2 | **HTTP Request** | `GET http://localhost:8000/relawan/mission/export` |
| 3 | **Google Sheets** | Append data ke sheet "Riwayat Misi" di Google Drive |

---

## 🧪 3. Estimasi Waktu Implementasi

| Modul | Waktu | Tingkat Kesulitan |
|-------|-------|-------------------|
| Install & setup n8n lokal | 30 menit | ✅ Mudah |
| Workflow 1: Auto-WA saat approved | 1-2 jam | ✅ Mudah |
| Workflow 2: Monitoring SOS lama | 2-3 jam | ✅ Mudah |
| Workflow 3: Backup DB otomatis | 1 jam | ✅ Mudah |
| Workflow 4: Export ke Google Sheets | 1-2 jam | ✅ Mudah |
| Total estimasi | **~8 jam** | |

---

## 💰 4. Biaya & Lisensi

| Komponen | Biaya | Catatan |
|----------|-------|---------|
| **n8n Community Edition** | **Gratis** | Tanpa batas workflow, self-hosted |
| **WhatsApp Cloud API (Meta)** | **Gratis** | 1.000 pesan/hari gratis |
| **Google Drive / Sheets API** | **Gratis** | 15 GB penyimpanan gratis |
| **MySQL connection** | **Gratis** | Sudah terinstall dengan TitikAman |

> **Total biaya tambahan**: **Rp 0** (semua komponen gratis untuk penggunaan internal)

---

## ⚠️ 5. Hal-hal yang Perlu Disiapkan

| Item | Status |
|------|--------|
| **n8n sudah terinstall di lokal** | ❌ Belum |
| **WhatsApp Business Account (Meta)** | ❌ Perlu daftar di https://developers.facebook.com |
| **Google Cloud Project** (untuk Sheets/Drive) | ❌ Perlu buat di console.cloud.google.com |
| **Endpoint API TitikAman untuk n8n** | ❌ Perlu dibuat |

### Yang Perlu Dibangun di Laravel (Endpoint API)

Jika n8n membutuhkan data khusus dari TitikAman, kita perlu membuat endpoint API sederhana:

```php
// routes/api.php
Route::get('/api/team-link', function (Request $request) {
    $kec = $request->kecamatan;
    $links = [
        'Bekasi Timur' => 'https://chat.whatsapp.com/INVITE_TIM_BEKASTIMUR',
        // ...
    ];
    return response()->json(['link' => $links[$kec] ?? $links['backup']]);
});

Route::get('/api/sos/latest', function () {
    return SosRequest::where('status', 'waiting')->get();
});
```

---

## 📌 6. Catatan Penting

1. **n8n berjalan terpisah** dari Laravel — tidak ada perubahan kode di TitikAman yang diperlukan untuk workflow sederhana (kecuali endpoint API baru jika dibutuhkan).
2. **Workflow dieksekusi di lokal** — semua data tetap aman di server/PC tim, tidak ada data yang dikirim ke cloud.
3. **WhatsApp Cloud API** membutuhkan koneksi internet untuk mengirim pesan, tetapi tidak ada data yang disimpan di server Meta (hanya nomor tujuan dan pesan teks).
4. **Prioritas implementasi**: Mulai dari **Workflow 1 (Auto-WA)** dulu karena paling berdampak langsung pada proses approval anggota baru.
