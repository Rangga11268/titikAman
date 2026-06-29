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

| ID | Skenario | Langkah | Expected Result |
|----|----------|---------|----------------|
| **TC-01** | Login via Email | Buka `/login`, isi email + password, klik Masuk | Redirect ke dashboard sesuai role |
| **TC-02** | Login via No HP | Buka `/login`, isi nomor HP + password, klik Masuk | Redirect ke dashboard sesuai role |
| **TC-03** | Login gagal (salah password) | Input email benar + password salah | Pesan error: "Kredensial tidak cocok" |
| **TC-04** | Register Warga | Pilih role Warga, isi form lengkap | Auto login, redirect ke dashboard |
| **TC-05** | Register Relawan | Pilih role Relawan/SAR, isi form + upload dokumen | Redirect ke login, status pending |
| **TC-06** | Register Pengelola Posko | Pilih role Pengelola Posko, isi data posko + foto | Redirect ke login, status pending |
| **TC-07** | Login akun pending | Login dengan akun Relawan yang masih pending | Redirect ke `/status-verifikasi` (tampilkan jam pasir) |
| **TC-08** | Login akun rejected | Login dengan akun Relawan yang ditolak | Redirect ke `/status-verifikasi` (tampilkan silang merah) |
| **TC-09** | Login akun approved (Relawan) | Login dengan akun Relawan yang sudah disetujui | Redirect ke `/status-verifikasi` (tampilkan centang hijau + link grup WA + tombol kirim info ke WA sendiri + tombol Lanjut ke Dashboard) |
| **TC-10** | Logout | Klik tombol Keluar | Redirect ke halaman login, session dihapus |

### 1.2 Dashboard Admin Relawan (Mission Control)

| ID | Skenario | Langkah | Expected Result |
|----|----------|---------|----------------|
| **TC-11** | Akses dashboard | Login sebagai `relawan@example.com`, buka `/relawan/dashboard` | Halaman dashboard mission control tampil |
| **TC-12** | Lihat antrian SOS | Dashboard terbuka | Antrian SOS muncul (status waiting + assigned) |
| **TC-13** | Tugaskan misi ke tim | Klik TUGASKAN KE TIM pada SOS, pilih tim, klik Tugaskan Misi | Mission created + banner dengan tombol WA + Maps + Share Grup |
| **TC-14** | Kirim bantuan tim | Klik KIRIM BANTUAN TIM (SOS status assigned) | Modal penugasan terbuka, bisa pilih tim lain |
| **TC-15** | Review anggota baru | Klik Review pada pendaftar | Modal review muncul dengan pratinjau dokumen |
| **TC-16** | Approve anggota | Klik Terima & Masukkan Tim | Banner approval muncul + tombol Kirim WA ke anggota + Link Grup |
| **TC-17** | Selesaikan misi | Klik SELESAI pada misi aktif, konfirmasi | Mission marked completed |
| **TC-18** | Detail riwayat misi | Klik Detail pada tabel riwayat | Modal detail misi muncul (data lengkap) |
| **TC-19** | Export CSV | Klik Export CSV | File CSV terdownload |
| **TC-20** | Fullscreen peta | Klik tombol fullscreen (maximize-2) | Peta membesar ke layar penuh, icon berubah |
| **TC-21** | Refresh peta | Klik tombol refresh | Marker SOS direfresh dari server |
| **TC-22** | Kirim WA ke anggota baru | Klik Kirim Info via WA di banner approval | WhatsApp terbuka dengan pesan berisi link grup |
| **TC-23** | Share misi ke grup tim | Klik Share Grup [Tim] | WhatsApp terbuka dengan pesan terformat |
| **TC-24** | Minta bantuan backup | Klik Minta Bantuan (Grup Gabungan) | WhatsApp terbuka dengan pesan bantuan |

### 1.3 Manajemen Anggota Tim

| ID | Skenario | Langkah | Expected Result |
|----|----------|---------|----------------|
| **TC-25** | Lihat anggota tim | Klik card tim pada panel Anggota Tim Aktif | Modal terbuka dengan daftar anggota (nama, no HP, status, aksi) |
| **TC-26** | Edit anggota | Klik Edit pada anggota di modal | Modal edit terbuka, bisa ubah keahlian/organisasi/pindah tim |
| **TC-27** | Pindah anggota ke tim lain | Di modal edit, ubah kecamatan tujuan | Anggota pindah ke tim baru |
| **TC-28** | Hapus anggota | Klik Hapus, konfirmasi | Anggota dinonaktifkan (status rejected) |

### 1.4 Dashboard Admin BPBD

| ID | Skenario | Langkah | Expected Result |
|----|----------|---------|----------------|
| **TC-29** | Akses admin dashboard | Login sebagai `admin@example.com`, buka `/admin/dashboard` | Dashboard admin BPBD tampil |
| **TC-30** | Verifikasi laporan banjir | Klik Verifikasi pada laporan pending | Status berubah jadi verified |
| **TC-31** | Tolak laporan banjir | Klik Tolak pada laporan pending | Status berubah jadi rejected |
| **TC-32** | Set Surut | Klik Set Surut pada laporan verified | Water height = 0 cm |
| **TC-33** | Update TMA | Input tinggi air baru di Kelola TMA | Status bahaya otomatis dihitung |

### 1.5 Dashboard Warga & Fitur

| ID | Skenario | Langkah | Expected Result |
|----|----------|---------|----------------|
| **TC-34** | Kirim SOS | Buka `/warga/sos`, isi form, kirim | SOS tersimpan, muncul di antrian Admin Relawan |
| **TC-35** | Lapor banjir | Buka `/warga/lapor`, isi form wizard | Laporan tersimpan dengan status pending |
| **TC-36** | Donasi logistik | Buka `/donasi`, pilih posko & barang, submit | Donasi tersimpan, muncul di pengelola posko |
| **TC-37** | Lihat detail berita | Klik berita di dashboard | Modal detail laporan banjir muncul |
| **TC-38** | Buka peta evakuasi | Buka `/peta-evakuasi` | Peta dengan marker posko (icon rumah) dan laporan (icon tetes air) |

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
