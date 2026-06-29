# Use Case Scenario - TitikAman

Dokumen ini berisi skenario use case yang telah disesuaikan dengan implementasi sistem TitikAman saat ini.

---

## 1. Skenario Akses & Akun

### Tabel 1.1 Skenario Use Case – Login (UC-01)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Login |
| **Use Case ID** | UC-01 |
| **Actor** | Warga, Admin BPBD, Pengelola Posko, Admin Relawan |
| **Description** | Proses masuk ke dalam sistem menggunakan Email atau Nomor HP untuk mengakses fitur sesuai hak akses (role). |
| **Precondition** | Pengguna telah memiliki akun yang terdaftar di database dengan status **approved**. |
| **Normal Flow** | 1. Pengguna membuka halaman Login.<br>2. Pengguna memasukkan **Email atau Nomor HP** dan password.<br>3. Sistem memverifikasi kecocokan kredensial.<br>4a. Jika status **approved**, sistem mengarahkan ke dashboard sesuai peran (Warga → dashboard umum, Admin BPBD → admin dashboard, Admin Relawan → dashboard relawan, Pengelola Posko → dashboard umum).<br>4b. Jika status **pending** atau **rejected**, sistem mengarahkan ke halaman `/status-verifikasi`. |
| **Exception** | Email/HP atau password salah → sistem menampilkan pesan error. |

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

### Tabel 1.3 Skenario Use Case – Register Relawan / SAR (UC-02b)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Register Relawan / SAR |
| **Use Case ID** | UC-02b |
| **Actor** | Calon Relawan |
| **Description** | Proses pendaftaran akun bagi calon relawan. Calon relawan hanya mengisi data dan mengunggah dokumen. Seluruh akses sistem dan penugasan dikelola oleh Admin Relawan. |
| **Precondition** | Calon relawan belum memiliki akun. |
| **Normal Flow** | 1. Calon relawan memilih menu Register.<br>2. Calon relawan memilih peran **Relawan / SAR**.<br>3. Calon relawan mengisi form lengkap (Nama, NIK, No HP, Email, Kecamatan, Kelurahan, Keahlian, Organisasi, Password).<br>4. Calon relawan mengunggah dokumen KTP/Sertifikat.<br>5. Sistem menyimpan data dengan role `Relawan` dan status **pending**.<br>6. Sistem mengarahkan ke halaman Login.<br>7. Admin Relawan meninjau dokumen melalui dashboard → memilih **Terima** atau **Tolak**.<br>8. Jika diterima → session approval tersimpan di dashboard Admin Relawan, menampilkan banner dengan tombol **Kirim Info via WA ke [Nama]** (mengirim link grup WA Tim ke nomor anggota baru). Status berubah menjadi **approved**.<br>9. Relawan login → sistem deteksi status approved + role Relawan → redirect ke `/status-verifikasi` dengan halaman approved yang menampilkan: nama tim, **Gabung Grup WhatsApp [Tim]**, **Kirim Info ke WhatsApp Saya**, dan **Lanjut ke Dashboard**.<br>10. Jika ditolak → status berubah menjadi **rejected**.<br>11. **Pencatatan penting**: Relawan tidak memiliki akses ke halaman dashboard operasional. Seluruh penugasan misi dikirimkan melalui **Grup WhatsApp** oleh Admin Relawan. |
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
| **Actor** | Calon Relawan / Pengelola Posko (dengan status pending/rejected) |
| **Description** | Halaman khusus yang menampilkan status verifikasi akun bagi user yang belum disetujui. |
| **Precondition** | User login dengan status **pending** atau **rejected**. |
| **Normal Flow** | 1. Sistem mendeteksi status akun tidak **approved**.<br>2. Sistem mengarahkan ke halaman `/status-verifikasi`.<br>3a. Jika **pending**: menampilkan ikon jam pasir, info proses verifikasi, tombol Hubungi Admin BPBD via WA, dan tombol Keluar.<br>3b. Jika **rejected**: menampilkan ikon silang merah, info penolakan dokumen, tombol Hubungi Admin BPBD, dan tombol Keluar. |
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
| **Normal Flow** | 1. Warga membuka halaman Dashboard.<br>2. Sistem menampilkan statistik (titik banjir, warga terdampak, SOS menunggu, posko aktif).<br>3. Sistem menampilkan daftar berita laporan banjir yang bisa diklik untuk lihat detail.<br>4. Sistem menampilkan peta Leaflet dengan marker posko dan laporan banjir.<br>5. Sistem menampilkan data status pintu air dan log aktivitas. |
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
| **Normal Flow** | User mengklik berita → modal detail berisi: foto, tinggi air, nama jalan, status akses, listrik, kondisi air, evakuasi, terisolasi, keterangan, pelapor. |
| **Exception** | - |

---

### Tabel 2.3 Skenario Use Case – Lihat Kebutuhan Posko (UC-05)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Lihat Kebutuhan Posko |
| **Use Case ID** | UC-05 |
| **Actor** | Warga (Donatur) |
| **Description** | Melihat rincian kebutuhan logistik di tiap posko. |
| **Precondition** | Warga sudah login. |
| **Normal Flow** | 1. Warga membuka Hub Logistik & Donasi.<br>2. Sistem menampilkan daftar posko + kebutuhan barang + progres pemenuhan. |
| **Exception** | Data posko kosong jika belum ada update. |

---

### Tabel 2.4 Skenario Use Case – Mengirim Permintaan SOS (UC-06)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Mengirim Permintaan SOS |
| **Use Case ID** | UC-06 |
| **Actor** | Warga (Korban) |
| **Description** | Warga meminta bantuan evakuasi darurat saat terjebak banjir. |
| **Precondition** | Warga sudah login. |
| **Normal Flow** | 1. Warga menekan tombol **SOS Darurat**.<br>2. Warga mengisi jumlah orang, kelompok rentan, deskripsi.<br>3. Sistem mengambil koordinat GPS dan menghitung prioritas.<br>4. Data disimpan dengan status **waiting**.<br>5. SOS muncul di antrian dashboard Admin Relawan.<br>6. Admin Relawan menugaskan misi ke anggota tim atau menghubungi pihak terkait. |
| **Exception** | GPS tidak diizinkan → koordinat default. Bisa kirim via SMS offline. |

---

### Tabel 2.5 Skenario Use Case – Melaporkan Genangan Banjir (UC-07)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Melaporkan Genangan Banjir |
| **Use Case ID** | UC-07 |
| **Actor** | Warga |
| **Description** | Warga melaporkan titik banjir secara partisipatif (crowdsourcing). |
| **Precondition** | Warga sudah login. |
| **Normal Flow** | 1. Warga membuka form Laporan Banjir.<br>2. Warga mengisi TMA (slider), nama jalan, status akses, kondisi listrik, kondisi air, upload foto.<br>3. Data disimpan dengan status **pending**.<br>4. Admin BPBD verifikasi → **verified** (muncul di peta) atau **rejected**. |
| **Exception** | Foto tidak diupload atau terlalu besar. |

---

### Tabel 2.6 Skenario Use Case – Melakukan Donasi Logistik (UC-08)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Melakukan Donasi Logistik |
| **Use Case ID** | UC-08 |
| **Actor** | Warga (Donatur) |
| **Description** | Warga memberikan bantuan logistik ke posko. |
| **Precondition** | Warga melihat daftar kebutuhan. |
| **Normal Flow** | 1. Warga memilih posko dan barang.<br>2. Warga mengisi jumlah (tidak boleh melebihi sisa kebutuhan).<br>3. Upload foto bukti kirim.<br>4. Donasi tercatat dengan status **pending**.<br>5. Pengelola Posko verifikasi fisik → status **delivered** → otomatis kurangi kebutuhan. |
| **Exception** | Jumlah melebihi sisa → ditolak. |

---

## 3. Skenario Aktor: Admin Relawan

### Tabel 3.1 Skenario Use Case – Dashboard Mission Control (UC-09)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Dashboard Mission Control |
| **Use Case ID** | UC-09 |
| **Actor** | Admin Relawan |
| **Description** | Admin Relawan memantau antrian SOS, menugaskan misi ke tim, mengirim info ke grup WA, dan melihat riwayat seluruh misi. |
| **Precondition** | Admin Relawan login dengan role **Admin_Relawan**. |
| **Normal Flow** | 1. Admin membuka Dashboard Relawan.<br>2. Sistem menampilkan statistik dan antrian SOS.<br>3. Admin menekan **TUGASKAN KE TIM** pada SOS (status waiting).<br>4. Sistem menampilkan dropdown berisi daftar tim (Lead per kecamatan).<br>5. Admin memilih tim → **Tugaskan Misi**.<br>6. Sistem membuat misi + menampilkan banner permanen dengan 4 tombol: **Kirim ke WhatsApp (Relawan)**, **Share Grup [Tim]**, **Minta Bantuan (Grup Gabungan)**, **Buka Google Maps**.<br>7. Admin bisa klik **Share Grup [Tim]** untuk mengirim info misi ke grup WA tim.<br>8. Admin bisa klik **Minta Bantuan** untuk meminta backup dari grup gabungan. |
| **Exception** | Semua tim sedang sibuk, tapi tetap bisa dipilih sebagai backup (label "(Dalam Misi — Kirim Bantuan)"). |

---

### Tabel 3.2 Skenario Use Case – Kirim Bantuan Tim (UC-10)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Kirim Bantuan Tim |
| **Use Case ID** | UC-10 |
| **Actor** | Admin Relawan |
| **Description** | Admin Relawan mengirim tim tambahan (backup) untuk membantu SOS yang sudah ditangani. |
| **Precondition** | Terdapat SOS dengan status **assigned** (sedang ditangani tim lain). |
| **Normal Flow** | 1. SOS berlabel **"BANTUAN"** muncul di antrian.<br>2. Admin menekan tombol **KIRIM BANTUAN TIM** (warna oranye).<br>3. Sistem menampilkan dropdown tim (termasuk tim yang sedang dalam misi).<br>4. Admin memilih tim backup → **Tugaskan Misi**.<br>5. Mission baru tercatat untuk tim backup (1 SOS = multiple missions allowed).<br>6. Banner muncul dengan tombol **Share Grup [Tim]** dan **Minta Bantuan (Grup Gabungan)**. |
| **Exception** | - |

---

### Tabel 3.3 Skenario Use Case – Review & Approve Anggota Baru (UC-11)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Review & Approve Anggota Baru |
| **Use Case ID** | UC-11 |
| **Actor** | Admin Relawan |
| **Description** | Admin Relawan meninjau dokumen calon anggota dan mengirim link grup WA ke anggota yang disetujui. |
| **Precondition** | Terdapat pendaftar baru dengan status **pending**. |
| **Normal Flow** | 1. Admin melihat card **Pendaftar Baru** di dashboard.<br>2. Admin mengklik **Review** pada pendaftar di tabel.<br>3. Sistem menampilkan modal berisi data diri + pratinjau dokumen KTP.<br>4. Admin memilih **Terima & Masukkan Tim** atau **Tolak**.<br>5. Jika diterima, session approval tersimpan dan banner biru muncul dengan 2 tombol:<br>&nbsp;&nbsp;- **Kirim Info via WA ke [Nama]**: Kirim pesan berisi link grup WA tim ke nomor anggota baru.<br>&nbsp;&nbsp;- **Link Grup [Tim]**: Buka link undangan grup WA tim.<br>6. Relawan login → lihat halaman `/status-verifikasi` dengan status approved → bisa gabung grup WA. |
| **Exception** | - |

---

### Tabel 3.4 Skenario Use Case – Selesaikan Misi & Riwayat (UC-12)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Selesaikan Misi & Lihat Riwayat |
| **Use Case ID** | UC-12 |
| **Actor** | Admin Relawan |
| **Description** | Admin Relawan menyelesaikan misi dan melihat riwayat lengkap. |
| **Precondition** | Terdapat misi aktif. |
| **Normal Flow** | 1. Admin menekan **SELESAI** pada misi aktif → konfirmasi.<br>2. Status SOS menjadi **completed**.<br>3. Admin melihat tabel Riwayat Misi (semua misi, tidak hanya hari ini).<br>4. Admin bisa klik **Detail** untuk info lengkap, **Export CSV**, atau **WA** untuk hubungi relawan. |
| **Exception** | - |

---

## 4. Skenario Aktor: Pengelola Posko

### Tabel 4.1 Skenario Use Case – Kelola Kapasitas & Status Posko (UC-12)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Kelola Kapasitas & Status Posko |
| **Use Case ID** | UC-12 |
| **Actor** | Pengelola Posko / Admin BPBD |
| **Description** | Memperbarui daya tampung dan status operasional posko. |
| **Precondition** | Pengelola telah login. |
| **Normal Flow** | 1. Pengelola membuka menu Kelola Kebutuhan Posko.<br>2. Update jumlah pengungsi, fasilitas MCK, status (Aktif/Penuh/Tutup).<br>3. Jika status **closed** → posko hilang dari peta publik (arsip abu-abu). |
| **Exception** | Input bukan angka → ditolak. |

---

### Tabel 4.2 Skenario Use Case – Verifikasi Donasi (UC-13)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Verifikasi Donasi |
| **Use Case ID** | UC-13 |
| **Actor** | Pengelola Posko |
| **Description** | Memvalidasi kedatangan fisik barang donasi. |
| **Precondition** | Ada donasi masuk dengan status **pending**. |
| **Normal Flow** | 1. Pengelola cek daftar donasi.<br>2. Cocokkan fisik barang dengan resi/foto.<br>3. Ubah status → **delivered** → stok kebutuhan otomatis terisi. |
| **Exception** | Barang tidak sesuai → ditolak. |

---

## 5. Skenario Aktor: Admin BPBD

### Tabel 5.1 Skenario Use Case – Dashboard & Verifikasi Laporan (UC-14)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Dashboard & Verifikasi Laporan |
| **Use Case ID** | UC-14 |
| **Actor** | Admin BPBD |
| **Description** | Memantau dashboard dan memverifikasi laporan banjir dari warga. |
| **Precondition** | Admin login (diarahkan ke admin dashboard). |
| **Normal Flow** | 1. Admin melihat statistik, peta, log aktivitas.<br>2. Admin verifikasi/tolak laporan pending.<br>3. Admin bisa tandai **Set Surut** untuk laporan yang sudah surut. |
| **Exception** | - |

---

### Tabel 5.2 Skenario Use Case – Kelola TMA & Peringatan Dini (UC-15)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Kelola TMA & Peringatan Dini |
| **Use Case ID** | UC-15 |
| **Actor** | Admin BPBD |
| **Description** | Memperbarui data TMA untuk peringatan dini. |
| **Precondition** | Admin login ke halaman Kelola TMA. |
| **Normal Flow** | 1. Admin update tinggi air (cm).<br>2. Sistem hitung status bahaya (Normal/Siaga 3/2/1).<br>3. Jika batas terlewati → kirim notifikasi ke warga di kecamatan terdampak (DAS mapping).<br>4. Sistem memiliki **throttling 1 jam** untuk cegah spam notifikasi. |
| **Exception** | Input bukan angka → ditolak. |

---

### Tabel 5.3 Skenario Use Case – Verifikasi Pengguna (UC-16)

| Elemen | Deskripsi |
|--------|-----------|
| **Use Case Name** | Verifikasi Pengguna |
| **Use Case ID** | UC-16 |
| **Actor** | Admin BPBD |
| **Description** | Menyetujui atau menolak pendaftaran akun baru (Relawan & Pengelola Posko). |
| **Precondition** | Terdapat pengguna baru dengan status **pending**. |
| **Normal Flow** | 1. Admin buka `/admin/verifikasi-pengguna`.<br>2. Admin lihat daftar pengajuan + filter.<br>3. Admin pilih salah satu → lihat data lengkap + dokumen.<br>4. Admin **Setujui** atau **Tolak**.<br>5. Status akun berubah menjadi **approved** atau **rejected**. |
| **Exception** | - |
