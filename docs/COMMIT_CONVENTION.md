# 📝 COMMIT MESSAGE CONVENTION — TitikAman

Panduan ini mengatur format pesan commit Git yang digunakan di proyek **TitikAman**. Mengikuti konvensi **Conventional Commits** yang terstandar agar riwayat Git mudah dibaca, di-*filter*, dan dapat di-*generate* changelog secara otomatis.

---

## 📐 Format Dasar

```
<type>(<scope>): <deskripsi singkat>

[body opsional — penjelasan tambahan]

[footer opsional — breaking change / closes issue]
```

**Aturan:**
- Gunakan **Bahasa Indonesia** untuk deskripsi.
- Huruf pertama deskripsi **kecil** (lowercase).
- Tidak diakhiri tanda titik (`.`).
- Maksimal **72 karakter** per baris.

---

## 🏷️ Daftar Type Commit

| Type | Kapan Digunakan | Contoh |
|:---|:---|:---|
| `feat` | Fitur baru | `feat(sos): tambah validasi koordinat GPS` |
| `fix` | Perbaikan bug | `fix(auth): perbaiki redirect setelah login` |
| `docs` | Perubahan dokumentasi saja | `docs(relasi): tambah penjelasan relasi tabel` |
| `refactor` | Perubahan kode tanpa mengubah perilaku | `refactor(shelter): pindah logika ke ShelterService` |
| `style` | Perubahan tampilan/CSS/formatting (bukan logika) | `style(map): sesuaikan warna marker SOS` |
| `test` | Tambah atau perbaiki unit/feature test | `test(sos): tambah feature test kirim SOS` |
| `chore` | Konfigurasi, dependency, script build | `chore(deps): install intervention/image` |
| `perf` | Optimasi performa | `perf(query): tambah index latitude di tabel shelters` |
| `ci` | Konfigurasi CI/CD pipeline | `ci: tambah github actions untuk run phpunit` |
| `revert` | Membatalkan commit sebelumnya | `revert: kembalikan perubahan auth middleware` |
| `wip` | Pekerjaan sedang berjalan (jangan push ke main) | `wip(donation): form upload resi masih progres` |

---

## 🗂️ Daftar Scope (Lingkup Modul)

Scope merujuk pada **modul atau bagian sistem** yang diubah:

| Scope | Area Sistem |
|:---|:---|
| `auth` | Autentikasi, login, register, session |
| `user` | Manajemen profil & peran pengguna |
| `map` | Leaflet.js, peta, tiles, marker |
| `sos` | Fitur SOS request & evakuasi |
| `flood` | Laporan genangan banjir (crowdsourcing) |
| `shelter` | Manajemen posko pengungsian |
| `needs` | Kebutuhan logistik posko |
| `donation` | Donasi logistik & resi pengiriman |
| `mission` | Misi penyelamatan relawan |
| `watergate` | Tinggi muka air & status pintu air |
| `notification` | Notifikasi real-time, WebSocket, FCM |
| `admin` | Dashboard & panel admin BPBD |
| `api` | Endpoint API / Resource transformasi |
| `db` | Migrasi, seeder, factory |
| `config` | File konfigurasi sistem |
| `deps` | Dependency composer/npm |
| `ci` | Pipeline CI/CD |

---

## ✅ Contoh Commit yang Benar

```bash
# Fitur baru
feat(sos): tambah broadcast event SOS ke channel relawan

# Perbaikan bug
fix(flood): perbaiki gagal upload foto saat ukuran lebih dari 5MB

# Refactoring
refactor(shelter): pindah logika haversine ke ShelterRepository

# Dokumentasi
docs(skills): tambah contoh implementasi Haversine Formula

# Style/UI
style(map): ganti marker default leaflet ke SVG custom biru primer

# Testing
test(donation): tambah feature test verifikasi donasi oleh pengelola

# Dependency
chore(deps): install maatwebsite/excel untuk export laporan BPBD

# Performa
perf(db): tambah composite index (status, created_at) di tabel flood_reports

# Migration baru
feat(db): buat migration tambah kolom fcm_token di tabel users

# Breaking change (tulis di footer)
feat(api)!: ubah format response SOS menjadi nested location object

BREAKING CHANGE: field latitude/longitude dipindah ke dalam object location {}
```

---

## ❌ Contoh Commit yang Salah

```bash
# ❌ Terlalu umum / tidak informatif
git commit -m "fix bug"
git commit -m "update"
git commit -m "perubahan"
git commit -m "wip"
git commit -m "edit file"

# ❌ Tidak ada type
git commit -m "tambah validasi form SOS"

# ❌ Scope salah / tidak ada di daftar
git commit -m "feat(halaman): ..."

# ❌ Bahasa campur aduk tidak konsisten
git commit -m "feat(sos): add new SOS validation dan perbaiki bug"
```

---

## 🔀 Aturan Push & Branch

- Branch **`main`**: Branch produksi. **DILARANG** push langsung. Hanya melalui merge/PR dari `develop`.
- Branch **`develop`**: Branch integrasi utama. Semua fitur di-merge ke sini sebelum ke `main`.
- Branch **`feature/nama-fitur`**: Untuk pengerjaan fitur baru. Contoh: `feature/sos-broadcast`, `feature/shelter-nearme`.
- Branch **`fix/nama-bug`**: Untuk perbaikan bug. Contoh: `fix/upload-foto-validasi`.
- Branch **`hotfix/nama-masalah`**: Untuk perbaikan darurat langsung ke `main`. Contoh: `hotfix/auth-session-expired`.

---

## ⚡ Shortcut Commit Cepat (Copy-Paste)

```bash
# Template yang biasa dipakai tim:
git commit -m "feat(sos): "
git commit -m "feat(flood): "
git commit -m "fix(auth): "
git commit -m "feat(db): buat migration "
git commit -m "refactor(): pindah logika ke Service"
git commit -m "chore(deps): install "
git commit -m "docs(): "
git commit -m "test(): tambah feature test "
git commit -m "style(map): "
```
