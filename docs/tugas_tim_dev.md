# 📋 Pembagian Tugas & Alur Integrasi AI Developer - TitikAman

Dokumen ini berisi pembagian tugas spesifik untuk tim developer **TitikAman** (Innes, Fatur, Januar, Jeffry). Untuk menyiasati proyek tugas bootcamp ini secara cerdas dan efisien, kita akan menggunakan metode **AI-Assisted Development** dengan pembagian peran yang terpusat.

---

## 📢 Pesan PM & Lead Developer (Darell)
> **"Halo Tim!** Untuk mempercepat dan menyederhanakan proyek kita, tugas kalian **difokuskan pada pembuatan halaman tampilan (Blade View Templates) beserta CSS/styling-nya** agar sesuai dengan mock-up Figma. 
> 
> Seluruh proses integrasi backend, penggabungan database migrasi, pembuatan logic Controller/Service, routing web, hingga penggabungan akhir ke repositori utama akan saya (**Darell**) tangani sepenuhnya. Tugas kalian adalah menjalankan asisten AI (seperti Cursor/Gemini) menggunakan prompt di bawah ini, memastikan halaman HTML/Blade-nya tampil cantik di lokal masing-masing, kemudian mengirimkan berkas `.blade.php` atau file CSS-nya ke saya untuk digabungkan secara terintegrasi."

---

## 🔄 Alur Kerja Tim (Workflow)
Agar pengerjaan terpantau rapi dan formalitas bootcamp terpenuhi dengan baik:
1. **Salin Prompt**: Cari nama Anda di bawah ini dan salin prompt AI yang sudah disediakan.
2. **Jalankan AI**: Buka editor Anda (seperti Cursor atau VS Code dengan ekstensi AI) lalu tempel prompt tersebut.
3. **Cek Tampilan**: Jalankan secara lokal, pastikan seluruh tata letak, tombol, dan warna sudah sesuai dengan rancangan Figma.
4. **Kirim Berkas**: Kirimkan berkas `.blade.php` (dan `.css` jika ada) yang dihasilkan ke **Darell** (bisa melalui Pull Request di branch Git masing-masing, atau kirim langsung berkasnya).
5. **Integrasi Akhir**: Darell akan menggabungkan modul tampilan kalian dengan sistem database terintegrasi TitikAman.

---

## 👤 Pembagian Modul & AI Prompts Siap Pakai

### 1. Innes (Developer 1 - Tampilan Autentikasi & Registrasi Warga)
* **Tanggung Jawab Halaman**:
  * `resources/views/layouts/app.blade.php` (Kerangka layout utama/wrapper CSS)
  * `resources/views/auth/login.blade.php` (Form Login ganda: email/nomor HP - Figma Node `211:271`)
  * `resources/views/auth/register-step1.blade.php` (Halaman pemilihan peran user - Figma Node `82:156`)
  * `resources/views/auth/register-step2-warga.blade.php` (Form pengisian data diri warga - Figma Node `82:329`)

> [!IMPORTANT]
> **🤖 AI Prompt untuk Innes (Tinggal Copy-Paste ke AI Coder Anda):**
> ```text
> Saya adalah Innes, Developer 1. Saya sedang membangun proyek Laravel 12 bernama TitikAman tanpa Laravel Breeze menggunakan CSS murni (tanpa Tailwind).
> Tugas saya adalah membuat seluruh halaman autentikasi dan layout dasar. Tolong buatkan kode lengkap untuk file-file berikut:
> 1. File `resources/views/layouts/app.blade.php` sebagai layout utama template.
> 2. File `resources/views/auth/login.blade.php` (Halaman Login sesuai desain Figma Node 211:271) dengan input ganda (email atau nomor HP), password, toggle intip password, dan styling modern menggunakan aset lokal di folder public/assets/.
> 3. File `resources/views/auth/register-step1.blade.php` (Halaman Pilih Peran sesuai Figma Node 82:156) dengan pilihan kartu peran Warga, Relawan, Pengelola Posko, dan Admin BPBD.
> 4. File `resources/views/auth/register-step2-warga.blade.php` (Halaman Registrasi Warga sesuai Figma Node 82:329) dengan input Nama Lengkap, Nomor HP, Password, Kecamatan, Kelurahan, dan checkbox Persetujuan Syarat & Ketentuan.
> 
> Karena berkas-berkas ini akan diintegrasikan oleh Lead Developer kami (Darell), buatkan kode tampilan Blade & CSS murni yang bersih, rapi, dan langsung bisa dijalankan di browser.
> ```

---

### 2. Fatur (Developer 2 - Tampilan Portal Warga & Peta Laporan)
* **Tanggung Jawab Halaman**:
  * `resources/views/warga/dashboard.blade.php` (Dashboard Warga & Peta Genangan Leaflet.js - Figma Node `190:2` / `174:2`)
  * `resources/views/warga/lapor-banjir.blade.php` (Form Lapor Genangan Banjir - Figma Node `163:1375`)

> [!IMPORTANT]
> **🤖 AI Prompt untuk Fatur (Tinggal Copy-Paste ke AI Coder Anda):**
> ```text
> Saya adalah Fatur, Developer 2. Saya sedang mengimplementasikan modul GIS Portal Warga di Laravel 12 menggunakan Leaflet.js dengan CSS murni (tanpa Tailwind).
> Tugas saya adalah membuat tampilan dashboard peta dan form pelaporan. Tolong buatkan kode lengkap untuk file-file berikut:
> 1. File `resources/views/warga/dashboard.blade.php` (Dashboard Warga sesuai Figma Node 190:2 dan 174:2) yang memuat peta Leaflet.js dengan basemap 'CartoDB Voyager', marker lokasi genangan air, marker posko aktif, dan tombol mengambang merah mencolok "KIRIM SINYAL SOS".
> 2. File `resources/views/warga/lapor-banjir.blade.php` (Form Lapor Banjir sesuai Figma Node 163:1375) dengan input tinggi genangan (cm), nama jalan, koordinat GPS (didapat otomatis dari geolocation browser), serta input upload foto bukti banjir.
> 
> Karena berkas-berkas ini akan diintegrasikan oleh Lead Developer kami (Darell), buatkan kode tampilan Blade & CSS murni yang bersih, rapi, dan langsung bisa dijalankan di browser.
> ```

---

### 3. Januar (Developer 3 - Tampilan Portal Relawan & Evakuasi SOS)
* **Tanggung Jawab Halaman**:
  * `resources/views/relawan/dashboard.blade.php` (Dashboard Relawan & Peta Evakuasi Misi - Figma Node `163:2528`)

> [!IMPORTANT]
> **🤖 AI Prompt untuk Januar (Tinggal Copy-Paste ke AI Coder Anda):**
> ```text
> Saya adalah Januar, Developer 3. Saya sedang mengimplementasikan modul Relawan di Laravel 12 menggunakan Leaflet.js dan WebSocket (Laravel Reverb) dengan CSS murni (tanpa Tailwind).
> Tugas saya adalah membuat halaman dashboard misi penyelamatan relawan. Tolong buatkan kode lengkap untuk file berikut:
> 1. File `resources/views/relawan/dashboard.blade.php` (Dashboard Relawan sesuai Figma Node 163:2528) yang menampilkan peta Leaflet.js berisi marker posisi koordinat SOS korban banjir (status waiting) secara real-time. Peta harus menyertakan pop-up info korban beserta tombol aksi "Terima Misi" dan "Evakuasi Selesai" untuk mengubah status misi.
> 
> Karena berkas-berkas ini akan diintegrasikan oleh Lead Developer kami (Darell), buatkan kode tampilan Blade & CSS murni yang bersih, rapi, dan langsung bisa dijalankan di browser.
> ```

---

### 4. Jeffry (Developer 4 - Tampilan Kelola Posko & Hub Donasi)
* **Tanggung Jawab Halaman**:
  * `resources/views/pengelola/dashboard.blade.php` (Dashboard Pengelola Posko - Figma Node `163:2917` & `187:2`)
  * `resources/views/donasi/hub.blade.php` (Portal Hub Donasi Publik - Figma Node `186:2`)

> [!IMPORTANT]
> **🤖 AI Prompt untuk Jeffry (Tinggal Copy-Paste ke AI Coder Anda):**
> ```text
> Saya adalah Jeffry, Developer 4. Saya sedang mengimplementasikan modul Kelola Posko dan Hub Donasi Publik di Laravel 12 menggunakan CSS murni (tanpa Tailwind).
> Tugas saya adalah membuat halaman manajemen posko dan halaman donasi donatur. Tolong buatkan kode lengkap untuk file-file berikut:
> 1. File `resources/views/pengelola/dashboard.blade.php` (Dashboard Posko sesuai Figma Node 163:2917 dan 187:2) untuk pengelola memperbarui jumlah pengungsi aktif, status posko (aktif/penuh/tutup), dan memantau pemenuhan barang logistik posko.
> 2. File `resources/views/donasi/hub.blade.php` (Portal Donasi sesuai Figma Node 186:2) berisi daftar kebutuhan barang di posko pengungsian dan formulir kirim donasi barang bagi masyarakat umum (input jumlah barang, resi pengiriman, dan upload foto bukti kirim).
> 
> Karena berkas-berkas ini akan diintegrasikan oleh Lead Developer kami (Darell), buatkan kode tampilan Blade & CSS murni yang bersih, rapi, dan langsung bisa dijalankan di browser.
> ```
