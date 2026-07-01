LEMBAR TUGAS PROYEK PERANGKAT LUNAK
"Siaga Banjir" (Sistem Monitoring Peringatan Dini dan Antisipasi Banjir)
Disusun sebagai Bukti Laporan Hasil Kegiatan IT Bootcamp
Semester Genap T.A. 2025/2026
Disusun oleh: Kelompok 8
1. Bryan Dexter Gunawan NIM : 17235005 Kelas : 17.4A.03
2. Dedi Martdiansyah NIM : 19235174 Kelas : 19.4A.26
3. Erlangga Mulya NIM : 19235049 Kelas : 19.4A.26
4. Irfan Sulthoni NIM : 19235020 Kelas : 19.4A.26
5. Nawaf Milfi Mubarok NIM : 19235080 Kelas : 19.4A.25
6. Rizky Aditya R NIM : 17235048 Kelas : 17.4A.25
7. Surya Satria Prakoso NIM : 17235020 Kelas : 17.4A.25
Fakultas Teknik dan Informatika
Universitas Bina Sarana Informatika
Jakarta
2026

KATA PENGANTAR
Puji syukur kami panjatkan kehadirat Allah SWT yang telah melimpahkan rahmat dan karuniaNya
sehingga kami kelompok 8 dapat menyusun tugas proyek perangkat lunak ini dengan baik sebagai
bagian dari kegiatan IT Bootcamp UBSI. Dalam makalah ini kami membahas tema
"SiAGA Banjir" (Sistem Monitoring Peringatan Dini dan Antisipasi Banjir)
Terima kasih kami haturkan kepada Bapak Andriansah, M.Kom. selaku Dosen Pembimbing
yang telah memberikan arahan serta bimbingan, juga semua pihak yang turut memberikan kontribusi
dalam penyusunan makalah ini.
Harapan kami makalah ini dapat memberikan manfaat dan inspirasi positif bagi kegiatan IT
Bootcamp serta menjadi bahan evaluasi kedepannya. Namun demikian, kami menyadari bahwa masih
terdapat kekurangan, baik dari penyusunan, rancangan maupun tata Bahasa penyampaian dalam
makalah ini karena keterbatasan pengetahuan kami. Oleh karena itu, saran dan masukan sangat kami
harapkan dari berbagai pihak untuk kesempurnaan makalah ini dan agar kami dapat terus melakukan
pengembangan dan peningkatan kualitas sistem yang lebih baik di masa yang akan datang.
Jakarta, 12 Januari 2026
Penulis
ii

DAFTAR ISI
KATA PENGANTAR ............................................................................................................................ ii
DAFTAR ISI .......................................................................................................................................... iii
ABSTRAK ............................................................................................................................................. iv
FASE 1 RISET (PENELITIAN) DAN ANALISIS ................................................................................ 1
1.1 Latar Belakang .............................................................................................................................. 1
1.2 Tujuan ........................................................................................................................................... 2
1.3 Harapan ......................................................................................................................................... 3
FASE 2 PERENCANAAN DAN STRATEGI ....................................................................................... 4
2.1 Analisa Kebutuhan ........................................................................................................................ 4
2.2 Usecase Diagram .......................................................................................................................... 4
2.3 Entity Relationship Diagram (ERD) ............................................................................................. 7
2.4 Logical Record Structure (LRS) ................................................................................................... 8
FASE 3 DESAIN DAN WIREFRAME ................................................................................................ 15
3.1 Wireframe ................................................................................................................................... 15
3.2 Prototype ..................................................................................................................................... 17
FASE 4 CONTENT CREATION (PEMBUATAN KONTEN) .......................................................... 26
4.1 Typography ................................................................................................................................. 26
4.2 Color Style .................................................................................................................................. 26
4.3 Gambar ........................................................................................................................................ 27
4.4 Tombol dan Ikon ......................................................................................................................... 29
FASE 5 CODE AND DEVELOPMENT (KODE DAN PENGEMBANGAN) .................................. 30
5.1 Tech Stack ................................................................................................................................... 30
5.2 Tampilan Coding ........................................................................................................................ 30
FASE 6 TESTING AND QUALITY ASSURANCE (PENGUJIAN DAN JAMINAN KUALITAS)41
6.1 Blackbox Testing ........................................................................................................................ 41
6.2 Hasil Pengujian Usability Testing ............................................................................................... 47
FASE 7 DEPLOYMENT AND MAINTENANCE (PENERAPAN DAN PEMELIHARAAN) ........ 52
7.1 Deployment ................................................................................................................................. 52
7.2 Maintenance ................................................................................................................................ 54
KESIMPULAN ..................................................................................................................................... 59
LAMPIRAN .......................................................................................................................................... 60
iii

DAFTAR TABEL
Fase 2:
Table 2.1 Use Case Login .................................................................................................................... 6
Table 2.2 Use Case Lapor Kejadian Banjir .......................................................................................... 6
Tabel 2.3 Use Case Kirim Sinyal Sos .................................................................................................. 6
Table 2.4 Use Case Cari Lokasi Pengungsi .......................................................................................... 6
Table 2.5 Use Case Melihat Status Pintu Air ....................................................................................... 6
Table 2.6 Use Case Input Laporan ....................................................................................................... 7
Table 2.7 Use Case Validasi Laporan .................................................................................................. 7
Table 2.8 Use Case Kelola Data ........................................................................................................... 7
Table 2.9 Spesifikasi File Users ........................................................................................................... 9
Table 2.10 Spesifikasi File Stations ................................................................................................... 10
Table 2.11 Spesifikasi File Regions ................................................................................................... 11
Table 2.12 Spesifikasi Public_Reports ............................................................................................... 11
Table 2.13 Spesifikasi File Officer_Reports ...................................................................................... 12
Table 2.14 Spesifikasi File Notifications ........................................................................................... 12
Table 2.15 Spesifikasi File Disaster_Facilities .................................................................................. 13
Table 2.16 Spesifikasi File Station_Users .......................................................................................... 13
Table 2.17 Spesifikasi File Region_Station ....................................................................................... 14
Fase 6:
Table 6.1 Blackbox Testing ADMIN ................................................................................................. 41
Table 6.2 Blackbox Testing PETUGAS ............................................................................................. 44
Table 6.3 Blackbox Testing USER .................................................................................................... 45
Table 6.4 Skor Responden .................................................................................................................. 47
Table 6.5 Jumlah dan Rata-Rata Skor ................................................................................................ 50
iv

DAFTAR GAMBAR
Gambar 1 Use Case Diagram .................................................................................................................. 5
Gambar 2 ERD Sistem Monitoring Peringatan Dini dan Antipasi Banjir .............................................. 8
Gambar 3 LRS Sistem Monitoring Peringatan Dini dan Antisipasi Banjir ............................................ 9
Gambar 4 Wireframe Login .................................................................................................................. 15
Gambar 5 Profil user ............................................................................................................................. 15
Gambar 6 Wireframe Lapor Banjir ....................................................................................................... 15
Gambar 7 Wireframe Lapor Peta .......................................................................................................... 15
Gambar 8 Wireframe Riwayat .............................................................................................................. 16
Gambar 9 Wireframe Notifikasi ........................................................................................................... 16
Gambar 10 Wireframe Riwayat ............................................................................................................ 16
Gambar 11 Wireframe Peta................................................................................................................... 16
Gambar 12 Wireframe Beranda ............................................................................................................ 16
Gambar 13 Wireframe Login ................................................................................................................ 16
Gambar 14 Wireframe Detail Laporan ................................................................................................. 17
Gambar 15 Wireframe Buat Laporan .................................................................................................... 17
Gambar 16 Wireframe Edit Profil ......................................................................................................... 17
Gambar 17 Wireframe Profil Petugas ................................................................................................... 17
Gambar 18 Wireframe Login ................................................................................................................ 17
Gambar 19 Wireframe Dashboard ........................................................................................................ 18
Gambar 20 Wireframe Potensi Banjir ................................................................................................... 18
Gambar 21 Wireframe Laporan Masyarakat ........................................................................................ 18
Gambar 22 Wireframe Manajemen Pos Pantau dan Wireframe Manajemen Wilayah ......................... 19
Gambar 23 Wireframe Rekap Laporan ................................................................................................. 19
Gambar 24 Halaman Beranda ............................................................................................................... 19
Gambar 25 Halaman Login ................................................................................................................... 19
Gambar 26 Halaman Notifikasi ............................................................................................................ 20
Gambar 27 Halaman Riwayat ............................................................................................................... 20
Gambar 28 Halman Beranda ................................................................................................................. 20
Gambar 29 Halaman Peta ..................................................................................................................... 20
Gambar 30 Halaman Beranda ............................................................................................................... 20
Gambar 31 Halaman Login ................................................................................................................... 20
Gambar 32 Halaman Notifikasi ............................................................................................................ 21
Gambar 33 Halaman Detail Laporan .................................................................................................... 21
Gambar 34 Halaman Buat Laporan ...................................................................................................... 21
Gambar 35 Halaman Peta ..................................................................................................................... 21
Gambar 36 Halaman Profil ................................................................................................................... 21
Gambar 37 Halaman Edit Profil............................................................................................................ 21
Gambar 38 Halaman Login Admin ....................................................................................................... 22
Gambar 39 Halaman Dashboard ........................................................................................................... 22
Gambar 40 Halaman Manajemen Akun Masyarakat ............................................................................ 22
Gambar 41 Halaman Manajemen Fasilitas bencana ............................................................................. 23
Gambar 42 Halaman Manajemen Petugas ............................................................................................ 23
Gambar 43 Halaman Manajemen Laporan Petugas .............................................................................. 23
Gambar 44 Halaman Manajemen Pos Pantau ....................................................................................... 24
Gambar 45 Halaman Manajemen Wilayah ........................................................................................... 24
Gambar 46 Halaman Potensi Banjir ...................................................................................................... 24
v

Gambar 47 Halaman Rekap Laporan .................................................................................................... 25
Gambar 48 Typoraphy .......................................................................................................................... 26
Gambar 49 Color Style ......................................................................................................................... 26
Gambar 50 LOGO ................................................................................................................................. 27
Gambar 51 Tombol dan Ikon ................................................................................................................ 29
Gambar 52 Login .................................................................................................................................. 30
Gambar 53 Register Akun .................................................................................................................... 30
Gambar 54 Dashboard .......................................................................................................................... 31
Gambar 55 Lapor Banjir ....................................................................................................................... 31
Gambar 56 Detail Laporan .................................................................................................................... 31
Gambar 57 Riwayat Laporan ................................................................................................................ 32
Gambar 58 Status Wilayah ................................................................................................................... 32
Gambar 59 Peta ..................................................................................................................................... 32
Gambar 60 Profil ................................................................................................................................... 33
Gambar 61 Edit Profil ........................................................................................................................... 33
Gambar 62 Notifikasi Warga ................................................................................................................ 33
Gambar 63 Buat laporan Petugas .......................................................................................................... 34
Gambar 64 Login Petugas ..................................................................................................................... 34
Gambar 65 Dashboard .......................................................................................................................... 34
Gambar 66 Detail Laporan .................................................................................................................... 35
Gambar 67 Riwayat Laporan ................................................................................................................ 35
Gambar 68 Peta ..................................................................................................................................... 35
Gambar 69 Notifikasi petugas............................................................................................................... 36
Gambar 70 Profil ................................................................................................................................... 36
Gambar 71 Edit Profil ........................................................................................................................... 36
Gambar 72 Laporan Masyarakat ........................................................................................................... 37
Gambar 73 Login Admin ...................................................................................................................... 37
Gambar 74 Dashboard Admin .............................................................................................................. 37
Gambar 75 Laporan Petugas ................................................................................................................. 38
Gambar 76 Manajemen Fasilitas ........................................................................................................... 38
Gambar 77 Manajemen Masyarakat ..................................................................................................... 38
Gambar 78 Manajemen Petugas ........................................................................................................... 39
Gambar 79 Manajemen Pos Pantau ...................................................................................................... 39
Gambar 80 Manajemen Wilayah .......................................................................................................... 39
Gambar 81 Peta ..................................................................................................................................... 40
Gambar 82 Rekap Laporan ................................................................................................................... 40
Gambar 83 Rating Quisioner ................................................................................................................ 51
Gambar 84 Usia Peserta Responden ..................................................................................................... 51
Gambar 85 Kritik dan Saran Responden ............................................................................................... 51
vi

ABSTRAK
Banjir merupakan tantangan utama di wilayah perkotaan yang memerlukan respons cepat dan data yang
akurat. Makalah ini membahas pengembangan aplikasi "SiAGA Banjir", sebuah sistem monitoring dan
peringatan dini yang dikembangkan oleh Kelompok 8 IT Bootcamp Next Gen. Aplikasi ini dirancang
untuk menjembatani kesenjangan informasi antara kondisi pintu air dan dampak nyata di pemukiman
warga.
Sistem ini dibangun menggunakan arsitektur multi-platform, di mana sisi backend dikembangkan
menggunakan kerangka kerja Laravel yang berfungsi sebagai API, sedangkan antarmuka pengguna
(Frontend) untuk Petugas dan Masyarakat berbasis aplikasi mobile menggunakan Flutter, dan
antarmuka Administrator berbasis website untuk kemudahan pengelolaan data. Fitur unggulan meliputi
pemantauan status pintu air (Normal, Siaga, Awas), pelaporan warga berbasis lokasi (GPS) dan bukti
foto real-time, serta manajemen fasilitas bencana seperti dapur umum dan zona pengungsian. Hasil
pengujian menunjukkan sistem mampu memvalidasi laporan dan memvisualisasikan wilayah potensi
banjir melalui peta interaktif, yang diharapkan dapat meningkatkan efisiensi mitigasi bencana.
Kata Kunci: SiAGA Banjir, Laravel, Flutter, Peringatan Dini, Manajemen Bencana, GPS.
vii

FASE 1
RISET (PENELITIAN) DAN ANALISIS
1.1 Latar Belakang
Bencana banjir merupakan permasalahan tahunan yang kerap melanda wilayah perkotaan dan
menimbulkan kerugian material maupun non-material yang signifikan. Salah satu kendala utama dalam
mitigasi bencana ini adalah keterlambatan penyebaran informasi mengenai kenaikan debit air di pintu
air serta kurangnya data real-time mengenai kondisi genangan di pemukiman warga. Seringkali,
informasi status siaga bersifat satu arah dan tidak terintegrasi langsung dengan kondisi riil di lapangan,
sehingga menyulitkan pengambilan keputusan evakuasi yang cepat dan tepat.
Sebagai respons terhadap permasalahan tersebut, Kelompok 8 IT Bootcamp Next Gen
mengembangkan aplikasi "SiAGA Banjir" (Sistem Monitoring Peringatan Dini dan Antisipasi Banjir).
Pengembangan sistem ini sempat mempertimbangkan penggunaan sensor Internet of Things (IoT)
untuk pengukuran debit air otomatis, namun karena keterbatasan waktu dan perangkat keras, tim
memutuskan untuk fokus pada pengembangan perangkat lunak yang mengintegrasikan input data
manual dari petugas dengan laporan partisipatif masyarakat. Solusi ini dirancang untuk menjembatani
kesenjangan informasi dengan memvalidasi data lapangan melalui fitur foto real-time dan pelacakan
lokasi berbasis GPS guna meminimalisir laporan palsu.
Secara teknis, sistem ini dibangun dengan arsitektur multi-platform untuk memenuhi kebutuhan
tiga peran pengguna utama: Admin Pusat, Petugas, dan Masyarakat. Sisi backend dan manajemen
administrator dikembangkan berbasis website menggunakan kerangka kerja Laravel untuk
memudahkan pengelolaan data yang kompleks , sementara antarmuka untuk petugas dan masyarakat
dikembangkan berbasis aplikasi mobile menggunakan Flutter agar responsif dan mudah diakses saat
kondisi darurat. Proyek ini dikerjakan sebagai syarat penyelesaian program bootcamp, dengan harapan
dapat menghasilkan purwarupa sistem peringatan dini yang efektif dan siap dikembangkan lebih lanjut.
1

1.2 Tujuan
Tujuan dari pengembangan aplikasi SiAGA Banjir dirinci sebagai berikut:
A. Penyediaan Informasi dan Peringatan Dini
1. Menyajikan status ketinggian air (Normal, Siaga, Awas) secara real-time kepada
masyarakat.
2. Memberikan visualisasi wilayah yang berpotensi terdampak banjir melalui peta
interaktif.
3. Menyebarkan notifikasi status bahaya kepada pengguna yang berada di wilayah
terdampak
B. Integrasi dan Validasi Data Lapangan
1. Mengintegrasikan laporan teknis dari petugas pintu air dengan laporan visual dari
masyarakat.
2. Menerapkan validasi lokasi berbasis koordinat (Latitude/Longitude) untuk
memastikan keaslian laporan.
3. Menyediakan fitur unggah foto real-time (bukan galeri) untuk mencegah
manipulasi data kejadian.
C. Manajemen Fasilitas dan Penanggulangan Bencana
1. Menyediakan informasi lokasi fasilitas penting seperti Dapur Umum dan Zona
Pengungsian.
2. Memudahkan Admin Pusat dalam mengelola data pos pantau (Stations) dan data
wilayah (Regions).
3. Memfasilitasi respons cepat melalui tombol "Emergency Banjir" bagi warga yang
membutuhkan pertolongan segera.
D. Efisiensi Operasional Petugas dan Admin
1. Memisahkan antarmuka Admin ke platform web untuk memudahkan pengelolaan
tabel data yang kompleks.
2. Menyediakan dashboard khusus bagi petugas untuk memantau tugas dan riwayat
laporan mereka.
3. Mengotomatisasi perubahan status wilayah berdasarkan laporan ketinggian air
yang diinput oleh petugas.
E. Pengembangan Teknis dan Edukasi
1. Mengimplementasikan arsitektur backend Laravel sebagai API yang aman dan
terstruktur.
2. Mengembangkan antarmuka frontend Flutter yang responsif dan mudah
digunakan.
2

3. Menjadi sarana pembelajaran kolaboratif bagi tim pengembang dalam siklus hidup
pengembangan perangkat lunak (SDLC).
1.3 Harapan
A. Seperti apa tampilan dan nuansa yang Anda harapkan?
Aplikasi diharapkan memiliki antarmuka yang bersih, intuitif, dan responsif.
1. Untuk Masyarakat & Petugas (Mobile): Tampilan harus sederhana dan fokus pada
kecepatan akses, terutama untuk fitur pelaporan darurat dan peta interaktif, mengingat
penggunaannya seringkali dalam kondisi mendesak.
2. Untuk Admin (Web): Tampilan dashboard harus informatif dan terorganisir dalam
bentuk tabel (datatable) yang memudahkan manajemen data dalam jumlah besar tanpa
harus melakukan scrolling horizontal yang berlebihan.
B. Bagaimana dan apa yang akan dihasilkan situs web untuk bisnis Anda?
Situs web (khusus Admin) akan menghasilkan pusat komando yang efektif.
1. Situs ini akan menjadi pusat validasi data yang masuk dari ribuan pengguna mobile.
2. Menghasilkan laporan rekapitulasi kejadian banjir yang terstruktur untuk keperluan
analisis pasca-bencana.
3. Memastikan integritas data petugas dan pos pantau melalui fitur manajemen pengguna
yang terpusat.
C. Fitur apa yang diharapkan pengguna dari aplikasi semacam itu?
Pengguna (Warga) mengharapkan aplikasi yang tidak hanya informatif tetapi juga partisipatif.
1. Akurasi Lokasi: Pengguna mengharapkan peta yang menunjukkan titik banjir dan
fasilitas terdekat secara presisi.
2. Kemudahan Pelaporan: Proses pelaporan yang tidak berbelit-belit, cukup dengan foto
dan lokasi otomatis.
3. Info Fasilitas: Informasi mengenai di mana lokasi dapur umum dan pengungsian saat
darurat.
D. Apa sajakah fitur yang ingin Anda sertakan?
Berdasarkan perancangan final dan diskusi tim, fitur utama meliputi:
1. Interactive Map untuk sebaran wilayah potensi banjir dan fasilitas bencana.
2. Manajemen Status Pintu Air (Normal, Siaga, Awas) dengan ambang batas yang dapat
diatur.
3. Validasi Laporan Petugas oleh Admin untuk menjaga kualitas data.
4. Fitur Profil Warga yang memungkinkan pengubahan data wilayah domisili untuk
notifikasi yang relevan.
3

FASE 2
PERENCANAAN DAN STRATEGI
2.1 Analisa Kebutuhan
Berdasarkan hasil riset dan analisis kebutuhan, sistem SiAGA Banjir dirancang sebagai solusi
terintegrasi untuk monitoring dan peringatan dini banjir. Implementasi sistem melibatkan 3
komponen utama.
A. Teknologi dan Platform
Backend (Laravel 10)
1. Framework: Laravel 10 dengan PHP 8.2+
2. Database: MySQL/SQLite dengan 9 tabel utama
3. API: 48 RESTful endpoints dengan Laravel Sanctum authentication
4. Real-time Notifications: Firebase Cloud Messaging (FCM)
5. File Storage: Laravel Storage untuk foto/bukti
6. Base URL: http://localhost:8000/api
Frontend Mobile (Flutter)
1. Framework: Flutter 3.9.2 (Cross-platform: Android & iOS)
2. State Management: Provider pattern
3. Secure Storage: FlutterSecureStorage untuk token
4. Maps Integration: Google Maps Flutter
5. Image Handling: Image Picker & Cached Network Image
6. HTTP Client: Dio/HTTP package
B. Arsitektur Sistem
Role-Based Access Control (3 Role)
1. Admin (Super User): Dashboard analytics, management stasiun, validasi laporan,
broadcast notifikasi
2. Petugas Lapangan: Lapor kondisi stasiun (water level, rainfall, pump status),
upload bukti foto
3. Masyarakat (Public): Lapor banjir, emergency SOS, monitoring peta, riwayat
laporan, notifikasi
Database Schema (9 Tabel)
1. users - Data pengguna semua role
2. regions - Wilayah/kecamatan
3. stations - Pos pantau/stasiun
4. officer_reports - Laporan dari petugas
5. public_reports - Laporan dari masyarakat
6. notifications - Sistem notifikasi
7. notification_settings_rules - Template notifikasi
8. station_user - Penugasan petugas ke stasiun
9. password_reset_tokens - Reset password
Fitur Utama yang Diimplementasikan
Untuk Masyarakat:
1. Dashboard dengan info wilayah real-time
2. Pelaporan banjir (lokasi GPS + foto)
4

3. Tombol Emergency SOS (prioritas tinggi)
4. Riwayat laporan dengan status tracking
5. Notifikasi peringatan dini
6. Peta monitoring stasiun
7. Filter status wilayah (Normal/Siaga/Awas)
Untuk Petugas:
1. Dashboard statistik pribadi
2. Laporan kondisi stasiun (data teknis):
a. Water level (cm)
b. Rainfall (mm)
c. Pump status (Normal/Siaga/Awas)
3. Upload foto bukti (wajib)
4. Riwayat laporan dengan validasi status
5. Info stasiun yang ditugaskan
Untuk Admin:
1. Dashboard analytics lengkap
2. Management CRUD stasiun
3. Management petugas & penugasan
4. Validasi laporan (Approve/Reject)
5. Broadcast notifikasi ke wilayah
6. Rekapitulasi laporan per periode
7. Export data (Excel/PDF)
8. Monitoring flood potential
2.2 Usecase Diagram
Use Case Diagram digunakan untuk menggambarkan interaksi antara pengguna dan sistem
SiAGA Banjir berdasarkan peran masing-masing pengguna. Diagram ini menunjukkan fungsi-
fungsi utama sistem yang dapat diakses oleh aktor serta batasan tanggung jawab setiap aktor
dalam sistem.
Dalam sistem SiAGA Banjir terdapat tiga aktor utama, yaitu Warga, Petugas, dan Admin.
Masing-masing aktor memiliki hak akses dan kebutuhan yang berbeda sesuai perannya.
Gambar 1 Use Case Diagram
5

Deskripsi Use Case Diagram:
Table 2.1 Use Case Login
Use Case Name Login
Use Case id 01
Actor Warga, Petugas, Admin
Description Menggambarkan proses pengguna melakukan login untuk mengakses
fitur sistem sesuai dengan hak akses masing-masing.
Precondition Pengguna, petugas, admin sudah memiliki akun.
Normal Flow 1. Pengguna membuka halaman login.
2. Pengguna memasukkan username dan password.
3. Sistem memverifikasi kredensial pengguna.
4. Sistem membuat sesi login.
5. Sistem mengarahkan pengguna ke dashboard sesuai peran.
Expection Username atau password salah, login gagal
Table 2.2 Use Case Lapor Kejadian Banjir
Use Case Name Lapor Kejadian Banjir
Use Case id 02
Actor Warga
Description Digunakan oleh warga untuk melaporkan kejadian banjir di wilayahnya.
Precondition Warga telah login
Normal Flow 1. Warga memilih menu lapor banjir.
2. Warga mengisi data lokasi dan kondisi banjir.
3. Warga mengirim laporan.
4. Sistem menyimpan laporan ke database.
Expection Data laporan tidak lengkap atau lokasi tidak valid
Tabel 2. 3 Use Case Kirim Sinyal Sos
Use Case Name Kirim Sinyal Sos
Use Case id 03
Actor Warga
Description Digunakan oleh warga untuk mengirim sinyal darurat saat berada dalam
kondisi berbahaya akibat banjir.
Precondition Warga telah login dan berada dalam kondisi darurat
Normal Flow 1. Warga memilih menu SOS.
2. Sistem mengambil lokasi pengguna.
3. Sistem mengirim sinyal darurat ke petugas dan admin.
Expection Lokasi tidak terdeteksi atau koneksi tidak stabil
Table 2.4 Use Case Cari Lokasi Pengungsi
Use Case Name Cari Lokasi Pengungsi
Use Case id 04
Actor Warga
Description Menyediakan informasi lokasi pengungsian yang tersedia dan aman bagi
warga terdampak banjir.
Precondition Warga telah login.
Normal Flow 1. Warga memilih menu lokasi pengungsian.
2. Sistem menampilkan daftar lokasi pengungsian.
Expection Data pengungsian belum tersedia.
Table 2.5 Use Case Melihat Status Pintu Air
Use Case Name Melihat Status Pintu Air
Use Case id 05
Actor Warga, Petugas
6

Description Digunakan untuk memantau kondisi pintu air secara real-time sebagai
indikator potensi banjir.
Precondition Warga, petugas sudah login.
Normal Flow 1. Pengguna memilih menu status pintu air.
2. Sistem menampilkan kondisi pintu air.
Expection Data sensor tidak tersedia atau belum diperbarui.
Table 2.6 Use Case Input Laporan
Use Case Name Input Laporan
Use Case id 06
Actor Petugas
Description Digunakan petugas untuk memasukan laporan hasil pantauan di
lapangan.
Precondition petugas sudah login.
Normal Flow 1. Petugas memilih menu input laporan.
2. Petugas mengisi data laporan.
3. Sistem mengirim laporan ke admin.
Expection Data laporan tidak lengkap.
Table 2.7 Use Case Validasi Laporan
Use Case Name Validasi Laporan
Use Case id 07
Actor Admin
Description Proses validasi laporan dari warga dan petugas dan diterima oleh admin.
Precondition Laporan telah dibuat.
Normal Flow 1. Admin menerima notifikasi laporan.
2. Admin membuka laporan.
3. Admin memeriksa laporan.
4. Admin memberikan status sesuai dengan laporan yang diterima.
Expection Laporan tidak sesuai dan laporan ditolak.
Table 2.8 Use Case Kelola Data
Use Case Name Kelola data
Use Case id 07
Actor Admin
Description Admin mengelola seluruh data.
Precondition Admin telah login.
Normal Flow 5. Admin memilih menu manajemen data.
6. Admin dapat mengubah, menghapus, mengedit data.
7. Sistem menyimpan perubahan yang dilakukan oleh admin.
Expection Data tidak valid dan gagal disimpan.
2.3 Entity Relationship Diagram (ERD)
Entity Relationship Diagram atau ERD digunakan untuk menggambarkan struktur data dan
hubungan antar tabel yang mendukung proses pelaporan, pemantauan, serta validasi kejadian
banjir. ERD ini akan menunjukkan bagaimana data pengguna, wilayah, laporan, dan stasiun
7

pemantauan saling terhubung sehingga informasi dapat dikelola secara terintegrasi dan
konsisten.
Gambar 2 ERD Sistem Monitoring Peringatan Dini dan Antipasi Banjir
Relasi antar table:
1. Regions 1 : M Users
Satu wilayah (region) dapat memiliki banyak pengguna yang tinggal didalamnya.
Setiap user hanya terdaftar pada satu region.
2. Regions 1 : M Stations
Satu region dapat memiliki banyak stasiun pemantauan banjir. Setiap station hanya
terhubung dengan satu region.
3. Users 1 : M Public_Reports
Satu user (warga) dapat membuat banyak laporan kejadian banjir. Setiap public report
hanya dibuat oleh satu user.
4. Users 1 : M Officer_Reports
Satu user dengan peran petugas dapat membuat banyak laporan hasil pemantauan
lapangan. Setiap officer report hanya dibuat oleh satu user.
5. Stations 1 : M Officer_Reports
Satu station dapat memiliki banyak laporan petugas yang berkaitan dengan kondisi
stasiun tersebut. Setiap officer report hanya terkait dengan satu station.
6. Users 1 : M Officer_Reports (Validasi)
Satu user (petugas atau admin) dapat melakukan validasi terhadap banyak laporan
petugas. Setiap laporan petugas hanya divalidasi oleh satu user.
2.4 Logical Record Structure (LRS)
Logical Relational Structure (LRS) merupakan representasi dari struktur tabel-tabel relasional
yang terbentuk berdasarkan hasil perancangan Entity Relationship Diagram (ERD). LRS ini
menggambarkan bagaimana data disusun ke dalam tabel beserta atribut-atributnya untuk
mendukung proses pengelolaan data dalam sistem.
8

Gambar 3 LRS Sistem Monitoring Peringatan Dini dan Antisipasi Banjir
Berikut adalah Logical Relational Structure (LRS) dari Perancangan Sistem SiAGA Banjir yang
digunakan  untuk  mendukung  proses  pelaporan,  pemantauan,  dan  pengelolaan  informasi
kebencanaan secara terintegrasi.
2.5 Spesifikasi File
Untuk memberikan gambaran secara detail isi dari tabel-tabel yang dipergunakan dalam
Sistem Monitoring Peringatan Dini dan Antisipasi Banjir, berikut struktur table dengan rincian data
item:
1.  Spesifikasi File Users
Nama file: user
Akronim: tabel_users.MYD
Tipe file: File Master
Access file: Random
Panjang record: 2180 Karakter
Field key: id
Software: MySQL
 Table 2.9 Spesifikasi File Users
| No  Element  | Nama Field  | Type  | Size  Keterangan  |
| ------------ | ----------- | ----- | ----------------- |
Data
| 1  Id User  | id  | BigInt  | 20  Primary Key, Auto  |
| ----------- | --- | ------- | ---------------------- |
Increment
| 2  Name  | name  | Varchar  | 255  Nama lengkap  |
| -------- | ----- | -------- | ------------------ |
pengguna
| 3  Email     | email     | Varchar  | 255  Email pengguna        |
| ------------ | --------- | -------- | -------------------------- |
| 4  Username  | username  | Varchar  | 255  Username login        |
| 5  Phone     | phone     | Varchar  | 255  Nomor telepon         |
| 6  Password  | password  | Varchar  | 255  Password terenkripsi  |
| 7  Role      | role      | Enum     | –  Hak akses (admin,       |
petugas, public)
| 8  Nomor  | nomor_induk  | Varchar  | 255  Nomor induk petugas  |
| --------- | ------------ | -------- | ------------------------- |
9

| No  Element  | Nama Field  | Type  | Size  | Keterangan  |
| ------------ | ----------- | ----- | ----- | ----------- |
Data
Induk
| 9  Region Id      | region_id      | BigInt     | 20   | FK → tabel regions     |
| ----------------- | -------------- | ---------- | ---- | ---------------------- |
| 10  Notification  | notification_c | Varchar    | 255  | Kanal notifikasi user  |
| Channel           | hannel         |            |      |                        |
| 11  Photo         | photo          | Varchar    | 255  | Foto profil            |
| 12  Remember      | remember_to    | Varchar    | 100  | Token autentikasi      |
| Token             | ken            |            |      |                        |
| 13  Created At    | created_at     | Timestamp  | –    | Waktu pembuatan        |
data
| 14  Updated At  | updated_at  | Timestamp  | –   | Waktu update  |
| --------------- | ----------- | ---------- | --- | ------------- |
terakhir

2.  Spesifikasi File Stations
Nama file: stations
Akronim: tabel_stations.MYD
Tipe file: File Master
Access file: Random
Panjang record: 1040 Karakter
Field key: id
Software: MySQL
|              |        Table 2.10 Spesifikasi File Stations  |       |       |             |
| ------------ | -------------------------------------------- | ----- | ----- | ----------- |
| No  Element  | Nama Field                                   | Type  | Size  | Keterangan  |
Data
| 1  Id Station    | id            | BigInt   | 20   | Primary Key        |
| ---------------- | ------------- | -------- | ---- | ------------------ |
| 2  Kode Stasiun  | station_code  | Varchar  | 255  | Kode unik stasiun  |
| 3  Nama          | name          | Varchar  | 255  | Nama stasiun       |
Stasiun
| 4  Lokasi       | location            | Varchar  | 255   | Alamat stasiun      |
| --------------- | ------------------- | -------- | ----- | ------------------- |
| 5  Latitude     | latitude            | Decimal  | 10,8  | Koordinat           |
| 6  Longitude    | longitude           | Decimal  | 11,8  | Koordinat           |
| 7  Water Level  | water_level         | Decimal  | 8,2   | Ketinggian air      |
| 8  Status       | status              | Enum     | –     | normal/siaga/awas   |
| 9  Status       | operational_status  | Enum     | –     | active/non-         |
| Operasional     |                     |          |       | active/maintenance  |
| 10  Deskripsi   | description         | Varchar  | 255   | Keterangan          |
| 11  Threshold   | threshold_siaga     | Int      | 11    | Ambang siaga        |
Siaga
| 12  Threshold  | threshold_awas  | Int  | 11  | Ambang awas  |
| -------------- | --------------- | ---- | --- | ------------ |
Awas
| 13  Last Update  | last_update  | Timestamp  | –   | Update sensor  |
| ---------------- | ------------ | ---------- | --- | -------------- |
terakhir
| 14  Created At  | created_at  | Timestamp  | –   | Tanggal input      |
| --------------- | ----------- | ---------- | --- | ------------------ |
| 15  Updated At  | updated_at  | Timestamp  | –   | Tanggal perubahan  |

3.  Spesifikasi File Regions
Nama file: regions
Akronim: tabel_regions.MYD
Tipe file: File Master
Access file: Random
Panjang record: 785 Karakter
Field key: id
10

Software: MySQL

                      Table 2.11 Spesifikasi File Regions
| No  Element Data  | Nama Field    | Type       | Size                  | Keterangan  |
| ----------------- | ------------- | ---------- | --------------------- | ----------- |
| 1  Id Region      | id            | BigInt     | 20  Primary Key       |             |
| 2  Nama Wilayah   | name          | Varchar    | 255  Nama wilayah     |             |
| 3  Foto           | photo         | Varchar    | 255  Foto wilayah     |             |
| 4  Lokasi         | location      | Varchar    | 255  Alamat           |             |
| 5  Latitude       | latitude      | Decimal    | 10,8  Koordinat       |             |
| 6  Longitude      | longitude     | Decimal    | 11,8  Koordinat       |             |
| 7  Flood Status   | flood_status  | Enum       | –  normal/siaga/awas  |             |
| 8  Risk Note      | risk_note     | Text       | –  Catatan risiko     |             |
| 9  Created At     | created_at    | Timestamp  | –  Tanggal input      |             |
| 10  Updated At    | updated_at    | Timestamp  | –  Tanggal perubahan  |             |

4.  Spesifikasi File Public_Reports
Nama file: public_reports
Akronim: tabel_public_reports.MYD
Tipe file: File transaksi
Access file: Random
Panjang record: 805 Karakter
Field key: id
Software: MySQL
                               Table 2.12 Spesifikasi Public_Reports
| No  Element  | Nama Field  | Type  | Size  | Keterangan  |
| ------------ | ----------- | ----- | ----- | ----------- |
Data
| 1  Id Report  | id           | BigInt   | 20  Primary Key    |     |
| ------------- | ------------ | -------- | ------------------ | --- |
| 2  Kode       | report_code  | Varchar  | 255  Kode laporan  |     |
Laporan
| 3  User Id      | user_id      | BigInt   | 20  FK → users              |     |
| --------------- | ------------ | -------- | --------------------------- | --- |
| 4  Lokasi       | location     | Varchar  | 255  Lokasi laporan         |     |
| 5  Latitude     | latitude     | Decimal  | 10,8  Koordinat             |     |
| 6  Longitude    | longitude    | Decimal  | 11,8  Koordinat             |     |
| 7  Water Level  | water_level  | Decimal  | 8,2  Ketinggian air         |     |
| 8  Foto         | photo        | Varchar  | 255  Foto kondisi           |     |
| 9  Note         | note         | Text     | –  Catatan user             |     |
| 10  Admin Note  | admin_note   | Text     | –  Catatan admin            |     |
| 11  Status      | status       | Enum     | –  pending/diproses/selesai |     |
/emergency
| 12  Validated  | validated_by  | BigInt  | 20  Admin validator  |     |
| -------------- | ------------- | ------- | -------------------- | --- |
By
| 13  Created At  | created_at  | Timestamp  | –  Tanggal input      |     |
| --------------- | ----------- | ---------- | --------------------- | --- |
| 14  Updated At  | updated_at  | Timestamp  | –  Tanggal perubahan  |     |

Spesifikasi File Officer_Reports
5.
Nama file: officer_reports
Akronim: tabel_officer_reports.MYD
Tipe file: File transaksi
Access file: Random
Panjang record: 1100 Karakter
Field key: id
Software: MySQL

11

                                 Table 2.13 Spesifikasi File Officer_Reports
| No  Element  | Nama Field  |     |     | Type  | Size  | Keterangan  |
| ------------ | ----------- | --- | --- | ----- | ----- | ----------- |
Data
| 1  Id    | id           |     |     | BigInt   | 20   | Primary Key   |
| -------- | ------------ | --- | --- | -------- | ---- | ------------- |
| 2  Kode  | report_code  |     |     | Varchar  | 255  | Kode laporan  |
Laporan
| 3  Officer Id  | officer_id   |     |          | BigInt  | 20   | FK → users     |
| -------------- | ------------ | --- | -------- | ------- | ---- | -------------- |
| 4  Station Id  | station_id   |     |          | BigInt  | 20   | FK → stations  |
| 5  Water       | water_level  |     | Decimal  |         | 8,2  | Level air      |
Level
| 6  Rainfall  | rainfall     |     |     | Varchar  | 255  | Curah hujan   |
| ------------ | ------------ | --- | --- | -------- | ---- | ------------- |
| 7  Pump      | pump_status  |     |     | Varchar  | 255  | Status pompa  |
Status
| 8  Calculated  | calculated_status  |     |     | Enum  | –   | normal/siaga/awas  |
| -------------- | ------------------ | --- | --- | ----- | --- | ------------------ |
Status
| 9  Photo   | photo       |     |     | Varchar  | 255  | Foto lokasi      |
| ---------- | ----------- | --- | --- | -------- | ---- | ---------------- |
| 10  Note   | note        |     |     | Text     | –    | Catatan petugas  |
| 11  Admin  | admin_note  |     |     | Text     | –    | Catatan admin    |
Note
| 12  Validation  | validation_status  |     |     | Enum  | –   | pending/approved/rejected  |
| --------------- | ------------------ | --- | --- | ----- | --- | -------------------------- |
Status
| 13  Validated  | validated_by  |     |     | BigInt  | 20  | Admin  |
| -------------- | ------------- | --- | --- | ------- | --- | ------ |
By
| 14  Created At  | created_at  |     | Timestamp  |     | –   | Tanggal input      |
| --------------- | ----------- | --- | ---------- | --- | --- | ------------------ |
| 15  Updated     | updated_at  |     | Timestamp  |     | –   | Tanggal perubahan  |
At

6.  Spesifikasi File Notifications
Nama file: notifications
Akronim: tabel_notifications.MYD
Tipe file: File transaksi
Access file: Random
Panjang record: 550 Karakter
Field key: id
Software: MySQL

                                     Table 2.14 Spesifikasi File Notifications
| No  Element Data  |     | Nama Field  |     | Type       | Size  | Keterangan        |
| ----------------- | --- | ----------- | --- | ---------- | ----- | ----------------- |
| 1  Id Notif       |     | id          |     | BigInt     | 20    | Primary Key       |
| 2  User Id        |     | user_id     |     | BigInt     | 20    | FK → users        |
| 3  Title          |     | title       |     | Varchar    | 255   | Judul             |
| 4  Message        |     | message     |     | Text       | –     | Isi notifikasi    |
| 5  Type           |     | type        |     | Varchar    | 255   | Jenis notifikasi  |
| 6  Data           |     | data        |     | Json       | –     | Data tambahan     |
| 7  Read At        |     | read_at     |     | Timestamp  | –     | Status dibaca     |
| 8  Created At     |     | created_at  |     | Timestamp  | –     | Tanggal kirim     |
| 9  Updated At     |     | updated_at  |     | Timestamp  | –     | Tanggal update    |

7.  Spesifikasi File Disaster_Facilities
Nama file: disaster_facilities
Akronim: tabel_disaster_facilities.MYD
Tipe file: File master
12

Access file: Random
Panjang record: 550 Karakter
Field key: id
Software: MySQL

                                       Table 2.15 Spesifikasi File Disaster_Facilities
| No  Element  | Nama   | Type     | Size  |                 | Keterangan  |
| ------------ | ------ | -------- | ----- | --------------- | ----------- |
| Data         | Field  |          |       |                 |             |
| 1  Id        | id     | BigInt   | 20    | Primary Key     |             |
| 2  Unique    | unique | Varchar  | 20    | Kode unik       |             |
| Code         | _code  |          |       |                 |             |
| 3  Nama      | name   | Varchar  | 255   | Nama fasilitas  |             |
Fasilitas
| 4  Type  | type  | Enum  | –   | pengungsian/dapur_umum/posko_k |     |
| -------- | ----- | ----- | --- | ------------------------------ | --- |
esehatan/logistik
| 5  Status    | status    | Enum       | –     | buka/tutup/penuh  |     |
| ------------ | --------- | ---------- | ----- | ----------------- | --- |
| 6  Address   | address   | Text       | –     | Alamat            |     |
| 7  Latitude  | latitude  | Decimal    | 10,8  | Koordinat         |     |
| 8  Longitud  | longitu   | Decimal    | 11,8  | Koordinat         |     |
| e            | de        |            |       |                   |     |
| 9  Photo     | photo_    | Varchar    | 255   | Foto              |     |
| Path         | path      |            |       |                   |     |
| 10  Notes    | notes     | Text       | –     | Catatan           |     |
| 11  Created  | created   | Timestamp  | –     | Tanggal input     |     |
| At           | _at       |            |       |                   |     |
| 12  Updated  | updated   | Timestamp  | –     | Tanggal update    |     |
| At           | _at       |            |       |                   |     |

8.  Spesifikasi File Station_User
Nama file: station_user
Akronim: tabel_station_user.MYD
Tipe file: File Transaksix
Access file: Random
Panjang record: 60 Karakter
Field key: id
Software: MySQL
                                        Table 2.16 Spesifikasi File Station_Users
| No  Element Data  | Nama Field  |     | Type       | Size  | Keterangan                 |
| ----------------- | ----------- | --- | ---------- | ----- | -------------------------- |
| 1  Id Relasi      | id          |     | BigInt     | 20    | Primary Key                |
| 2  User Id        | user_id     |     | BigInt     | 20    | FK → users.id              |
| 3  Station Id     | station_id  |     | BigInt     | 20    | FK → stations.id           |
| 4  Created At     | created_at  |     | Timestamp  | –     | Tanggal relasi dibuat      |
| 5  Updated At     | updated_at  |     | Timestamp  | –     | Tanggal relasi diperbarui  |

Spesifikasi File Region_Station
9.
Nama file: region_station
Akronim: tabel_region_station.MYD
Tipe file: File transaksi
Access file: Random
Panjang record: 82 Karakter
Field key: id
Software: MySQL

13

Table 2.17 Spesifikasi File Region_Station
| No  Element Data  | Nama Field  | Type    | Size  Keterangan     |
| ----------------- | ----------- | ------- | -------------------- |
| 1  Id Relasi      | id          | BigInt  | 20  Primary Key      |
| 2  Region Id      | region_id   | BigInt  | 20  FK → regions.id  |
FK → stations.id
| 3  Station Id   | station_id           | BigInt     | 20                   |
| --------------- | -------------------- | ---------- | -------------------- |
| 4  Impact       | impact_percentage    | Int        | 11  Persentase       |
| Percentage      |                      |            | dampak wilayah       |
| 5  Travel Time  | travel_time_minutes  | Int        | 11  Waktu tempuh ke  |
| Minutes         |                      |            | wilayah              |
| 6  Created At   | created_at           | Timestamp  | –  Tanggal relasi    |
dibuat
| 7  Updated At  | updated_at  | Timestamp  | –  Tanggal relasi  |
| -------------- | ----------- | ---------- | ------------------ |
diperbarui

14

FASE 3
DESAIN DAN WIREFRAME
3.1 Wireframe
Wireframe adalah kerangka dasar yang menampilkan tata letak elemen-elemen utama pada
halaman aplikasi atau sebuah website. Tujuan dari Wireframe yaitu untuk memberikan gambaran
awal tentang aplikasi atau website yang akan kita buat.
Berikut adalah bebrapa wireframe dari Sistem Monitoring Peringatan Dini dan Antisipasi Banjir:
1. Wireframe untuk Warga
Memberikan gambaran dari wireframe tampilan untuk user warga biasa:
Gambar 4 Gambar 5 Profil
Wireframe Login user
Gambar 6 Wireframe Gambar 7 Wireframe
Lapor Banjir Lapor Peta
15

Gambar 9 Wireframe Gambar 8 Wireframe
Notifikasi Riwayat
2. Wireframe untuk petugas
Memberikan gambaran dari wireframe tampilan petugas:
Gambar 13 Wireframe Gambar 11 Wireframe
Login Beranda
Gambar 12 Wireframe Gambar 10 Wireframe
Peta Riwayat
16

Gambar 17 Wireframe Gambar 16 Wireframe Edit
Detail Profil Petugas Profil
Gambar 14 Wireframe Gambar 15 Wireframe
Buat laporan Laporan
4. Wireframe untuk admin
Memberikan sebagian gambaran tampilan wireframe dari admin:
Gambar 18 Wireframe Login
17

Gambar 19 Wireframe Dashboard
Gambar 20 Wireframe Potensi Banjir
Gambar 21 Wireframe Laporan Masyarakat
18

Gambar 22 Wireframe Manajemen Pos Pantau dan Wireframe Manajemen Wilayah
Gambar 23 Wireframe Rekap Laporan
3.2 Prototype
Prototype adalah simulasi gambaran bagaimana user berinteraksi dengan website secara nyata,
dengan menggunakan alat desain digital seperti figma untuk melakukan desain prototype. Prototipe
ini adalah tahapan awal dalam pengembangan sistem yang digunakan untuk menggambarkan bentuk
dan alur kerja aplikasi secara visual sebelum sistem dikembangkan secara penuh. Prototipe ini
bertujuan untuk memberikan gambaran awal mengenai tampilan antarmuka, fungsi utama, serta
interaksi pengguna dengan sistem yang dirancang. Berikut adalah beberapa tampilan prototype dari
Sistem Monitoring Peringatan Dini dan Antisipasi Banjir:
1. Prototype user
Bagian tampilan dari beberapa halaman user:
Gambar 25 Halaman Gambar 24 Halaman
Login Dashboard
19

Gambar 27 Halaman Gambar 29
Peta Halaman Laporan
Gambar 26 Gambar 28
Halman Notifikasi Halaman Riwayat
2. Prototype Petugas
Bagian tampilan dari beberapa halaman petugas:
Gambar 31 Gambar 30
Halaman Login Halaman Beranda
20

Gambar 37 Halaman Gambar 36 Halaman Edit
Profil Profil
Gambar 34 Halaman Peta
Gambar 35 Buat
Laporan
Gambar 32 Halaman Gambar 33 Halaman
Detail Laporan Notifikasi
21

3. Prototype Admin
Memberikan gambaran bagaimana admin berinteraksi dengan halaman webnya, serta
memberikan tampilan prototype:
Gambar 38 Halaman Login Admin
Gambar 39 Halaman Dashboard
Gambar 40 Halaman Manajemen Akun Masyarakat
22

Gambar 41 Halaman Manajemen Fasilitas bencana
Gambar 42 Halaman Manajemen Petugas
Gambar 43 Halaman Manajemen Laporan Petugas
23

Gambar 44 Halaman Manajemen Pos Pantau
Gambar 45 Halaman Manajemen Wilayah
Gambar 46 Halaman Potensi Banjir
24

Gambar 47 Halaman Rekap Laporan
25

FASE 4
CONTENT CREATION
(PEMBUATAN KONTEN)
4.1 Typography
Tipografi pada aplikasi SiAGA Banjir menggunakan font Public Sans yang dirancang untuk
keterbacaan tinggi dan tampilan yang bersih. Penggunaan ukuran dan ketebalan huruf disusun
secara hierarkis untuk membedakan tingkat informasi, mulai dari judul utama, status penting,
hingga teks pendukung. Ukuran teks terbesar digunakan untuk menampilkan identitas aplikasi dan
informasi krusial seperti peringatan dini, sehingga mudah dikenali oleh pengguna. Ukuran
menengah digunakan untuk judul konten seperti laporan dan status wilayah, sedangkan ukuran
lebih kecil diterapkan pada isi teks dan keterangan tambahan agar informasi tetap ringkas dan
mudah dipahami. Dengan pengaturan tipografi ini, pengguna dapat menangkap informasi secara
cepat dan jelas, terutama dalam kondisi darurat.
Gambar 48 Typoraphy
4.2 Color Style
Gambar 49 Color Style
26

4.3 Gambar
Gambar 50 LOGO
Filosofi Logo Sistem Siaga Banjir:
Logo sistem SiAGA Banjir dirancang dengan konsep visual yang mencerminkan misi dan fungsi utama
aplikasi sebagai sistem monitoring peringatan dini dan antisipasi banjir. Logo ini memiliki beberapa
elemen kunci:
1. Elemen visual dari logo:
1) Bentuk Circular (Bulat):
a. Melambangkan kesatuan dan kolaborasi.
b. Menunjukkan sistem yang terintegrasi dan menyeluruh.
c. Merepresentasikan siklus monitoring yang berkelanjutan 24/7.
2) Awan Hujan dengan Tetesan Air:
a. Awan Abu-abu: Melambangkan cuaca buruk dan potensi hujan.
b. Tetesan Air: Menunjukkan curah hujan sebagai penyebab utama banjir.
c. Merepresentasikan sistem yang dapat memprediksi dan memantau kondisi cuaca.
3) Gelombang Air (Gradient Biru):
a. Biru Muda: Melambangkan status "Normal" - kondisi aman dan terkendali.
b. Biru Sedang: Melambangkan status "Siaga" - peringatan dini dan kewaspadaan.
c. Biru Tua: Melambangkan status "Awas" - kondisi darurat dan bahaya tinggi.
d. Gradasi bertingkat: Menunjukkan level ketinggian air yang bervariasi sesuai
threshold monitoring.
2. Makna Warna
1) Biru(Dominan):
a. Melambangkan air, kepercayaan, dan stabilitas.
b. Menunjukkan bahwa sistem informasi banjir ini dapat diandalkan dan
menghasilkan data yang akurat.
c. Merepresentasikan teknologi dan profesionalisme dalam penanganan bencana.
2) Abu-abu(Awan):
a. Melambangkan kesederhanaan dan netralitas.
b. Menunjukkan bahwa sistem informasi banjir ini mudah digunakan dan dipahami
semua kalangan.
c. Merepresentasikan kondisi cuaca yang perlu diwaspadai.
27

3) Putih(Background):
a. Melambangkan transparansi dan keterbukaan data.
b. Menunjukkan kesederhanaan interface dan kemudahan akses informasi.
c. Merepresentasikan harapan akan kondisi cerah setelah banjir.
3. Filosofi yang dapat diinterpretasikan:
1) Modernisasi dan Efisiensi:
a. Logo dengan desain minimalis modern mencerminkan solusi teknologi digital
untuk monitoring banjir.
b. Sistem real-time yang efisien dalam mencatat dan menyampaikan informasi
kehadiran.
2) Keakuratan dan Keandalan:
a. Gradasi warna biru yang terukur menunjukkan akurasi data ketinggian air.
b. Penggunaan teknologi GPS dan sensor yang memastikan keandalan data.
3) Keterbukaan dan Transparansi:
a. Desain terbuka dan sederhana menunjukkan sistem yang transparan.
b. Semua pihak (admin, petugas, masyarakat) dapat mengakses informasi sesuai
kewenangannya.
4) Kemajuan dan Inovasi:
a. Logo modern mencerminkan sistem yang terus berkembang dan berinovasi.
b. Integrasi teknologi mobile app (Flutter) dan backend (Laravel) untuk
meningkatkan motivasi pelaporan dan monitoring.
5) Keterhubungan dan Kolaborasi:
a. Bentuk circular menunjukkan sistem yang menghubungkan dan mempermudah
kolaborasi.
b. Koneksi antara Admin Pusat, Petugas Lapangan, dan Masyarakat dalam satu
ekosistem.
6) Peringatan Dini dan Antisipasi:
a. Awan hujan sebagai simbol early warning system.
b. Gelombang bertingkat sebagai visualisasi threshold monitoring (Normal-Siaga-
Awas).
4. Kesimpulan
Logo sistem SiAGA Banjir mencerminkan filosofi modernisasi, efisiensi, keakuratan,
keterbukaan, kemajuan, dan kolaborasi. Logo ini diharapkan dapat menarik perhatian pengguna
dan memberikan kesan bahwa aplikasi ini adalah solusi teknologi yang dapat diandalkan untuk
monitoring dan peringatan dini banjir, serta membantu dalam mitigasi dan respon cepat terhadap
bencana banjir di Indonesia.
28

4.4 Tombol dan Ikon
Gambar 51 Tombol dan Ikon
29

FASE 5
CODE AND DEVELOPMENT
(KODE DAN PENGEMBANGAN)
5.1 Tech Stack
Dalam mengembangkan Sistem Monitoring Peringatan Dini dan Antisipasi Banjir, kami
menggunakan beberapa tech stack diantaranya adalah:
a. Framework: Laravel 10 (Backend & Website Admin), Flutter (Aplikasi Mobile Android
& iOS)
b. Database: MySQL
c. Text Editor: Visual Studio Code
d. Bahasa Pemrograman: PHP
e. Server: Localhost (Local Development), Apache / Nginx (Web Server)
5.2 Tampilan Coding
1. Tampilan Coding Warga:
Gambar 53 Register Akun
Gambar 52 Login
30

Gambar 54 Dashboard
Gambar 55 Lapor Banjir
Gambar 56 Detail Laporan
31

Gambar 57 Riwayat Laporan
Gambar 58 Status Wilayah
Gambar 59 Peta
32

Gambar 60 Profil
Gambar 61 Edit Profil
Gambar 62 Notifikasi Warga
33

2. Tampilan Coding Petugas:
Gambar 64 Login Petugas
Gambar 65 Dashboard
Gambar 63 Buat laporan Petugas
34

Gambar 66 Detail Laporan
Gambar 67 Riwayat Laporan
Gambar 68 Peta
35

Gambar 69 Notifikasi petugas
Gambar 70 Profil
Gambar 71 Edit Profil
36

3. Tampilan Coding Admin:
Gambar 73 Login Admin
Gambar 74 Dashboard Admin
Gambar 72 Laporan Masyarakat
37

Gambar 75 Laporan Petugas
Gambar 76 Manajemen Fasilitas
Gambar 77 Manajemen Masyarakat
38

Gambar 78 Manajemen Petugas
Gambar 79 Manajemen Pos Pantau
Gambar 80 Manajemen Wilayah
39

Gambar 81 Peta
Gambar 82 Rekap Laporan
40

FASE 6
TESTING AND QUALITY ASSURANCE
(PENGUJIAN DAN JAMINAN KUALITAS)
6.1 Blackbox Testing
Table 6.1 Blackbox Testing ADMIN
Fungsi/Feature Hasil
No Input yang Diuji Langkah Uji Status
Admin Uji
1. setelah input
user&password ketika click
tombol login akan di
arahkan ke dashboard
Admin sesuai role
1. Login User ADMIN
2. harus muncul error user
2. Login user yang tidak
name & password salah atau
terdaftar
tidak terdaftar silahkan
3. Dashboard tampil contact administrator
setelah login
3. dashboard admin akan
4. Statistik Laporan menampilkan semua fitur
masyarakat dan petugas sesuai role setelah log in
1 Dashboard Admin Sesuai Lulus
5. Jumlah pos pantau 4. status statistik Laporan
active masyarakat dan petugas
tampil
6. Jumlah laporan yang
butuh di validasi 5. Jumlah pos pantau active
tampil
7. Perhitungan index resiko
6. jumlah Jumlah laporan
8. Data dashboard refresh
yang butuh di validasi tampil
7. Perhitungan index resiko
tampil
8. Data dashboard refresh
1. Admin dapat melihat
daftar user 1. Admin dapat melihat
detail daftar user
2. Admin dapat mengedit
data user 2. Admin dapat mengedit
detail data user
3. Admin dapat menghapus
2 Manajemen User Sesuai Lulus
user 3. Admin dapat menghapus
user
4. Validasi input user
kosong 4. akan menampilkan
informasi error jika user di
5. Username dan email kosongkan
tidak boleh duplikat
41

Fungsi/Feature Hasil
No Input yang Diuji Langkah Uji Status
Admin Uji
7. Username dan email tidak
boleh duplikat
1. Admin dapat menambah
1. Admin dapat menambah
lokasi potensi banjir
lokasi potensi banjir
2. Admin dapat mengedit
2. Admin dapat mengedit
lokasi potensi banjir
lokasi potensi banjir
3. Admin dapat menghapus
3. Admin dapat menghapus
lokasi potensi banjir
lokasi potensi banjir
Lulus
Manajemen Lokasi & 4. Lokasi yang berpotensi
3 4. Lokasi tampil di peta Sesuai
Peta banjir tampil di peta
5. Titik lokasi sesuai
5. Titik lokasi sesuai
koordinat
koordinat
6. Warna indikator sesuai
6. Warna indikator sesuai
status air
status air
7. Filter lokasi berdasarkan
7. Filter lokasi berdasarkan
status
status ketinggian air
1. Admin dapat
memperbaharui Status
1. Admin dapat
ketinggian air
memperbaharui Status
2. Admin dapat ketinggian air
mengaitkan nama pintu air
2. Admin dapat mengaitkan
ke lokasi wilayah potensi
nama pintu air ke lokasi
banjir
wilayah potensi banjir
Manajemen 3. Admin dapat mengedit
4 3. Admin dapat mengedit Sesuai Lulus
Ketinggian Air data pintu air
data pintu air
4. Data ketinggian air
4. Data ketinggian air tampil
tampil
5. Data berubah sesuai input
5. Data berubah sesuai
input 6. Status peringatan berubah
otomatis
6. Status peringatan
berubah otomatis
1. Admin dapat menambah
1. Admin dapat menambah
status fasilitas
status fasilitas
Manajemen fasilitias penanggulangan bencana
penanggulangan bencana
5 penanggualangan yang tersedia sesuai lokasi Sesuai Lulus
yang tersedia sesuai lokasi
bencana
2. Status buka/penuh/tutup
2. Status buka/penuh/tutup
tampil
42

| Fungsi/Feature  |                   | Hasil        |         |
| --------------- | ----------------- | ------------ | ------- |
| No              | Input yang Diuji  | Langkah Uji  | Status  |
Admin  Uji
|     | 3. Lokasi tampil di peta  | 3. Lokasi fasilitas dapat di  |     |
| --- | ------------------------- | ----------------------------- | --- |
lihat di peta
1. Admin dapat mengatur
|     | 1. Admin dapat mengatur  | info ambang batas air  |     |
| --- | ------------------------ | ---------------------- | --- |
info ambang batas air
2. Notifikasi terkirim ke user
|     | 2. Notifikasi terkirim saat  | sesuai daftar dan lokasi    |     |
| --- | ---------------------------- | --------------------------- | --- |
|     | siaga                        | tercatat saat status siaga  |     |
Notifikasi &  3. Notifikasi terkirim saat  3. Notifikasi terkirim ke user
| 6           |         | Sesuai Lulus              |     |
| ----------- | ------- | ------------------------- | --- |
| Peringatan  | bahaya  | sesuai daftar dan lokasi  |     |
tercatat saat status bahaya
4. Admin dapat mengirim
|     | pengumuman  | 4. Admin dapat mengirim  |     |
| --- | ----------- | ------------------------ | --- |
pengumuman
5. Notifikasi diterima user
|     | & petugas  | 5. Notifikasi diterima user &  |     |
| --- | ---------- | ------------------------------ | --- |
petugas

|     |     |     |     |
| --- | --- | --- | --- |
43

Table 6.2 Blackbox Testing PETUGAS
Fungsi/Feature Hasil
No Input yang Diuji Langkah Uji Status
Petugas Uji
1. Dashboard tampil
sesuai role petugas 1. menampilkan Dashboard sesuai
role petugas
2. Data lokasi tugas
1 Dashboard Petugas Sesuai Lulus
tampil 2. Data lokasi tugas tampil
3. Status ketinggian 3. Status ketinggian air tampil
air tampil
1. Petugas dapat input
1. Petugas dapat input dan edit
ketinggian air
ketinggian air secara realtime sesuai
2. Data tersimpan ke di lapangan
server
Update Data 2. Data tersimpan ke server
2 Sesuai Lulus
Lapangan
3. Perubahan terlihat
3. Perubahan terlihat di dashboard
di dashboard admin
admin
4. Timestamp update
4. Timestamp update tercatat
tercatat
44

Table 6.3 Blackbox Testing USER
| Fungsi/Feature  |                   |              |     | Hasil   |
| --------------- | ----------------- | ------------ | --- | ------- |
| No              | Input yang Diuji  | Langkah Uji  |     | Status  |
User  Uji
|                    | 1. Dashboard tampil      | 1. Dashboard tampil dengan       |     |                |
| ------------------ | ------------------------ | -------------------------------- | --- | -------------- |
|                    | dengan baik              | baik sesuai role                 |     |                |
|                    | 2. informasi Status      | 2. informasi Status ketinggian   |     |                |
|                    | ketinggian air dan info  | air dan info wilayah yang        |     |                |
| 1  Dashboard User  |                          |                                  |     | Sesuai  Lulus  |
|                    | wilayah yang berpotensi  | berpotensi banjir tampil dengan  |     |                |
|                    | banjir tampil            | baik                             |     |                |
|                    | 3. Indikator informasi   | 3. Indikator informasi  mudah    |     |                |
|                    | mudah dipahami           | dipahami                         |     |                |
user melakukan resgistrasi
dengan Langkah di bawah
1. input user name
1. input user name
2. Email address personal
2. Email address personal
3. No Hand phone
| 2  Register user baru  |     | 3. No Hand phone  |     | Sesuai  Lulus  |
| ---------------------- | --- | ----------------- | --- | -------------- |
4. Input password
4. Input password
5. Pilih Lokasi sesuai
5. Pilih Lokasi sesuai tempat
tempat tinggal
tinggal
6. daftar
1. Menuju ke halaman
login
|                     |                        | Inputkan  data     | credential  yang      |        |
| ------------------- | ---------------------- | ------------------ | --------------------- | ------ |
| Login dengan        | 2. Inputkan data       |                    |                       |        |
| 3                   |                        | tidak  terdaftar,  | muncul  error Sesuai  | Lulus  |
| invalid credential  | credential yang tidak  |                    |                       |        |
notifikasi invalid credential
terdaftar
3. Klik button login
1. Menuju ke halaman
login
Tidak input credential maka
| Login dengan null  |                      | muncul error notifikasi User  |     |                |
| ------------------ | -------------------- | ----------------------------- | --- | -------------- |
| 4                  | 2. Tidak input data  |                               |     | Sesuai  Lulus  |
| credential         |                      | name & password tidak boleh   |     |                |
credential
kosong
3. Klik button login
1. Menuju ke halaman
|                     | login             | Input dengan credential       |     |                |
| ------------------- | ----------------- | ----------------------------- | --- | -------------- |
| Login dengan valid  |                   | terdaptar maka setelah login  |     |                |
| 5                   | 2. Inputkan data  |                               |     | Sesuai  Lulus  |
| credential          |                   | akan masuk ke dashboard user  |     |                |
credential
sesuai role dan profile user
3. Klik button login
45

| Fungsi/Feature  |                   |              | Hasil   |
| --------------- | ----------------- | ------------ | ------- |
| No              | Input yang Diuji  | Langkah Uji  | Status  |
User  Uji
Reset & lupa  Tekan menu lupa  Tidak muncul menu reset  Tidak  Tidak
6
| password  | password  | password  | sesuai  lulus  |
| --------- | --------- | --------- | -------------- |
1. User dapat melihat status
1. User dapat melihat
ketinggian air
laporan status ketinggian
|     | air  | 2. User dapat melihat Status  |     |
| --- | ---- | ----------------------------- | --- |
Informasi
| 7               |                           | aman / siaga / awas jelas  | Sesuai  Lulus  |
| --------------- | ------------------------- | -------------------------- | -------------- |
| Ketinggian Air  | 2. Status aman / siaga /  |                            |                |
awas jelas
3. User dapat melihat kapan
data ini di update dengan
3. Data update refresh
refresh manual
|     | 1. User dapat melihat  | 1. User dapat melihat peta    |     |
| --- | ---------------------- | ----------------------------- | --- |
|     | peta potensi banjir    | lokasi potensi banjir secara  |     |
umum
2. Titik rawan sesuai
Peta Lokasi Potensi
| 8       | wilayah  | 2. Titik rawan yang berpotensi  | Sesuai  Lulus  |
| ------- | -------- | ------------------------------- | -------------- |
| banjir  |          | banjir sesuai wilayah           |                |
3. Zoom & geser peta
|     | berfungsi                | 3. Zoom & geser peta berfungsi  |     |
| --- | ------------------------ | ------------------------------- | --- |
|     | 4. Detail lokasi dibuka  | 4. Detail lokasi bisa dibuka    |     |
1. User dapat melihat
1. User dapat meengetahui
fasilitas penanggulangan
fasilitas penanggulangan
bencana terdekat
| Fasilitas           |                           | bencana terdekat  |                |
| ------------------- | ------------------------- | ----------------- | -------------- |
| 9  penanggualangan  | 2. Status buka / penuh /  |                   | Sesuai  Lulus  |
2. Status buka / penuh / tutup
| bencana  | tutup tampil  |     |     |
| -------- | ------------- | --- | --- |
tampil
3. Navigasi ke lokasi
3. Navigasi ke lokasi berfungsi"
berfungsi
1. Menu darurat tampil di
dashboard
2. Menu dapat akes
3. Konfirmasi sebelum
Tentukan lokasi sesuai map,
kirim
Input bukti foto, pilih
| Menu laporan  | 4. Lokasi user terkirim  |                            |                |
| ------------- | ------------------------ | -------------------------- | -------------- |
| 10            |                          | ketinggian air, keterangn  | Sesuai  Lulus  |
Darurat
5. Petugas menerima
laporan. Setelah kirim dapat
notifikasi
notif pesan terkirim
6. Riwayat laporan
tersimpan

| Notifikasi terkirim  |     | Semua user yang terdaftar akan  |     |
| -------------------- | --- | ------------------------------- | --- |
1. User menerima
11  kepada User  menerima notifikasi peringata  Sesuai  Lulus
notifikasi siaga
| terdaftar  |     | dari admin  |     |
| ---------- | --- | ----------- | --- |
46

Fungsi/Feature Hasil
No Input yang Diuji Langkah Uji Status
User Uji
2. User menerima
notifikasi bahaya
3. User menerima
pengumuman admin
4. Notifikasi tersimpan
Keterangan:
• Fungsi/Feature: Bagian dari aplikasi yang diuji.
• Input yang Diuji: Variasi data input yang digunakan dalam uji coba.
• Langkah Uji: Proses yang harus dilakukan untuk menguji aplikasi.
• Output yang Diharapkan: Hasil yang harus muncul setelah input diberikan.
• Status: Menandakan apakah pengujian berhasil atau gagal.
6.2 Hasil Pengujian Usability Testing
Tabel Kuisioner Quality Assurance Aplikasi Siaga Banjir
Table 6.4 Skor Responden
N Adha Andr Ange Dwi Fauziah Jama Lia Rahmia She
Pertanyaan Greg
o m e l Putri Syafrina l Ruhlia Puji na
Aplikasi
siaga banjir
dapat
1 diakses 5 5 5 5 5 5 4 4 4 3
dengan baik
melalui HP
Android.
Aplikasi
mampu
menampilka
n informasi
2 5 5 5 5 4 5 4 3 5 5
status
potensi
banjir secara
real-time
Data tinggi
muka air dan
curah hujan
3 5 5 5 5 5 5 4 4 4 5
ditampilkan
dengan
akurat
47

Fitur
peringatan
4 dini banjir 5 5 5 5 5 5 4 3 4 5
berfungsi
dengan baik
Aplikasi
memberikan
5 notifikasi 5 5 5 5 5 5 4 3 4 4
banjir secara
tepat waktu
Bagian 1 Total
(Fungsionalitas) 25 25 25 25 24 25 20 17 21 23
Antarmuka
aplikasi
mudah
6 dipahami 5 5 5 5 5 5 4 4 5 5
oleh
pengguna
umum
Tampilan
peta wilayah
7 banjir jelas 5 5 5 5 5 5 4 3 4 5
dan
informatif
Navigasi
menu
8 aplikasi 5 5 5 5 4 5 4 3 4 4
mudah
digunakan
Informasi
status siaga,
waspada,
9 5 5 5 5 4 5 4 4 3 5
dan bahaya
mudah
dibedakan
Aplikasi
menampilka
n informasi
10 dengan tata 5 5 5 5 5 5 4 3 4 4
letak yang
rapi dan
konsisten
Bagian 2 Total
(Antarmuka) 25 25 25 25 23 25 16 17 20 23
48

Data
pengguna
tersimpan
11 5 5 5 5 5 5 4 5 3 4
dengan
aman di
dalam sistem
Aplikasi
terlindungi
12 4 5 5 5 5 4 4 4 3 4
dari akses
tidak sah
Aplikasi
tetap stabil
dan tidak
13 5 5 5 5 5 5 4 4 4 5
mengalami
crash saat
digunakan
Proses
pengiriman
data laporan
14 ke sistem 5 5 5 5 5 5 4 4 5 5
berjalan
dengan
aman
Aplikasi
memiliki
15 mekanisme 5 5 5 5 5 5 4 5 5 5
backup data
yang baik
Bagian 3 Total
(Keamanan) 24 25 25 25 25 24 20 22 20 23
Informasi
kontak atau
16 bantuan 5 5 5 5 4 5 4 4 5 4
mudah
ditemukan
Aplikasi
memberikan
respon yang
17 5 5 5 5 5 5 4 4 5 3
baik saat
terjadi
kesalahan
Aplikasi
membantu
pengguna
dalam
18 5 5 5 5 5 5 4 4 5 5
pengambilan
keputusan
saat kondisi
darurat
Panduan
atau tutorial
penggunaan
19 0 0 0 0 0 0 0 0 0 0
aplikasi
mudah
ditemukan
49

Tim
dukungan
aplikasi
20 responsif 0 0 0 0 0 0 0 0 0 0
terhadap
masalah atau
pertanyaan
Bagian 4 Total
(Dukungan) 15 15 15 15 14 15 12 12 15 12
2. Tabel Jumlah dan Rata-Rata Skor per Kategori
Table 6.5 Jumlah dan Rata-Rata Skor
Rata-rata Skor per
Kategori Total Skor
Kategori
Bagian 1 (Fungsionalitas) 25+25+25+25+24+25+20+17+21+23= 230 / 10 = 23
Bagian 2 (Antarmuka) 25+25+25+25+23+25+16+17+20+23= 224 / 10 = 22.4
Bagian 3 (Keamanan) 24+25+25+25+25+24+20+22+20+23= 233 / 10 = 23.3
Bagian 4 (Dukungan) 15+15+15+15+14+15+12+12+15+12= 140 / 10 = 14
Total Skor 89+90+90+90+86+89+68+68+76+81= 827 / 4 = 206.75
3. Interpretasi Hasil:
• Skor Maksimal per Kategori: 5 x 5 (karena 5 pertanyaan di tiap bagian) = 25
• Skor Total Maksimal: 100 (karena ada 4 kategori, masing-masing maksimal 25 poin)
Hasil Rata-Rata:
• Total Rata-Rata Skor: 206.75 / 25 (karena ada 4 kategori) = 80.2%
Berdasarkan skor ini, dapat disimpulkan bahwa aplikasi Siaga Banjir mendapatkan penilaian baik.
Beberapa area yang mendapat skor tertinggi adalah Keamanan (rata-rata 23.3) dan Antarmuka
(rata-rata 22.4), menunjukkan bahwa aplikasi aman digunakan dan memiliki antarmuka yang mudah
dipahami. Aspek Fungsionalitas dan Dukungan memperoleh skor yang sedikit lebih rendah, yang
menunjukkan ada sedikit ruang untuk perbaikan dalam hal kelancaran proses dan dukungan
pengguna.
50

Gambar 83 Rating Quisioner
Gambar 84 Usia Peserta Responden
Gambar 85 Kritik dan Saran Responden
51

FASE 7
DEPLOYMENT AND MAINTENANCE
(PENERAPAN DAN PEMELIHARAAN)
7.1 Deployment
Proses deployment aplikasi SiAGA Banjir melibatkan dua komponen utama: backend Laravel
dan aplikasi mobile Flutter, dengan konfigurasi khusus untuk infrastruktur server dan Firebase Cloud
Messaging.
7.1.1. Deployment Backend Laravel
Kebutuhan server:
1. PHP 8.2 atau lebih tinggi
2. Composer (versi terbaru)
3. MySQL 5.7+ atau PostgreSQL 10+ (untuk produksi)
4. SQLite 3 (untuk pengembangan)
5. Laravel 10.x framework
6. Web Server (Apache/Nginx)
7. Sertifikat SSL (untuk produksi)
Langkah-Langkah Instalasi:
1. Clone Repository & Instalasi Dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build
2. Konfigurasi Environment
Copy .env.example ke .env
Generate application key: php artisan key:generate
Configure database credentials:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=siaga_banjir
DB_USERNAME=root
DB_PASSWORD=secret
Set application URL: APP_URL=https://domain.com
3. Pengaturan Firebase
Unduh firebase-credentials.json dari Firebase Console
Simpan di direktori root project
Atur path di file .env:
FIREBASE_CREDENTIALS=./firebase-credentials.json
4. Migrasi Database dan Seeding
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder
5. Konfigurasi Storage
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
52

6. Optimasi Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
7. Menjalankan Server Produksi
Pengembangan: php artisan serve --host=0.0.0.0 --port=8000
Produksi: Konfigurasi Apache/Nginx virtual host dengan document root ke
/public
Checklist Produksi:
1. (cid:33158)(cid:57393) Set APP_ENV=production dan APP_DEBUG=false
2. (cid:33158)(cid:57393) Update Firebase credentials untuk production project
3. (cid:33158)(cid:57393) Configure SSL certificate (Let's Encrypt/Commercial)
4. (cid:33158)(cid:57393) Set proper file permissions (755 directories, 644 files)
5. (cid:33158)(cid:57393) Enable error logging: LOG_CHANNEL=daily
6. (cid:33158)(cid:57393) Configure backup strategy untuk database
7. (cid:33158)(cid:57393) Setup cron job untuk php artisan schedule:run
7.1.2. Deployment Aplikasi Mobile Flutter
Lingkungan Pengembangan:
1. Flutter SDK 3.9.2 atau lebih tinggi
2. Dart SDK 3.0+
3. Android Studio (untuk deployment Android)
4. Xcode 15+ (untuk deployment iOS, hanya macOS)
Instalasi Dependencies:
flutter pub get
flutter pub upgrade
Konfigurasi Environment:
1. Pengaturan API Base URL:
a. Pengembangan (Emulator Android): http://10.0.2.2:8000/api
b. Pengembangan (Simulator iOS): http://localhost:8000/api
c. Perangkat Fisik: http://192.168.x.x:8000/api
d. Produksi: https://api.domain.com/api
2. Konfigurasi Firebase:
a. Android: Unduh google-services.json → android/app/
b. iOS: Unduh GoogleService-Info.plist → ios/Runner/
Deployment Android:
1. Konfigurasi Build (android/app/build.gradle.kts):
compileSdk = 34
minSdk = 21
targetSdk = 34
applicationId = "com.siagabanjir.app"
53

2. Izin yang Diperlukan (AndroidManifest.xml):
INTERNET - Komunikasi API
CAMERA - Pengambilan foto untuk laporan
READ_EXTERNAL_STORAGE / WRITE_EXTERNAL_STORAGE - Upload file
ACCESS_FINE_LOCATION / ACCESS_COARSE_LOCATION - Pelacakan GPS
3. Build APK/AAB:
flutter build apk --release
flutter build appbundle --release
4. Lokasi Output:
APK: build/app/outputs/flutter-apk/app-release.apk
AAB: build/app/outputs/bundle/release/app-release.aab
Deployment iOS:
1. Konfigurasi Build (ios/Runner.xcodeproj):
a. Bundle Identifier: com.siagabanjir.app
b. Target Deployment: iOS 12.0+
c. Sertifikat Signing: Sertifikat Apple Developer yang valid
2. Izin yang Diperlukan (Info.plist):
a. NSCameraUsageDescription - Akses kamera
b. NSPhotoLibraryUsageDescription - Akses galeri foto
c. NSLocationWhenInUseUsageDescription - Pelacakan lokasi
3. Build IPA:
flutter build ios --release
Pengujian Cross-Platform:
flutter test # Unit & widget tests
flutter analyze # Analisis kode statis
flutter doctor -v # Verifikasi environment
Target Deployment:
1. Google Play Console (Android) - Format AAB diperlukan
2. Apple App Store (iOS) - IPA via Xcode/Transporter
3. Distribusi APK langsung (untuk pengujian internal)
7.2 Maintenance
Pemeliharaan berkelanjutan untuk memastikan sistem SiAGA Banjir beroperasi optimal, aman,
dan dapat diandalkan.
7.2.1. Monitoring Sistem
Monitoring log:
1. Log Backend Laravel:
Lokasi: storage/logs/laravel.log (tunggal) atau laravel-YYYY-MM-
DD.log (harian)
Channel Log: stack (default), single, daily, slack, syslog
Konfigurasi: config/logging.php
54

Level Log: debug, info, notice, warning, error, critical, alert,
emergency
# Monitor real-time logs
tail -f storage/logs/laravel.log
# Search for errors
grep -i "error" storage/logs/laravel.log
2. Pelacakan Error Aplikasi:
a. Error response API (kode status 4xx, 5xx)
b. Kegagalan query database
c. Error pengiriman notifikasi Firebase
d. Kegagalan autentikasi/otorisasi
e. Error upload/storage file
Monitoring performa:
a. Performa query database (slow query log)
b. Pelacakan waktu respons API
c. Penggunaan resource server (CPU, Memory, Disk I/O)
d. Konsumsi bandwidth jaringan
e. Tingkat pengiriman Firebase FCM
7.2.2. Pemeliharaan Database
Prosedur backup:
1. Backup Otomatis Harian:
# MySQL dump
mysqldump -u root -p siaga_banjir > backup_$(date +%Y%m%d).
sql
# SQLite backup
sqlite3 database/database.sqlite ".backup backup_$(date +%
Y%m%d).sqlite"
2. Kebijakan Retensi Backup:
a. Backup harian: Simpan 7 hari terakhir
b. Backup mingguan: Simpan 4 minggu terakhir
c. Backup bulanan: Simpan 12 bulan terakhir
Manajemen migrasi:
# Check migration status
php artisan migrate:status
# Rollback last migration (emergency)
php artisan migrate:rollback
# Fresh migration dengan seeding (development only)
php artisan migrate:fresh --seed
Pemeriksaan integrasi data:
a. Verifikasi foreign key constraints
b. Periksa orphaned records (laporan tanpa user/station)
55

c. Validasi konsistensi data (tinggi air, koordinat)
d. Monitor pertumbuhan ukuran database
7.2.3. Prosedur Update
Update Backend (Laravel):
1. Update Dependencies:
composer update --no-dev
composer audit # Check security vulnerabilities
2. Update Framework:
# Update Laravel
composer update laravel/framework
# Publikasikan file konfigurasi baru
php artisan vendor:publish --tag=config
3. Perintah Pasca-Update:
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
Update Aplikasi Mobile (Flutter):
1. Update Flutter SDK:
flutter upgrade
flutter doctor -v # Verifikasi environment
2. Update Package:
flutter pub upgrade
flutter pub outdated # Periksa update yang tersedia
flutter pub audit # Pemeriksaan keamanan
3. Rebuild Aplikasi:
flutter clean
flutter pub get
flutter build apk –release
Manajemen Versi:
a. Backend: Update version di composer.json
b. Mobile: Increment version dan build number di pubspec.yaml
c. Kelola changelog: BACKEND_UPDATE_LOG.md,
IMPLEMENTATION_CHANGELOG.md
7.2.4. Pemeliharaan Keamanan
Rotasi Kredensial:
a. Password database (triwulanan).
b. Token/key API (triwulanan).
c. Kredensial service account Firebase (tahunan).
d. Sertifikat SSL (sebelum kedaluwarsa).
56

Patch Keamanan:
a. Monitor advisory keamanan Laravel.
b. Terapkan patch kritis dalam 24-48 jam.
c. Uji update keamanan di staging environment terlebih dahulu.
d. Review output composer audit secara berkala.
Kontrol Akses:
a. Review izin pengguna secara berkala.
b. Nonaktifkan akun yang tidak aktif.
c. Monitor percobaan login yang gagal.
d. Audit tindakan admin/petugas.
7.2.5. Perbaikan Bug & Resolusi Masalah
Alur Pelacakan Bug:
1. Identifikasi Masalah:
a. Laporan pengguna via sistem feedback.
b. Logging error otomatis (Laravel log).
c. Alert monitoring (downtime API, tingkat error tinggi).
2. Klasifikasi Prioritas:
a. Kritis: Sistem down, kehilangan data, pelanggaran keamanan.
b. Tinggi: Fungsi utama rusak, mempengaruhi banyak pengguna.
c. Sedang: Masalah fitur minor, ada workaround.
d. Rendah: Masalah kosmetik, perbaikan minor.
3. Prosedur Pengujian:
# Backend testing
php artisan test
php artisan test --filter=TestClassName
# Flutter testing
flutter test
flutter test lib/tests/specific_test.dart
flutter analyze
4. Deployment Perbaikan:
a. Backend: Deploy via git pull + composer install + migrate.
b. Mobile: Build APK/AAB baru, rilis via Play Store/App Store.
c. Hotfix: Gunakan feature branches, merge ke main setelah testing.
Update Dokumentasi:
a. Update dokumentasi API (API_DOCUMENTATION.md)
b. Update panduan troubleshooting
(REGION_STATUS_TROUBLESHOOTING.md)
c. Kelola log update (BACKEND_UPDATE_LOG.md)
d. Update panduan pengguna jika ada perubahan UI/flow
57

7.2.6. Pemeriksaan Kesehatan Sistem
Pemeriksaan Mingguan:
1. (cid:33158)(cid:57393) Verifikasi semua endpoint API merespons (200 OK)
2. (cid:33158)(cid:57393) Periksa stabilitas koneksi database
3. (cid:33158)(cid:57393) Uji pengiriman push notification
4. (cid:33158)(cid:57393) Verifikasi fungsi upload/storage file
5. (cid:33158)(cid:57393) Monitor penggunaan disk space (>20% kosong)
Pemeriksaan Bulanan:
1. (cid:33158)(cid:57393) Review log error untuk masalah berulang
2. (cid:33158)(cid:57393) Analisis pola penggunaan API
3. (cid:33158)(cid:57393) Periksa tanggal kedaluwarsa sertifikat SSL
4. (cid:33158)(cid:57393) Verifikasi prosedur restore backup
5. (cid:33158)(cid:57393) Update dependencies dengan patch keamanan
6. (cid:33158)(cid:57393) Review optimasi performa
Mode Pemeliharaan:
# Aktifkan mode pemeliharaan
php artisan down --secret="maintenance-token"
# Lakukan tugas pemeliharaan (updates, migrations)
# Nonaktifkan mode pemeliharaan
php artisan up
Pemeliharaan yang konsisten memastikan sistem SiAGA Banjir tetap andal, aman,
dan siap melayani masyarakat dalam pemantauan bencana banjir.
58

KESIMPULAN
Aplikasi SiAGA Banjir berhasil memenuhi tujuan sebagai sistem peringatan dini banjir terintegrasi
yang menggabungkan data teknis petugas dan laporan masyarakat. Sistem ini mampu memberikan
informasi real-time status ketinggian air di berbagai pos pantau serta memfasilitasi pelaporan banjir
berbasis GPS dengan bukti foto. Arsitektur multi-platform menggunakan Laravel 10 untuk backend dan
Flutter untuk aplikasi mobile terbukti efektif menghadirkan sistem yang responsif dan mudah diakses.
Implementasi role-based access control untuk tiga pengguna (Admin, Petugas, Masyarakat)
memastikan keamanan data dan pembagian tanggung jawab yang jelas.
Mekanisme validasi melalui penandaan lokasi GPS otomatis dan upload foto real-time berhasil
meminimalkan manipulasi laporan. Integrasi Firebase Cloud Messaging memberikan peringatan dini
yang cepat kepada masyarakat. Hasil pengujian menunjukkan tingkat fungsionalitas dan user
experience yang baik, meskipun masih diperlukan optimasi performa. Sistem memiliki potensi
pengembangan dengan integrasi IoT menggunakan sensor otomatis untuk meningkatkan akurasi data.
Proyek berhasil diselesaikan sesuai metodologi yang direncanakan dengan dokumentasi komprehensif
untuk pengembangan masa depan. Sistem SiAGA Banjir memberikan kontribusi signifikan dalam
mitigasi bencana banjir dan dapat diimplementasikan untuk memberikan manfaat bagi masyarakat di
wilayah rawan banjir.
59

LAMPIRAN
60

61