# 🤖 AGENT RULES — TitikAman

Dokumen ini adalah panduan wajib bagi AI agent maupun developer dalam mengerjakan proyek **TitikAman** (Sistem Manajemen Kebencanaan Banjir Kota Bekasi). Baca seluruh rules ini sebelum mulai mengerjakan task apapun.

---

## 🎯 Fokus Proyek

TitikAman adalah **platform tanggap darurat banjir** berbasis web yang menghubungkan:
- **Warga** terdampak (laporan genangan, SOS evakuasi)
- **Relawan** lapangan (misi penyelamatan, pemetaan lapangan)
- **Pengelola Posko** (manajemen kapasitas, kebutuhan logistik)
- **Admin BPBD/Kelurahan** (validasi data, pemantauan Tinggi Muka Air)

**Stack utama**: Laravel 12 (PHP 8.2+), MySQL, Leaflet.js + OpenStreetMap, Laravel Reverb (WebSocket), Blade templating.

---

## ⚙️ RULES BACKEND (PRIORITAS UTAMA)

### 1. Struktur & Arsitektur
- **WAJIB** mengikuti pola **Service-Repository** untuk logika bisnis kompleks (SOS routing, notifikasi, geospasial).
- Controller harus **tipis (thin)** — tidak boleh ada logika bisnis langsung di dalam controller. Delegasikan ke Service class.
- Gunakan **Form Request** untuk validasi semua input — jangan taruh `$request->validate()` langsung di controller.
- Seluruh interaksi database harus melalui **Eloquent Model** — hindari raw query kecuali untuk Haversine (jarak terdekat).
- Gunakan **Laravel Resource** (`JsonResource`) untuk semua response JSON API — jangan return `$model->toArray()` langsung.

### 2. Penamaan & Konvensi Kode
- Nama file mengikuti konvensi Laravel: `UserController`, `FloodReportService`, `ShelterRepository`.
- Nama method: `camelCase` untuk PHP, gunakan kata kerja deskriptif (`storeSosRequest`, `findNearestShelter`).
- Nama kolom database: `snake_case`.
- Konstanta: `SCREAMING_SNAKE_CASE` di dalam class atau config file.
- Hindari magic number — gunakan konstanta atau config value.

### 3. Keamanan
- **WAJIB** gunakan Policy & Gate untuk otorisasi. Jangan cek role manual (`if ($user->role === 'admin')`) langsung di controller — buat Policy-nya.
- Semua upload file foto (bukti banjir, donasi) **WAJIB** divalidasi tipe MIME, ukuran maksimal 5MB, dan dikompres menggunakan `intervention/image` sebelum disimpan.
- Jangan pernah expose `id` integer di URL publik untuk data sensitif (SOS, user). Gunakan **UUID** atau **hashids** jika diperlukan.
- Seluruh API endpoint yang membutuhkan autentikasi harus dilindungi middleware `auth:sanctum`.

### 4. Real-Time & Event (Laravel Reverb)
- Setiap perubahan status kritikal (status SOS berubah, TMA naik ke Siaga 1, laporan banjir diverifikasi) **WAJIB** men-dispatch Event Laravel.
- Event harus implement `ShouldBroadcast` agar diteruskan ke Reverb WebSocket.
- Beri nama channel dengan format: `disaster.{kecamatan_slug}` atau `sos.{id}`.
- Jangan lakukan broadcasting langsung dari controller — selalu lewat Event + Listener.

### 5. Queue & Jobs
- Semua pekerjaan berat (kirim email, push notification FCM, kompresi foto, export Excel) **WAJIB** didelegasikan ke **Laravel Job** yang berjalan di background queue.
- Gunakan `dispatch()->afterResponse()` untuk job yang tidak butuh hasil langsung.
- Setiap Job harus implement `$tries = 3` dan `$backoff = [10, 60, 300]` untuk retry otomatis.

### 6. Database & Migrasi
- Setiap tabel baru **WAJIB** memiliki migration tersendiri — jangan edit migration yang sudah pernah dijalankan.
- Selalu sertakan `$table->index()` pada kolom yang sering digunakan di `WHERE`, `ORDER BY`, dan FK.
- Gunakan `softDeletes()` untuk tabel data operasional (flood_reports, sos_requests, donations).
- Seeders harus menggunakan **Factory** — tidak boleh hard-code data dummy langsung di Seeder.

### 7. Geospasial (Peta & Koordinat)
- Koordinat disimpan sebagai `DECIMAL(10, 8)` untuk latitude dan `DECIMAL(11, 8)` untuk longitude.
- Pencarian posko/shelter terdekat menggunakan **Haversine Formula** langsung di Eloquent query builder.
- Validasi koordinat di Form Request: latitude range `-90` s.d `90`, longitude range `-180` s.d `180`.

### 8. Testing
- Setiap Service class **WAJIB** memiliki minimal 1 unit test.
- Setiap API endpoint **WAJIB** memiliki feature test (request + response assertion).
- Gunakan `RefreshDatabase` trait — jangan andalkan data live database saat testing.
- Mock eksternal service (FCM, Reverb broadcast) di dalam test menggunakan `Event::fake()` dan `Bus::fake()`.

---

## 🎨 RULES FRONTEND (BLADE / UI)

### 1. Desain & Visual
- **DILARANG** menggunakan ikon bawaan sistem operasi (emoji OS, Windows emoji, macOS emoji). Gunakan ikon dari library konsisten: **Lucide Icons**, **Heroicons**, atau **Phosphor Icons** — pilih satu saja dan konsisten.
- **DILARANG** menggunakan gradient multi-warna yang noisy/ramai. Jika gradient dibutuhkan, gunakan hanya 2 warna dalam satu tone/shade yang sama (misal: `biru-600` ke `biru-800`).
- Palet warna utama proyek adalah **satu warna primer** yang ditetapkan oleh tim UI/UX. Gunakan shade/tint dari warna tersebut untuk variasi (100–900).
- **Ikuti design token dan komponen dari MCP server tim UI/UX** ketika sudah aktif — jangan buat komponen UI sendiri jika sudah tersedia di library tim.
- Selalu gunakan **Google Fonts** (Inter, Outfit, atau yang ditetapkan tim) — jangan gunakan font system fallback bawaan browser.

### 2. Komponen & Layout
- Gunakan **Blade Component** (`<x-button>`, `<x-card>`) untuk elemen yang berulang — jangan copy-paste HTML berulang kali.
- Layout halaman menggunakan `resources/views/layouts/` — wajib pakai `@extends` dan `@section`.
- Responsive-first: desain mobile-first, kemudian desktop.
- Hindari inline style (`style="..."`) — gunakan class CSS atau utility.

### 3. Peta (Leaflet.js)
- Map tiles menggunakan **CartoDB Voyager** (terang) atau **CartoDB Dark Matter** (gelap/malam).
- Marker cluster wajib menggunakan `leaflet.markercluster` jika jumlah marker bisa >50.
- Seluruh interaksi peta (zoom, add marker, update koordinat) dikelola dalam satu file JS modular — jangan taruh script peta di dalam file Blade view.
- Custom marker menggunakan file SVG — jangan gunakan default Leaflet marker (terlihat generik).

### 4. Notifikasi Real-Time (Laravel Echo)
- Echo listener diinisialisasi satu kali saat halaman load — jangan buat duplikasi channel listener.
- Tampilkan toast/snackbar ketika event real-time diterima — jangan reload halaman penuh.

---

## 📋 HAL-HAL YANG WAJIB DIHINDARI

| ❌ Jangan | ✅ Lakukan Ini |
|:---|:---|
| Logika di Controller | Pindahkan ke Service class |
| `$request->validate()` di Controller | Buat Form Request class |
| Return `$model->toArray()` di API | Gunakan Laravel Resource |
| `if ($user->role === 'admin')` di Controller | Buat Gate / Policy |
| Upload file tanpa validasi | Validasi MIME + size di Form Request |
| Job berat di dalam request | Dispatch ke Laravel Queue |
| Emoji OS sebagai ikon | Gunakan Lucide / Heroicons |
| Gradient >2 warna berbeda hue | Gradient satu hue, variasi shade saja |
| Buat komponen UI sendiri jika ada di MCP | Ikuti komponen library tim UI/UX |
| Edit migration yang sudah jalan | Buat migration baru `_alter_xxx` |
| Raw query selain Haversine | Eloquent + Query Builder |

---

## 📚 Referensi Dokumentasi

- [`docs/database.md`](docs/database.md) — Skema 8 tabel utama
- [`docs/RELASI_DATABASE.md`](docs/RELASI_DATABASE.md) — Penjelasan relasi antar-tabel
- [`docs/requirements.md`](docs/requirements.md) — User persona & alur sistem
- [`docs/analisis_peta_dan_library.md`](docs/analisis_peta_dan_library.md) — Stack teknologi & library
- [`docs/SKILLS.md`](docs/SKILLS.md) — Panduan skill & teknik implementasi
- [`docs/COMMIT_CONVENTION.md`](docs/COMMIT_CONVENTION.md) — Konvensi pesan commit Git
