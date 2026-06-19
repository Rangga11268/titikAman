# Rancangan Database - TitikAman

Sistem TitikAman menggunakan basis data relasional untuk mengelola status kedaruratan, logistik, pengungsian, dan data pengguna. Di bawah ini adalah detail skema dari 8 tabel utama.

---

## 1. Tabel: `users`
Tabel untuk mengelola autentikasi dan peran hak akses di dalam sistem.
* **`user_id`** (BigInt, PK, Auto Increment)
* **`fullname`** (String, 100) - Nama lengkap pengguna.
* **`email`** (String, 100, Unique)
* **`password`** (String, 250)
* **`phone`** (String, 20) - Nomor telepon aktif untuk koordinasi.
* **`role`** (Enum: `'Warga'`, `'Relawan'`, `'Pengelola_Posko'`, `'Admin_BPBD'`)
* **`kecamatan`** (String, 100, Nullable) - Kecamatan domisili warga (untuk push notification peringatan dini).
* **`kelurahan`** (String, 100, Nullable) - Kelurahan domisili warga (untuk push notification peringatan dini).
* **`remember_token`** (String, Nullable)
* **`created_at` / `updated_at`** (Timestamp)

---

## 2. Tabel: `water_gates`
Pencatatan kondisi teknis sungai-sungai utama untuk sistem peringatan dini.
* **`gate_id`** (BigInt, PK, Auto Increment)
* **`gate_name`** (String, 100) - Contoh: "Pintu Air Pondok Gede Permai".
* **`river_name`** (String, 100) - Contoh: "Sungai Cileungsi", "Sungai Bekasi".
* **`water_level_cm`** (Decimal, 5,2) - Tinggi Muka Air (TMA) saat ini dalam cm.
* **`danger_status`** (Enum: `'Normal'`, `'Siaga_3'`, `'Siaga_2'`, `'Siaga_1'`)
* **`last_updated`** (Timestamp) - Waktu update TMA terakhir oleh petugas.
* **`created_at` / `updated_at`** (Timestamp)

---

## 3. Tabel: `flood_reports`
Laporan genangan banjir berbasis kontribusi warga (*crowdsourcing*).
* **`report_id`** (BigInt, PK, Auto Increment)
* **`user_id`** (BigInt, FK to `users.user_id`, Cascade) - ID warga pelapor.
* **`water_height_cm`** (Integer) - Perkiraan tinggi air dalam cm.
* **`street_name`** (String, 255) - Lokasi detail/nama jalan terdampak.
* **`latitude`** (Decimal, 10,8)
* **`longitude`** (Decimal, 11,8)
* **`photo_evidence`** (String, 255, Nullable) - Jalur file foto bukti genangan air.
* **`verification_status`** (Enum: `'pending'`, `'verified'`, `'rejected'` - Default: `'pending'`)
* **`created_at` / `updated_at`** (Timestamp)

---

## 4. Tabel: `shelters`
Mengelola data posko pengungsian, termasuk ketersediaan fasilitas krusial seperti toilet.
* **`shelter_id`** (BigInt, PK, Auto Increment)
* **`shelter_name`** (String, 100) - Contoh: "Masjid Raya Al-Muwahhidin".
* **`address`** (Text) - Alamat posko.
* **`max_capacity`** (Integer) - Kapasitas maksimal jiwa yang ditampung.
* **`current_occupants`** (Integer, Default: 0) - Jumlah pengungsi aktif saat ini.
* **`has_toilet_facilities`** (Enum: `'Yes'`, `'No'` - Default: `'Yes'`) - Mengatasi gap sanitasi di posko darurat.
* **`status`** (Enum: `'active'`, `'full'`, `'closed'` - Default: `'active'`)
* **`latitude`** (Decimal, 10,8) - Koordinat GPS lintang.
* **`longitude`** (Decimal, 11,8) - Koordinat GPS bujur.
* **`created_at` / `updated_at`** (Timestamp)

---

## 5. Tabel: `shelter_needs`
Mencatat kebutuhan logistik mendesak hasil asesmen pengelola posko di lapangan.
* **`need_id`** (BigInt, PK, Auto Increment)
* **`shelter_id`** (BigInt, FK to `shelters.shelter_id`, Cascade) - Lokasi posko yang membutuhkan.
* **`item_name`** (String, 100) - Contoh: "Selimut", "Susu Bayi Formula".
* **`quantity_need`** (Integer) - Jumlah barang yang dibutuhkan.
* **`quantity_fulfilled`** (Integer, Default: 0) - Jumlah barang yang sudah terpenuhi/didonasikan.
* **`urgency`** (Enum: `'low'`, `'medium'`, `'high'`)
* **`created_at` / `updated_at`** (Timestamp)

---

## 6. Tabel: `donations`
Log transaksi bantuan dari masyarakat/donatur untuk memotong panjangnya jalur birokrasi konvensional.
* **`donation_id`** (BigInt, PK, Auto Increment)
* **`donor_id`** (BigInt, FK to `users.user_id`, Cascade) - ID donatur.
* **`need_id`** (BigInt, FK to `shelter_needs.need_id`, Cascade) - Kebutuhan barang posko yang ingin dipenuhi.
* **`quantity_donated`** (Integer) - Jumlah barang yang dikirim.
* **`shipping_receipt_no`** (String, 100, Nullable) - Resi kurir pengiriman.
* **`proof_photo`** (String, 255) - Bukti foto pengiriman donasi.
* **`status`** (Enum: `'pending'`, `'accepted'`, `'delivered'` - Default: `'pending'`)
* **`donated_at`** (Timestamp) - Waktu pengiriman donasi.
* **`created_at` / `updated_at`** (Timestamp)

---

## 7. Tabel: `sos_requests`
Modifikasi penting pada penambahan kolom kelompok rentan untuk filter skala prioritas relawan.
* **`sos_id`** (BigInt, PK, Auto Increment)
* **`user_id`** (BigInt, FK to `users.user_id`, Cascade) - ID warga yang meminta bantuan.
* **`latitude`** (Decimal, 10,8) - Lokasi persis korban terjebak.
* **`longitude`** (Decimal, 11,8)
* **`people_trapped`** (Integer) - Jumlah total orang di lokasi.
* **`vulnerable_groups_count`** (Integer, Default: 0) - Jumlah Lansia/Balita/Ibu Hamil di lokasi.
* **`priority_level`** (Enum: `'low'`, `'medium'`, `'high'` - Default: `'low'`) - Tingkat prioritas evakuasi.
* **`description`** (Text, Nullable) - Catatan khusus (misal: "butuh obat asma").
* **`status`** (Enum: `'waiting'`, `'assigned'`, `'rescued'`, `'completed'` - Default: `'waiting'`)
* **`created_at` / `updated_at`** (Timestamp)

---

## 8. Tabel: `rescue_missions`
Tabel pencatatan misi penyelamatan di lapangan oleh relawan.
* **`mission_id`** (BigInt, PK, Auto Increment)
* **`sos_id`** (BigInt, FK to `sos_requests.sos_id`, Cascade, Unique) - Sinyal SOS yang ditangani (Relasi 1:1).
* **`volunteer_id`** (BigInt, FK to `users.user_id`, Cascade) - ID relawan/personel penyelamat.
* **`assigned_at`** (Timestamp) - Waktu penugasan relawan.
* **`resolved_at`** (Timestamp, Nullable) - Waktu korban berhasil tiba di posko.
* **`created_at` / `updated_at`** (Timestamp)
