# Use Case Scenario - TitikAman

Dokumen ini berisi skenario use case yang telah disesuaikan dengan implementasi sistem TitikAman saat ini.

---

## 1. Skenario Akses & Akun

### Tabel 1.1 Skenario Use Case – Login (UC-01)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Login |
| **Use Case ID** | UC-01 |
| **Actor** | Warga, Admin BPBD, Pengelola Posko, Relawan |
| **Description** | Proses masuk ke dalam sistem menggunakan Email atau Nomor HP untuk mengakses fitur sesuai hak akses (role). |
| **Precondition** | Pengguna telah memiliki akun yang terdaftar di database dengan status **approved**. |
| **Normal Flow** | 1. Pengguna membuka halaman Login.<br>2. Pengguna memasukkan **Email atau Nomor HP** dan password.<br>3. Sistem memverifikasi kecocokan kredensial.<br>4a. Jika status akun **approved**, sistem mengarahkan ke dashboard sesuai peran (Warga → dashboard umum, Admin BPBD → admin dashboard, Relawan → dashboard relawan, Pengelola Posko → dashboard umum).<br>4b. Jika status **pending** atau **rejected**, sistem mengarahkan ke halaman `/status-verifikasi`. |
| **Exception** | Email/HP atau password salah → sistem menampilkan pesan error. Akun pending → redirect ke halaman status verifikasi. Akun rejected → redirect ke halaman status verifikasi dengan pesan penolakan. |

---

### Tabel 1.2 Skenario Use Case – Register Warga (UC-02a)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Register Warga |
| **Use Case ID** | UC-02a |
| **Actor** | Warga (Masyarakat) |
| **Description** | Proses pendaftaran akun baru bagi masyarakat umum. |
| **Precondition** | Warga belum memiliki akun di sistem TitikAman. |
| **Normal Flow** | 1. Warga memilih menu Register di halaman awal.<br>2. Warga memilih peran **Warga**.<br>3. Warga mengisi form data diri (Nama, Email, Password, No HP, Kecamatan, Kelurahan).<br>4. Sistem memvalidasi dan menyimpan data ke tabel Users dengan status **approved** (auto-approve).<br>5. Sistem langsung login dan mengarahkan warga ke Dashboard. |
| **Exception** | Email/HP sudah terdaftar atau form tidak lengkap → pendaftaran ditolak dan ditampilkan pesan error. |

---

### Tabel 1.3 Skenario Use Case – Register Relawan (UC-02b)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Register Relawan / SAR |
| **Use Case ID** | UC-02b |
| **Actor** | Calon Relawan / SAR |
| **Description** | Proses pendaftaran akun baru bagi calon relawan yang memerlukan verifikasi dokumen oleh Admin Relawan. |
| **Precondition** | Calon relawan belum memiliki akun di sistem. |
| **Normal Flow** | 1. Calon relawan memilih menu Register.<br>2. Calon relawan memilih peran **Relawan / SAR**.<br>3. Calon relawan mengisi form lengkap (Nama, NIK, No HP, Email, Kecamatan, Kelurahan, Keahlian, Organisasi, Password).<br>4. Calon relawan mengunggah dokumen KTP/Sertifikat untuk verifikasi.<br>5. Sistem menyimpan data dengan status **pending**.<br>6. Sistem mengarahkan ke halaman Login.<br>7. Admin Relawan meninjau dokumen melalui dashboard relawan → memilih **Terima** atau **Tolak**.<br>8. Jika diterima → status berubah menjadi **approved**.<br>9. Jika ditolak → status berubah menjadi **rejected**, user dapat melihat alasan di halaman status verifikasi. |
| **Exception** | Dokumen tidak diunggah atau NIK tidak valid → pendaftaran ditolak sistem. |

---

### Tabel 1.4 Skenario Use Case – Register Pengelola Posko (UC-02c)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Register Pengelola Posko |
| **Use Case ID** | UC-02c |
| **Actor** | Calon Pengelola Posko |
| **Description** | Proses pendaftaran akun baru bagi pengelola posko sekaligus mendaftarkan posko pengungsian baru. |
| **Precondition** | Calon pengelola belum memiliki akun. |
| **Normal Flow** | 1. Calon pengelola memilih menu Register.<br>2. Calon pengelola memilih peran **Pengelola Posko**.<br>3. Calon pengelola mengisi data diri (Nama, Email, No HP, Password).<br>4. Calon pengelola mengisi data posko (Nama Posko, Kapasitas, Alamat, Fasilitas, Foto Posko, Lokasi GPS).<br>5. Sistem membuat Shelter baru dan menghubungkan akun pengelola dengan posko tersebut.<br>6. Sistem menyimpan data dengan status **pending**.<br>7. Admin BPBD melakukan verifikasi akun pengelola melalui halaman `/admin/verifikasi-pengguna`. |
| **Exception** | Data posko tidak lengkap atau lokasi GPS tidak dipilih → pendaftaran ditolak. |

---

### Tabel 1.5 Skenario Use Case – Status Verifikasi Akun (UC-03)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Status Verifikasi Akun |
| **Use Case ID** | UC-03 |
| **Actor** | Relawan / Pengelola Posko (dengan status pending/rejected) |
| **Description** | Halaman khusus yang menampilkan status verifikasi akun bagi user yang belum disetujui. |
| **Precondition** | User login dengan status **pending** atau **rejected**. |
| **Normal Flow** | 1. Sistem mendeteksi status akun tidak **approved**.<br>2. Sistem mengarahkan ke halaman `/status-verifikasi`.<br>3a. Jika **pending**: menampilkan ikon jam pasir, info proses verifikasi maksimal 1x24 jam, tombol Hubungi Admin BPBD via WA, dan tombol Keluar.<br>3b. Jika **rejected**: menampilkan ikon silang merah, info penolakan dokumen, tombol Hubungi Admin BPBD, dan tombol Keluar. |
| **Exception** | - |

---

## 2. Skenario Aktor: Warga (Masyarakat / Korban / Donatur)

### Tabel 2.1 Skenario Use Case – Lihat Peta & Info Kebencanaan (UC-04)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Lihat Peta & Info Kebencanaan |
| **Use Case ID** | UC-04 |
| **Actor** | Warga |
| **Description** | Proses warga memantau dashboard utama yang berisi peta genangan, statistik, berita laporan, aktivitas terbaru, dan status pintu air. |
| **Precondition** | Warga sudah login ke sistem. |
| **Normal Flow** | 1. Warga membuka halaman Dashboard.<br>2. Sistem menampilkan statistik (titik banjir, warga terdampak, SOS menunggu, posko aktif).<br>3. Sistem menampilkan daftar berita laporan banjir terverifikasi yang bisa diklik untuk melihat detail.<br>4. Sistem menampilkan peta Leaflet dengan marker posko (icon rumah) dan laporan banjir (icon tetesan air).<br>5. Sistem menampilkan data status pintu air dan log aktivitas terbaru.<br>6. Warga dapat mengakses Peta Evakuasi khusus untuk melihat shelter dan zona bahaya. |
| **Exception** | Peta gagal dimuat karena gangguan jaringan. |

---

### Tabel 2.2 Skenario Use Case – Lihat Detail Laporan Banjir (UC-04b)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Lihat Detail Laporan Banjir |
| **Use Case ID** | UC-04b |
| **Actor** | Semua role |
| **Description** | Melihat informasi lengkap laporan banjir melalui popup modal. |
| **Precondition** | Terdapat laporan banjir terverifikasi di daftar berita. |
| **Normal Flow** | 1. User mengklik salah satu berita laporan banjir.<br>2. Sistem menampilkan modal detail berisi: foto bukti, tinggi air, nama jalan, domisili, status akses jalan, kondisi listrik, kondisi air, kebutuhan evakuasi, status warga terisolasi, keterangan, dan informasi pelapor. |
| **Exception** | - |

---

### Tabel 2.3 Skenario Use Case – Lihat Kebutuhan Posko (UC-05)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Lihat Kebutuhan Posko |
| **Use Case ID** | UC-05 |
| **Actor** | Warga (Donatur) |
| **Description** | Proses warga melihat rincian kebutuhan logistik di tiap posko pengungsian. |
| **Precondition** | Warga sudah login ke sistem. |
| **Normal Flow** | 1. Warga membuka halaman Hub Logistik & Donasi.<br>2. Sistem menampilkan daftar posko beserta status kebutuhan barangnya (rendah, sedang, mendesak).<br>3. Sistem menampilkan progres pemenuhan kebutuhan dan donasi terbaru. |
| **Exception** | Data posko kosong jika belum ada pengelola yang melakukan update. |

---

### Tabel 2.4 Skenario Use Case – Mengirim Permintaan SOS (UC-06)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Mengirim Permintaan SOS |
| **Use Case ID** | UC-06 |
| **Actor** | Warga (Korban) |
| **Description** | Proses warga meminta bantuan evakuasi darurat saat terjebak banjir. |
| **Precondition** | Warga sudah login dan berada dalam kondisi darurat. |
| **Normal Flow** | 1. Warga menekan tombol **SOS Darurat** pada navigasi.<br>2. Warga mengisi form: jumlah orang terjebak, jumlah kelompok rentan (lansia/balita/ibu hamil), dan deskripsi.<br>3. Sistem mengambil koordinat GPS otomatis.<br>4. Sistem menghitung skala prioritas (high jika ada kelompok rentan, medium jika >2 orang, low jika sisanya).<br>5. Sistem menyimpan data ke tabel `sos_requests` dengan status **waiting**.<br>6. SOS muncul di antrian dashboard Admin Relawan.<br>7. Admin Relawan menugaskan misi ke anggota tim melalui tombol **TUGASKAN KE TIM**. |
| **Exception** | Akses lokasi (GPS) pada perangkat warga tidak diizinkan → koordinat default Bekasi digunakan. Koneksi terputus → warga dapat mengirim via SMS offline. |

---

### Tabel 2.5 Skenario Use Case – Melaporkan Genangan Banjir (UC-07)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Melaporkan Genangan Banjir |
| **Use Case ID** | UC-07 |
| **Actor** | Warga |
| **Description** | Proses warga memberikan informasi titik banjir baru secara partisipatif (crowdsourcing). |
| **Precondition** | Warga sudah login. |
| **Normal Flow** | 1. Warga membuka form Laporan Banjir.<br>2. Warga mengisi estimasi Tinggi Muka Air (slider), nama jalan, status akses jalan, kondisi listrik, kondisi air (naik/surut), dan mengunggah foto bukti.<br>3. Sistem menangkap koordinat lokasi dan menyimpannya di tabel `flood_reports` dengan status **pending**.<br>4. Admin BPBD memverifikasi laporan → **verified** (muncul di peta dan berita) atau **rejected**. |
| **Exception** | Bukti foto tidak diunggah atau ukuran file terlalu besar. |

---

### Tabel 2.6 Skenario Use Case – Melakukan Donasi Logistik (UC-08)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Melakukan Donasi Logistik |
| **Use Case ID** | UC-08 |
| **Actor** | Warga (Donatur) |
| **Description** | Proses warga memberikan bantuan logistik ke posko yang membutuhkan. |
| **Precondition** | Warga sedang melihat daftar kebutuhan logistik posko. |
| **Normal Flow** | 1. Warga memilih posko dan jenis barang yang ingin didonasikan.<br>2. Warga mengisi **jumlah barang** (tidak boleh melebihi sisa kebutuhan posko).<br>3. Warga mengisi nomor resi (opsional) dan mengunggah foto bukti pengiriman.<br>4. Sistem memvalidasi jumlah donasi ≤ sisa kebutuhan (`quantity_need - quantity_fulfilled`).<br>5. Sistem mencatat donasi di tabel `donations` dengan status **pending**.<br>6. Pengelola Posko memverifikasi kedatangan fisik barang → status menjadi **delivered**.<br>7. Sistem otomatis menambah `quantity_fulfilled` pada tabel `shelter_needs`. |
| **Exception** | Jumlah donasi melebihi sisa kebutuhan → ditolak dengan pesan error. Bukti foto tidak dilampirkan → ditolak sistem. |

---

## 3. Skenario Aktor: Pengelola Posko

### Tabel 3.1 Skenario Use Case – Kelola Kapasitas & Status Posko (UC-09)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Kelola Kapasitas & Status Posko |
| **Use Case ID** | UC-09 |
| **Actor** | Pengelola Posko / Admin BPBD |
| **Description** | Proses memperbarui daya tampung dan status operasional posko pengungsian. |
| **Precondition** | Pengelola posko telah login ke dashboard pengelolaan. |
| **Normal Flow** | 1. Pengelola membuka menu **Kelola Kebutuhan Posko**.<br>2. Pengelola memperbarui jumlah pengungsi saat ini, status fasilitas MCK, dan status posko (Aktif/Penuh/Tutup).<br>3. Sistem menyimpan perubahan ke tabel `shelters`.<br>4. Jika status diubah menjadi **closed**, posko otomatis hilang dari peta publik dan hub donasi (masih tampil di daftar sebagai arsip dengan label abu-abu). |
| **Exception** | Jumlah pengungsi diinputkan dengan format yang salah (misal: huruf) → ditolak sistem. |

---

### Tabel 3.2 Skenario Use Case – Mengelola Kebutuhan Logistik (UC-10)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Mengelola Kebutuhan Logistik |
| **Use Case ID** | UC-10 |
| **Actor** | Pengelola Posko |
| **Description** | Proses mendata barang kebutuhan yang mendesak di posko. |
| **Precondition** | Pengelola posko telah login ke dashboard manajemen. |
| **Normal Flow** | 1. Pengelola memasukkan nama barang (susu, popok, dll) dan jumlah kebutuhan.<br>2. Pengelola menetapkan tingkat urgensi (low/medium/high).<br>3. Sistem menyimpan ke tabel `shelter_needs` dan menampilkannya di halaman Publik (Hub Donasi). |
| **Exception** | Terjadi kesalahan input pada koneksi database. |

---

### Tabel 3.3 Skenario Use Case – Verifikasi Donasi (UC-11)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Verifikasi Donasi |
| **Use Case ID** | UC-11 |
| **Actor** | Pengelola Posko |
| **Description** | Memvalidasi kedatangan fisik barang donasi dari warga/donatur. |
| **Precondition** | Ada donasi masuk dengan status **pending** untuk posko yang dikelola. |
| **Normal Flow** | 1. Pengelola melihat daftar donasi masuk di dashboard.<br>2. Pengelola memeriksa fisik barang dan mencocokkan dengan foto/resi di sistem.<br>3. Pengelola mengubah status donasi menjadi **delivered**.<br>4. Sistem secara otomatis menambah `quantity_fulfilled` pada tabel `shelter_needs`. |
| **Exception** | Barang fisik tidak sesuai dengan data resi → status donasi dapat ditolak (rejected). |

---

## 4. Skenario Aktor: Admin Relawan

### Tabel 4.1 Skenario Use Case – Dashboard Mission Control (UC-12)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Dashboard Mission Control |
| **Use Case ID** | UC-12 |
| **Actor** | Admin Relawan |
| **Description** | Admin Relawan memantau antrian SOS, menugaskan misi ke anggota tim, dan melihat riwayat seluruh misi. |
| **Precondition** | Admin Relawan login dengan akun **relawan@example.com**. |
| **Normal Flow** | 1. Admin membuka halaman Dashboard Relawan.<br>2. Sistem menampilkan statistik (SOS antri, prioritas tinggi, misi aktif, misi selesai).<br>3. Sistem menampilkan antrian SOS dengan prioritas (Tinggi/Sedang/Rendah).<br>4. Admin menekan tombol **TUGASKAN KE TIM** pada salah satu SOS.<br>5. Sistem menampilkan modal berisi daftar anggota tim yang tersedia (tidak termasuk Admin Relawan sendiri).<br>6. Admin memilih anggota tim dan menekan **Tugaskan Misi**.<br>7. Sistem membuat misi baru dan menampilkan link WhatsApp untuk mengirim detail ke relawan yang ditugaskan.<br>8. Sistem juga menampilkan peta dengan marker SOS dan misi aktif. |
| **Exception** | Tidak ada anggota tim yang tersedia (semua sedang dalam misi) → opsi anggota dinonaktifkan dengan label "(Sedang Dalam Misi)". |

---

### Tabel 4.2 Skenario Use Case – Menugaskan Misi via WhatsApp (UC-12b)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Menugaskan Misi via WhatsApp |
| **Use Case ID** | UC-12b |
| **Actor** | Admin Relawan |
| **Description** | Admin Relawan mengirim detail misi penyelamatan ke nomor WhatsApp anggota tim yang ditugaskan. |
| **Precondition** | Misi berhasil dibuat melalui form **TUGASKAN KE TIM**. |
| **Normal Flow** | 1. Setelah misi berhasil dibuat, sistem menampilkan pesan sukses dan tombol **Kirim Pesan via WhatsApp**.<br>2. Admin mengklik tombol WhatsApp.<br>3. Sistem membuka WhatsApp dengan pesan berisi: detail SOS, nama pelapor, lokasi, prioritas, dan **link Google Maps** ke lokasi korban. |
| **Exception** | - |

---

### Tabel 4.3 Skenario Use Case – Selesaikan Misi (UC-13)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Selesaikan Misi |
| **Use Case ID** | UC-13 |
| **Actor** | Admin Relawan |
| **Description** | Proses menandai misi penyelamatan bahwa korban telah berhasil dievakuasi ke titik aman. |
| **Precondition** | Admin Relawan memiliki misi aktif yang sedang berjalan. |
| **Normal Flow** | 1. Admin menekan tombol **SELESAI** pada kartu misi aktif.<br>2. Sistem mengkonfirmasi ("Konfirmasi: Korban sudah berhasil dievakuasi dengan aman?").<br>3. Admin mengkonfirmasi.<br>4. Sistem mengubah status SOS menjadi **completed** dan mencatat `resolved_at` pada misi.<br>5. Misi masuk ke tabel Riwayat Misi. |
| **Exception** | - |

---

### Tabel 4.4 Skenario Use Case – Review Anggota Tim Baru (UC-12c)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Review Anggota Tim Baru |
| **Use Case ID** | UC-12c |
| **Actor** | Admin Relawan |
| **Description** | Admin Relawan meninjau dokumen calon anggota tim yang mendaftar sebagai Relawan. |
| **Precondition** | Terdapat pendaftar baru dengan status **pending**. |
| **Normal Flow** | 1. Admin melihat daftar "Pendaftar Anggota Tim Baru" di dashboard.<br>2. Admin mengklik tombol **Review** pada pendaftar.<br>3. Sistem menampilkan modal berisi data diri, keahlian, organisasi, dan **pratinjau dokumen KTP/Sertifikat**.<br>4. Admin memilih **Terima & Masukkan Tim** atau **Tolak**.<br>5. Sistem memperbarui status akun menjadi **approved** atau **rejected**. |
| **Exception** | - |

---

### Tabel 4.5 Skenario Use Case – Export & Detail Riwayat Misi (UC-13b)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Export & Detail Riwayat Misi |
| **Use Case ID** | UC-13b |
| **Actor** | Admin Relawan |
| **Description** | Admin Relawan melihat seluruh riwayat misi (aktif & selesai), detail lengkap, dan mengexport ke CSV. |
| **Precondition** | Terdapat data misi di database. |
| **Normal Flow** | 1. Admin melihat tabel **Riwayat Misi** yang berisi semua misi (tidak hanya hari ini).<br>2. Admin dapat mengklik tombol **Detail** untuk melihat informasi lengkap misi (pelapor, lokasi, jumlah orang, prioritas, deskripsi, relawan ditugaskan, waktu, durasi, status).<br>3. Admin dapat mengklik tombol **Export CSV** untuk mengunduh seluruh data misi.<br>4. Admin dapat mengklik ikon **WhatsApp** untuk menghubungi relawan yang ditugaskan. |
| **Exception** | - |

---

## 5. Skenario Aktor: Admin BPBD

### Tabel 5.1 Skenario Use Case – Dashboard Kebencanaan (UC-14)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Dashboard Kebencanaan |
| **Use Case ID** | UC-14 |
| **Actor** | Admin BPBD |
| **Description** | Admin BPBD memantau ringkasan statistik, laporan banjir masuk, dan aktivitas kebencanaan secara keseluruhan. |
| **Precondition** | Admin BPBD sudah login (diarahkan otomatis ke admin dashboard). |
| **Normal Flow** | 1. Admin melihat statistik utama (total titik banjir, warga terdampak, SOS menunggu, posko aktif).<br>2. Admin melihat daftar laporan genangan yang perlu diverifikasi.<br>3. Admin melihat peta ringkasan dengan marker banjir dan posko.<br>4. Admin melihat log aktivitas terbaru (laporan, SOS, pintu air, donasi).<br>5. Admin dapat menandai laporan yang sudah surut (**Set Surut**). |
| **Exception** | Data lambat dimuat jika terjadi beban server yang tinggi. |

---

### Tabel 5.2 Skenario Use Case – Verifikasi Laporan Banjir (UC-15)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Verifikasi Laporan Banjir |
| **Use Case ID** | UC-15 |
| **Actor** | Admin BPBD |
| **Description** | Menyaring informasi titik banjir dari warga sebelum disebarkan ke publik untuk mencegah hoaks. |
| **Precondition** | Terdapat laporan genangan masuk dengan status **pending**. |
| **Normal Flow** | 1. Admin melihat daftar laporan genangan masuk di dashboard.<br>2. Admin mengecek foto bukti dan kecocokan koordinat.<br>3. Admin memilih **Verifikasi** (status → **verified**) atau **Tolak** (status → **rejected**).<br>4. Laporan yang terverifikasi muncul di peta publik dan berita dashboard.<br>5. Admin juga dapat menandai laporan yang sudah surut (**Set Surut** → `water_height_cm = 0`). |
| **Exception** | Foto buram atau laporan terindikasi palsu → Admin menolak laporan. |

---

### Tabel 5.3 Skenario Use Case – Kelola Data Pintu Air (UC-16)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Kelola Data Pintu Air & Peringatan Dini |
| **Use Case ID** | UC-16 |
| **Actor** | Admin BPBD |
| **Description** | Memperbarui data TMA (Tinggi Muka Air) untuk indikator sistem peringatan dini yang terintegrasi dengan aliran sungai. |
| **Precondition** | Admin login dan membuka halaman Kelola TMA. |
| **Normal Flow** | 1. Admin masuk ke halaman **Kelola TMA Air**.<br>2. Admin memperbarui Tinggi Muka Air dalam ukuran CM.<br>3. Sistem otomatis menentukan status bahaya berdasarkan threshold (Normal < 150cm, Siaga_3 ≥ 150cm, Siaga_2 ≥ 200cm, Siaga_1 ≥ 250cm).<br>4. Jika status naik melewati ambang batas, sistem mengirim peringatan dini ke warga di kecamatan terdampak (berdasarkan DAS/mapping aliran sungai).<br>5. Sistem memiliki **throttling notification** (1 jam) untuk mencegah spam notifikasi jika TMA berfluktuasi. |
| **Exception** | Input bukan angka → sistem menolak. |

---

### Tabel 5.4 Skenario Use Case – Verifikasi Pengguna (UC-17)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Verifikasi Pengguna |
| **Use Case ID** | UC-17 |
| **Actor** | Admin BPBD |
| **Description** | Admin BPBD menyetujui atau menolak pendaftaran akun baru (Relawan & Pengelola Posko). |
| **Precondition** | Terdapat pengguna baru dengan status **pending**. |
| **Normal Flow** | 1. Admin membuka halaman `/admin/verifikasi-pengguna`.<br>2. Admin melihat daftar pengajuan (filter: Semua / Relawan / Posko).<br>3. Admin memilih salah satu pengajuan.<br>4. Sistem menampilkan data lengkap pengaju (termasuk pratinjau dokumen KTP untuk relawan).<br>5. Admin memilih **Setujui** atau **Tolak**.<br>6. Status akun berubah menjadi **approved** atau **rejected**. |
| **Exception** | - |
