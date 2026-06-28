# FASE 1: RISET (PENELITIAN) DAN ANALISIS

## 1.1 Latar Belakang

Berdasarkan data Badan Nasional Penanggulangan Bencana (BNPB) per Maret 2025, bencana banjir hidrometeorologi basah telah merendam wilayah Jabodetabek dengan total warga terdampak mencapai 37.058 Kepala Keluarga (KK). Kota Bekasi menjadi wilayah terdampak paling masif dengan 18.738 KK (61.233 jiwa) di 25 kelurahan yang mengalami genangan air. Skala bencana ini menuntut adanya intervensi sistem teknologi informasi untuk mitigasi dan percepatan evakuasi.

Bencana banjir merupakan tantangan hidrometeorologis tahunan yang kerap melanda berbagai kawasan perkotaan di Indonesia. Dampak yang ditimbulkan tidak hanya terbatas pada kerugian material dan rusaknya infrastruktur, tetapi juga ancaman serius terhadap keselamatan jiwa. Sering kali, jatuhnya korban bukan semata-mata disebabkan oleh tingginya debit air, melainkan akibat keterlambatan penerimaan informasi peringatan dini serta kurangnya panduan evakuasi yang terarah. Sistem penyebaran informasi terkait status siaga pintu air yang ada saat ini umumnya masih berjalan satu arah dan belum terintegrasi secara real-time dengan kondisi genangan riil di wilayah pemukiman. Akibatnya, masyarakat yang bermukim jauh dari titik pemantauan hulu sungai kerap terjebak karena tidak menyadari pergerakan air dan kehilangan waktu krusial untuk melakukan evakuasi mandiri.

Selain masalah peringatan dini, kendala operasional yang signifikan juga sering terjadi pada manajemen tanggap darurat di lapangan. Saat banjir memuncak, warga kelompok rentan (seperti lansia, ibu hamil, balita, atau warga yang sakit) kerap kesulitan mengirimkan sinyal permintaan evakuasi yang presisi, sehingga tim relawan kesulitan menemukan titik pasti lokasi korban yang terjebak. Di sisi lain, proses pengelolaan posko pengungsian dan distribusi donasi logistik sering kali tidak terdata secara transparan. Hal ini memicu terjadinya penumpukan bantuan di satu posko, sementara posko lainnya mengalami krisis pasokan dasar. Ketiadaan platform terpadu yang mampu menghubungkan data dari korban, relawan, dan pengelola posko secara langsung menjadi titik lemah dalam respons penanggulangan bencana selama ini.

Menjawab urgensi tersebut, Kelompok 44 merancang "TitikAman", sebuah Sistem Informasi Mitigasi Banjir dan Navigasi Jalur Evakuasi Berbasis Partisipasi Masyarakat (Crowdsourcing). Pengembangan sistem berbasis Web ini selaras dengan target Sustainable Development Goals (SDGs), secara khusus pada upaya mewujudkan Kota dan Permukiman yang Tangguh Bencana (SDG 11) serta Penanganan Perubahan Iklim (SDG 13). TitikAman mengintegrasikan fitur pelaporan genangan oleh warga, peringatan dini pintu air, sinyal darurat (Emergency SOS) berbasis GPS, serta manajemen direktori posko pengungsian ke dalam satu ekosistem digital. Kehadiran inovasi perangkat lunak ini diharapkan dapat mempercepat respons penyelamatan relawan, mencegah disinformasi, dan meminimalisir risiko korban jiwa secara efektif.

## 1.2 Tujuan

Tujuan utama dari pengembangan sistem informasi TitikAman adalah sebagai berikut:

1. **Membangun Sistem Peringatan Dini**: Menyajikan data status ketinggian air hulu kepada masyarakat secara real-time untuk meningkatkan kewaspadaan sebelum genangan meluas.
2. **Mempercepat Respons Evakuasi Darurat**: Menyediakan fitur tombol SOS berbasis GPS yang secara otomatis meneruskan titik koordinat korban kepada Admin Relawan untuk ditugaskan ke tim.
3. **Memetakan Dampak Bencana secara Akurat**: Membentuk peta visual interaktif (Crowdsourced Flood Map) berdasarkan laporan aktual dari warga di lokasi kejadian untuk mencari jalur aman.
4. **Optimalisasi Manajemen Pengungsian**: Menyediakan direktori digital yang menampilkan lokasi posko aman, sisa kapasitas pengungsi, serta update kebutuhan bantuan logistik untuk mencegah penumpukan donasi.

Berdasarkan fungsionalitasnya, TitikAman merupakan jenis sistem informasi berbasis Layanan Publik dan Tanggap Darurat Kebencanaan. Adapun target audiens dari sistem ini terbagi menjadi empat lapis, yaitu: Warga masyarakat di wilayah rawan banjir, Relawan/Tim Penyelamat Lapangan, Administrator Pengelola Posko Bencana, dan Admin BPBD.

## 1.3 Harapan

Pengembangan sistem informasi TitikAman didasarkan pada visi untuk menciptakan platform penanggulangan bencana yang responsif, adaptif, dan berpusat pada keselamatan masyarakat. Guna memastikan implementasi sistem dapat berjalan optimal dan memberikan dampak nyata selama fase tanggap darurat, target ekspektasi terhadap aspek visual, pengalaman pengguna, output solusi, hingga cakupan fungsionalitas platform dijabarkan sebagai berikut:

1. **Seperti apa tampilan dan nuansa yang Anda harapkan?** Mengingat sistem ini akan diakses pada kondisi darurat dan kepanikan, antarmuka (User Interface) diharapkan bersifat clean, modern, dan intuitif. Desain memprioritaskan gaya minimalis dengan palet warna yang menenangkan (seperti putih dan biru muda) untuk memberikan kesan pelayanan publik yang profesional, serta penggunaan warna merah solid khusus pada tombol aksi darurat (SOS). Huruf (tipografi) harus berukuran besar dan mudah dibaca oleh semua kalangan usia.

2. **Bagaimana dan apa yang akan dihasilkan situs web untuk penyelesaian masalah?** Situs web ini akan bertindak sebagai "Pusat Komando Digital". Sistem akan menghasilkan peta evakuasi yang memandu warga ke tempat aman, menghasilkan jalur instruksi penjemputan yang efisien bagi tim relawan melalui komunikasi WhatsApp, serta menghasilkan rekapan data terstruktur terkait ketersediaan logistik di posko-posko penampungan.

3. **Fitur apa yang diharapkan pengguna dari aplikasi semacam itu?** Pengguna sangat mengharapkan kecepatan akses tanpa proses loading yang berat, kemudahan pelaporan tanpa formulir yang rumit, keakuratan sistem dalam mendeteksi titik lokasi (GPS), serta kejelasan informasi apakah wilayah mereka berstatus aman, siaga, atau bahaya.

4. **Apa sajakah fitur yang ingin Anda sertakan?**
   a. **Fitur SOS Darurat**: Tombol aksi cepat untuk memanggil bantuan evakuasi dengan forwarding lokasi real-time ke Admin Relawan.
   b. **Peta Genangan Interaktif**: Visualisasi titik banjir berdasarkan input/laporan partisipatif dari warga.
   c. **Dashboard Pintu Air**: Indikator tingkat kewaspadaan arus air sungai/bendungan.
   d. **Manajemen Posko & Donasi**: Direktori digital yang menampilkan lokasi posko aman, sisa kapasitas pengungsi, serta update kebutuhan bantuan.

---

# FASE 2: PERENCANAAN DAN STRATEGI

## 2.1 Analisa Kebutuhan

### a. Siapakah pengguna yang akan menggunakan sistem tersebut

Sistem informasi TitikAman dirancang untuk mengakomodasi 4 (empat) kategori pengguna yang saling terintegrasi dan berkolaborasi dalam satu ekosistem mitigasi serta tanggap darurat bencana banjir:

1. **Warga Terdampak (Masyarakat Umum / Donatur)**: Pengguna dari elemen masyarakat luas, baik yang berada di area rawan bencana untuk melaporkan situasi, maupun masyarakat umum yang ingin berpartisipasi memberikan donasi bantuan.
2. **Admin Relawan (Komandan Tim SAR / Dispatcher)**: Pengguna yang bertindak sebagai pusat kendali operasional — memantau antrian SOS, menugaskan misi ke anggota tim, dan mereview pendaftar relawan baru.
3. **Pengelola Posko (Petugas Shelter)**: Pengguna yang berada di titik pengungsian untuk mengelola fasilitas akomodasi, mendata kapasitas hunian, serta mengatur ketersediaan logistik bantuan.
4. **Admin BPBD (Badan Penanggulangan Bencana Daerah)**: Pengguna tingkat tinggi (Super User) yang memegang kendali penuh atas validasi data kebencanaan, pembaruan status peringatan dini, verifikasi akun pengguna, serta pengawasan sistem secara makro.

Selain 4 aktor di atas, terdapat **Relawan Lapangan** yang terdaftar melalui formulir registrasi Relawan/SAR. Relawan Lapangan menyimpan data diri dan dokumen di sistem, namun tidak memiliki akses ke halaman dashboard operasional. Seluruh penugasan dan koordinasi dilakukan oleh **Admin Relawan** melalui komunikasi WhatsApp.

### b. Jelaskan fungsi sesuai kebutuhan pengguna

Berikut adalah penjabaran lengkap mengenai fungsi-fungsi sistem yang diimplementasikan untuk memenuhi kebutuhan spesifik dari masing-masing peran pengguna:

#### 1. Warga Terdampak (Masyarakat Umum / Donatur)

a) **Autentikasi dan Manajemen Profil**: Melakukan registrasi dan login akun guna memastikan setiap laporan yang masuk ke dalam sistem memiliki identitas yang valid.

b) **Mengirim Laporan Titik Banjir (Flood Reporting)**: Menginput data tinggi genangan air secara aktual (dalam satuan cm), nama jalan, mengunggah bukti foto visual, serta menandai koordinat lokasi otomatis menggunakan GPS.

c) **Mengirim Sinyal Darurat (Emergency SOS)**: Mengirimkan panggilan penyelamatan instan saat terjebak banjir dengan menyertakan jumlah jiwa dan jumlah kelompok rentan (lansia, balita, ibu hamil, atau warga sakit) demi menentukan skala prioritas evakuasi.

d) **Memantau Peta Genangan Interaktif (Crowdsourced Flood Map)**: Mengakses visualisasi peta digital untuk melihat titik-titik banjir dan jalur aman evakuasi yang diperbarui secara langsung berdasarkan partisipasi publik.

e) **Memantau Sistem Peringatan Dini (Early Warning System)**: Memantau data Tinggi Muka Air (TMA) dan status kesiagaan pintu air utama secara real-time untuk mengantisipasi air kiriman sebelum banjir meluas.

f) **Partisipasi Donasi Logistik**: Melihat daftar kebutuhan mendesak di tiap-tiap posko pengungsian dan menginput data donasi beserta bukti resi pengiriman logistik secara transparan. Sistem memiliki validasi anti-over-donasi untuk mencegah penumpukan barang.

g) **Melihat Detail Laporan Banjir**: Mengklik berita laporan banjir untuk melihat informasi lengkap (foto, tinggi air, kondisi akses jalan, listrik, dan status evakuasi) melalui popup modal.

#### 2. Admin Relawan (Komandan Tim SAR / Dispatcher)

a) **Dashboard Mission Control**: Memantau statistik operasional (jumlah SOS antri, prioritas tinggi, misi aktif, misi selesai) serta peta dengan marker SOS dan misi aktif.

b) **Menugaskan Misi Evakuasi ke Anggota Tim**: Melihat antrian SOS yang diurutkan berdasarkan prioritas, lalu menekan tombol **TUGASKAN KE TIM** untuk membuka modal pemilihan anggota. Sistem menampilkan hanya anggota tim yang tersedia (tidak sedang dalam misi dan tidak termasuk Admin Relawan sendiri).

c) **Komunikasi via WhatsApp**: Setelah misi ditugaskan, sistem menyediakan tautan WhatsApp yang berisi pesan lengkap dengan detail lokasi korban, nama pelapor, tingkat prioritas, dan **link Google Maps** menuju titik koordinat evakuasi.

d) **Review dan Verifikasi Anggota Tim Baru**: Meninjau dokumen KTP/Sertifikat calon anggota relawan yang mendaftar melalui modal Review. Admin dapat menyetujui (**Terima & Masukkan Tim**) atau menolak pendaftaran.

e) **Memantau dan Menyelesaikan Misi Aktif**: Melihat misi yang sedang berjalan dan menekan tombol **SELESAI** setelah menerima konfirmasi dari tim di lapangan bahwa korban telah berhasil dievakuasi.

f) **Melihat Riwayat & Ekspor Data Misi**: Mengakses seluruh riwayat misi (aktif dan selesai, sepanjang masa), melihat detail lengkap misi melalui modal, mengekspor data ke CSV, dan menghubungi relawan via WhatsApp langsung dari tabel riwayat.

#### 3. Pengelola Posko (Petugas Shelter)

a) **Manajemen Daya Tampung Posko**: Memperbarui data jumlah pengungsi riil secara berkala (kapasitas maksimum vs hunian aktual) untuk mencegah terjadinya penumpukan pengungsi yang melebihi batas muat aman posko.

b) **Input Kebutuhan Logistik Spesifik**: Menginput dan mengontrol daftar barang kebutuhan pokok yang sedang mengalami krisis di tenda pengungsian (seperti popok bayi, selimut, susu, obat-obatan, atau pembalut) ke dalam sistem agar terpantau oleh donatur.

c) **Verifikasi dan Validasi Donasi**: Memeriksa kiriman logistik yang datang dari donatur, mencocokkannya dengan nomor resi, dan mengonfirmasi status kedatangan bantuan untuk memperbarui sisa kebutuhan posko.

d) **Manajemen Status Operasional**: Mengubah status posko menjadi **Aktif**, **Penuh**, atau **Tutup**. Posko yang ditutup akan disembunyikan dari peta publik dan hub donasi (tetap tampil di arsip sebagai riwayat dengan label abu-abu).

#### 4. Admin BPBD (Badan Penanggulangan Bencana Daerah)

a) **Moderasi dan Validasi Laporan Warga**: Memeriksa laporan titik banjir yang dikirim oleh masyarakat (pending, verified, rejected) guna menyaring informasi palsu atau hoaks sebelum dipublikasikan secara luas di peta digital. Admin juga dapat menandai laporan yang sudah surut (**Set Surut**).

b) **Manajemen Parameter Pintu Air**: Memperbarui status ketinggian air hulu sungai dan level bahaya (Normal, Siaga 3, Siaga 2, Siaga 1) pada sistem peringatan dini berdasarkan pemantauan fisik di lapangan. Sistem memiliki mekanisme **throttling notifikasi** untuk mencegah spam jika TMA berfluktuasi.

c) **Verifikasi Pengguna (User Control)**: Mengontrol hak akses pengguna, melakukan verifikasi terhadap akun Relawan dan Pengelola Posko yang terdaftar. Admin dapat melihat pratinjau dokumen KTP dan data lengkap pengaju sebelum memutuskan menyetujui atau menolak.

d) **Dashboard Analytics Kebencanaan**: Mengakses grafik statistik total persebaran titik banjir, jumlah logistik terdistribusi, dan jumlah korban yang selamat sebagai basis data pengambilan kebijakan taktis.
