# 📊 Penjelasan Relasi Database - TitikAman

Dokumen ini memaparkan hubungan (relasi) antar-tabel dalam database sistem tanggap darurat banjir **TitikAman** berdasarkan skema 8 tabel utama. Dokumentasi ini dibuat agar tim analis dapat memahami alur data dan logika bisnis sistem dengan jelas.

---

## 🗺️ Tabel Ringkasan Relasi

Berikut adalah kata kerja (verb) sebutan relasi, tipe kardinalitas, dan deskripsi relasi antar-tabel dalam sistem TitikAman:

| Tabel Asal | Sebutan Relasi (Verb) | Tabel Tujuan | Kardinalitas | Penjelasan Operasional |
| :--- | :---: | :--- | :---: | :--- |
| **users** | `melaporkan` | **flood_reports** | 1:N | Seorang warga (`users`) dapat melaporkan banyak kejadian genangan banjir di sekitarnya. |
| **users** | `mengirim` | **sos_requests** | 1:N | Seorang warga (`users`) yang terjebak banjir dapat mengirimkan permohonan bantuan SOS. |
| **users** | `menyumbang` | **donations** | 1:N | Seorang donatur (`users`) dapat memberikan banyak donasi logistik untuk posko. |
| **users** | `menangani` | **rescue_missions** | 1:N | Seorang relawan (`users`) dapat ditugaskan untuk menangani banyak misi penyelamatan. |
| **shelters** | `membutuhkan` | **shelter_needs** | 1:N | Posko pengungsian (`shelters`) mendaftarkan berbagai macam kebutuhan logistiknya. |
| **shelter_needs** | `terpenuhi` | **donations** | 1:N | Kebutuhan posko (`shelter_needs`) dapat dipenuhi secara berangsur oleh banyak transaksi donasi. |
| **sos_requests** | `memicu` | **rescue_missions** | 1:1 | Laporan bahaya SOS (`sos_requests`) memicu satu misi evakuasi lapangan oleh relawan. |

---

## 🔍 Detail Penjelasan Relasi & Logika Operasional

### 1. Akun Pengguna (`users`) sebagai Aktor Multi-Peran
Sistem TitikAman menyatukan seluruh aktor di tabel `users`. Peran masing-masing diatur menggunakan otorisasi berbasis *Role*:
* **Warga ↔ Laporan Banjir (`melaporkan` - 1:N)**: Warga yang telah login dapat memetakan tinggi air banjir di kawasannya. ID pelapor dicatat di kolom `user_id` pada tabel `flood_reports`.
* **Warga ↔ Permintaan SOS (`mengirim` - 1:N)**: Saat terjebak dalam kondisi darurat, warga mengirimkan koordinat GPS. ID pengirim tersimpan sebagai `user_id` di tabel `sos_requests`.
* **Donatur ↔ Donasi (`menyumbang` - 1:N)**: Pengguna yang ingin menyumbang barang logistik dicatat sebagai `donor_id` di tabel `donations`.
* **Relawan ↔ Misi Penyelamatan (`menangani` - 1:N)**: Relawan lapangan yang dikirim kelurahan/BPBD untuk evakuasi dihubungkan lewat `volunteer_id` di tabel `rescue_missions`.

### 2. Logistik Pengungsian (`shelters` ↔ `shelter_needs` ↔ `donations`)
* **Relasi `membutuhkan` (1:N)**: Satu posko (`shelters`) mendaftarkan banyak jenis barang kebutuhan (misal: "Selimut", "Susu Bayi") di tabel `shelter_needs`.
* **Relasi `terpenuhi` (1:N)**: Kebutuhan barang tertentu di posko (contoh: Butuh 100 Selimut) dapat dipenuhi oleh donasi dari berbagai donatur secara kolektif. Status pemenuhan dihitung lewat selisih `quantity_need` dan `quantity_fulfilled`.

### 3. Alur Penyelamatan SOS (`sos_requests` ↔ `rescue_missions`)
* **Relasi `memicu` (1:1)**: Sinyal SOS warga memicu pembentukan entitas `rescue_missions` baru yang mengikat `sos_id` secara unik. Satu laporan SOS hanya ditugaskan ke satu misi evakuasi aktif agar tidak ada tumpang tindih relawan di lokasi penyelamatan yang sama.
