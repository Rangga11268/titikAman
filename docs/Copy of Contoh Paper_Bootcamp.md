LEMBAR TUGAS PROYEK PERANGKAT LUNAK
QR Access: Solusi Absensi Siswa Sekolah Menengah Atas
Dengan QR Code Berbasis Web
Disusun sebagai Bukti Laporan Hasil Kegiatan IT Booth Camp
Semester Genap T.A. 2023/2024
Disusun oleh: Kelompok 49
1. Desi Kartika NIM : 12220575 Kelas : 12.4E.13
2. Azzahra Ramadhanti NIM : 12220178 Kelas : 12.4E.13
3. Nur Alliza Pramudya R. H. NIM : 12221321 Kelas : 12.4G.13
4. Hanna Dwi Lestari NIM : 12221270 Kelas : 12.4G.13
5. Bhayu Kresnaputra NIM : 12220693 Kelas : 12.4A.39
6. Zevanya Ronanto Foortse NIM : 12220240 Kelas : 12.4A.39
7. Himdami Hibatul Haqqi D. NIM : 12220963 Kelas : 12.4C.13
8. Javier Rejekinta Tarigan NIM : 12220928 Kelas : 12.4C.13
9. M. Hudan Lil Muttaqien NIM : 12220633 Kelas : 12.4E.13
10. Mohamad Ardana NIM : 12221180 Kelas : 12.4E.13
11. Lutfi Aziz NIM : 12220195 Kelas : 12.4E.13
Fakultas Teknik dan Informatika
Universitas Bina Sarana Informatika
Bogor
2024

KATA PENGANTAR
Puji syukur kami panjatkan kehadirat Allah SWT yang telah melimpahkan rahmat dan
karuniaNya sehingga kami kelompok 49 dapat menyusun tugas proyek perangkat lunak ini
dengan baik sebagai bagian dari kegiatan IT Bootcamp UBSI. Dalam makalah ini kami
membahas tema "QR Access: Solusi Absensi Siswa Sekolah Menengah Atas dengan QR Code
Berbasis Web".
Terima kasih kami haturkan kepada Ibu Sunarti, M.Kom. selaku Dosen Pembimbing
yang telah memberikan arahan serta bimbingan, juga semua pihak yang turut memberikan
kontribusi dalam penyusunan makalah ini.
Harapan kami makalah ini dapat memberikan manfaat dan inspirasi positif bagi kegiatan
IT Bootcamp serta menjadi bahan evaluasi kedepannya. Namun demikian, kami menyadari
bahwa masih terdapat kekurangan, baik dari penyusunan, rancangan maupun tata bahasa
penyampaian dalam makalah ini karena keterbatasan pengetahuan kami. Oleh karena itu, saran
dan masukan sangat kami harapkan dari berbagai pihak untuk kesempurnaan makalah ini dan
agar kami dapat terus melakukan pengembangan dan peningkatan kualitas sistem yang lebih
baik di masa yang akan datang.
Bogor, Mei 2024
Penulis
ii

DAFTAR ISI
KATA PENGANTAR ............................................................................................................................. ii
DAFTAR ISI .......................................................................................................................................... iii
DAFTAR GAMBAR ............................................................................................................................. iv
DAFTAR TABEL .................................................................................................................................. vi
ABSTRACT .......................................................................................................................................... vii
FASE 1 RISET (PENELITIAN) DAN ANALISIS ................................................................................ 1
Latar Belakang ........................................................................................................................ 1
Tujuan ...................................................................................................................................... 2
Harapan ................................................................................................................................... 2
FASE 2 PERENCANAAN DAN STRATEGI........................................................................................ 5
Analisa Kebutuhan .................................................................................................................. 5
Usecase Diagram ..................................................................................................................... 6
Entity Relationship Diagram (ERD) ....................................................................................... 6
Logical Record Structure (LRS) .............................................................................................. 8
Spesifikasi File ........................................................................................................................ 8
FASE 3 DESAIN DAN WIREFRAME ................................................................................................ 12
Wireframe .............................................................................................................................. 12
Prototype ............................................................................................................................... 23
FASE 4 CONTENT CREATION (PEMBUATAN KONTEN)............................................................ 39
Typography ........................................................................................................................... 39
Color Style ............................................................................................................................. 39
Gambar .................................................................................................................................. 40
Tombol dan Ikon ................................................................................................................... 41
FASE 5 CODE AND DEVELOPMENT (KODE DAN PENGEMBANGAN) ................................... 42
Tech Stack ............................................................................................................................. 42
Tampilan Coding ................................................................................................................... 42
FASE 6 TESTING AND QUALITY ASSURANCE (PENGUJIAN DAN JAMINAN KUALITAS) 46
Blackbox Testing ................................................................................................................... 46
Tabel 11. Hasil Pengujian Fitur SiswaUsability Testing ....................................................... 49
User Experience (UX) .......................................................................................................... 50
FASE 7 DEPLOYMENT AND MAINTENANCE (PENERAPAN DAN PEMELIHARAAN) ........ 53
Deployment ........................................................................................................................... 53
Maintenance .......................................................................................................................... 54
KESIMPULAN ..................................................................................................................................... 56
LAMPIRAN .......................................................................................................................................... 57
iii

DAFTAR GAMBAR
Gambar 1. Use Case Diagram .................................................................................................................. 6
Gambar 2. Entity Relationship Diagram (ERD) ........................................................................................ 7
Gambar 3. Logical Record Structure (LRS) ............................................................................................... 8
Gambar 4. Tampilan Login .................................................................................................................... 12
Gambar 5. Tampilan Dashboard ............................................................................................................ 12
Gambar 6. Tampilan Scan QR Code ....................................................................................................... 13
Gambar 7. Tampilan Form Izin .............................................................................................................. 13
Gambar 8. Tampilan Verifikasi Izin ........................................................................................................ 14
Gambar 9. Tampilan Login Siswa........................................................................................................... 14
Gambar 10.Tampilan Dashboard Siswa ................................................................................................ 15
Gambar 11. Tampilan Form Permintaan Tidak Masuk .......................................................................... 15
Gambar 12. Tampilan Form Login Admin .............................................................................................. 16
Gambar 13. Tampilan Dashboard Admin .............................................................................................. 16
Gambar 14. Tampilan Data Siswa Kelas Dan Jurusan............................................................................ 17
Gambar 15. Tampilan Fitur Tambah Kelas ............................................................................................ 17
Gambar 16. Tampilan Tambah Jurusan ................................................................................................. 18
Gambar 17. Tampilan Kelola Absensi .................................................................................................... 18
Gambar 18. Tampilan Mengatur Jam Masuk ........................................................................................ 19
Gambar 19. Tampilan Mengatur Libur Nasional ................................................................................... 19
Gambar 20. Tampilan Seluruh Data Siswa ............................................................................................ 20
Gambar 21. Tampilan Data Izin Siswa ................................................................................................... 20
Gambar 22. Tampilan Data Libur Siswa ................................................................................................ 21
Gambar 23. Tampilan Hasil Absen Siswa .............................................................................................. 21
Gambar 24. Tampilan Modal Title ......................................................................................................... 22
Gambar 25. Tampilan Kamera ............................................................................................................... 22
Gambar 26. Tampilan login ................................................................................................................... 23
Gambar 27. Tampilan dashboad ........................................................................................................... 23
Gambar 28. Tampilan Profil Siswa ......................................................................................................... 24
Gambar 29. TampilanForm Permintaan Tidak Masuk .......................................................................... 24
Gambar 30. Tampilan halaman Scan QR .............................................................................................. 25
Gambar 31. Tampilan Login Admin ....................................................................................................... 25
Gambar 32. Tamplan Beranda Admin ................................................................................................... 26
Gambar 33. Data Kelas Jurusan ............................................................................................................. 26
Gambar 34. Tambah Jurusan................................................................................................................. 27
Gambar 35. Kelola Absen ...................................................................................................................... 27
Gambar 36. Mengatur Jam Masuk ........................................................................................................ 28
Gambar 37. Mengatur Libur Nasional ................................................................................................... 28
Gambar 38. Tampilan Data Siswa .......................................................................................................... 29
Gambar 39. Tampilan izin Siswa ............................................................................................................ 29
Gambar 40. Data Libur Siswa ................................................................................................................ 30
Gambar 41. Hasil Absensi Siswa ............................................................................................................ 30
Gambar 42. Tampilan Login Siswa ........................................................................................................ 31
Gambar 43. Tampilan Profil Siswa ......................................................................................................... 31
Gambar 44. Tampilan Form Izin ............................................................................................................ 32
Gambar 45. Halaman Scan QR Code ..................................................................................................... 32
iv

Gambar 46. Tampilan Login Admin ....................................................................................................... 33
Gambar 47. Tampilan Dashboard Admin .............................................................................................. 33
Gambar 48. Tampilan Data Siswa, Kelas dan Jurusan ........................................................................... 34
Gambar 49. Tampilan Tambah Jurusan ................................................................................................. 34
Gambar 50. Tampilan Fitur Tambah Kelas ............................................................................................ 35
Gambar 51. Tampilan Kelola Absensi .................................................................................................... 35
Gambar 52. Tampilan Mengatur Jam Masuk ........................................................................................ 36
Gambar 53. Tampilan Mengatur Libur Nasional ................................................................................... 36
Gambar 54. Tampilan Seluruh Data Siswa ............................................................................................ 37
Gambar 55. Tampilan Data Izin Siswa ................................................................................................... 37
Gambar 56. Tampilan Data Libur Siswa ................................................................................................ 38
Gambar 57. Tampilan Hasil Absen Siswa .............................................................................................. 38
v

DAFTAR TABEL
Tabel 1. Spesifikasi File User.................................................................................................................... 9
Tabel 2. Spesifikasi File Siswa .................................................................................................................. 9
Tabel 3. Spesifikasi File Detail Absen..................................................................................................... 10
Tabel 4. Spesifikasi File Libur ................................................................................................................. 10
Tabel 5. Spesifikasi File Kelas ................................................................................................................. 10
Tabel 6. Spesifikasi File Jurusan ............................................................................................................. 11
Tabel 7. Spesifikasi File Jam Absen ........................................................................................................ 11
Tabel 8. Spesifikasi File Izin ................................................................................................................... 11
Tabel 9. Hasil Pengujian Fitur Login ...................................................................................................... 46
Tabel 10. Hasil Pengujian Fitur Admin................................................................................................... 47
Tabel 11. Hasil Pengujian Fitur Siswa .................................................................................................... 48
vi

ABSTRACT
Di era digital ini, pemanfaatan teknologi dalam berbagai aspek kehidupan memberikan
pengaruh yang besar. Hal ini juga berlaku di dunia pendidikan, di mana teknologi dimanfaatkan
untuk meningkatkan efektivitas dan efisiensi proses belajar mengajar. Sistem absensi
konvensional yang menggunakan buku daftar hadir memiliki beberapa keterbatasan, seperti
inefisensi waktu dan tenaga, ketidakakuratan data, kurangnya transparansi, dan sulitnya
memantau kehadiran siswa. Masalah lainnya yang sering terjadi data absensi yang dicatat
secara manual rentan terhadap kerusakan atau kehilangan. Oleh karena itu, diperlukan solusi
yang lebih modern dan efisien untuk mengelola absensi siswa. QR Access hadir sebagai solusi
inovatif untuk absensi dan manajemen kehadiran siswa di SMA. Sistem ini memanfaatkan
teknologi QR Code untuk memudahkan proses absensi dan menyediakan data kehadiran yang
akurat dan real-time.
vii

FASE 1
RISET (PENELITIAN) DAN ANALISIS
Latar Belakang
Pendidikan adalah fondasi utama bagi kemajuan suatu bangsa. Di dalamnya,
peran guru dan siswa sangat penting untuk mencapai tujuan pembelajaran yang optimal.
Salah satu aspek yang tidak boleh diabaikan dalam proses pendidikan adalah pentingnya
memantau absensi siswa. Absensi yang baik mencerminkan keterlibatan siswa secara
aktif dalam pembelajaran, dan ketersediaan data absensi dapat memberikan informasi
berharga bagi pihak sekolah dan orang tua. Tradisi pencatatan absensi konvensional
menggunakan buku daftar hadir masih banyak digunakan di sekolah-sekolah di
Indonesia. Cara ini memiliki beberapa kekurangan, seperti ketidakakuratan data akibat
kesalahan manusia, inefisiensi waktu dan tenaga karena pencatatan manual membutuhkan
waktu yang cukup lama, kurangnya efisiensi dalam pengelolaan data absensi jangka
panjang, kurangnya transparansi karena orang tua tidak dapat mengetahui kehadiran anak
mereka secara real-time, serta kurangnya keamanan karena data absensi manual rentan
terhadap kerusakan atau kehilangan.
Untuk mengatasi kekurangan-kekurangan tersebut, diperlukan solusi absensi
yang lebih modern, akurat, dan efisien. Salah satu solusi yang potensial adalah QR
Access: Solusi Absensi Siswa Sekolah Menengah Atas dengan QR Code Berbasis Web.
Sistem ini memanfaatkan teknologi QR Code untuk memudahkan proses absensi dan
menyediakan data kehadiran yang akurat dan real-time. Penerapan QR Access di SMA
diprediksi akan memberikan dampak positif, di antaranya meningkatkan kualitas
pembelajaran karena guru dapat lebih fokus pada proses pembelajaran tanpa terbebani
tugas absensi manual, meningkatkan transparansi dan akuntabilitas melalui data
kehadiran siswa yang akurat dan transparan sehingga akuntabilitas sekolah kepada orang
tua dan pemangku kepentingan lainnya meningkat, serta meningkatkan efektivitas
manajemen sekolah melalui penggunaan data kehadiran siswa untuk analisis dan
pengambilan keputusan yang lebih baik.
Berdasarkan latar belakang tersebut, penulis mengusulkan untuk melakukan
penelitian lebih lanjut tentang QR Access sebagai solusi absensi dan manajemen
kehadiran siswa di SMA. Penelitian ini diharapkan dapat memberikan kontribusi dalam
meningkatkan kualitas pendidikan di Indonesia.
1

Tujuan
A. Meningkatkan Efisiensi dan Akurasi Absensi:
1. Mempercepat proses absensi, sehingga menghemat waktu guru dan siswa.
2. Meminimalisir kesalahan pencatatan absensi.
3. Menghasilkan data absensi yang akurat dan real-time.
B. Meningkatkan Keamanan Data Absensi:
1. Meminimalisir pemalsuan absensi oleh siswa.
2. Memastikan data absensi hanya dapat diakses oleh pihak yang berwenang.
3. Melindungi data absensi dari kerusakan atau kehilangan.
C. Memudahkan Pengelolaan Data Absensi:
1. Menyediakan data absensi yang mudah diakses dan dianalisis.
2. Membantu guru dan staf sekolah dalam memantau kehadiran siswa.
3. Memudahkan pembuatan laporan absensi untuk berbagai keperluan.
D. Meningkatkan Disiplin Siswa:
1. Meningkatkan kesadaran siswa tentang pentingnya kehadiran di sekolah.
2. Mendorong siswa untuk datang ke sekolah tepat waktu dan mengikuti pelajaran
dengan disiplin.
3. Membantu orang tua dalam memantau kehadiran anak mereka di sekolah.
E. Manfaat Tambahan:
1. Meningkatkan citra sekolah sebagai institusi yang modern dan inovatif.
2. Mendukung penerapan pembelajaran berbasis digital di sekolah.
Harapan
A. Seperti apa tampilan dan nuansa yang Anda harapkan?
1. Desain sederhana dan intuitif;
2. Kemudahan penggunaan;
3. Tampilan bersih dan minimalis;
4. Visualisasi data yang jelas;
5. Fleksibel dan responsif
2

B. Bagaimana dan apa yang akan dihasilkan situs web untuk bisnis Anda?
1. Manajemen Pengguna : Sekolah dapat menambahkan, mengedit, dan
menghapus pengguna sistem QR Access.
2. Kelas dan Siswa : Sekolah dapat membuat kelas dan
menambahkan siswa ke dalam kelas tersebut.
3. Generasi QR Code : Sekolah dapat menghasilkan QR Code unik
untuk setiap siswa setiap melakukan absensi.
4. Absensi : Siswa dapat menandai kehadiran mereka dengan
memindai QR Code menggunakan smartphone
mereka.
5. Data Kehadiran : siswa, sekolah dan orang tua dapat melihat data
kehadiran siswa secara real-time dan historis
yang mencakup jumlah hadir, izin, sakit, alfa dan
terlambat.
6. Laporan : Sekolah dapat generate berbagai laporan
kehadiran siswa, seperti laporan harian,
mingguan, dan bulanan.
7. Pengaturan : Sekolah dapat mengatur berbagai pengaturan
sistem QR Access, seperti jam masuk dan keluar
sekolah, serta hari libur.
C. Fitur apa yang diharapkan pengguna dari aplikasi semacam itu?
1. Siswa dapat melakukan presensi menggunakan QR Code;
2. Siswa dapat mengetahui kapan waktu absen masuk dan absen keluar;
3. Siswa dapat melihat data absen, data libur, dan data izin di dalam tampilan
beranda;
4. Siswa dapat mengisi form permintaan izin tidak masuk yang disertakan bukti
foto agar dapat divalidasi;
5. Siswa dapat melihat profil data diri dan mengatur sandi untuk mengakses QR
Access.
3

D. Apa sajakah fitur yang ingin Anda sertakan?
1. Pembuatan QR Code : Fitur QR code unik untuk setiap NIS siswa.
2. Real time scanning : Melakukan pemindaian QR code secara
langsung dan real time menggunakan
smartphone.
3. Dashboard Admin : Sebuah dashboard admin untuk memantau dan
mengelola aktivitas siswa dalam melakukan
absensi.
4. User friendly interface : Desain sistem antarmuka yang dapat diakses
melalui PC, tablet dan smartphone.
Dengan fitur - fitur tersebut, sistem informasi absensi siswa berbasis QR Code dapat
meningkatkan efisiensi dan akurasi dalam manajemen kehadiran, sekaligus
memberikan kemudahan bagi semua pihak yang terlibat.
4

FASE 2
PERENCANAAN DAN STRATEGI
Analisa Kebutuhan
A. Administrator
Admin sebagai administrator yang mengelola sistem pada sistem informasi absensi
siswa dengan QR Code.
1) Admin dapat melakukan login.
2) Admin dapat melakukan ubah password.
3) Admin dapat mengelola data user.
4) Admin dapat mengelola kelas dan jurusan.
5) Admin dapat mengelola libur (nasional dan weekend).
6) Admin dapat mengelola jam absensi siswa.
7) Admin dapat mengelola data izin.
8) Admin dapat mengelola data absensi siswa.
9) Admin dapat mengelola rekap absen.
B. Siswa
Siswa sebagai pengguna yang akan menggunakan website absensi tersebut.
1) Siswa dapat melakukan login.
2) Siswa dapat mencetak kartu absensi.
3) Siswa dapat memindai kartu absensi.
4) Siswa dapat mengakses jumlah absen.
5) Siswa dapat mengakses data libur.
6) Siswa dapat mengakses data izin.
7) Siswa dapat mengakses jadwal masuk dan keluar.
8) Siswa dapat melakukan ubah password.
9) Siswa dapat mengelola form izin tidak masuk.
5

Usecase Diagram
Pada Use Case Diagram dari aplikasi yang dibuat, terdapat 2 pengguna yang dapat
menggunakan aplikasi, yaitu anggota dan admin. Pada use case diagram dijabarkan
interaksi atau aktivitas yang dapat dilakukan oleh pengguna di dalam program aplikasi.
Tampilan dari use case diagram dapat dilihat pada gambar 3 sebagai berikut:
Gambar 1. Use Case Diagram
Entity Relationship Diagram (ERD)
6

Pada Entity Relationship Diagram terdapat total 8 tabel yaitu, tabel user, tabel
siswa, tabel detail_absen, tabel libur, tabel kelas, tabel jurusan, tabel jam_absen, tabel
izin. Tampilan dari entity relationship diagram dapat dilihat pada Gambar 2 berikut:
Gambar 2. Entity Relationship Diagram (ERD)
7

Logical Record Structure (LRS)
Gambar 3. Logical Record Structure (LRS)
Spesifikasi File
Untuk memberikan gambaran secara detail isi dari tabel-tabel yang dipergunakan
dalam sistem informasi komunitas, berikut struktur table dengan rincian data item:
A. Spesifikasi File User
Nama file : User
Akronim : tabel_user.MYD
Tipe file : File master
Access file : Random
Panjang record : 532 bytes
Field key : id_user
Software : MySQL
8

| No  Element data  | Nama field   | Type   Size    | Keterangan   |
| ----------------- | ------------ | -------------- | ------------ |
| 1  Id User        | id_user      | Int   10       | Primary key  |
| 2  Name           | name         | Varchar   128  |              |
| 3  Email          | email        | Varchar   128  |              |
| 4  Password       | password     | Varchar   128  |              |
| 5  Image          | image        | Varchar   128  |              |
| 6  Is Active      | is_active    | Int  10        |              |
| 7  Date Create    | date_create  | Date           |              |
Tabel 1. Spesifikasi File User
B. Spesifikasi File Siswa
| Nama file     : Siswa          |     |     |     |
| ------------------------------ | --- | --- | --- |
| Akronim     : tabel_siswa.MYD  |     |     |     |
| Tipe file     : File master    |     |     |     |
| Access file    : Random        |     |     |     |
Panjang record   : 465 bytes
| Field key     : id_siswa  |     |     |     |
| ------------------------- | --- | --- | --- |
| Software     : MySQL      |     |     |     |

| No   Element data  | Nama field     | Type   Size   | Keterangan   |
| ------------------ | -------------- | ------------- | ------------ |
| 1  Id siswa        | id_siswa       | Int   10      | Primary key  |
| 2  Nama siswa      | nama_siswa     | Text          |              |
| 3  NIS             | nis            | Varchar   12  |              |
| 4  Password        | password       | Varchar  128  |              |
| 4  Tanggal lahir   | tgl_lahir      | Date          |              |
| 5  Jenis kelamin   | jenis_kelamin  | Text          |              |
| 6  Alamat          | alamat         | Varchar  200  |              |
| 7  No telepon      | no_telepon     | Varchar   15  |              |
| 8  Kode jurusan    | kode_jurusan   | Varchar  15   |              |
| 9  Kode kelas      | kode_kelas     | Varchar  15   |              |
| 10  Gambar         | gambar         | Varchar   70  |              |
Tabel 2. Spesifikasi File Siswa
C. Spesifikasi File Detail Absen
| Nama file     : Detail Absen          |     |     |     |
| ------------------------------------- | --- | --- | --- |
| Akronim     : tabel_detail_absen.MYD  |     |     |     |
| Tipe file     : File Transaksi        |     |     |     |
| Access file    : Random               |     |     |     |
Panjang record   : 66 bytes
| Field key     : id_detail  |     |     |     |
| -------------------------- | --- | --- | --- |
| Software     : MySQL       |     |     |     |

| No  Element data  | Nama field  | Type   Size  | Keterangan   |
| ----------------- | ----------- | ------------ | ------------ |
| 1  Id detail      | id_detail   | int  10      | Primary key  |
| 2  Jam absen      | jam_absen   | Time         |              |
9

| 3  Tanggal absen  | tanggal_absen  |     | Date         |     |
| ----------------- | -------------- | --- | ------------ | --- |
| 4  NIS            | nis            |     | Varchar  12  |     |
| 5  Keterangan     | keterangan     |     | Text         |     |
| 6  Kode Kelas     | kode_kelas     |     | Int   11     |     |
| 7  Kode Jurusan   | kode_jurusan   |     | Int   11     |     |
| 8  Masuk          | masuk          |     | Int   11     |     |
| 9  Keluar         | keluar         |     | Int   11     |     |
Tabel 3. Spesifikasi File Detail Absen

D. Spesifikasi File Libur
| Nama file          | : Libur            |           |                      |              |
| ------------------ | ------------------ | --------- | -------------------- | ------------ |
| Akronim            | : tabel_libur.MYD  |           |                      |              |
| Tipe file          | : File Transaksi   |           |                      |              |
| Access file        | : Random           |           |                      |              |
| Panjang record     | : 220 bytes        |           |                      |              |
| Field key          | : id_libur         |           |                      |              |
| Software           | : MySQL            |           |                      |              |
| No   Element data  | Nama field         | Type      | Size                 | Keterangan   |
| 1  Id libur        | id_libur           | Int       | 10                   | Primary key  |
| 2  Type            | type               | Enum      | ‘weekend’, ‘other    |              |
| 3  Tanggal         | tanggal            | Varchar   | 110                  |              |
| 4  Keterangan      | keterangan         | Varchar   | 100                  |              |
| 5  Status          | status             | Enum      | ‘Aktif’,’Non Aktif’  |              |
Tabel 4. Spesifikasi File Libur
E. Spesifikasi File Kelas
| Nama file          | : Kelas            |     |               |              |
| ------------------ | ------------------ | --- | ------------- | ------------ |
| Akronim            | : tabel_kelas.MYD  |     |               |              |
| Tipe file          | : File Transaksi   |     |               |              |
| Access file        | : Random           |     |               |              |
| Panjang record     | : 120 bytes        |     |               |              |
| Field key          | : id_kelas         |     |               |              |
| Software           | : MySQL            |     |               |              |
| No   Element data  | Nama field         |     | Type   Size   | Keterangan   |
| 1  Id kelas        | id_kelas           |     | Int   10      | Primary key  |
| 2  Nama kelas      | nama_kelas         |     | varchar  100  |              |
| 3  Kelas           | kelas              |     | varchar  10   |              |
Tabel 5. Spesifikasi File Kelas

10

F. Spesifikasi File Jurusan
| Nama file        |                    | : Jurusan            |             |     |           |       |              |
| ---------------- | ------------------ | -------------------- | ----------- | --- | --------- | ----- | ------------ |
| Akronim          |                    | : tabel_jurusan.MYD  |             |     |           |       |              |
| Tipe file        |                    | : File Transaksi     |             |     |           |       |              |
| Access file      |                    | : Random             |             |     |           |       |              |
| Panjang record   |                    | : 61 bytes           |             |     |           |       |              |
| Field key        |                    | : id_jurusan         |             |     |           |       |              |
| Software         |                    | : MySQL              |             |     |           |       |              |
|                  | No   Element data  |                      | Nama field  |     | Type      | Size  | Keterangan   |
|                  | 1  Id jurusan      |                      | id_jurusan  |     | Int       | 11    | Primary key  |
|                  | 2  Jurusan         |                      | jurusan     |     | Varchar   | 50    |              |
Tabel 6. Spesifikasi File Jurusan
G. Spesifikasi File  Jam Absen
| Nama file        |                    | : Jam Absen            |             |     |                         |       |              |
| ---------------- | ------------------ | ---------------------- | ----------- | --- | ----------------------- | ----- | ------------ |
| Akronim          |                    | : tabel_jam_absen.MYD  |             |     |                         |       |              |
| Tipe file        |                    | : File Transaksi       |             |     |                         |       |              |
| Access file      |                    | : Random               |             |     |                         |       |              |
| Panjang record   |                    | : 10 bytes             |             |     |                         |       |              |
| Field key        |                    | : id_jam               |             |     |                         |       |              |
| Software         |                    | : MySQL                |             |     |                         |       |              |
|                  | No   Element data  |                        | Nama field  |     | Type                    | Size  | Keterangan   |
|                  | 1  Id Jam Absen    |                        | id_jam      |     | Int   10                |       | Primary key  |
|                  | 2  Type            |                        | type        |     | Enum  ‘masuk’.’keluar’  |       |              |
|                  | 3  Mulai           |                        | mulai       |     | Time                    |       |              |
|                  | 4  Selesai         |                        | selesai     |     | Time                    |       |              |
Tabel 7. Spesifikasi File Jam Absen
H. Spesifikasi File Izin
| Nama file        |               | : Izin            |     |           |                       |       |              |
| ---------------- | ------------- | ----------------- | --- | --------- | --------------------- | ----- | ------------ |
| Akronim          |               | : tabel_izin.MYD  |     |           |                       |       |              |
| Tipe file        |               | : File Transaksi  |     |           |                       |       |              |
| Access file      |               | : Random          |     |           |                       |       |              |
| Panjang record   |               | : 410 bytes       |     |           |                       |       |              |
| Field key        |               | : id_izin         |     |           |                       |       |              |
| Software         |               | : MySQL           |     |           |                       |       |              |
| No               | Element data  | Nama field        |     | Type      |                       | Size  | Keterangan   |
| 1                | Id izin       | id_izin           |     | Int       | 10                    |       | Primary key  |
| 2                | NIS           | nis               |     | Varchar   | 100                   |       |              |
| 3                | Type          | type              |     | Varchar   | 100                   |       |              |
| 4                | File bukti    | file_bukti        |     | Varchar   | 100                   |       |              |
| 5                | Keterangan    | keterangan        |     | Varchar   | 100                   |       |              |
| 6                | Tanggal izin  | tanggal_izin      |     | Date      |                       |       |              |
| 7                | Status        | status            |     | Enum      | ‘Diterima’.’ditolak’  |       |              |
Tabel 8. Spesifikasi File Izin
11

FASE 3
DESAIN DAN WIREFRAME
Wireframe
A. Versi Mobile
1. Tampilan Mobil Siswa
1) Tampilan login
Gambar 4. Tampilan Login
2) Tampilan Dashboard
Gambar 5. Tampilan Dashboard
12

3) Tampilan Scan QR Code
Gambar 6. Tampilan Scan QR Code
4) Tampilan Form Izin
Gambar 7. Tampilan Form Izin
13

2. Tampilan Mobile Admin
1) Tampilan Verifikasi Izin Siswa
Gambar 8. Tampilan Verifikasi Izin
B. Versi Desktop
1. Tampilan Website Siswa
1) Tampilan Login Siswa
Gambar 9. Tampilan Login Siswa
14

2) Tampilan Dashboard Siswa
Gambar 10.Tampilan Dashboard Siswa
3) Tampilan From Izin
Gambar 11. Tampilan Form Permintaan Tidak Masuk
15

2. Tampilan Website Admin
1) Tampilan From Login Admin
Gambar 12. Tampilan Form Login Admin
2) Tampilan Dashboard Admin
Gambar 13. Tampilan Dashboard Admin
16

3) Tampilan Data Siswa Kelas Dan Jurusan
Gambar 14. Tampilan Data Siswa Kelas Dan Jurusan
4) Tampilan Fitur Tambah Kelas
Gambar 15. Tampilan Fitur Tambah Kelas
17

5) Tampilan Tambah Jurusan
Gambar 16. Tampilan Tambah Jurusan
6) Tampilan Kelola Absensi
Gambar 17. Tampilan Kelola Absensi
18

7) Tampilan Mengatur Jam Masuk
Gambar 18. Tampilan Mengatur Jam Masuk
8) Tampilan Mengatur Libur Nasional
Gambar 19. Tampilan Mengatur Libur Nasional
19

9) Tampilan Seluruh Data Siswa
Gambar 20. Tampilan Seluruh Data Siswa
10) Tampilan Data Izin Siswa
Gambar 21. Tampilan Data Izin Siswa
20

11) Tampilan Data Libur Siswa
Gambar 22. Tampilan Data Libur Siswa
12) Tampilan Hasil Absen Siswa
Gambar 23. Tampilan Hasil Absen Siswa
21

13) Modal Title
Gambar 24. Tampilan Modal Title
14) Tampilan Kamera
Gambar 25. Tampilan Kamera
22

Prototype
A. Versi Mobile
1. Tampilan Mobile Siswa
1) Tampilan Login Siswa
Gambar 26. Tampilan login
2) Tampilan Dashboard
Gambar 27. Tampilan dashboad
23

3) Profil Siswa
Gambar 28. Tampilan Profil Siswa
4) Form Permintaan Tidak Masuk
Gambar 29. TampilanForm Permintaan Tidak Masuk
24

5) Halaman QR Code
Gambar 30. Tampilan halaman Scan QR
2. Tampilan Mobil Administrator
1) Tampilan Login Admin
Gambar 31. Tampilan Login Admin
25

2) Tampilan Beranda Admin
Gambar 32. Tamplan Beranda Admin
3) Data Kelas Jurusan
Gambar 33. Data Kelas Jurusan
26

4) Tambah Jurusan
Gambar 34. Tambah Jurusan
5) Kelola Absen
Gambar 35. Kelola Absen
27

6) Mengatur Jam Masuk
Gambar 36. Mengatur Jam Masuk
7) Mengatur Libur Nasional
Gambar 37. Mengatur Libur Nasional
28

8) Tampilan Data Siswa
Gambar 38. Tampilan Data Siswa
9) Tampilan Izin Siswa
Gambar 39. Tampilan izin Siswa
29

10) Data Libur Siswa
Gambar 40. Data Libur Siswa
11) Hasil Absensi Siswa
Gambar 41. Hasil Absensi Siswa
30

B. Versi Desktop
1. Tampilan Web Siswa
a. Tampilan Login Siswa
Gambar 42. Tampilan Login Siswa
b. Tampilan Profil Siswa
Gambar 43. Tampilan Profil Siswa
31

c. Tampilan Form Permintaan Tidak Masuk
Gambar 44. Tampilan Form Izin
d. Halaman Scan QR
Gambar 45. Halaman Scan QR Code
32

2. Tampilan Web Administrator
1) Tampilan Login Admin
Gambar 46. Tampilan Login Admin
2) Tampilan Dashboard Admin
Gambar 47. Tampilan Dashboard Admin
33

3) Tampilan Data Siswa Kelas dan Jurusan
Gambar 48. Tampilan Data Siswa, Kelas dan Jurusan
4) Tampilan Tambah Jurusan
Gambar 49. Tampilan Tambah Jurusan
34

5) Tampilan Fitur Tambah Kelas
Gambar 50. Tampilan Fitur Tambah Kelas
6) Tampilan Kelola Absensi
Gambar 51. Tampilan Kelola Absensi
35

7) Tampilan Mengatur Jam Masuk
Gambar 52. Tampilan Mengatur Jam Masuk
8) Tampilan Mengatur Libur Nasional
Gambar 53. Tampilan Mengatur Libur Nasional
36

9) Tampilan Seluruh Data Siswa
Gambar 54. Tampilan Seluruh Data Siswa
10) Tampilan Data Izin Siswa
Gambar 55. Tampilan Data Izin Siswa
37

11) Tampilan Data Libur Siswa
Gambar 56. Tampilan Data Libur Siswa
12) Tampilan Hasil Absen Siswa
Gambar 57. Tampilan Hasil Absen Siswa
38

FASE 4
CONTENT CREATION (PEMBUATAN KONTEN)
Typography
Color Style
39

Gambar
Logo :
Filosofi Logo Sistem Informasi Absensi dengan QR Code :
Logo sistem informasi absensi dengan QR code memiliki beberapa elemen kunci:
● QR Code: Simbol teknologi digital dengan garis unik
● Huruf "C" dan "C terbalik": Melambangkan saling keterhubungan
● Warna Biru, putih dan orange :
Warna biru : melambangkan kepercayaan dan stabilitas, hal ini menunjukkan bahwa
sistem informasi absensi siswa ini dapat diandalkan dan menghasilkan data yang akurat.
Warna putih : melambangkan kesederhanaan dan kejelasan. Hal ini menunjukkan
bahwa sistem informasi absensi siswa ini mudah digunakan dan dipahami.
Warna oranye : melambangkan energi, semangat, dan optimisme. Hal ini menunjukkan
bahwa sistem informasi absensi siswa ini dirancang untuk meningkatkan motivasi dan
semangat belajar siswa.
Filosofi yang dapat diinterpretasikan dari logo tersebut adalah:
● Modernisasi dan Efisiensi: Sistem absensi dengan QR code merupakan solusi modern
dan efisien untuk mencatat kehadiran siswa.
● Keakuratan dan Keandalan: Penggunaan QR code memastikan akurasi dan
keandalan data absensi.
● Keterbukaan dan Transparansi: Sistem absensi yang transparan dan mudah diakses
oleh semua pihak yang berkepentingan.
● Kemajuan dan Inovasi: Sistem absensi yang terus berkembang dan berinovasi untuk
memenuhi kebutuhan absensi siswa di masa depan.
● Keterhubungan dan Kolaborasi: Sistem absensi yang menghubungkan dan
mempermudah kolaborasi antara guru dan siswa
Kesimpulan:
Logo sistem informasi absensi dengan QR code mencerminkan filosofi modernisasi, efisiensi,
keakuratan, keterbukaan, kemajuan, dan kolaborasi. Logo ini diharapkan dapat menarik
perhatian pengguna dan memberikan gambaran tentang manfaat yang ditawarkan oleh sistem
absensi tersebut.
40

Tombol dan Ikon
41

FASE 5
CODE AND DEVELOPMENT (KODE DAN PENGEMBANGAN)

  Tech Stack
         Dalam mengembangkan website QRAccess, kami menggunakan beberapa tech
stack diantaranya adalah :
| a. Framework            |     | : PHP Codeigniter, Bootstrap     |
| ----------------------- | --- | -------------------------------- |
| b. Database             |     | : MySQL                          |
| c Text Editor           |     | : Visual Studio Code             |
| d. Bahasa Pemrograman   |     | : PHP, CSS, Javascript           |
| e. Server               |     | : Localhost (Local Development)  |

  Tampilan Coding
1) Login Siswa

2) Dashboard Siswa

42

3) Login User
4) Dashboard User
43

5) Camera
6) Pengaturan Waktu Absen
44

7) Rekap Absensi
45

FASE 6
TESTING AND QUALITY ASSURANCE
(PENGUJIAN DAN JAMINAN KUALITAS)
Blackbox Testing
1. Testing Login
Hasil
No. Skenario Pengujian Test case Hasil yang diharapkan Kesimpulan
Pengujian
1. Mengetikan email dan password Username: (kosong) Sistem akan menolak lalu muncul Sesuai dengan Valid
tidak diisi kemudian klik tombol pesan “the email field is required” harapan
Password: (kosong)
masuk dan “the password field is required”
2. Mengetikan email tidak diisi dan Username: (kosong) Sistem akan menolak lalu muncul Sesuai dengan Valid
password diisi kemudian klik Password: (admin) pesan “the email field is required” harapan
tombol masuk
3. Mengetikan email dengan benar Username: (benar) Sistem akan menolak lalu muncul Sesuai dengan Valid
dan password salah kemudian Password: (salah) pesan “Failed password yang anda harapan
klik tombol masuk masukkan salah!”
4. Mengetikan email dengan salah Username: (salah) Sistem akan menolak lalu muncul Sesuai dengan Valid
dan password benar kemudian Password: (benar) pesan “Failed Email tidak harapan
klik tombol masuk terdaftar!”
5. Mengetikkan username dan Username: (benar) Login berhasil dan akan muncul Sesuai dengan valid
password dengan benar Password: (benar) halaman dashboard admin harapan
kemudian klik tombol masuk
Tabel 9. Hasil Pengujian Fitur Login
46

2.  Hasil Pengujian Black Box Testing Fitur Admin

No.  Skenario Pengujian  Test case  Hasil yang diharapkan  Hasil Pengujian  Kesimpulan
1.    Kelola absensi  Admin berhasil melihat dan  Data berhasil tampil dengan benar  Sesuai dangan harapan  Valid
|     | mengelola data absensi  | dan  dapat diedit oleh admin  |
| --- | ----------------------- | ----------------------------- |
2.    Absen manual dan  Admin melakukan absen  Data absen manual berhasil dicatat  Sesuai dangan harapan  Valid
| rekap absen perbulan  | manual dan melakukan rekap  | dan rekap absen perbulan  |
| --------------------- | --------------------------- | ------------------------- |
|                       | absen perbulan              | ditampilkan dengan benar  |
3.    Atur jam absen masuk,  Admin mengatur jam absen  Kamera absen on/of sesuai dengan  Sesuai dangan harapan  Valid
| keluar, dan terlambat   | sesuai ketentuan   | jam yang diatur  |
| ----------------------- | ------------------ | ---------------- |
4.    Atur libur weekend  Admin mengatur libur  Data libur weekend dan nasianal  Sesuai dangan harapan  Valid
| dan nasional   | weekend dan nasional   | berhasil disimpan   |
| -------------- | ---------------------- | ------------------- |
5.    Kelola kelas dan  Admin mengelola kelas dan  Data kelas dan jurusan berhasil  Sesuai dangan harapan  Valid
| jurusan   | jurusan  | ditambah, diedit, atau dihapus  |
| --------- | -------- | ------------------------------- |
6.    Melihat siswa sesuai  Admin melihat siswa  Data siswa tampil sesuai dengan  Sesuai dangan harapan  Valid
| kelas dan jurusan   | berdasarkan kelas dan  | kelas dan jurusan yang dipilih  |
| ------------------- | ---------------------- | ------------------------------- |
jurusan
7.    CRUD data siswa dan  Admin mengelola data siswa  Data siswa dan izin berhasil  Sesuai dangan harapan  Valid
| data izin   | dan data izin   | ditambah, diubah, dihapus  |
| ----------- | --------------- | -------------------------- |
8.    Kelola user staff dan  Admin mengelola user staff  Data user staff dan siswa berhasil  Sesuai dangan harapan  Valid
user siswa  dan user siswa   ditambah, diubah, atau dihapus  (berhasil dan terdapat
pesan/notifikasi sukses)
9.    Login  Admin berhasil login   Admin berhasil login dan diarahkan  Sesuai dangan harapan  Valid
ke dashboard
Tabel 10. Hasil Pengujian Fitur Admin

47

3.  Hasil Pengujian Black Box Testing Fitur Siswa

No  Skenario Pengujian  Test case  Hasil yang diharapkan  Hasil  Kesimpulan
Pengujian
1.    Download Kartu Absen  Siswa berhasil mengunduh  Kartu absen dengan QR code berhasil  Sesuai dengan  Valid
(QR Code)  kartu absen dengan QR code  diunduh dalam format yang sesuai  harapan
2.    Izin Tidak Masuk Sekolah  Siswa mengirim permohonan  Permohonan izin berhasil dikirim dan  Sesuai dengan  Valid
| izin tidak masuk  | masuk ke dalam daftar pemohonan izin  | harapan   |
| ----------------- | ------------------------------------- | --------- |
3.    Absen Masuk Siswa  Siswa berhasil melakukan  Status kehadiran siswa tercatat sebagai  Sesuai dengan  Valid
| absen masuk menggunakan  | “Hadir”  | harapan  |
| ------------------------ | -------- | -------- |
QR code
4.    Absen Keluar Siswa  Siswa berhasil melakukan  Status kehadiran siswa tercatat sebagai  Sesuai dengan  Valid
| absen keluar mengggunakan  | “Keluar”  | harapan  |
| -------------------------- | --------- | -------- |
QR code
5.    Lihat Data Libur dan Data  Siswa dapat melihat data  Tampilan data libur dan izin yang telah  Sesuai dengan  Valid
Izin  libur dan izin yang disetujui  disetujui oleh wali kelas atau guru  harapan
6.    Lihat Data Absen Perbulan  Siswa dapat melihat rekap  Tampilan data rekap absen siswa selama  Sesuai dengan  Valid
| absen per bulan  | satu bulan  | harapan  |
| ---------------- | ----------- | -------- |
7.    Change Password  Siswa berhasil mengubah  Kata sandi berhasil di ubah, dan siswa  Sesuai dengan  Valid
| kata sandi  | dapat login menggunakan kata sandi baru  | harapan  |
| ----------- | ---------------------------------------- | -------- |
Tabel 11. Hasil Pengujian Fitur Siswa
48

Usability Testing
a. Halaman Admin
Nama Penguji : Rama Absori 12220253 Kelas 12.4E.13 Bogor (Kelompok 46)
49

User Experience (UX)
A. User Experience Questionnaire (UEQ)
UEQ (User Experience Questionnaire) merupakan alat atau kuesioner yang mudah
dan efisien untuk mengukur User Experience (UX). UEQ ini memudahkan kita untuk
mengukur UX pada sebuah desain aplikasi. UEQ berisi 6 skala penilaian, yaitu:
1. Daya Tarik (Attractiveness): Apakah pengguna menyukai atau tidak menyukai
produk?
2. Kejelasan (Perspicuity): Apakah mudah untuk mengenal produk? Apakah
mudah untuk
3. belajar bagaimana gunakan produknya?
4. Efisiensi (Efficiency): Bisakah pengguna menyelesaikan tugas mereka tanpa
usaha yang
5. sederhana?
6. Ketepatan (Dependability): Apakah pengguna merasa terkendali terhadap
interaksi?
7. Stimulasi (Stimulation): Apakah menarik dan memotivasi untuk menggunakan
produk
8. Kebaruan (Novelty): Apakah produk itu inovatif dan kreatif? Apakah produk
menangkap minat pengguna?
UEQ memiliki 26 komponen pertanyaan dan 7 pilihan jawaban. UEQ dalam bahasa
aslinya menggunakan bahasa Inggris. Namun sudah ada penelitian atau sebuah paper
yang sudah membuat UEQ menjadi bahasa Indonesia pada penelitian Santoso (2016).
Berikut daftar pertanyaan dari UEQ:
50

B. Pengantar Kuesioner untuk Responden
Halo,
Terima kasih telah meluangkan waktu Anda untuk berpartisipasi dalam kuesioner ini. Kami
sedang mengembangkan Aplikasi Absensi Siswa berbasis web yang menggunakan QR Code
untuk memudahkan proses absensi. Umpan balik Anda akan sangat membantu kami dalam
menciptakan aplikasi yang lebih mudah digunakan dan bermanfaat bagi siswa.
Kuesioner ini hanya akan memakan waktu sekitar 5-10 menit untuk diselesaikan. Semua
jawaban Anda akan dirahasiakan dan hanya digunakan untuk tujuan penelitian.
Kuesioner dapat diakses melalui tautan berikut :
https://tinyurl.com/qraccess-uxq
Aplikasi dapat diakses melalui tautan berikut:
HALAMAN ADMIN
…..
email : admin@gmail.com
password : 123456
HALAMAN SISWA
…..
NIS : 1234567890
password : 123456
AKSES KAMERA ABSENSI
……
Salam,
Kelompok 49
51

C. Hasil Quesioner
Berdasarkan UXQ tools
D. Saran dan masukan
Berikut adalah saran dan masukan dari responden yang kami himpun untuk
pengembangan QR Access :
52

FASE 7
DEPLOYMENT AND MAINTENANCE
(PENERAPAN DAN PEMELIHARAAN)
Deployment
I. Penerapan Fitur Siswa:
1. Download Kartu Absen (QR Code): Implementasi fitur yang memungkinkan siswa
untuk mengunduh kartu absen yang berisi QR Code. Pastikan sistem mampu
menghasilkan QR Code unik untuk setiap siswa dan kartu ini dapat diakses melalui
dashboard siswa.
2. Izin Tidak Masuk Sekolah: Penerapan sistem pengajuan izin di mana siswa dapat
mengajukan izin tidak masuk. Formulir izin akan diajukan melalui portal siswa
dan notifikasi akan dikirimkan kepada wali kelas atau guru untuk persetujuan.
3. Absen Masuk dan Keluar Siswa: Integrasi sistem absen masuk dan keluar
menggunakan QR Code. Kamera di sekolah akan memindai QR Code yang dibawa
oleh siswa untuk mencatat kehadiran mereka.
4. Lihat Data Libur dan Data Izin: Menyediakan dashboard di mana siswa dapat
melihat data hari libur dan izin mereka. Status izin akan diperbarui berdasarkan
persetujuan wali kelas atau guru.
5. Lihat Data Absen Perbulan: Dashboard siswa akan menampilkan rekap absensi
bulanan sehingga siswa dapat memantau kehadiran mereka.
6. Change Password: Implementasi fitur untuk mengubah kata sandi di profil siswa
guna menjaga keamanan akun.
J. Penerapan Fitur Admin:
1. Kelola Absensi:Implementasi fitur untuk memantau dan mengelola data absensi
siswa, termasuk absen manual dan rekap bulanan. Admin dapat mengakses laporan
lengkap absensi siswa.
2. Atur Jam Absen: Admin dapat mengatur jadwal absen masuk, keluar, dan
terlambat. Fitur ini mengontrol kapan kamera absen aktif atau non-aktif.
53

3. Atur Libur Weekend dan Nasional: Admin dapat mengatur hari libur akhir pekan
dan libur nasional dalam sistem, sehingga absensi tidak diperlukan pada hari-hari
tersebut.
4. Kelola Kelas dan Jurusan: Sistem untuk mengelola data kelas dan jurusan,
termasuk penambahan, pengeditan, dan penghapusan data.
5. Melihat Siswa Berdasarkan Kelas dan Jurusan: Admin dapat memfilter dan
melihat data siswa berdasarkan kelas dan jurusan.
6. CRUD Data Siswa dan Data Izin: Admin dapat melakukan operasi Create, Read,
Update, Delete pada data siswa dan data izin, termasuk mengonfirmasi atau
menolak izin.
7. Kelola User Staff dan User Siswa: Admin dapat mengelola data pengguna, baik
staf maupun siswa
Maintenance
A. Pemeliharaan Perangkat Lunak/Sistem Operasi:
1. Update Rutin: Melakukan update rutin pada sistem operasi dan perangkat lunak
untuk memastikan kinerja optimal dan keamanan.
2. Penambahan Fitur: Menambahkan fitur baru sesuai kebutuhan, seperti paket
membership atau peningkatan pada fitur yang sudah ada.
3. Monitoring Performa: Pemantauan terus-menerus terhadap performa sistem
untuk mendeteksi masalah teknis dan melakukan perbaikan segera.
B. Pemeliharaan Data:
1. Pemeriksaan Rutin: Melakukan pemeriksaan dan perbaikan data pada database
secara berkala untuk menjaga integritas dan akurasi data.
2. Backup Data: Melakukan backup data secara berkala untuk memastikan data
aman dan dapat dipulihkan jika terjadi kegagalan sistem.
C. Pemantauan Kinerja Sistem:
1. Pengawasan Server: Memastikan server berjalan dengan lancar dan tidak
mengalami downtime yang signifikan.
54

2. Pengawasan Aplikasi dan Jaringan: Memantau aplikasi dan jaringan untuk
memastikan bahwa pengguna dapat mengakses sistem tanpa gangguan.
3. Respon Cepat: Menyiapkan tim teknis yang siap merespon dengan cepat jika ada
masalah teknis yang mempengaruhi kinerja sistem.
Dengan deployment dan maintenance yang terstruktur dengan baik, sistem QR
Access akan dapat berjalan dengan efisien dan memberikan layanan yang andal bagi
siswa dan admin di Sekolah Menengah Atas.
55

KESIMPULAN
Kesimpulan dari pembahasan ini adalah QR Access dapat menjadi solusi yang efektif untuk
presensi dan manajemen presensi siswa SMA. Sistem ini menawarkan beberapa keuntungan
dibandingkan sistem presensi konvensional, seperti kemudahan penggunaan, penghematan
waktu, dan pengurangan kesalahan manusia. Dengan QR Access, proses presensi dapat
dilakukan secara cepat dan efisien, serta memberikan data yang akurat dan real-time mengenai
kehadiran siswa. Selain itu, implementasi QR Access di SMA dapat mendorong partisipasi
siswa yang lebih baik, meminimalkan potensi kecurangan dalam presensi, dan secara
keseluruhan meningkatkan kualitas pendidikan melalui manajemen yang lebih baik.
56

LAMPIRAN
57

58