# Analisis Kebutuhan Sistem - TitikAman

TitikAman adalah platform manajemen kebencanaan banjir terpadu (Jabodetabek & Kota Bekasi) yang menghubungkan warga terdampak, admin relawan, pengelola posko pengungsian, dan admin BPBD secara real-time.

## 1. Sasaran Pengguna (User Persona)

### Persona 1: Budi Santoso (42 Tahun) - Warga Terdampak / Donatur
* **Pekerjaan**: Karyawan Swasta
* **Lokasi**: Perumahan Margahayu Jaya / Pondok Gede Permai, Kota Bekasi (Zona Rawan Banjir)
* **Kebutuhan**:
  - Informasi kenaikan tinggi muka air (TMA) secara real-time.
  - Lokasi posko terdekat yang layak huni (kamar mandi bersih & ramah anak/lansia).
  - Rute evakuasi aman sebelum listrik padam total.
  - Melaporkan genangan dan mengirim sinyal SOS.
  - Berdonasi logistik ke posko.
* **Kendala**: Informasi sering terlambat/hoax dan kesulitan mendapatkan logistik darurat bayi/lansia.

### Persona 2: Darell (25 Tahun) - Admin Relawan (Dispatcher)
* **Pekerjaan**: Koordinator Tim SAR / Relawan
* **Lokasi**: Bekasi Selatan, Kota Bekasi
* **Kebutuhan**:
  - Memantau antrian SOS yang masuk dari warga secara real-time.
  - Menugaskan misi evakuasi ke tim-tim kecamatan.
  - Mengirim instruksi + link Google Maps ke grup WhatsApp tim.
  - Memantau status misi (aktif/selesai).
  - Mereview dan menyetujui anggota relawan baru.
  - Mengelola data anggota tim (edit, hapus, pindah tim).
* **Kendala**: Koordinasi multi-tim yang rumit dan keterbatasan personel.

### Persona 3: Siti (35 Tahun) - Pengelola Posko
* **Pekerjaan**: Ketua RW / Petugas Shelter
* **Lokasi**: Posko Pengungsian, Bekasi
* **Kebutuhan**:
  - Memperbarui kapasitas hunian posko secara real-time.
  - Mendata kebutuhan logistik (makanan, obat, selimut).
  - Memverifikasi donasi yang datang dari warga/donatur.
* **Kendala**: Data stok barang tidak akurat dan donasi menumpuk di satu posko.

### Persona 4: Andi (40 Tahun) - Admin BPBD
* **Pekerjaan**: Aparatur BPBD Kota Bekasi
* **Lokasi**: Kantor BPBD, Bekasi
* **Kebutuhan**:
  - Memverifikasi laporan banjir dari warga (verified/rejected).
  - Memantau dan memperbarui data Tinggi Muka Air (TMA) pintu air.
  - Menyetujui/menolak pendaftaran akun Relawan & Pengelola Posko.
  - Melakukan ekspor data laporan ke CSV.
* **Kendala**: Informasi banjir yang belum terverifikasi (hoaks) dan data yang tersebar.

---

## 2. Alur Kerja Sistem (System Flows)

### 2.1 Alur SOS Kedaruratan (Versi Saat Ini)
1. Warga terjebak menekan tombol **SOS** di website, mengisi deskripsi & jumlah kelompok rentan (lansia/balita/ibu hamil).
2. Sistem mendeteksi koordinat GPS korban, menghitung skala prioritas, dan menyimpan data ke database dengan status **waiting**.
3. SOS muncul di antrian dashboard **Admin Relawan**.
4. Admin Relawan mengklik **TUGASKAN KE TIM**, memilih tim kecamatan yang sesuai.
5. Sistem membuat misi penyelamatan dan menampilkan banner dengan 4 tombol:
   - **Kirim ke WhatsApp (Relawan)**: WA personal ke lead tim.
   - **Share Grup [Tim]**: Bagikan info ke grup WA tim kecamatan.
   - **Minta Bantuan (Grup Gabungan)**: Minta backup dari tim lain.
   - **Buka Google Maps**: Navigasi ke lokasi korban.
6. Lead tim berkoordinasi dengan anggota timnya di lapangan dan bergerak bersama ke lokasi.
7. Admin Relawan atau lead tim menekan **SELESAI** setelah evakuasi berhasil.

### 2.2 Alur Peta Genangan Independen (Crowdsourcing)
1. Warga membuka halaman **Peta Banjir**.
2. Warga mengirim laporan berupa tinggi air, nama jalan, foto kondisi terkini, dan koordinat GPS.
3. Laporan disimpan ke database dan diverifikasi oleh Admin BPBD.
4. Laporan yang terverifikasi muncul sebagai marker ikon tetesan air (biru) di peta interaktif secara real-time.
5. Warga lain bisa mengklik laporan untuk melihat detail lengkap melalui modal.

### 2.3 Alur Distribusi Logistik & Donasi Posko
1. Pengelola posko mendata kebutuhan spesifik di posko (contoh: Posko A butuh selimut, susu bayi).
2. Kebutuhan logistik diinput ke dalam website agar dapat dilihat oleh publik/donatur.
3. Donatur memilih posko dan barang, mengisi jumlah (dibatasi maksimal sisa kebutuhan), dan mengunggah foto/resi.
4. Pengelola posko memverifikasi kedatangan fisik barang, lalu mengubah status menjadi **delivered**.
5. Sistem otomatis mengurangi sisa kebutuhan di tabel `shelter_needs`.

### 2.4 Alur Peringatan Dini Pintu Air
1. Admin BPBD memperbarui data tinggi muka air (TMA) di halaman Kelola TMA.
2. Sistem otomatis menghitung status bahaya (Normal < 150cm, Siaga_3 ≥ 150cm, Siaga_2 ≥ 200cm, Siaga_1 ≥ 250cm).
3. Jika status naik melewati ambang batas, sistem mengirim peringatan dini ke warga di kecamatan terdampak (berdasarkan mapping DAS/aliran sungai).
4. Sistem memiliki **throttling 1 jam** untuk mencegah spam notifikasi jika TMA berfluktuasi.

### 2.5 Alur Registrasi Relawan & Approval
1. Calon relawan mengisi form registrasi (NIK, keahlian, organisasi, upload KTP).
2. Data tersimpan dengan role `Relawan` dan status **pending**.
3. Admin Relawan melihat pendaftar baru di dashboard, mengklik **Review**.
4. Admin Relawan mengecek dokumen KTP/Sertifikat melalui modal pratinjau.
5. Admin Relawan memilih **Terima & Masukkan Tim** atau **Tolak**.
6. Jika diterima, banner approval muncul dengan 2 tombol:
   - **Kirim Info via WA ke [Nama]**: Kirim pesan berisi link grup WA tim ke nomor relawan.
   - **Link Grup [Tim]**: Salin link undangan grup WA tim.
7. Relawan login → lihat halaman status approved → bisa gabung grup WA via tombol yang tersedia.

### 2.6 Alur Penugasan Backup Tim
1. SOS sedang ditangani oleh Tim A (status `assigned`).
2. Admin Relawan melihat SOS berlabel **"BANTUAN"** di antrian.
3. Admin Relawan mengklik **KIRIM BANTUAN TIM** (warna oranye).
4. Memilih Tim B/C dari dropdown (termasuk tim yang sedang dalam misi).
5. Mission baru untuk Tim B tercatat di database (1 SOS bisa memiliki multiple missions).
6. Admin Relawan mengirim instruksi ke grup WA Tim B atau grup gabungan.

### 2.7 Alur Penutupan Posko
1. Pengelola Posko mengubah status posko menjadi **closed**.
2. Posko otomatis:
   - Hilang dari peta publik.
   - Disembunyikan dari halaman Hub Donasi.
   - Tetap tampil di arsip daftar posko dengan label abu-abu **"POSKO DITUTUP"**.
