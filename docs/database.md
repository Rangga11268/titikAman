# Rancangan Database - TitikAman

Sistem TitikAman menggunakan basis data relasional untuk mengelola status kedaruratan, logistik, pengungsian, dan data pengguna. Di bawah ini adalah detail skema dari 8 tabel utama.

## 1. Tabel: `users`
Tabel bawaan Laravel yang dimodifikasi untuk menyimpan data autentikasi dan informasi tambahan pengguna.
* **`id`** (BigInt, PK, Auto Increment)
* **`name`** (String) - Nama lengkap pengguna.
* **`email`** (String, Unique)
* **`email_verified_at`** (Timestamp, Nullable)
* **`password`** (String)
* **`phone`** (String, Nullable) - Nomor telepon aktif untuk koordinasi darurat.
* **`remember_token`** (String, Nullable)
* **`created_at` / `updated_at`** (Timestamp)

*Catatan: Hak akses/peran (role: warga, relawan, pengelola, admin) diatur secara terpisah menggunakan tabel relasi bawaan package Spatie Laravel Permission.*

---

## 2. Tabel: `water_gates`
Pencatatan kondisi teknis sungai-sungai utama untuk memantau air kiriman (bogor) dan peringatan dini.
* **`id`** (BigInt, PK, Auto Increment)
* **`gate_name`** (String) - Contoh: "Pintu Air Pondok Gede Permai".
* **`river_name`** (String) - Contoh: "Sungai Cileungsi", "Sungai Bekasi".
* **`water_level_cm`** (Integer) - Tinggi Muka Air (TMA) saat ini dalam cm.
* **`danger_status`** (Enum: `Normal`, `Siaga 3`, `Siaga 2`, `Siaga 1`) - Ditentukan otomatis berdasarkan tinggi air.
* **`last_updated`** (Timestamp) - Waktu update TMA terakhir oleh petugas.
* **`created_at` / `updated_at`** (Timestamp)

---

## 3. Tabel: `shelters`
Daftar posko pengungsian darurat beserta kapasitas tampung dan status operasional.
* **`id`** (BigInt, PK, Auto Increment)
* **`shelter_name`** (String) - Contoh: "Masjid Raya Al-Muwahhidin".
* **`address`** (Text) - Alamat posko.
* **`max_capacity`** (Integer) - Kapasitas maksimal jiwa yang ditampung.
* **`current_occupants`** (Integer, Default: 0) - Jumlah pengungsi aktif saat ini.
* **`status`** (Enum: `active`, `full`, `inactive` - Default: `active`)
* **`latitude`** (Decimal, 10,8) - Koordinat GPS lintang.
* **`longitude`** (Decimal, 11,8) - Koordinat GPS bujur.
* **`created_at` / `updated_at`** (Timestamp)

---

## 4. Tabel: `flood_reports`
Laporan genangan banjir berbasis kontribusi mandiri warga (*crowdsourcing*).
* **`id`** (BigInt, PK, Auto Increment)
* **`reporter_id`** (BigInt, FK to `users.id`, Cascade) - ID warga pelapor.
* **`gate_id`** (BigInt, FK to `water_gates.id`, Nullable, Set Null) - Pintu air terdekat untuk validasi silang (opsional).
* **`water_height_cm`** (Integer) - Perkiraan tinggi air dalam cm.
* **`street_name`** (String) - Lokasi detail/nama jalan terdampak.
* **`latitude`** (Decimal, 10,8)
* **`longitude`** (Decimal, 11,8)
* **`photo_evidence`** (String, Nullable) - Jalur file foto bukti genangan air.
* **`status`** (Enum: `waiting`, `verified`, `rejected` - Default: `waiting`)
* **`created_at` / `updated_at`** (Timestamp)

---

## 5. Tabel: `shelter_needs`
Kebutuhan logistik mendesak hasil asesmen pengelola posko di lapangan.
* **`id`** (BigInt, PK, Auto Increment)
* **`shelter_id`** (BigInt, FK to `shelters.id`, Cascade) - Lokasi posko yang membutuhkan.
* **`item_name`** (String) - Contoh: "Selimut", "Susu Bayi Formula".
* **`quantity_needed`** (Integer) - Jumlah barang yang dibutuhkan.
* **`quantity_fulfilled`** (Integer, Default: 0) - Jumlah barang yang sudah terpenuhi/didonasikan.
* **`urgency`** (Enum: `low`, `medium`, `high` - Default: `medium`)
* **`created_at` / `updated_at`** (Timestamp)

---

## 6. Tabel: `donations`
Log kontribusi donatur untuk memenuhi kebutuhan posko secara langsung tanpa jalur birokrasi.
* **`id`** (BigInt, PK, Auto Increment)
* **`donor_id`** (BigInt, FK to `users.id`, Cascade) - ID donatur.
* **`need_id`** (BigInt, FK to `shelter_needs.id`, Cascade) - Kebutuhan barang posko yang ingin dipenuhi.
* **`quantity_donated`** (Integer) - Jumlah barang yang dikirim.
* **`shipping_receipt_no`** (String, Nullable) - Resi kurir pengiriman.
* **`status`** (Enum: `pending`, `shipped`, `received` - Default: `pending`)
* **`donated_at`** (Timestamp) - Waktu pengiriman donasi.
* **`created_at` / `updated_at`** (Timestamp)

---

## 7. Tabel: `sos_requests`
Permintaan evakuasi darurat dari warga yang terjebak banjir di rumahnya.
* **`id`** (BigInt, PK, Auto Increment)
* **`sender_id`** (BigInt, FK to `users.id`, Cascade) - ID warga yang meminta bantuan.
* **`latitude`** (Decimal, 10,8) - Lokasi persis korban terjebak.
* **`longitude`** (Decimal, 11,8)
* **`people_trapped`** (Integer, Default: 1) - Jumlah total orang di lokasi.
* **`elderly_count`** (Integer, Default: 0) - Jumlah lansia (kelompok rentan).
* **`infant_count`** (Integer, Default: 0) - Jumlah balita (kelompok rentan).
* **`pregnant_count`** (Integer, Default: 0) - Jumlah ibu hamil (kelompok rentan).
* **`description`** (Text, Nullable) - Catatan khusus (misal: "butuh obat asma").
* **`status`** (Enum: `waiting`, `assigned`, `resolved` - Default: `waiting`)
* **`created_at` / `updated_at`** (Timestamp)

---

## 8. Tabel: `rescue_missions`
Tabel pencatatan misi penyelamatan korban SOS oleh relawan.
* **`id`** (BigInt, PK, Auto Increment)
* **`sos_id`** (BigInt, FK to `sos_requests.id`, Cascade) - Sinyal SOS yang ditangani.
* **`volunteer_id`** (BigInt, FK to `users.id`, Cascade) - ID relawan/personel penyelamat.
* **`assigned_at`** (Timestamp) - Waktu penugasan relawan.
* **`resolved_at`** (Timestamp, Nullable) - Waktu korban berhasil tiba di posko terry-safe.
* **`created_at` / `updated_at`** (Timestamp)
