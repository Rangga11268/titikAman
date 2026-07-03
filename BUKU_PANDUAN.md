# PETUNJUK PENGGUNAAN APLIKASI TITIKAMAN

**Sistem Informasi Mitigasi Banjir dan Navigasi Jalur Evakuasi**  
*Platform Manajemen Kebencanaan Banjir Terpadu — Kota Bekasi*

---

## DAFTAR ISI

*   **A. PENDAHULUAN**
    *   1. Latar Belakang
    *   2. Tujuan Sistem
    *   3. Batasan Kebencanaan (Bounding Box Bekasi)
*   **B. PENGENALAN TITIKAMAN**
    *   1. Deskripsi Peran Pengguna (Roles)
    *   2. Spesifikasi Hardware dan Software
        *   2.1. Spesifikasi Hardware
        *   2.2. Spesifikasi Software
*   **C. IMPLEMENTASI DAN ANTARMUKA APLIKASI**
    *   1. Tahapan Penginstalan Aplikasi (Lokal)
    *   2. Panduan Antarmuka & Cara Penggunaan (Dilengkapi Panduan Screenshot)
        *   2.1. Landing Page Utama (Halaman Awal)
        *   2.2. Autentikasi Pengguna (Register & Login)
        *   2.3. Halaman Status Verifikasi Relawan
        *   2.4. Portal Fitur Warga (Dashboard, Peta Evakuasi, Lapor Banjir, SOS Darurat, Donasi Posko)
        *   2.5. Portal Dispatcher Admin Relawan (Mission Control, Penugasan Tim, Notifikasi WhatsApp, Manajemen Anggota)
        *   2.6. Portal Pengelola Posko (Kapasitas Posko, Logistik Kebutuhan, Verifikasi Donasi)
        *   2.7. Portal Super-User Admin BPBD (Dashboard BPBD, Verifikasi Laporan Banjir, Kelola TMA, Verifikasi Akun Baru)

---

<!-- PAGEBREAK -->

## A. PENDAHULUAN

### 1. Latar Belakang
Kota Bekasi merupakan salah satu wilayah yang rentan terhadap bencana banjir musiman akibat luapan daerah aliran sungai (DAS) seperti Kali Bekasi, Cikeas, dan Cakung, serta intensitas curah hujan lokal yang tinggi. Selama ini, koordinasi evakuasi warga, pelaporan titik genangan, pemantauan pintu air, hingga penyaluran bantuan logistik di posko pengungsian masih berjalan secara terpisah dan kurang terintegrasi. 

**TitikAman** hadir sebagai platform web kebencanaan banjir terpadu yang dirancang khusus untuk menjembatani komunikasi dan koordinasi antara warga, tim relawan evakuasi (SAR), pengelola posko, dan dinas kebencanaan pemerintah (BPBD) secara cepat, akurat, dan real-time.

### 2. Tujuan Sistem
*   **Akselerasi Evakuasi Darurat**: Mempercepat proses penyelamatan warga terjebak banjir menggunakan modul SOS Darurat terintegrasi GPS.
*   **Peta Kebencanaan Partisipatif (Crowdsourcing)**: Membantu publik melihat lokasi titik banjir secara dinamis berdasarkan laporan terverifikasi langsung dari lapangan.
*   **Peringatan Dini Terintegrasi**: Menyediakan informasi tinggi muka air (TMA) pintu air hulu secara real-time demi meningkatkan kewaspadaan dini.
*   **Transparansi & Efisiensi Logistik**: Menghubungkan donatur dengan kebutuhan riil posko pengungsian guna menghindari penumpukan atau kekurangan logistik.

### 3. Batasan Kebencanaan (Bounding Box Bekasi)
Untuk mencegah laporan spam atau penyalahgunaan di luar cakupan layanan, aplikasi ini membatasi semua interaksi berbasis GPS (SOS, Laporan Genangan, dan Lokasi Posko) hanya di wilayah Kota Bekasi dan sekitarnya. Batas wilayah yang dikunci oleh sistem adalah:

| Batas Geografis | Latitude (Bintang Selatan) | Longitude (Bujur Timur) |
|---|---|---|
| **Batas Utara** | -6.100 | - |
| **Batas Selatan** | -6.350 | - |
| **Batas Barat** | - | 106.800 |
| **Batas Timur** | - | 107.100 |

*Sistem akan otomatis menolak pelaporan apabila koordinat GPS terdeteksi di luar area batas tersebut.*

---

<!-- PAGEBREAK -->

## B. PENGENALAN TITIKAMAN

### 1. Deskripsi Peran Pengguna (Roles)
Sistem informasi TitikAman membagi hak akses ke dalam 5 peran dengan fungsionalitas spesifik:

1.  **Warga (Masyarakat Umum)**
    Mengirim laporan banjir, meminta bantuan darurat SOS, melihat peta kebencanaan, pemantau pintu air, dan berdonasi logistik ke posko.
2.  **Relawan (Anggota Tim SAR)**
    Merupakan data anggota di lapangan. Relawan tidak memiliki dashboard sistem, namun datanya dikelola oleh Admin Relawan untuk penugasan tim.
3.  **Admin Relawan (Dispatcher/Komandan Tim)**
    Memantau antrean SOS masuk, menugaskan tim relawan, mengirim pesan instruksi otomatis ke grup WhatsApp relawan, dan mengelola profil anggota tim.
4.  **Pengelola Posko (Petugas Shelter)**
    Mengelola kuota posko pengungsian, memperbarui status posko, menginput kebutuhan logistik spesifik, dan memverifikasi donasi masuk.
5.  **Admin BPBD (Super-User/Aparatur)**
    Memverifikasi laporan banjir warga, mengupdate Tinggi Muka Air (TMA) Pintu Air hulu, serta menyetujui (approve) pendaftaran akun Pengelola Posko baru.

---

### 2. Spesifikasi Hardware dan Software

#### 2.1. Spesifikasi Hardware
Aplikasi TitikAman dapat dijalankan dengan spesifikasi perangkat keras minimum berikut:

**Sisi Server (Hosting/Local Development):**
*   **Processor**: Intel Core i3 / AMD Ryzen 3 (atau setara)
*   **RAM**: Minimal 4 GB (Disarankan 8 GB)
*   **Storage**: Tersedia ruang kosong minimal 500 MB untuk instalasi library dan database.

**Sisi Klien (Pengguna - Desktop & HP):**
*   **Smartphone**: Android 8.0+ atau iOS 12+ (dilengkapi modul GPS/Lokasi aktif)
*   **Laptop/PC**: Sistem operasi Windows/Linux/macOS dengan browser modern.

#### 2.2. Spesifikasi Software
*   **Sistem Operasi**: Windows 10/11, macOS, atau Ubuntu Server 20.04 LTS+
*   **Bahasa Pemrograman**: PHP 8.2+ dan JavaScript (ES6)
*   **Framework Utama**: Laravel 12.x
*   **Database Management**: MySQL 8.0+ atau MariaDB 10.5+
*   **Package Manager**: Composer 2.x & npm (Node Package Manager) 18+
*   **Browser**: Google Chrome, Mozilla Firefox, Safari, atau Microsoft Edge verisi terbaru.

---

<!-- PAGEBREAK -->

## C. IMPLEMENTASI DAN ANTARMUKA APLIKASI

### 1. Tahapan Penginstalan Aplikasi (Lokal)

Ikuti instruksi berikut untuk menjalankan TitikAman pada komputer lokal menggunakan Web Server lokal (seperti Laragon, XAMPP, atau PHP CLI):

1.  **Clone Repositori Proyek:**
    ```bash
    git clone https://github.com/Rangga11268/titikAman.git
    cd titikAman
    ```
2.  **Instalasi Library PHP & Node.js:**
    ```bash
    composer install
    npm install
    ```
3.  **Konfigurasi Environment File:**
    Salin file `.env.example` menjadi `.env` kemudian sesuaikan konfigurasi database Anda (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
4.  **Generate Application Key & Database Link:**
    ```bash
    php artisan key:generate
    php artisan storage:link
    ```
5.  **Migrasi Database & Seeding Data Awal:**
    ```bash
    php artisan migrate --seed
    ```
6.  **Kompilasi Aset Frontend (CSS/Tailwind):**
    ```bash
    npm run build
    ```
7.  **Jalankan Server Lokal & Service WebSocket (Reverb):**
    Jalankan perintah ini di dua terminal terpisah:
    *   Terminal 1: `php artisan serve`
    *   Terminal 2: `php artisan reverb:start`
8.  **Akses Aplikasi**: Buka browser Anda ke alamat `http://localhost:8000`

---

<!-- PAGEBREAK -->

### 2. Panduan Antarmuka & Cara Penggunaan (Dilengkapi Panduan Screenshot)

#### 2.1. Landing Page Utama (Halaman Awal)
Halaman awal saat pengguna pertama kali mengakses `http://localhost:8000`. Halaman ini menyediakan gambaran umum status banjir di Bekasi, sebaran posko, tinggi pintu air, dan tombol akses cepat untuk masuk ke dalam sistem.

> 📸 **[SCREENSHOT: Halaman Landing Page Utama]**  
> *Petunjuk Pengambilan Gambar: Ambil screenshot area hero section beranda utama. Pastikan tombol "Laporkan Bencana" dan "Butuh Bantuan SOS" terlihat jelas.*

**Deskripsi Elemen Antarmuka:**
1.  **Tombol Login & Register**: Berada di pojok kanan atas untuk mengakses akun pengguna.
2.  **Informasi Tinggi Muka Air**: Menampilkan ringkasan status siaga 3 pintu air utama di Bekasi.
3.  **Peta Sebaran Banjir**: Visualisasi peta Bekasi dengan penanda (marker) merah untuk banjir aktif.

---

#### 2.2. Autentikasi Pengguna (Register & Login)
Aplikasi memisahkan alur pendaftaran untuk Warga dan Relawan.

> 📸 **[SCREENSHOT: Form Registrasi Pilihan Akun]**  
> *Petunjuk Pengambilan Gambar: Tampilkan halaman pilihan registrasi (/register) yang memperlihatkan kartu opsi antara "Daftar sebagai Warga" dan "Daftar sebagai Relawan".*

> 📸 **[SCREENSHOT: Form Login]**  
> *Petunjuk Pengambilan Gambar: Tampilkan form login (/login) saat user memasukkan email dan password.*

**Langkah Pendaftaran Warga:**
1.  Buka `/register` → Pilih **Warga**.
2.  Isi Nama, No. HP, Email, Kecamatan, Kelurahan (wajib Bekasi), dan Password.
3.  Klik **Daftar Akun**. Sistem akan langsung melakukan *auto-login* dan mengarahkan Warga ke dashboard tanpa perlu menunggu verifikasi admin.

**Langkah Pendaftaran Relawan:**
1.  Buka `/register` → Pilih **Relawan / SAR**.
2.  Isi data diri beserta NIK, keahlian khusus (Water Rescue/Medis/Logistik), serta **wajib mengupload dokumen pendukung** (KTP/Sertifikat Keahlian).
3.  Klik **Kirim Pengajuan**. Status akun Anda akan di-set menjadi *pending* menunggu persetujuan Admin Relawan.

---

#### 2.3. Halaman Status Verifikasi Relawan
Apabila akun Relawan Anda belum disetujui atau ditolak, halaman ini akan otomatis muncul saat Anda mencoba login kembali.

> 📸 **[SCREENSHOT: Halaman Status Pending Relawan]**  
> *Petunjuk Pengambilan Gambar: Tangkap layar halaman status verifikasi (/status-verifikasi) saat akun bertuliskan "Sedang Ditinjau" dengan ikon jam pasir kuning.*

**Penjelasan Status Akun:**
*   **Pending (Ditangguhkan)**: Terlihat info peninjauan akun dan terdapat tombol cepat "Hubungi Admin via WA" untuk konfirmasi pendaftaran.
*   **Disetujui (Approved)**: Pengguna langsung diarahkan ke Dashboard. Di halaman ini juga akan tampil link WhatsApp Group Tim Kecamatan tempat relawan ditugaskan.
*   **Ditolak (Rejected)**: Menampilkan alasan penolakan dan instruksi untuk mendaftar kembali atau menghubungi admin.

---

<!-- PAGEBREAK -->

#### 2.4. Portal Fitur Warga

##### Dashboard Utama Warga
Merupakan beranda warga yang menampilkan statistik bencana terkini, data cuaca, peta interaktif, dan status pintu air.

> 📸 **[SCREENSHOT: Dashboard Warga]**  
> *Petunjuk Pengambilan Gambar: Ambil screenshot halaman dashboard utama warga (/dashboard) setelah login. Pastikan widget ringkasan statistik dan peta interaktif termuat penuh.*

##### Form Lapor Genangan Banjir
Formulir bagi warga untuk melaporkan genangan air baru di jalan atau lingkungan sekitar mereka.

> 📸 **[SCREENSHOT: Form Lapor Banjir]**  
> *Petunjuk Pengambilan Gambar: Buka menu "Lapor Banjir" (/warga/lapor). Isi data dummy (Tinggi air: 50 cm, akses terputus, dsb.) dan tampilkan peta deteksi lokasi koordinat otomatis.*

**Cara Melaporkan Genangan:**
1.  Isi slider ketinggian air (0 cm sampai 200 cm).
2.  Unggah foto bukti genangan air asli di lokasi.
3.  Pilih opsi ketersediaan akses jalan (Bisa dilalui/Hanya roda 4/Lumpuh total) dan status jaringan listrik.
4.  Pastikan koordinat lokasi di peta sesuai (sistem mendeteksi GPS otomatis).
5.  Klik **Kirim Laporan**. Laporan masuk antrean verifikasi BPBD.

##### Fitur SOS Darurat Warga
Fitur kritis bagi warga yang memerlukan bantuan evakuasi darurat (evakuasi perahu karet/medis).

> 📸 **[SCREENSHOT: Fitur SOS Warga Aktif]**  
> *Petunjuk Pengambilan Gambar: Buka halaman SOS (/warga/sos). Lakukan simulasi dengan menekan tombol merah SOS selama 2 detik hingga status berubah menjadi "SOS AKTIF". Tampilkan timeline di sebelah kanan yang menunjukkan langkah "Terkirim".*

**Cara Mengaktifkan SOS Darurat:**
1.  Buka menu **SOS Darurat** di navigasi utama.
2.  Izinkan akses GPS/Lokasi pada browser Anda.
3.  Tentukan jumlah warga yang terjebak dan jumlah kelompok rentan (lansia/balita/bumil).
4.  **Tekan dan tahan tombol lingkaran merah "SOS" selama 2 detik** untuk mencegah penekanan tidak sengaja.
5.  Sinyal terkirim. Halaman akan mem-polling status evakuasi secara otomatis tanpa perlu di-refresh.

##### Halaman Posko & Form Donasi Logistik Warga
Warga dapat memantau sebaran posko pengungsian aktif dan melakukan donasi barang sesuai kebutuhan nyata posko.

> 📸 **[SCREENSHOT: Detail Posko & Form Donasi]**  
> *Petunjuk Pengambilan Gambar: Buka menu "Daftar Posko" (/posko). Klik salah satu posko, lalu ambil screenshot form donasi logistik di bawahnya lengkap dengan kolom upload bukti pengiriman.*

**Langkah Donasi:**
1.  Lihat daftar kebutuhan barang posko yang belum terpenuhi.
2.  Isi form donasi dengan memilih jenis barang logistik, jumlah yang disumbangkan, dan unggah foto bukti pengiriman/resi.
3.  Submit donasi. Donasi akan diverifikasi oleh pengelola posko ketika barang tiba.

---

<!-- PAGEBREAK -->

#### 2.5. Portal Dispatcher Admin Relawan (Mission Control)
Dashboard terpadu yang dirancang dengan layout 3-panel responsif tanpa reload (menggunakan polling berkala otomatis) untuk koordinasi taktis penyelamatan.

> 📸 **[SCREENSHOT: Dashboard Mission Control Relawan]**  
> *Petunjuk Pengambilan Gambar: Login sebagai relawan@example.com. Ambil tangkapan layar penuh dashboard (/relawan/dashboard) yang memperlihatkan layout 3-panel: Kiri (Antrean SOS), Tengah (Peta Operasional), dan Kanan (Manajemen Anggota/Tim).*

##### Cara Menugaskan Tim Evakuasi (Alur SOS Real-time)
1.  Ketika ada SOS warga masuk, dasbor relawan akan otomatis mengupdate antrean di panel kiri dalam **10 detik** tanpa refresh.
2.  Klik tombol **TUGASKAN KE TIM** pada kartu antrean SOS.
3.  Modal pemilihan tim respon akan muncul.

> 📸 **[SCREENSHOT: Modal Tugaskan Tim SOS]**  
> *Petunjuk Pengambilan Gambar: Klik tombol "Tugaskan ke Tim" pada salah satu antrean SOS untuk menampilkan modal pilihan tim respon. Pastikan dropdown tim aktif terbuka.*  
> *Catatan Desain: Tim yang sedang bertugas di misi lain akan otomatis berstatus abu-abu (disabled) dengan keterangan "(Sedang Bertugas)".*

4.  Pilih tim yang berstatus "(Tersedia)" lalu klik **Tugaskan Misi**.
5.  Setelah submit, dasbor akan otomatis di-reload untuk memperbarui panel Misi Aktif, dan sistem memunculkan **Banner Notifikasi WhatsApp** persisten tepat di atas kartu statistik.

> 📸 **[SCREENSHOT: Banner Notifikasi WhatsApp Persisten]**  
> *Petunjuk Pengambilan Gambar: Ambil screenshot area bagian atas dasbor relawan sesaat setelah berhasil menugaskan misi. Pastikan banner hijau dengan 4 tombol WA/Maps terlihat jelas.*

**Fungsi 4 Tombol WhatsApp & Navigasi di Banner:**
*   **Kirim ke WhatsApp (Nama Relawan)**: Mengirim chat teks detail darurat pre-filled langsung ke WhatsApp pribadi ketua tim respon.
*   **Share Grup [Nama Tim]**: Membagikan format instruksi misi evakuasi langsung ke grup WhatsApp internal tim kecamatan.
*   **Minta Bantuan (Grup Gabungan)**: Menyebarkan info permintaan bantuan cadangan (backup) ke grup relawan gabungan se-Bekasi.
*   **Buka Google Maps**: Membuka aplikasi peta navigasi GPS langsung menuju koordinat lintang/bujur korban terjebak.

*Banner ini bersifat persisten (tidak bisa ditutup) dan akan terus tampil di dashboard admin hingga misi terkait dinyatakan selesai.*

##### Selesaikan Misi
1.  Jika tim relawan telah berhasil mengevakuasi korban ke posko terdekat, klik tombol **SELESAI** pada kartu Misi Aktif di dasbor.
2.  Konfirmasi kotak dialog persetujuan. Sistem akan membersihkan banner notifikasi WhatsApp dari layar secara otomatis.

##### Review Anggota Relawan Baru
1.  Buka panel kanan di bagian "Pendaftar Anggota Baru".
2.  Klik **Review** → Pratinjau KTP/Dokumen pendaftar akan dimuat.
3.  Klik **Terima** atau **Tolak**. Jika diterima, klik tombol WA yang muncul untuk mengirimkan link undangan masuk ke grup WhatsApp tim respon.

---

<!-- PAGEBREAK -->

#### 2.6. Portal Pengelola Posko (Petugas Shelter)
Menyediakan antarmuka khusus bagi pengelola posko untuk memperbarui kondisi pengungsi secara real-time.

> 📸 **[SCREENSHOT: Dashboard Pengelola Posko]**  
> *Petunjuk Pengambilan Gambar: Login sebagai pengelola@example.com. Ambil screenshot dashboard kelola posko (/pengelola/dashboard) dengan fokus pada form update kapasitas pengungsi di bagian atas.*

##### Update Status & Kapasitas Posko
1.  Pada kolom **Pengungsi Saat Ini**, input jumlah terbaru pengungsi yang terdaftar di posko.
2.  Pilih **Status Posko**:
    *   **Aktif**: Kuota posko masih tersedia.
    *   **Penuh**: Pengungsi sudah mencapai batas kapasitas maksimal.
    *   **Tutup**: Posko selesai beroperasi dan otomatis tersembunyi dari peta publik warga.
3.  Klik **Perbarui Status Posko**.

##### Kelola Kebutuhan Logistik & Donasi
1.  Klik **Tambah Kebutuhan** → Masukkan nama barang (misal: Selimut), jumlah unit yang kurang, dan prioritas urgensinya.
2.  Untuk donasi yang dikirimkan warga, cek detail foto bukti pada tabel donasi masuk. Jika barang telah sampai secara fisik, klik **Verifikasi** untuk mengubah status menjadi *delivered* (secara otomatis mengurangi angka kekurangan kebutuhan logistik).

---

<!-- PAGEBREAK -->

#### 2.7. Portal Super-User Admin BPBD
Merupakan pusat kendali administratif untuk mengontrol validitas seluruh data di platform TitikAman.

> 📸 **[SCREENSHOT: Dashboard Utama Admin BPBD]**  
> *Petunjuk Pengambilan Gambar: Login sebagai admin@example.com. Tampilkan dashboard admin (/admin/dashboard) dengan bagan grafik total laporan banjir dan daftar antrean verifikasi.*

##### Verifikasi Laporan Banjir Warga
1.  Semua laporan genangan dari warga masuk ke dashboard admin dengan status *pending*.
2.  Tinjau foto bukti banjir dan deskripsi lokasi.
3.  Klik **Verifikasi** untuk mempublikasikannya di peta utama publik, atau klik **Tolak** jika laporan dirasa palsu atau berada di luar batas wilayah.
4.  Jika banjir di area laporan warga telah sepenuhnya surut, klik tombol **Set Surut** untuk mengembalikan ketinggian air ke 0 cm.

##### Kelola Tinggi Muka Air (TMA)
Modul untuk mengupdate ketinggian air pintu air hulu Bekasi.

> 📸 **[SCREENSHOT: Halaman Kelola TMA BPBD]**  
> *Petunjuk Pengambilan Gambar: Buka menu "Kelola TMA" (/admin/tma). Tampilkan modal popup update tinggi air pintu air.*

**Cara Mengupdate Ketinggian Air Pintu Air:**
1.  Klik **Update** pada baris pintu air (misal: Pintu Air Kali Bekasi).
2.  Input tinggi air terbaru dalam satuan centimeter (cm).
3.  Sistem secara otomatis menghitung tingkat kerawanan (Normal / Siaga 3 / Siaga 2 / Siaga 1) berdasarkan aturan batas tinggi air dan langsung mendistribusikan status terbaru ke halaman utama warga secara real-time.

##### Verifikasi Akun Pengelola Posko Baru
1.  Buka menu **Verifikasi Pengguna** (/admin/verifikasi-pengguna).
2.  Halaman menampilkan daftar pengajuan akun posko baru.
3.  Tinjau kebenaran koordinat posko di peta dan foto fisik shelter.
4.  Klik **Setujui** untuk mengaktifkan akun dan posko, atau klik **Tolak** jika data shelter tidak valid.