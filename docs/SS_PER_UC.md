# Panduan Screenshot Kode per Use Case

---

## 1. Login (UC-01)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/AuthController.php` | 160–200 | Method `login()` — validasi kredensial & deteksi status pending/rejected |
| `resources/views/auth/login.blade.php` | full | Form login (email/HP + password) |

## 2. Register Warga (UC-02a)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/AuthController.php` | 50–75 | Method `registerStep2Warga()` — validasi & simpan user role Warga |
| `resources/views/auth/register-step2-warga.blade.php` | full | Form registrasi warga |

## 3. Register Relawan / SAR (UC-02b)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/AuthController.php` | 100–160 | Method `registerStep2Relawan()` — validasi & upload dokumen |
| `resources/views/auth/register-step2-relawan.blade.php` | full | Form registrasi relawan (keahlian, dokumen, domisili) |

## 4. Register Pengelola Posko (UC-02c)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/AuthController.php` | 170–230 | Method `registerStep2Pengelola()` — buat shelter + user |
| `resources/views/auth/register-step2-pengelola.blade.php` | full | Form registrasi + peta pilih lokasi posko |

## 5. Status Verifikasi Akun (UC-03)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Middleware/EnsureUserApproved.php` | full | Middleware redirect logic |
| `app/Http/Controllers/AuthController.php` | 190–200 | Login redirect ke `/status-verifikasi` saat pending/rejected |
| `resources/views/auth/verification-status.blade.php` | full | Halaman status (pending/rejected/approved) |

## 6. Lihat Peta & Info Kebencanaan (UC-04)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/SharedController.php` | 30–100 | Method `dashboard()` — query data statistik & laporan |
| `resources/views/shared/dashboard.blade.php` | 1–120 | Layout dashboard (stat cards, peta, berita) |

## 7. Lihat Detail Laporan Banjir (UC-04b)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `resources/views/shared/dashboard.blade.php` | 200–230 | Modal detail laporan (HTML + JS) |

## 8. Lihat Kebutuhan Posko (UC-05)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/SharedController.php` | 300–360 | Method `posko()` — query shelter + needs |
| `resources/views/shared/posko.blade.php` | 188–252 | Tabel kebutuhan + form donasi |

## 9. Mengirim Permintaan SOS (UC-06)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/WargaController.php` | 60–90 | Method `submitSos()` — validasi & simpan |
| `app/Services/SosService.php` | full | Logic hitung prioritas + broadcast event |
| `resources/views/warga/sos.blade.php` | full | Form SOS + deteksi GPS |

## 10. Melaporkan Genangan Banjir (UC-07)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/WargaController.php` | 30–55 | Method `submitReport()` — validasi & simpan laporan |
| `app/Services/FloodReportService.php` | full | Logic upload foto & simpan |
| `resources/views/warga/lapor-banjir.blade.php` | full | Form laporan (slider tinggi air, upload foto) |

## 11. Melakukan Donasi Logistik (UC-08)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/DonasiController.php` | full | Validasi quantity (tidak > sisa) & simpan |
| `resources/views/shared/posko.blade.php` | 200–251 | Form donasi |

## 12. Dashboard Mission Control (UC-09)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/RelawanController.php` | 46–135 | Method `dashboard()` — semua query statistik & data |
| `app/Services/RescueMissionService.php` | 20–60 | Method `acceptMission()` — buat misi + validasi |
| `app/Repositories/SosRepository.php` | full | Query waiting & assigned SOS |
| `resources/views/relawan/dashboard.blade.php` | 1–200 | Layout dashboard (stat cards, antrian SOS, peta) |

## 13. Kirim Bantuan Tim (UC-10)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Services/RescueMissionService.php` | 20–60 | Method `acceptMission()` — multiple mission per SOS allowed |
| `resources/views/relawan/dashboard.blade.php` | 300–400 | Tombol "KIRIM BANTUAN TIM" + dropdown tim |

## 14. Review & Approve Anggota Baru (UC-11)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/RelawanController.php` | 260–280 | Method `reviewMember()` — tampilkan modal review |
| `app/Http/Controllers/RelawanController.php` | 290–320 | Method `approveMember()` — simpan session approval + WA link |
| `resources/views/relawan/dashboard.blade.php` | 400–500 | Review modal + banner approve dengan tombol WA |

## 15. Selesaikan Misi & Riwayat (UC-12)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/RelawanController.php` | 220–250 | Method `completeMission()` — update status |
| `app/Repositories/RescueMissionRepository.php` | full | Method `getAllMissions()` |
| `resources/views/relawan/dashboard.blade.php` | 500–600 | Kartu misi aktif + tabel riwayat |

## 16. Kelola Kapasitas & Status Posko (UC-13)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/PengelolaController.php` | 30–80 | Method `updateShelter()` — update kapasitas & status |
| `resources/views/pengelola/kelola-kebutuhan.blade.php` | 1–80 | Form update kapasitas + status posko |

## 17. Verifikasi Donasi (UC-14)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/PengelolaController.php` | 100–130 | Method `verifikasiDonasi()` — update status delivered |
| `resources/views/pengelola/hub-logistik-donasi.blade.php` | full | Tabel donasi + tombol verifikasi |

## 18. Dashboard & Verifikasi Laporan (UC-15)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/AdminController.php` | 30–100 | Method `dashboard()` + `verifyReport()` |
| `resources/views/admin/dashboard.blade.php` | full | Dashboard admin + kartu laporan pending |

## 19. Kelola TMA & Peringatan Dini (UC-16)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/AdminController.php` | 110–150 | Method `updateTma()` — update tinggi air |
| `app/Jobs/SendEarlyWarningNotificationJob.php` | full | DAS mapping + cache throttling logic |
| `resources/views/admin/tma.blade.php` | full | Tabel & form update TMA |

## 20. Verifikasi Pengguna (UC-17)
| File | Baris | Kode yang Di-screenshot |
|------|-------|------------------------|
| `app/Http/Controllers/AdminController.php` | 160–200 | Method `userVerification()` + `approveUser()` + `rejectUser()` |
| `app/Services/AdminService.php` | 76–82 | Method `getPendingUsers()` |
| `resources/views/admin/verifikasi-pengguna.blade.php` | full | Antrean + detail + tombol approve/reject |
