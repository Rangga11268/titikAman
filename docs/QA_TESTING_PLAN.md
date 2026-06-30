# 🧪 QA Testing Plan — TitikAman

Dokumen ini disusun untuk tim Quality Assurance (QA) dalam melakukan pengujian sistem TitikAman. Mencakup **Black Box Testing**, **Usability Testing (Kuesioner)**, dan **UX Testing**. Dokumen ini dirancang agar mudah dipahami oleh QA Engineer maupun AI Coding Assistant.

---

## 📋 Ringkasan Sistem (4 Aktor Utama)

| # | Role | Hak Akses |
|---|------|-----------|
| 1 | **Warga** | Dashboard umum, lapor banjir, SOS, donasi, lihat peta, lihat detail berita |
| 2 | **Admin_Relawan** | Dashboard mission control, tugaskan misi, review anggota, export CSV, fullscreen peta, refresh peta, manajemen anggota (edit/hapus/pindah tim) |
| 3 | **Pengelola_Posko** | Kelola kapasitas posko, kebutuhan logistik, verifikasi donasi |
| 4 | **Admin_BPBD** | Verifikasi laporan banjir, kelola TMA, set surut, verifikasi pengguna, export laporan |

**Catatan**: Role `Relawan` di database hanya sebagai label data registrasi — tidak memiliki akses dashboard khusus.

---

## 🧪 1. Black Box Testing (Fungsional)

### 1.1 Autentikasi & Akun

| No | Fungsi / Feature | Input yang Diuji | Langkah Uji | Output yang Diharapkan | Status |
|----|------------------|-----------------|-------------|------------------------|--------|
| 1 | Login via Email | Email dan Password valid | Buka form login, masukkan email dan password valid, klik "Login" | Redirect ke dashboard sesuai role | Lulus |
| 2 | Login via No HP | Nomor HP dan Password valid | Buka form login, masukkan nomor HP dan password valid, klik "Login" | Redirect ke dashboard sesuai role | Lulus |
| 3 | Login Gagal | Email valid, Password salah | Buka form login, masukkan email valid dan password salah, klik "Login" | Pesan error: "Kredensial tidak cocok" | Lulus |
| 4 | Register Warga | Form pendaftaran lengkap | Buka form register, pilih role Warga, isi data lengkap, klik "Daftar" | Auto-approve, langsung login ke dashboard Warga | Lulus |
| 5 | Register Relawan | Form pendaftaran + Upload KTP | Buka form register, pilih role Relawan/SAR, isi data + upload dokumen, klik "Daftar" | Redirect ke login, status akun "Pending" | Lulus |
| 6 | Register Pengelola Posko | Form pendaftaran + Data Posko | Buka form register, pilih role Pengelola, isi data diri + data posko + lokasi GPS, klik "Daftar" | Redirect ke login, status akun "Pending" | Lulus |
| 7 | Cek Status Pending | Akun dengan status Pending | Login dengan akun Relawan/Pengelola yang masih pending | Redirect ke `/status-verifikasi`, ikon jam pasir + info menunggu verifikasi | Lulus |
| 8 | Cek Status Rejected | Akun dengan status Rejected | Login dengan akun yang ditolak Admin | Redirect ke `/status-verifikasi`, ikon silang merah + info penolakan | Lulus |
| 9 | Cek Status Approved (halaman manual) | Akun Relawan status Approved | Login sebagai Relawan approved → akses manual `/status-verifikasi` | Ikon centang hijau + nama tim + link grup WA + tombol Lanjut ke Dashboard | Lulus |
| 10 | Login Approved (redirect) | Akun Relawan/role lain status Approved | Login dengan akun yang sudah approved selain Relawan | Redirect ke dashboard sesuai role (bukan ke `/status-verifikasi`) | Lulus |
| 11 | Logout | Klik tombol logout | Pengguna login, klik tombol "Keluar" | Session dihapus, redirect ke halaman Login | Lulus |

### 1.2 Dashboard Admin Relawan (Mission Control)

| No | Fungsi / Feature | Input yang Diuji | Langkah Uji | Output yang Diharapkan | Status |
|----|------------------|-----------------|-------------|------------------------|--------|
| 12 | Akses Dashboard Relawan | Akun Admin_Relawan | Login sebagai `relawan@example.com`, buka `/relawan/dashboard` | Dashboard Mission Control tampil lengkap (statistik, antrian, peta, tim) | Lulus |
| 13 | Antrian SOS | Data SOS di database | Dashboard terbuka, lihat panel antrian SOS | Daftar SOS tampil dengan badge prioritas (TINGGI/SEDANG/RENDAH), urut by status lalu waktu | Lulus |
| 14 | Tugaskan Misi ke Tim | SOS status waiting | Klik "Tugaskan ke Tim", pilih tim dari dropdown, klik "Tugaskan Misi" | Misi terbuat, banner hijau muncul: tombol WA ke Relawan, Share Grup, Minta Bantuan, Google Maps | Lulus |
| 15 | Kirim Bantuan Tim (Backup) | SOS status assigned | Klik "Kirim Bantuan Tim" pada SOS yang sudah ditangani | Modal penugasan terbuka, bisa pilih tim backup (multiple mission per SOS) | Lulus |
| 16 | Review Anggota Baru | Pendaftar Relawan baru | Klik "Review" pada pendaftar baru di tabel | Modal muncul: data diri + pratinjau dokumen KTP | Lulus |
| 17 | Terima Anggota Baru | Review modal terbuka | Klik "Terima & Masukkan Tim" | Status berubah approved, banner biru muncul: "Kirim Info via WA ke [Nama]" + "Link Grup [Tim]" | Lulus |
| 18 | Tolak Anggota Baru | Review modal terbuka | Klik "Tolak" | Status berubah rejected, anggota tidak masuk tim | Lulus |
| 19 | Selesaikan Misi | Misi berstatus aktif | Klik "Selesai" pada misi aktif, konfirmasi | Misi completed, SOS berubah jadi completed, masuk riwayat | Lulus |
| 20 | Riwayat Misi | Misi yang telah selesai | Buka tabel Riwayat Misi, klik "Detail" pada salah satu | Modal muncul dengan data lengkap: lokasi, korban, prioritas, tim, lead, waktu | Lulus |
| 21 | Export CSV Misi | Tabel Riwayat Misi | Klik "Export CSV" | File CSV terdownload otomatis | Lulus |
| 22 | Fullscreen Peta | Tombol fullscreen peta | Klik ikon maximize-2 pada peta | Peta membesar penuh layar, icon berubah jadi minimize-2 | Lulus |
| 23 | Refresh Peta | Tombol refresh peta | Klik ikon refresh pada peta | Data SOS di-refresh dari server via AJAX | Lulus |
| 24 | Kirim WA Anggota Baru | Anggota baru di-approve | Klik "Kirim Info via WA ke [Nama]" di banner approval | WhatsApp terbuka dengan pesan berisi link grup tim | Lulus |
| 25 | Share Grup WA (Tim) | Misi baru ditugaskan | Klik "Share Grup [Tim]" di banner sukses misi | WhatsApp terbuka dengan pesan instruksi: info SOS + Maps + prioritas | Lulus |
| 26 | Minta Bantuan Backup | Misi baru ditugaskan | Klik "Minta Bantuan (Grup Gabungan)" | WhatsApp terbuka dengan pesan minta bantuan ke grup gabungan | Lulus |
| 27 | Dismiss Banner WA | Banner sukses misi | Klik ikon X pada banner hijau setelah tugaskan misi | Banner hilang, session WA dihapus | Lulus |
| 28 | Dismiss Banner Approval | Banner sukses approve | Klik ikon X pada banner biru setelah approve anggota | Banner hilang, session approval dihapus | Lulus |

### 1.3 Manajemen Anggota Tim

| No | Fungsi / Feature | Input yang Diuji | Langkah Uji | Output yang Diharapkan | Status |
|----|------------------|-----------------|-------------|------------------------|--------|
| 29 | Daftar Anggota Tim | Card Tim di dashboard | Klik card "Tim [Kecamatan]" pada panel Anggota Tim Aktif | Modal terbuka menampilkan daftar anggota: nama, no HP, status, tombol aksi | Lulus |
| 30 | Edit Anggota | Keahlian & Organisasi | Klik "Edit" pada anggota, ubah keahlian/organisasi, simpan | Data anggota berhasil diperbarui | Lulus |
| 31 | Pindah Anggota Tim | Ubah domisili kecamatan | Di modal edit, ubah pilihan kecamatan, simpan | Anggota pindah ke tim kecamatan baru | Lulus |
| 32 | Hapus Anggota | Tombol Hapus | Klik "Hapus" pada anggota, konfirmasi | Anggota dinonaktifkan (status rejected) | Lulus |

### 1.4 Dashboard Admin BPBD

| No | Fungsi / Feature | Input yang Diuji | Langkah Uji | Output yang Diharapkan | Status |
|----|------------------|-----------------|-------------|------------------------|--------|
| 33 | Akses Dashboard Admin | Akun Admin_BPBD | Login sebagai `admin@example.com`, buka `/admin/dashboard` | Dashboard admin tampil: statistik, peta, log aktivitas, laporan pending | Lulus |
| 34 | Pilih Shelter (Admin BPBD) | Akun Admin_BPBD | Klik "Pilih Posko", pilih shelter dari dropdown | Session shelter tersimpan, redirect ke halaman kelola posko | Lulus |
| 35 | Verifikasi Laporan Banjir | Laporan status pending | Klik "Verifikasi" pada laporan banjir yang dikirim warga | Laporan diverifikasi, marker muncul di peta publik | Lulus |
| 36 | Tolak Laporan Banjir | Laporan status pending | Klik "Tolak" pada laporan banjir | Laporan ditolak (rejected), tidak muncul di peta | Lulus |
| 37 | Set Surut Banjir | Laporan status verified | Klik "Set Surut" pada laporan yang genangannya sudah hilang | Water height direset menjadi 0 cm | Lulus |
| 38 | Update TMA | Tinggi air (angka cm) | Buka Kelola TMA, masukkan nilai cm baru, klik "Update" | Status bahaya otomatis (Normal/Siaga 3/2/1) | Lulus |
| 39 | Verifikasi Pengguna Baru | Pengelola Posko Pending | Buka `/admin/verifikasi-pengguna`, pilih akun, klik "Setujui" | Status akun approved, pengelola bisa login | Lulus |
| 40 | Export Laporan Banjir | Tabel laporan | Klik "Export CSV" pada tabel laporan banjir | File CSV terdownload | Lulus |

### 1.5 Dashboard Warga & Fitur Publik

| No | Fungsi / Feature | Input yang Diuji | Langkah Uji | Output yang Diharapkan | Status |
|----|------------------|-----------------|-------------|------------------------|--------|
| 41 | Kirim SOS Darurat | Form SOS | Buka `/warga/sos`, izinkan GPS, isi jumlah orang + kelompok rentan, kirim | SOS tersimpan (status waiting), muncul di antrian Admin Relawan | Lulus |
| 42 | Update Lokasi SOS | SOS aktif | GPS berubah, sistem kirim update koordinat | Lokasi SOS diperbarui di database | Lulus |
| 43 | Lapor Genangan Banjir | Form laporan (wizard) | Buka `/warga/lapor`, isi tinggi air (slider), upload foto, pilih akses jalan/listrik, submit | Laporan tersimpan (status pending), menunggu verifikasi Admin BPBD | Lulus |
| 44 | Submit Donasi Logistik | Pilih barang + upload resi | Buka halaman Posko, scroll ke form donasi, pilih posko & barang, isi jumlah (≤ sisa), upload foto, submit | Donasi tersimpan (status pending), muncul di dashboard Pengelola Posko | Lulus |
| 45 | Verifikasi Donasi (Konfirmasi) | Donasi status pending | Login Pengelola, buka panel "Verifikasi Donasi", klik "Konfirmasi" pada donasi | Donasi langsung berubah jadi delivered, quantity_fulfilled otomatis bertambah | Lulus |
| 46 | Tolak Donasi | Donasi status pending | Klik "Tolak" pada donasi yang tidak sesuai | Donasi ditolak (rejected), tidak mengurangi stok kebutuhan | Lulus |
| 47 | Auto-Update Kebutuhan | Data kebutuhan posko | Setelah donasi diverifikasi delivered | Kolom quantity_fulfilled pada shelter_needs bertambah sesuai jumlah donasi | Lulus |
| 48 | Detail Modal Laporan | Klik berita di dashboard | Klik salah satu berita laporan banjir di dashboard | Modal muncul: foto, tinggi air, nama jalan, status akses/listrik/air, pelapor | Lulus |
| 49 | Peta Evakuasi | Akses `/peta-evakuasi` | Buka halaman Peta Evakuasi | Peta interaktif dengan marker posko (ikon rumah) & marker banjir (ikon tetes air) | Lulus |
| 50 | Data Pintu Air | Akses `/data-pintu-air` | Buka halaman Data Pintu Air | Tabel status pintu air + peta lokasi + status Siaga terkini + tombol Export CSV | Lulus |
| 51 | Export Data TMA | Tombol Export | Klik "Export CSV" di halaman Data Pintu Air | File CSV terdownload | Lulus |
| 52 | Info Statistik Posko | Akses `/posko` | Buka halaman direktori Posko | Daftar posko + kapasitas + status + search + pagination + peta sebaran + form donasi | Lulus |
| 53 | Update Shelter (Pengelola) | Data kapasitas & status | Login Pengelola, update jumlah pengungsi, ubah status (Aktif/Penuh/Tutup) | Data posko tersimpan, status di peta publik menyesuaikan | Lulus |
| 54 | Export Data Donasi | Tombol Export | Klik "Export CSV" di halaman donasi | File CSV terdownload | Lulus |

---

## 📝 2. Usability Testing (Kuesioner)

Kuesioner ini diisi oleh responden setelah mencoba sistem. Skala: 1 (Sangat Tidak Setuju) — 5 (Sangat Setuju).

### 2.1 Pertanyaan System Usability Scale (SUS)

| No | Pertanyaan | Skor (1-5) |
|----|-----------|------------|
| 1 | Saya pikir akan sering menggunakan sistem ini | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 2 | Sistem ini terlalu rumit (saya merasa perlu bantuan teknis) | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 3 | Saya pikir sistem ini mudah digunakan | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 4 | Saya pikir saya akan membutuhkan bantuan orang teknis untuk menggunakan sistem ini | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 5 | Saya menemukan berbagai fungsi di sistem ini terintegrasi dengan baik | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 6 | Saya pikir ada terlalu banyak inkonsistensi dalam sistem ini | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 7 | Saya bayangkan kebanyakan orang akan mudah mempelajari sistem ini | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 8 | Saya menemukan sistem ini sangat rumit untuk digunakan | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 9 | Saya merasa sangat percaya diri menggunakan sistem ini | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 10 | Saya perlu belajar banyak sebelum saya bisa menggunakan sistem ini | ☐1 ☐2 ☐3 ☐4 ☐5 |

### 2.2 Pertanyaan Task-Specific

| No | Pertanyaan | Skor (1-5) |
|----|-----------|------------|
| 11 | Apakah proses registrasi akun mudah diikuti? | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 12 | Apakah tombol SOS mudah ditemukan dan digunakan? | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 13 | Apakah peta interaktif mudah dipahami? | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 14 | Apakah informasi posko dan statusnya jelas ditampilkan? | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 15 | Apakah proses donasi logistik mudah dilakukan? | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 16 | Apakah menu navigasi dan tata letak sudah intuitif? | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 17 | Apakah tampilan di HP (mobile) sudah nyaman digunakan? | ☐1 ☐2 ☐3 ☐4 ☐5 |
| 18 | Apakah notifikasi/banner WhatsApp yang muncul sudah jelas? | ☐1 ☐2 ☐3 ☐4 ☐5 |

### 2.3 Pertanyaan Feedback Terbuka

| No | Pertanyaan |
|----|-----------|
| 19 | Fitur apa yang paling Anda sukai dari sistem ini? |
| 20 | Apa kesulitan utama yang Anda alami saat menggunakan sistem ini? |
| 21 | Saran perbaikan apa yang ingin Anda sampaikan? |

---

## 🎨 3. UX Testing (Heuristic Evaluation)

Pengujian menggunakan **10 Heuristic Nielsen** untuk mengevaluasi User Experience.

| # | Heuristic | Yang Diperiksa | Status (✅/❌) | Catatan |
|---|-----------|---------------|---------------|---------|
| H1 | **Visibility of System Status** | Apakah sistem memberikan feedback yang jelas saat user melakukan aksi (loading, sukses, error)? | | |
| H2 | **Match System & Real World** | Apakah ikon dan label yang digunakan sesuai dengan bahasa/istilah sehari-hari? | | |
| H3 | **User Control & Freedom** | Apakah user bisa membatalkan aksi (misal: tombol Batal di modal)? | | |
| H4 | **Consistency & Standards** | Apakah tampilan konsisten antar halaman (warna, font, layout)? | | |
| H5 | **Error Prevention** | Apakah ada validasi form sebelum submit? Apakah pesan error jelas? | | |
| H6 | **Recognition vs Recall** | Apakah navigasi dan menu mudah diingat tanpa perlu belajar ulang? | | |
| H7 | **Flexibility & Efficiency** | Apakah ada akses cepat (shortcut) untuk pengguna mahir? | | |
| H8 | **Aesthetic & Minimalist Design** | Apakah tampilan tidak berantakan dan hanya menampilkan informasi penting? | | |
| H9 | **Help Users Recognize Errors** | Apakah pesan error spesifik dan membantu user memperbaiki kesalahan? | | |
| H10 | **Help & Documentation** | Apakah ada panduan atau tooltip yang membantu user baru? | | |

### 3.1 Skenario UX Testing

| ID | Skenario | Pengguna | Tugas yang Diuji |
|----|----------|----------|-----------------|
| UX-01 | Registrasi & Login | Warga baru | Daftar → Login → Lihat dashboard |
| UX-02 | Kirim SOS | Warga | Buka SOS → Isi form → Kirim |
| UX-03 | Lapor Banjir | Warga | Buka lapor → Isi wizard → Upload foto |
| UX-04 | Lihat Peta & Detail | Semua role | Buka peta → Klik marker → Lihat popup |
| UX-05 | Manajemen Misi | Admin Relawan | Lihat antrian → Tugaskan tim → Kirim WA |
| UX-06 | Manajemen Anggota | Admin Relawan | Review pendaftar → Approve → Edit anggota |
| UX-07 | Kelola Posko | Pengelola Posko | Update kapasitas → Tambah kebutuhan → Verifikasi donasi |
| UX-08 | Verifikasi Laporan | Admin BPBD | Lihat laporan pending → Verifikasi/Tolak |
| UX-09 | Kelola TMA | Admin BPBD | Update tinggi air → Cek status bahaya |
| UX-10 | Mobile View | Semua role | Akses semua halaman via HP (≤768px) |

---

## 📄 4. Environment Testing

| Item | Spesifikasi |
|------|-------------|
| **Browser Desktop** | Chrome 120+, Firefox 120+, Edge 120+ |
| **Browser Mobile** | Chrome Android, Safari iOS |
| **Resolusi Desktop** | 1920×1080, 1366×768, 1440×900 |
| **Resolusi Mobile** | 414×896 (iPhone 11), 360×800 (Pixel 5), 768×1024 (iPad) |
| **Koneksi** | Broadband (20+ Mbps), 3G (simulasi lambat) |
| **Backend** | PHP 8.3 + MySQL + Laravel 12 |

---

## ✅ 5. Kriteria Kelulusan

| Item | Target |
|------|--------|
| **Functional Test Pass Rate** | 100% semua TC berstatus **✅ Pass** |
| **SUS Score** | Rata-rata ≥ 70 (Grade C atau lebih baik) |
| **Heuristic Violations** | Tidak ada violation dengan severity rating ≥ 3 (Major) |
| **Mobile Responsive** | Semua halaman tidak ada overflow horizontal & tombol dapat di-tap |
| **Error Rate** | Tidak ada fatal error (HTTP 500) di production |
