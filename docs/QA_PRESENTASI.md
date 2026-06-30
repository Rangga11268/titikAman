# Q&A Presentasi TitikAman — Bootcamp

> Dokumen ini berisi kemungkinan pertanyaan dari penguji/audience beserta jawaban yang sudah disiapkan per slide. Presenter tinggal membaca jawaban yang sudah disediakan.

---

## Slide 1-2: Nama Tim & Judul (Presenter: Darell)

*Tidak ada Q&A spesifik untuk slide ini — perkenalan tim dan judul.*

---

## Slide 3: Latar Belakang & Masalah (Presenter: Wahyu)

### Q1:

Berdasarkan data BNPB Maret 2025, dari total 37.058 KK terdampak banjir di Jabodetabek, **18.738 KK (50,5%) berada di Kota Bekasi** — menjadikannya wilayah terdampak paling masif. Selain itu, tim kami berasal dari daerah Bekasi dan sekitarnya, sehingga kami memahami langsung kondisi dan kebutuhan di lapangan.

### Q2:

Sejauh ini BPBD Kota Bekasi sudah memiliki sistem peringatan dini, tetapi **belum ada platform terpadu yang menghubungkan warga, relawan, posko, dan BPBD dalam satu ekosistem real-time**. Sebagian besar koordinasi evakuasi masih menggunakan telepon dan WhatsApp secara manual, tanpa data yang terstruktur.

### Q3:

Kami memfokuskan pada **Kota Bekasi** karena:

1. Bekasi adalah wilayah terdampak paling parah saat banjir Maret 2025
2. Koordinasi yang efektif membutuhkan batasan wilayah yang jelas
3. Sistem sudah dibekali **bounding box GPS** yang membatasi semua transaksi hanya di area Bekasi (Lat: -6.350 s.d -6.100, Lng: 106.800 s.d 107.100)
4. Ke depannya sistem bisa dikembangkan untuk wilayah lain dengan konfigurasi bounding box yang berbeda

### Q4:

Awalnya kami merancang 4 role aktif (Warga, Admin Relawan, Pengelola Posko, Admin BPBD). Role **Relawan di database hanya sebagai label data** — karena relawan lapangan bekerja melalui instruksi WhatsApp dari Admin Relawan, bukan melalui dashboard. Ini menyederhanakan sistem tanpa mengurangi efektivitas koordinasi.

### Q5:

Data tersebut kami kutip dari **laporan resmi BNPB** (Badan Nasional Penanggulangan Bencana) per Maret 2025 yang dirilis melalui kanal resmi dan pemberitaan nasional.

---

## Slide 4: Solusi (Presenter: Maul)

### Q6:

Prioritas dihitung otomatis berdasarkan 2 variabel:

| Kondisi | Prioritas |
|---------|-----------|
| Ada kelompok rentan (lansia, balita, ibu hamil, disabilitas) **ATAU** ≥ 5 orang terjebak | **HIGH** |
| 3-4 orang terjebak (tanpa rentan) | **MEDIUM** |
| < 3 orang terjebak (tanpa rentan) | **LOW** |

### Q7:

Ada **2 lapis validasi**:

1. **Lapisan 1 — Sistem**: Koordinat GPS diverifikasi apakah berada di dalam bounding box Bekasi. Foto bukti genangan diwajibkan (tidak bisa submit tanpa foto).
2. **Lapisan 2 — Admin BPBD**: Setiap laporan masuk sebagai status "pending" dan harus diverifikasi secara manual oleh Admin BPBD — lihat foto, cek lokasi, baru di-approve atau ditolak.

### Q8:

| Aspek | TitikAman | Platform Lain |
|-------|-----------|---------------|
| SOS + Prioritas | Otomatis hitung prioritas berdasarkan data korban | Biasanya hanya form biasa |
| Penugasan Tim | Dashboard Mission Control + WA integration | Biasanya hanya notifikasi |
| Verifikasi Laporan | 2 lapis (sistem + admin) | Sering hanya 1 lapis |
| Donasi Publik | Langsung terverifikasi + update stok otomatis | Sering hanya pengumuman |
| TMA Real-time | Data dari pintu air resmi, update manual oleh admin | - |

### Q9:

Data TMA diinput oleh Admin BPBD melalui form update (tidak otomatis dari sensor). Yang real-time adalah **status bahaya** yang otomatis dihitung oleh sistem setelah admin memasukkan tinggi air. Sistem juga akan mengirim notifikasi peringatan dini ke kecamatan terdampak jika batas Siaga terlampaui.

### Q10:

Laravel menyediakan:

- **Eloquent ORM** — memudahkan relasi antar tabel yang kompleks (users, sos, missions, donations)
- **Form Request Validation** — validasi input yang terpusat (termasuk bounding box Bekasi)
- **Broadcasting (Reverb)** — fitur real-time untuk update status SOS
- **Queue & Jobs** — untuk export CSV dan notifikasi yang tidak memblokir response
- Ekosistem yang luas dengan dokumentasi lengkap

### Q11:

Leaflet.js adalah **open source dan gratis** (license BSD), tidak perlu API key atau kartu kredit. Google Maps memerlukan API key dengan billing aktif. Untuk kebutuhan peta dasar, kami menggunakan tile dari **CartoDB** yang gratis untuk penggunaan non-komersial.

### Q12:

Sistem punya **beberapa mekanisme**:

1. **Fitur Backup** — SOS yang sudah ditugaskan ke satu tim tetap bisa dibantu tim lain. Admin Relawan tinggal klik **"Kirim Bantuan Tim"**, pilih tim lain sebagai backup. Satu SOS bisa punya banyak misi dari tim berbeda.

2. **Dropdown semua tim** — Admin Relawan bisa melihat dan memilih tim dari kecamatan MANAPUN, tidak terbatas pada kecamatan korban. Jadi kalau tim Bekasi Timur sedang sibuk, bisa tugaskan tim Bekasi Utara atau Jatiasih.

3. **Grup WhatsApp Gabungan** — Ada grup WhatsApp khusus untuk koordinasi antar tim. Kalau semua tim sibuk, Admin Relawan bisa broadcast ke grup gabungan minta relawan dari tim mana pun yang bisa bergerak.

4. **Tim lintas wilayah** — Relawan tidak terikat hanya di kecamatannya. Seorang relawan dari Bekasi Selatan bisa ditugaskan ke lokasi di Bekasi Timur. Fleksibilitas ini yang membedakan sistem kami dari sistem manual yang ada saat ini.

---

## Slide 5: Fitur Unggulan (Presenter: Maul)

> *Pertanyaan untuk slide ini sama dengan Slide 4 (Solusi). Pertanyaan tentang fitur spesifik bisa dijawab oleh Maul.*

---

## Slide 6: Demo LIVE (Presenter: Putri)

### Q13:

**Fitur SOS Darurat** — karena langsung menyentuh aspek keselamatan jiwa. Dalam 1 klik, warga bisa mengirim sinyal evakuasi lengkap dengan:
- Koordinat GPS real-time
- Prioritas otomatis berdasarkan jumlah korban & kelompok rentan
- Langmasuk antrian Admin Relawan yang bisa langsung menugaskan tim SAR

### Q14:

SOS tetap bisa dikirim. Koordinat akan menggunakan default (pusat Kota Bekasi). Tapi pengguna akan mendapat peringatan untuk mengaktifkan GPS agar lokasi lebih akurat.

Selain itu, ada juga **SMS offline** — warga bisa mengirim SOS melalui SMS ke nomor gateway yang sudah dikonfigurasi.

### Q15:

**Ya, tetap jalan.** Semua event broadcast (SOS baru, laporan diverifikasi) sudah kami bungkus dalam try-catch. Jika Reverb mati, sistem tetap menyimpan data ke database dan mencatat error ke log. Relawan tetap bisa merefresh halaman untuk melihat data terbaru.

### Q16:

Saat ini TitikAman adalah **responsive web app** — bisa diakses lewat browser HP tanpa perlu install aplikasi. Tampilan sudah dioptimalkan untuk layar kecil (mobile-first).

### Q17:

1. Warga kirim SOS → muncul di antrian dashboard Admin Relawan
2. Admin Relawan klik **"Tugaskan ke Tim"** → pilih tim dari dropdown
3. Sistem membuat mission record dan menampilkan banner dengan tombol **WA ke Relawan**
4. Klik tombol WA → otomatis redirect ke WhatsApp dengan pesan pre-filled berisi: nama pelapor, lokasi, prioritas, dan link Google Maps
5. Tim yang ditugaskan menerima instruksi dan bergerak ke lokasi

### Q18:

Validasi ada di **lapisan Admin Relawan**. Admin bisa melihat detail SOS (nama pelapor, jumlah korban, koordinat) sebelum memutuskan untuk menugaskan tim. Kalau ragu, Admin bisa menghubungi pelapor via WhatsApp untuk konfirmasi.

---

## Slide 7: Testing & Hasil (Presenter: Darell)

### Q19:

| Jenis Test | Jumlah | Status |
|-----------|--------|--------|
| PHPUnit (Unit + Feature) | 59 test cases, 200 assertions | ✅ Pass |
| Black Box Testing | 54 test case — 7 area fitur | ✅ Pass |
| Cakupan Role | Warga, Admin Relawan, Pengelola Posko, Admin BPBD | ✅ Tercover |

### Q20:

**11 area fitur** yang diuji melalui PHPUnit:

1. Login via email dan nomor HP
2. Registrasi 3 role (Warga auto-approve, Relawan & Pengelola pending)
3. Auto-redirect pending/approved/rejected
4. Lapor banjir + upload foto
5. SOS + prioritas otomatis
6. Tugaskan misi ke tim
7. Selesaikan misi
8. Review & approve anggota
9. Export CSV (laporan, misi, donasi, TMA)
10. Update shelter & kebutuhan logistik
11. Verifikasi donasi

### Q21:

Ada **3 lapis error handling**:

1. **Validation layer** — Form Request dengan pesan error dalam Bahasa Indonesia
2. **Try-catch di Service layer** — Semua event broadcast dibungkus try-catch agar tidak 500 saat WebSocket mati
3. **Custom error pages** — Halaman 403, 404, 419, 500 dengan branding TitikAman

### Q22:

Kami menerapkan:

- **CSRF Protection** — semua form POST menggunakan token CSRF
- **Role Middleware** — setiap route dicek rolenya (`role:Warga`, `Admin_Relawan`, dll)
- **Middleware EnsureUserApproved** — user dengan status pending/rejected tidak bisa akses dashboard
- **Validasi bounding box** di backend — mencegah input koordinat palsu di luar Bekasi

---

## Slide 8: Penutup (Presenter: Darell)

### Q23:

Beberapa fitur yang kami rencanakan:

1. **Notifikasi Push (FCM)** — peringatan banjir langsung ke HP warga tanpa perlu buka browser
2. **Sensor IoT TMA** — data tinggi air otomatis dari sensor di pintu air (tidak perlu input manual)
3. **Multi-wilayah** — bounding box bisa dikonfigurasi untuk kabupaten/kota lain
4. **Dashboard untuk Relawan lapangan** — agar relawan bisa update status langsung dari HP
5. **Integrasi dengan Google Maps API** — rute evakuasi tercepat

### Q24:

**Kendala terbesar adalah integrasi real-time dengan WebSocket (Laravel Reverb)** — karena butuh konfigurasi server yang tepat agar koneksi stabil. Kami juga mengalami kesulitan dalam merancang alur multi-role yang tetap sederhana untuk pengguna awam.

### Q25:

Saat ini pengujian masih terbatas pada **simulasi internal dan testing otomatis (59 test case)**. Kami belum melakukan uji coba langsung ke BPBD karena keterbatasan waktu menjelang bootcamp ini. Ke depannya kami sangat terbuka untuk uji coba lapangan.

### Q26:

Kurang lebih **3-4 minggu** untuk keseluruhan sistem — mulai dari riset, desain database, implementasi fitur, testing, hingga dokumentasi.

---

## Q&A Tambahan — Website & Teknis

### Q27:

**Ya, open source.** Repository ada di GitHub:
https://github.com/Rangga11268/titikAman

### Q28:

1. PHP 8.2+
2. Composer
3. Node.js 18+
4. MySQL/MariaDB atau SQLite
5. Web Server (Apache/Nginx atau `php artisan serve`)

Langkah lengkap ada di `BUKU_PANDUAN.md` — tinggal ikuti 13 langkah instalasi.

### Q29:

Kami menggunakan **Tailwind CSS v4** karena lebih fleksibel dalam Mendesain tampilan custom dan menghasilkan file CSS yang lebih kecil (tree-shaking). Bootstrap memang lebih cepat untuk prototyping, tapi untuk project dengan UI yang spesifik seperti dashboard multi-panel relawan, Tailwind memberikan kontrol yang lebih baik.

### Q30:

**MySQL/MariaDB** (bisa juga SQLite untuk development). Total ada **8 tabel utama**:

| No | Tabel | Fungsi |
|----|-------|--------|
| 1 | users | Data pengguna |
| 2 | water_gates | Data pintu air & TMA |
| 3 | shelters | Data posko |
| 4 | flood_reports | Laporan banjir |
| 5 | shelter_needs | Kebutuhan logistik |
| 6 | donations | Donasi |
| 7 | sos_requests | Permintaan SOS |
| 8 | rescue_missions | Misi penyelamatan |

### Q31:

Tidak ada chat internal. Kami menggunakan **integrasi WhatsApp** untuk koordinasi karena:

1. WhatsApp sudah digunakan oleh hampir semua relawan dan warga
2. Tidak perlu install aplikasi tambahan
3. Notifikasi lebih cepat sampai (push notification bawaan WA)
4. Grup WhatsApp sudah menjadi standar koordinasi SAR di Indonesia

### Q32:

Login menggunakan **email atau nomor HP** + password (registrasi mandiri). Tidak ada login Google/OAuth untuk menjaga kesederhanaan sistem dan karena target pengguna mungkin tidak semuanya memiliki akun Google.

### Q33:

- Semua password di-hash menggunakan **bcrypt**
- Semua form dilindungi **CSRF token**
- Upload file disimpan di **storage lokal** (bukan cloud publik)
- Role-based access — setiap user hanya bisa mengakses fitur sesuai rolenya
- Sistem menggunakan **session-based authentication**, bukan token JWT yang lebih rawan

---

## Lampiran: Pembagian Presenter

| Slide | Konten | Presenter |
|-------|--------|-----------|
| 1-2 | Nama Tim & Judul | **Darell** |
| 3 | Latar Belakang & Masalah | **Wahyu** |
| 4 | Solusi | **Maul** |
| 5 | Fitur Unggulan | **Maul** |
| 6 | Demo LIVE | **Putri** |
| 7 | Testing & Hasil | **Darell** |
| 8 | Penutup | **Darell** |
