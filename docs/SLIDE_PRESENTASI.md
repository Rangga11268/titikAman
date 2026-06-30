# Slide Presentasi TitikAman

> Copy-paste konten di bawah ke PPT.
> Format: **Judul Slide** → poin-poin atau tabel.

---

## Slide 1: Judul (Presenter: Darell)

**TitikAman**
*Sistem Informasi Mitigasi Banjir dan Navigasi Jalur Evakuasi*
*Platform Manajemen Kebencanaan Banjir Terpadu — Kota Bekasi*

---

## Slide 2: Anggota Tim (Presenter: Darell)

| Nama | Peran |
|------|-------|
| **Darell** | Project Manager & Lead Developer |
| **Wahyu** | Tim Analis |
| **Maul** | Tim Analis |
| **Putri** | Quality Assurance |

---

## Slide 3: Latar Belakang & Masalah (Presenter: Wahyu)

**Banjir Besar Maret 2025 — Bekasi Terparah (18.738 KK terdampak)**

| # | Masalah |
|---|---------|
| 1 | **Evakuasi manual**: Warga telepon/WA satu-satu, SAR tidak punya data lokasi & prioritas korban |
| 2 | **Koordinasi SAR tumpang tindih**: Tidak ada sistem penugasan, relawan datang sendiri-sendiri tanpa komando |
| 3 | **Warga tidak tahu posko**: Bingung cari pengungsian terdekat yang masih punya kapasitas |
| 4 | **Donasi tidak tepat sasaran**: Masyarakat ingin bantu tapi tidak tahu kebutuhan riil posko |
| 5 | **Data banjir tidak real-time**: BPBD tidak punya data tinggi air & sebaran genangan untuk pengambilan keputusan |

---

## Slide 4: Solusi — TitikAman (Presenter: Maul)

| Masalah | Solusi |
|---------|--------|
| Evakuasi manual, tidak ada data lokasi & prioritas | **SOS Darurat**: 1 klik, GPS otomatis, prioritas dihitung sistem → langsung ke tim SAR |
| Koordinasi SAR tumpang tindih, tidak ada penugasan | **Mission Control**: Antrian SOS + peta + tugaskan tim + backup dalam 1 dashboard |
| Warga bingung cari posko terdekat | **Peta Posko Interaktif**: Lihat kapasitas & status real-time, navigasi Google Maps |
| Donasi tidak tepat sasaran | **Donasi Publik**: Lihat kebutuhan riil per posko, donasi langsung terverifikasi |
| Data banjir tidak real-time untuk BPBD | **TMA Real-time** dari pintu air + **Laporan Crowdsource** terverifikasi + **Peta Genangan** |

---

## Slide 5: Fitur Unggulan (Presenter: Maul)

| Fitur | Manfaat |
|-------|---------|
| **SOS Darurat** | 1 klik kirim sinyal + GPS otomatis + prioritas korban otomatis |
| **Mission Control** | Antrian SOS + peta + tugaskan tim + backup dalam 1 dashboard |
| **Peta Posko Interaktif** | Lihat kapasitas & status real-time, langsung navigasi ke lokasi |
| **Donasi Publik** | Donasi sesuai kebutuhan riil posko, transparan & terverifikasi |
| **TMA + Laporan Banjir** | Data tinggi air pintu air real-time + peta genangan crowdsource terverifikasi |
| **Tambah Anggota Manual** | Admin Relawan bisa input data relawan existing tanpa registrasi mandiri |

**Menghubungkan 4 aktor dalam satu platform:**
```
Warga  →  Lapor/SOS  →  Relawan  →  Evakuasi  →  Posko  →  Logistik  →  BPBD
```

---

## Slide 6: Demo LIVE (Presenter: Putri)

- **Warga**: Lapor banjir + upload foto → Kirim SOS darurat (prioritas otomatis)
- **Admin Relawan**: Lihat antrian SOS → Tugaskan misi ke tim → Kirim WA instruksi
- **Admin BPBD**: Verifikasi laporan banjir → Update TMA pintu air
- **Pengelola Posko**: Update kapasitas pengungsi → Tambah kebutuhan logistik

> Tech stack: Laravel 12, Tailwind CSS, Leaflet.js, MySQL, Laravel Reverb

---

## Slide 7: Testing & Hasil (Presenter: Darell)

| Jenis | Detail | Hasil |
|-------|--------|-------|
| **PHPUnit** | 9 test suites, 200 assertions | **59 passed ✅** (2 pre-existing risky) |
| **Black Box** | 54 test case — 7 area fitur | **54 ✅** |
| **Error Handling** | Broadcast event graceful fallback saat WebSocket mati | Tidak 500 |
| **Scope** | SOS, lapor banjir, registrasi, posko — semua terkunci bounding box Bekasi | Terverifikasi |

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

## Slide 8: Penutup (Presenter: Darell)

**Kesimpulan:**
- TitikAman menyatukan **Warga → Relawan → Posko → BPBD** dalam satu platform real-time
- Respons evakuasi lebih cepat dengan prioritas otomatis & penugasan terstruktur
- Data banjir & logistik transparan untuk semua pihak

**Saran & masukan sangat kami harapkan 🙏**

**Repo:** https://github.com/Rangga11268/titikAman
