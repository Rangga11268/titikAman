# Implementation Plan - Implement 3 Figma Registration Designs

This plan outlines the steps to implement three registration pages corresponding to the Figma designs provided:
1. **Register Relawan / SAR** ([Figma Node 248-1747](https://www.figma.com/design/9c6FnRq7hGqNUlbe3Iaehq/titikAman?node-id=248-1747&m=dev))
2. **Register Admin / Petugas BPBD** ([Figma Node 248-2020](https://www.figma.com/design/9c6FnRq7hGqNUlbe3Iaehq/titikAman?node-id=248-2020&m=dev))
3. **Register Pengelola Posko** ([Figma Node 248-2353](https://www.figma.com/design/9c6FnRq7hGqNUlbe3Iaehq/titikAman?node-id=248-2353&m=dev))

To comply with the requirement of **NOT adding new database tables**, we will extend the existing `users` and `shelters` tables by adding the necessary columns.

---

## Proposed Database Changes (No New Tables)

We will create a new migration to add columns to existing tables:

### 1. `users` Table Updates
We will add columns to store details for volunteers and admins, and a link to the shelter they manage:
- `nik` (string, 16 characters, nullable) - Nomor Induk Kependudukan for Relawan.
- `keahlian` (string, nullable) - Comma-separated or JSON list of selected volunteer skills (e.g., `Medis`, `Evakuasi`, `Logistik`).
- `organisasi` (string, 100, nullable) - Organization name for Relawan.
- `nip` (string, 18, nullable) - Nomor Induk Pegawai for BPBD Admin.
- `jabatan` (string, 100, nullable) - Position/title for BPBD Admin.
- `unit_kerja` (string, 100, nullable) - Workplace unit/region for BPBD Admin.
- `document_path` (string, 255, nullable) - Path of uploaded verification files (KTP for Relawan, SK/Kartu Pegawai for BPBD Admin).
- `shelter_id` (foreign key pointing to `shelters.shelter_id`, nullable) - Links a `Pengelola_Posko` user to the shelter they manage.

### 2. `shelters` Table Updates
We will add a column to store facilities:
- `facilities` (text, nullable) - Stores facilities checklist as a JSON array (e.g., `["Dapur Umum", "Pos Medis", "Toilet"]`).

---

## Proposed Code Changes

### Routing Configuration

#### [MODIFY] [web.php](file:///d:/laragon/www/titikAman/routes/web.php)
- Add GET and POST routes for the three registration types:
  ```php
  Route::middleware('guest')->group(function () {
      // ... existing register routes ...
      
      // Relawan
      Route::get('/register/relawan', [AuthController::class, 'showRegisterStep2Relawan'])->name('register.step2.relawan');
      Route::post('/register/relawan', [AuthController::class, 'registerRelawan'])->name('register.step2.relawan.submit');
      
      // Admin BPBD
      Route::get('/register/admin', [AuthController::class, 'showRegisterStep2Admin'])->name('register.step2.admin');
      Route::post('/register/admin', [AuthController::class, 'registerAdmin'])->name('register.step2.admin.submit');
      
      // Pengelola Posko
      Route::get('/register/pengelola', [AuthController::class, 'showRegisterStep2Pengelola'])->name('register.step2.pengelola');
      Route::post('/register/pengelola', [AuthController::class, 'registerPengelola'])->name('register.step2.pengelola.submit');
  });
  ```

---

### Controller Logic

#### [MODIFY] [AuthController.php](file:///d:/laragon/www/titikAman/app/Http/Controllers/AuthController.php)
- Implement methods to render views:
  - `showRegisterStep2Relawan()`
  - `showRegisterStep2Admin()`
  - `showRegisterStep2Pengelola()`
- Implement validation and registration actions:
  - `registerRelawan(Request $request)`: Validates account fields + `nik`, `keahlian`, `organisasi`, and `document` (KTP upload).
  - `registerAdmin(Request $request)`: Validates account fields + `nip`, `jabatan`, `unit_kerja`, and `document` (SK/Kartu Pegawai upload).
  - `registerPengelola(Request $request)`: Validates account fields + Shelter info (`nama_posko`, `kapasitas_maksimum`, `alamat_lengkap`, `facilities`, `latitude`, `longitude`). Creates the new Shelter record first, then registers the user and links them to the newly created shelter using `shelter_id`.
- Auto-login and session initialization after successful registration.

---

### View Templates

#### [MODIFY] [register-step1.blade.php](file:///d:/laragon/www/titikAman/resources/views/auth/register-step1.blade.php)
- Update the javascript logic to redirect to the specific page based on selected role instead of alerting that it's closed:
  ```javascript
  document.getElementById('roleForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const selectedRole = document.querySelector('input[name="role_select"]:checked').value;
      window.location.href = '/register/' + selectedRole;
  });
  ```

#### [NEW] [register-step2-relawan.blade.php](file:///d:/laragon/www/titikAman/resources/views/auth/register-step2-relawan.blade.php)
- Implement the split-container design matching Figma.
- Form fields: Name, Phone, Email, Password, NIK (16 digits), Keahlian checkboxes (Medis, Evakuasi / SAR, Logistik), Organisasi (optional), and File Upload (KTP).

#### [NEW] [register-step2-admin.blade.php](file:///d:/laragon/www/titikAman/resources/views/auth/register-step2-admin.blade.php)
- Implement the split-container design matching Figma.
- Form fields: Name, Phone, Email, Password, NIP (18 digits), Jabatan dropdown (Kepala Seksi, Petugas Lapangan, Staf Administrasi, Analis Bencana), Unit Kerja, and File Upload (Kartu Pegawai/SK).

#### [NEW] [register-step2-pengelola.blade.php](file:///d:/laragon/www/titikAman/resources/views/auth/register-step2-pengelola.blade.php)
- Implement the split-container design matching Figma.
- Form fields: Name, Phone, Email, Password.
- Shelter fields: Nama Posko, Kapasitas Maksimum, Alamat Lengkap Posko, Fasilitas Checkboxes (Dapur Umum, Pos Medis, Toilet, Logistik, Genset/Listrik).
- Map section: Leaflet.js map with a draggable marker to select coordinates. Latitude and longitude inputs are updated dynamically and submitted.

---

## Verification Plan

### Automated Tests
- Run existing tests to ensure no regressions:
  ```bash
  php artisan test
  ```

### Manual Verification
1. Navigate to `/register` (Step 1).
2. Choose **Relawan / SAR**, verify redirect to `/register/relawan`. Complete registration (including file upload) and check database record and active session.
3. Logout and repeat for **Admin BPBD** at `/register/admin`. Verify NIP and document upload.
4. Logout and repeat for **Pengelola Posko** at `/register/pengelola`. Verify Leaflet map selection updates latitude/longitude inputs, check that both a new shelter record is added in the database and the user record links to it.
5. Verify access to specific role dashboards after logging in with the newly created accounts.
