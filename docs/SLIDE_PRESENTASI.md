# Slide Presentasi TitikAman

> Copy-paste konten di bawah ke PPT.
> Format: **Judul Slide** → poin-poin atau tabel.

---

## Slide 1: Judul

**TitikAman**
*Sistem Informasi Mitigasi Banjir dan Navigasi Jalur Evakuasi*
*Platform Manajemen Kebencanaan Banjir Terpadu — Kota Bekasi*

---

## Slide 2: Latar Belakang & Masalah

**Banjir Besar Maret 2025 — Bekasi Terparah (18.738 KK terdampak)**

| # | Masalah |
|---|---------|
| 1 | **Evakuasi manual**: Warga telepon/WA satu-satu, SAR tidak punya data lokasi & prioritas korban |
| 2 | **Koordinasi SAR tumpang tindih**: Tidak ada sistem penugasan, relawan datang sendiri-sendiri tanpa komando |
| 3 | **Warga tidak tahu posko**: Bingung cari pengungsian terdekat yang masih punya kapasitas |
| 4 | **Donasi tidak tepat sasaran**: Masyarakat ingin bantu tapi tidak tahu kebutuhan riil posko |
| 5 | **Data banjir tidak real-time**: BPBD tidak punya data tinggi air & sebaran genangan untuk pengambilan keputusan |

---

## Slide 3: Solusi — TitikAman

| Fitur | Manfaat |
|-------|---------|
| **SOS Darurat** | 1 klik kirim sinyal + GPS otomatis + prioritas korban otomatis |
| **Mission Control** | Antrian SOS + peta + tugaskan tim + backup dalam 1 dashboard |
| **Peta Posko Interaktif** | Lihat kapasitas & status real-time, langsung navigasi ke lokasi |
| **Donasi Publik** | Donasi sesuai kebutuhan riil posko, transparan & terverifikasi |
| **TMA + Laporan Banjir** | Data tinggi air pintu air real-time + peta genangan crowdsource terverifikasi |

**Menghubungkan 4 aktor dalam satu platform:**
```
Warga → [Lapor/SOS] → Relawan → [Evakuasi] → Posko → [Logistik] → BPBD → [Verifikasi & Pantau]
```

---

## Slide 4: Demo LIVE (5-6 menit)

- Buka titikaman.infinityfreeapp.com
- Login sebagai **Warga** → lapor banjir + kirim SOS
- Login sebagai **Admin Relawan** → lihat antrian SOS + tugaskan tim
- Login sebagai **Admin BPBD** → verifikasi laporan + update TMA
- Login sebagai **Pengelola Posko** → update kapasitas + verifikasi donasi

*(Sebutkan tech stack sepintas saat demo: Laravel 12, Tailwind CSS, Leaflet.js, MySQL)*

---

## Slide 5: Testing & Hasil

| Jenis | Detail | Hasil |
|-------|--------|-------|
| **PHPUnit** | 9 test suites, 200 assertions | **59 passed ✅** (2 pre-existing risky) |
| **Apa yang di-test** | Login (email & HP), registrasi 3 role, auto-redirect pending/approved/rejected, lapor banjir, SOS + prioritas, tugaskan misi, selesaikan misi, review anggota, export CSV, update shelter, kelola kebutuhan, verifikasi donasi, update TMA, export laporan, akses role (403/404/419/500) | Semua ✅ |
| **Black Box** | 54 test case — 7 area fitur | **54 ✅** |

**Per Fitur:**

```
Fitur                              Test Case   Status
Login/Registrasi/Verifikasi        11 TC       ✅
Dashboard Admin Relawan            17 TC       ✅
Manajemen Anggota Tim               4 TC        ✅
Admin BPBD (laporan, TMA, user)     8 TC        ✅
Warga (SOS, lapor, donasi)         14 TC        ✅
```

---

## Slide 6: Penutup

**Kesimpulan:**
- TitikAman menyatukan **Warga → Relawan → Posko → BPBD** dalam satu platform real-time
- Respons evakuasi lebih cepat dengan prioritas otomatis & penugasan terstruktur
- Data banjir & logistik transparan untuk semua pihak

**Repo:** https://github.com/Rangga11268/titikAman
