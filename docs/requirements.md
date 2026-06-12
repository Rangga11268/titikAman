# Analisis Kebutuhan Sistem - TitikAman

TitikAman adalah platform manajemen kebencanaan banjir terpadu (Jabodetabek & Kota Bekasi) yang menghubungkan warga terdampak, relawan kebencanaan, pengelola posko pengungsian, dan aparatur dinas secara real-time.

## 1. Sasaran Pengguna (User Persona)

### Persona 1: Budi Santoso (42 Tahun) - Warga Terdampak
* **Pekerjaan**: Karyawan Swasta
* **Lokasi**: Perumahan Margahayu Jaya / Pondok Gede Permai, Kota Bekasi (Zona Rawan Banjir)
* **Kebutuhan**:
  - Informasi kenaikan tinggi muka air (TMA) secara real-time.
  - Lokasi posko terdekat yang layak huni (kamar mandi bersih & ramah anak/lansia).
  - Rute evakuasi aman sebelum listrik padam total.
* **Kendala**: Informasi sering terlambat/hoax dan kesulitan mendapatkan logistik darurat bayi/lansia.

### Persona 2: Hendra Wijaya (29 Tahun) - Relawan Lapangan
* **Pekerjaan**: Anggota Komunitas Relawan / Aparatur Kelurahan
* **Lokasi**: Bekasi Timur, Kota Bekasi
* **Kebutuhan**:
  - Pendataan korban (KK/jiwa/lansia/balita) yang terjebak untuk evakuasi prioritas.
  - Pemetaan kebutuhan logistik posko pengungsian secara spesifik.
  - Validasi kedaruratan wilayah secara cepat.
* **Kendala**: Keterbatasan personel di lapangan, data donasi posko tumpang tindih, dan penolakan warga saat diajak evakuasi dini.

---

## 2. Alur Kerja Sistem (System Flows)

### 2.1 Alur SOS Kedaruratan
1. Warga terjebak menekan tombol **SOS** di website, mengisi deskripsi & jumlah kelompok rentan (lansia/balita/ibu hamil).
2. Sistem mendeteksi koordinat GPS korban dan menyimpan data ke database.
3. SOS diberi tanda prioritas tinggi (*high priority*) jika terdapat kelompok rentan.
4. Notifikasi darurat dikirim ke relawan terdekat.
5. Relawan menerima misi, mengevakuasi korban ke posko terdekat, dan mengubah status misi menjadi selesai (*completed*).

### 2.2 Alur Peta Genangan Independen (Crowdsourcing)
1. Warga membuka halaman **Peta Banjir**.
2. Warga mengirim laporan berupa tinggi air, nama jalan, foto kondisi terkini, dan koordinat GPS.
3. Laporan disimpan ke database dan diverifikasi oleh admin BPBD/Aparatur.
4. Laporan yang terverifikasi akan muncul sebagai pin indikator visual di peta secara real-time.

### 2.3 Alur Distribusi Logistik & Donasi Posko
1. Pengelola posko mendata kebutuhan spesifik di posko (contoh: Posko A butuh selimut, susu bayi).
2. Kebutuhan logistik diinput ke dalam website agar dapat dilihat oleh publik/donatur.
3. Donatur luar memantau daftar kebutuhan dan mengirim bantuan secara langsung atau via ekspedisi.
4. Donatur mengunggah foto barang dan resi pengiriman ke sistem.
5. Pengelola posko memverifikasi kedatangan fisik barang, lalu mengubah status menjadi diterima (*delivered*), yang otomatis mengurangi kuota kebutuhan di sistem.

### 2.4 Alur Peringatan Dini Pintu Air
1. Petugas pintu air (Sungai Cikeas, Bekasi, Cakung) memperbarui data tinggi muka air (TMA).
2. Sistem memantau batas status siaga (Normal, Siaga 3, Siaga 2, Siaga 1).
3. Jika TMA melompati ambang batas siaga, sistem mengirimkan push notification peringatan dini kepada warga di kelurahan/kecamatan yang searah dengan aliran sungai.
