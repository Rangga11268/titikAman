@extends('layouts.app')

@section('title', 'Lengkapi Data Dirimu - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="{{ asset('css/auth-registration.css') }}">
@endsection

@section('content')
<div class="split-container">
    <!-- Left Panel (Sidebar Info) -->
    <div class="left-panel">
        <div class="brand-section">
            <div class="brand-container">
                <div class="brand-logo-bg">
                    <img class="brand-logo-img" src="{{ asset('assets/logo-titikaman.png') }}" alt="Logo TitikAman">
                </div>
                <div class="brand-text">
                    <span class="brand-title">TitikAman</span>
                    <span class="brand-subtitle">Sistem Mitigasi Banjir</span>
                </div>
            </div>
            <div class="badge-official">
                <i data-lucide="shield-check" class="text-teal"></i>
                <span>Official BPBD Indonesia</span>
            </div>
        </div>

        <div class="stepper-section">
            <h2 class="left-panel-title">Bersama Membangun<br>Ketangguhan Komunitas.</h2>
            <p class="left-panel-desc">Sistem informasi banjir terintegrasi untuk membantu warga Jakarta memantau, melaporkan, dan merespons bencana dengan data akurat.</p>

            <!-- Stepper List -->
            <div class="stepper-list" style="display: flex; flex-direction: column; gap: 24px; margin-top: 32px; z-index: 2;">
                <!-- Step 1 -->
                <div class="step-item" style="display: flex; gap: 16px; align-items: center;">
                    <div class="step-circle" style="width: 32px; height: 32px; border-radius: 50%; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-weight: 600; color: #fff; font-size: 14px;">1</div>
                    <div class="step-text" style="display: flex; flex-direction: column;">
                        <span class="step-title" style="font-weight: 600; font-size: 14px; color: rgba(255,255,255,0.7);">Pilih Peran</span>
                        <span class="step-desc" style="font-size: 12px; color: rgba(255,255,255,0.5);">Tentukan hak akses Anda</span>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="step-item" style="display: flex; gap: 16px; align-items: center;">
                    <div class="step-circle" style="width: 32px; height: 32px; border-radius: 50%; background-color: var(--brand-teal, #006a60); display: flex; align-items: center; justify-content: center; font-weight: 600; color: #fff; font-size: 14px;">2</div>
                    <div class="step-text" style="display: flex; flex-direction: column;">
                        <span class="step-title" style="font-weight: 600; font-size: 14px; color: #fff;">Data Diri</span>
                        <span class="step-desc" style="font-size: 12px; color: rgba(255,255,255,0.8);">Informasi identitas resmi</span>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="step-item" style="display: flex; gap: 16px; align-items: center;">
                    <div class="step-circle" style="width: 32px; height: 32px; border-radius: 50%; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-weight: 600; color: #fff; font-size: 14px;">3</div>
                    <div class="step-text" style="display: flex; flex-direction: column;">
                        <span class="step-title" style="font-weight: 600; font-size: 14px; color: rgba(255,255,255,0.7);">Masuk</span>
                        <span class="step-desc" style="font-size: 12px; color: rgba(255,255,255,0.5);">Masuk ke halaman</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-box">
            <h4 class="info-box-title">Mengapa harus daftar?</h4>
            <ul class="info-list">
                <li class="info-item">
                    <i data-lucide="check-circle-2" style="color: #4ade80;"></i>
                    <span>Peringatan dini banjir real-time</span>
                </li>
                <li class="info-item">
                    <i data-lucide="check-circle-2" style="color: #4ade80;"></i>
                    <span>Lokasi posko terdekat dari koordinat Anda</span>
                </li>
                <li class="info-item">
                    <i data-lucide="check-circle-2" style="color: #4ade80;"></i>
                    <span>Kirim sinyal SOS dalam keadaan darurat</span>
                </li>
                <li class="info-item">
                    <i data-lucide="check-circle-2" style="color: #4ade80;"></i>
                    <span>Akses data validitas air sungai resmi</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Right Panel (Registration Form) -->
    <div class="right-panel">
        <div class="container-form-wrapper" style="max-width: 640px; margin: 0 auto; width: 100%;">
            <h1 class="form-title">Lengkapi Data Dirimu</h1>
            <p class="form-subtitle">Kami memerlukan data ini untuk verifikasi keamanan dan akurasi laporan mitigasi banjir di wilayah Anda. Data Anda tersimpan aman.</p>

            @if ($errors->any())
                <div class="error-alert">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.step2.pengelola.submit') }}" method="POST" id="pengelolaForm" enctype="multipart/form-data">
                @csrf

                <!-- Informasi Pengelola Section (Database Required Fields) -->
                <h3 class="section-title">
                    <i data-lucide="user"></i>
                    <span>Informasi Pengelola</span>
                </h3>

                <div class="form-group">
                    <label for="fullname" class="form-label">Nama Lengkap Pengelola *</label>
                    <input type="text"
                           id="fullname"
                           name="fullname"
                           class="form-input @error('fullname') error @enderror"
                           placeholder="Masukkan nama lengkap Anda"
                           value="{{ old('fullname') }}"
                           required>
                    @error('fullname')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="email" class="form-label">Email Pengelola *</label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-input @error('email') error @enderror"
                               placeholder="contoh@email.com"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label for="phone" class="form-label">Nomor HP Pengelola *</label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               class="form-input @error('phone') error @enderror"
                               placeholder="0812xxxx"
                               value="{{ old('phone') }}"
                               required>
                        @error('phone')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="password" class="form-label">Kata Sandi *</label>
                        <div class="password-wrapper">
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-input @error('password') error @enderror"
                                   placeholder="Minimal 8 karakter"
                                   required>
                            <button type="button" class="password-toggle-btn" onclick="togglePassword('password', 'eyeIcon1')">
                                <i data-lucide="eye" id="eyeIcon1"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi *</label>
                        <div class="password-wrapper">
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="form-input"
                                   placeholder="Ulangi kata sandi"
                                   required>
                            <button type="button" class="password-toggle-btn" onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                                <i data-lucide="eye" id="eyeIcon2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Informasi Posko Section -->
                <h3 class="section-title">
                    <i data-lucide="home"></i>
                    <span>Informasi Posko</span>
                </h3>

                <div class="form-row">
                    <!-- Nama Posko -->
                    <div class="form-group" style="flex: 2;">
                        <label for="shelter_name" class="form-label">Nama Posko</label>
                        <input type="text"
                               id="shelter_name"
                               name="shelter_name"
                               class="form-input @error('shelter_name') error @enderror"
                               placeholder="Contoh: Gedung Serbaguna"
                               value="{{ old('shelter_name') }}"
                               required>
                        @error('shelter_name')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kapasitas Maksimum -->
                    <div class="form-group" style="flex: 1;">
                        <label for="max_capacity" class="form-label">Kapasitas Maksimum (Jiwa)</label>
                        <div class="capacity-input-wrapper">
                            <input type="number"
                                   id="max_capacity"
                                   name="max_capacity"
                                   min="1"
                                   class="form-input @error('max_capacity') error @enderror"
                                   placeholder="0"
                                   value="{{ old('max_capacity') }}"
                                   required>
                            <span class="capacity-appender">Org</span>
                        </div>
                        @error('max_capacity')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Alamat Lengkap Posko -->
                <div class="form-group">
                    <label for="address" class="form-label">Alamat Lengkap Posko</label>
                    <textarea id="address"
                              name="address"
                              class="form-textarea @error('address') error @enderror"
                              placeholder="Masukkan alamat lengkap termasuk kelurahan/kecamatan..."
                              required>{{ old('address') }}</textarea>
                    @error('address')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Fasilitas & Logistik Section -->
                <h3 class="section-title">
                    <i data-lucide="box"></i>
                    <span>Fasilitas & Logistik</span>
                </h3>

                <div class="facility-grid">
                    <!-- Dapur Umum -->
                    <div class="facility-card {{ is_array(old('facilities')) && in_array('Dapur Umum', old('facilities')) ? 'selected' : '' }}" onclick="toggleFacility(this, 'check_dapur')">
                        <input type="checkbox" name="facilities[]" value="Dapur Umum" id="check_dapur" class="facility-checkbox" {{ is_array(old('facilities')) && in_array('Dapur Umum', old('facilities')) ? 'checked' : '' }} onclick="event.stopPropagation(); syncFacilityState(this);">
                        <div class="facility-label-wrapper">
                            <i data-lucide="soup"></i>
                            <span>Dapur Umum</span>
                        </div>
                    </div>

                    <!-- Posko Kesehatan -->
                    <div class="facility-card {{ is_array(old('facilities')) && in_array('Posko Kesehatan', old('facilities')) ? 'selected' : '' }}" onclick="toggleFacility(this, 'check_kesehatan')">
                        <input type="checkbox" name="facilities[]" value="Posko Kesehatan" id="check_kesehatan" class="facility-checkbox" {{ is_array(old('facilities')) && in_array('Posko Kesehatan', old('facilities')) ? 'checked' : '' }} onclick="event.stopPropagation(); syncFacilityState(this);">
                        <div class="facility-label-wrapper">
                            <i data-lucide="briefcase"></i>
                            <span>Posko Kesehatan</span>
                        </div>
                    </div>

                    <!-- Area Tidur Layak -->
                    <div class="facility-card {{ is_array(old('facilities')) && in_array('Area Tidur Layak', old('facilities')) ? 'selected' : '' }}" onclick="toggleFacility(this, 'check_tidur')">
                        <input type="checkbox" name="facilities[]" value="Area Tidur Layak" id="check_tidur" class="facility-checkbox" {{ is_array(old('facilities')) && in_array('Area Tidur Layak', old('facilities')) ? 'checked' : '' }} onclick="event.stopPropagation(); syncFacilityState(this);">
                        <div class="facility-label-wrapper">
                            <i data-lucide="bed"></i>
                            <span>Area Tidur Layak</span>
                        </div>
                    </div>

                    <!-- Toilet & Sanitasi -->
                    <div class="facility-card {{ is_array(old('facilities')) && in_array('Toilet', old('facilities')) ? 'selected' : '' }}" onclick="toggleFacility(this, 'check_toilet')">
                        <input type="checkbox" name="facilities[]" value="Toilet" id="check_toilet" class="facility-checkbox" {{ is_array(old('facilities')) && in_array('Toilet', old('facilities')) ? 'checked' : '' }} onclick="event.stopPropagation(); syncFacilityState(this);">
                        <div class="facility-label-wrapper">
                            <i data-lucide="users"></i>
                            <span>Toilet & Sanitasi</span>
                        </div>
                    </div>

                    <!-- Listrik / Genset -->
                    <div class="facility-card {{ is_array(old('facilities')) && in_array('Listrik', old('facilities')) ? 'selected' : '' }}" onclick="toggleFacility(this, 'check_listrik')">
                        <input type="checkbox" name="facilities[]" value="Listrik" id="check_listrik" class="facility-checkbox" {{ is_array(old('facilities')) && in_array('Listrik', old('facilities')) ? 'checked' : '' }} onclick="event.stopPropagation(); syncFacilityState(this);">
                        <div class="facility-label-wrapper">
                            <i data-lucide="bolt"></i>
                            <span>Listrik / Genset</span>
                        </div>
                    </div>

                    <!-- Lainnya Dummy option -->
                    <div class="facility-card" style="border-style: dashed; cursor: default; background-color: transparent;">
                        <span style="font-size: 11px; font-weight: 700; color: #8c8d99; text-transform: uppercase;">Fasilitas Lainnya?</span>
                    </div>
                </div>
                @error('facilities')
                    <span class="error-text" style="display: block; margin-top: -16px; margin-bottom: 16px;">{{ $message }}</span>
                @enderror

                <!-- Foto Posko Section -->
                <h3 class="section-title">
                    <i data-lucide="camera"></i>
                    <span>Foto Posko</span>
                </h3>

                <div class="upload-area" onclick="document.getElementById('shelter_photo').click()">
                    <input type="file" id="shelter_photo" name="photo" accept="image/jpeg,image/png,image/webp" style="display: none;" onchange="handlePhotoSelected(this)">
                    <div class="upload-icon-wrapper">
                        <i data-lucide="image-plus"></i>
                    </div>
                    <span class="upload-title" id="photoTitle">Upload Foto Posko (Opsional)</span>
                    <span class="upload-subtitle" id="photoSubtitle">Format JPG/PNG, Maks 5MB</span>
                </div>
                @error('photo')
                    <span class="error-text" style="display: block; margin-top: 4px;">{{ $message }}</span>
                @enderror

                <!-- Verifikasi Lokasi Section -->
                <h3 class="section-title">
                    <i data-lucide="map-pin"></i>
                    <span>Verifikasi Lokasi</span>
                </h3>

                <!-- Map element -->
                <div class="map-section-wrapper">
                    <button type="button" class="btn-gps-current" id="btnGps">
                        <i data-lucide="locate"></i>
                        <span>Gunakan Lokasi Saat Ini</span>
                    </button>
                    <div id="map"></div>
                </div>

                <!-- Hidden Latitude and Longitude fields -->
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', -6.2349) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', 106.9924) }}">

                @error('latitude')
                    <span class="error-text" style="display: block; margin-top: -8px;">{{ $message }}</span>
                @enderror

                <!-- GPS Info Alert Box -->
                <div class="info-alert-box">
                    <i data-lucide="info"></i>
                    <span class="info-alert-text">Lokasi GPS akurat sangat penting untuk memudahkan tim logistik dan warga menemukan titik koordinat posko Anda. Geser marker jika titik kurang akurat.</span>
                </div>

                  <!-- Action Button Row -->
                    <div class="action-row">
                        <a href="{{ route('register.step1') }}" class="btn-back-link">
                            <i data-lucide="arrow-left"></i>
                            <span>Sebelumnya</span>
                        </a>
                        <button type="submit" class="btn-submit-teal">
                            <span>Simpan & Lanjutkan</span>
                            <i data-lucide="arrow-right"></i>
                        </button>
                    </div>
            </form>

            <div class="auth-footer-text">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>

            <!-- Bottom disclaimer -->
            <div class="bottom-disclaimer">
                <span><i data-lucide="shield-check" style="width: 14px; height: 14px; display: inline; vertical-align: middle; margin-right: 4px;"></i> Data anda dienkripsi secara aman sesuai standar protokol keamanan siber nasional.</span>
                <div class="bottom-links">
                    <a href="#">Kebijakan Privasi</a>
                    <a href="#">Bantuan</a>
                </div>
                <span>© 2026 TitikAman. All Rights Reserved.</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
        }
        lucide.createIcons();
    }

    function toggleFacility(card, checkboxId) {
        const checkbox = document.getElementById(checkboxId);
        checkbox.checked = !checkbox.checked;
        if (checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    }

    function syncFacilityState(checkbox) {
        const card = checkbox.closest('.facility-card');
        if (checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Map integration
        const initialLat = parseFloat(document.getElementById('latitude').value);
        const initialLng = parseFloat(document.getElementById('longitude').value);

        // Center on Bekasi
        const map = L.map('map').setView([initialLat, initialLng], 13);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap contributors © CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        // Custom marker SVG/icon
        const customIcon = L.divIcon({
            html: `<div style="background-color: var(--brand-teal); width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                   </div>`,
            className: 'custom-map-marker',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        const marker = L.marker([initialLat, initialLng], {
            draggable: true,
            icon: customIcon
        }).addTo(map);

        function updateCoords(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
        }

        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            updateCoords(position.lat, position.lng);
        });

        // Use current location button
        document.getElementById('btnGps').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    marker.setLatLng([lat, lng]);
                    map.setView([lat, lng], 16);
                    updateCoords(lat, lng);
                }, function () {
                    alert('Gagal mengambil lokasi GPS Anda.');
                });
            } else {
                alert('Browser Anda tidak mendukung Geolocation.');
            }
        });
    });

    function handlePhotoSelected(input) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
            document.getElementById('photoTitle').textContent = "File Terpilih: " + fileName;
            document.getElementById('photoSubtitle').textContent = `Ukuran File: ${fileSize} MB (Klik kembali untuk mengganti)`;
            document.querySelector('.upload-area').style.borderColor = "var(--brand-teal)";
            document.querySelector('.upload-area').style.backgroundColor = "rgba(0, 106, 96, 0.02)";
        }
    }
</script>
@endsection
