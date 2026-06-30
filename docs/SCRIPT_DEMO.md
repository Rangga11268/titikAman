# Script Demo 5-6 Menit — TitikAman

> **Strategi:** Siapkan 3-4 tab browser sudah login sebelumnya, tinggal klik.

```
Tab 1: Warga     → warga@example.com     → sudah login
Tab 2: Relawan   → relawan@example.com   → sudah login
Tab 3: Admin     → admin@example.com     → sudah login
Tab 4: Pengelola → pengelola@example.com → sudah login (opsional)
```

---

## 0:00 – 1:00 | Slide 1-2 — Pembukaan + Nama Tim & Judul (Presenter: Darell)

**Assalamualaikum wr. wb.**

Selamat siang, Bapak/Ibu penguji dan teman-teman sekalian.

Perkenalkan, kami dari **Tim TitikAman**.

**Anggota Tim:**

| Nama | Peran |
|------|-------|
| Darell | Project Manager & Lead Developer |
| Wahyu | Tim Analis |
| Maul | Tim Analis |
| Putri | Quality Assurance |

Judul project kami:

> **"TitikAman — Sistem Informasi Mitigasi Banjir dan Navigasi Jalur Evakuasi Berbasis Web di Kota Bekasi"**

Kami akan mendemonstrasikan alur lengkap sistem: mulai dari **warga melapor banjir dan mengirim SOS**, **Admin Relawan menugaskan tim evakuasi**, **Admin BPBD memverifikasi laporan**, hingga **Pengelola Posko mengupdate logistik**.

---

## 1:00 – 2:30 | Slide 6 — Demo LIVE (Presenter: Putri)

**Scene 1 — Lapor Banjir (1 menit)**

> *"Pertama, kita lihat dari sisi warga. Warga yang terdampak banjir bisa melaporkan kondisi di lokasinya."*

- Buka `/warga/lapor`
- Isi tinggi air: slider ke **75 cm**
- Upload foto (buka file manager, pilih gambar)
- Pilih akses jalan: "Sulit Dilewati"
- Centang listrik padam
- **Submit**

> *"Laporan masuk dengan status pending, menunggu verifikasi Admin BPBD. Setiap laporan wajib disertai foto dan terikat bounding box Bekasi."*

**Scene 2 — SOS Darurat (30 detik)**

> *"Sekarang warga dalam situasi darurat — butuh evakuasi segera."*

- Buka `/warga/sos`
- Klik **"Kirim Sinyal SOS"**
- Isi: 4 orang terjebak, 1 lansia
- **Kirim**

> *"Sistem otomatis menghitung prioritas HIGH karena ada kelompok rentan (lansia). SOS langsung masuk antrian Admin Relawan beserta koordinat GPS."*

---

## 2:30 – 4:00 | Slide 6 — Demo LANJUTAN: Admin Relawan (Tab 2)

> *"Sekarang kita lihat dari sisi Admin Relawan — komandan tim evakuasi."*

- Pindah ke Tab 2, refresh
- Tunjukin **panel kiri** — SOS baru muncul dengan badge **TINGGI**

> *"Di sini Admin Relawan melihat antrian SOS. Status waiting, prioritas high, ada detail jumlah korban dan lokasi."*

- Klik **TUGASKAN KE TIM** → pilih tim **"Bekasi Timur (Lead: Budi Santoso)"**
- Klik **Tugaskan Misi**
- Banner hijau muncul

> *"Misi evakuasi langsung tercatat. Banner ini menampilkan beberapa tombol aksi."*

- Klik **"Kirim ke WhatsApp"** (buka WA, tunjukkin pesenya, langsung tutup)

> *"Satu klik, Admin Relawan bisa mengirim instruksi lengkap ke nomor ketua tim — berisi info lokasi, prioritas, dan link Google Maps. Tim langsung bergerak."*

> *"Kalau butuh backup, tinggal klik 'Minta Bantuan' — notifikasi dikirim ke grup gabungan."*

---

## 4:00 – 5:00 | Admin BPBD: Verifikasi Laporan (Tab 3)

> *"Sekarang dari sisi Admin BPBD — verifikator utama."*

- Pindah ke Tab 3, refresh
- Tunjukin laporan pending (foto, tinggi air, lokasi)

> *"Admin BPBD melihat laporan banjir yang tadi dikirim Warga. Ada foto, tinggi air 75 cm, lokasi jelas."*

- Klik **Verifikasi**

> *"Setelah diverifikasi, laporan muncul di peta publik — semua pengguna bisa melihat titik banjir ini."*

- Buka `/admin/tma`
- Klik **Update** pada pintu air, isi `220` cm

> *"Admin juga bisa update data TMA (Tinggi Muka Air). Sistem otomatis menghitung status bahaya. 220 cm masuk Siaga 2. Kalau melebihi 250, jadi Siaga 1 (Bahaya) dan sistem akan mengirim peringatan dini."*

---

## 5:00 – 5:30 | Slide 6 — Demo LANJUTAN: Pengelola Posko (Tab 4)

> *"Terakhir, pengelola posko — yang memastikan logistik berjalan."*

- Buka `/pengelola/dashboard`
- Ubah jumlah pengungsi dari 120 → **135**
- Tambah kebutuhan: **"Makanan Siap Saji"** → 500 pcs → Urgensi High

> *"Pengelola bisa update kapasitas dan kebutuhan logistik secara real-time. Warga yang ingin donasi tinggal buka halaman posko, lihat kebutuhan yang tertera, dan donasi langsung terverifikasi."*

---

## 5:30 – 6:00 | Slide 8 — Penutup (Presenter: Darell)

> *"Baik, sekian demo dari kami. Sekarang saya akan sampaikan kesimpulan."*

**Kesimpulan:**
- TitikAman menyatukan **Warga → Relawan → Posko → BPBD** dalam satu platform real-time
- Respons evakuasi lebih cepat dengan prioritas otomatis & penugasan terstruktur
- Data banjir & logistik transparan untuk semua pihak

---

## Lampiran: Ringkasan Waktu

| Waktu | Aksi | Slide | Presenter | Durasi |
|-------|------|-------|-----------|--------|
| 0:00 | Pembukaan + Perkenalan Tim | 1-2 | Darell | 1 menit |
| 1:00 | Warga: Lapor Banjir | 6 | Putri | 1 menit |
| 2:00 | Warga: Kirim SOS | 6 | Putri | 30 detik |
| 2:30 | Relawan: Tugaskan Misi + WA | 6 | Putri | 1.5 menit |
| 4:00 | Admin: Verifikasi Laporan + Update TMA | 6 | Putri | 1 menit |
| 5:00 | Pengelola: Update Kapasitas | 6 | Putri | 30 detik |
| 5:30 | Penutup | 8 | Darell | 30 detik |
| **Total** | | | | **~6 menit** |

## Tips Penting

1. **Siapkan file foto** di desktop biar gak searching pas upload
2. **GPS mock location** — setting DevTools → Sensors → Location → pilih "Bekasi" (Custom location: -6.24, 106.99)
3. **Pastikan semua tab sudah login SEBELUM demo dimulai**
4. **Kalau WA error** (koneksi): skip, bilang aja "Ini akan redirect ke WhatsApp dengan pesan otomatis"
5. **Kalau WebSocket error**: "Tenang, sistem tetap jalan karena sudah di-handle dengan try-catch"
6. **Jangan panik kalau ada error** — bilang aja "Ini feedback yang kami tangani" lalu lanjut
