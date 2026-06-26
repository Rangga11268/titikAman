@extends('layouts.app')

@section('title', 'Pendaftaran Relawan / SAR - TitikAman')

@section('styles')
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
                <p class="left-panel-desc">Sistem informasi banjir terintegrasi untuk membantu warga Jakarta memantau,
                    melaporkan, dan merespons bencana dengan data akurat.</p>

                <!-- Stepper List -->
                <div class="stepper-list"
                    style="display: flex; flex-direction: column; gap: 24px; margin-top: 32px; z-index: 2;">
                    <!-- Step 1 -->
                    <div class="step-item" style="display: flex; gap: 16px; align-items: center;">
                        <div class="step-circle"
                            style="width: 32px; height: 32px; border-radius: 50%; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-weight: 600; color: #fff; font-size: 14px;">
                            1</div>
                        <div class="step-text" style="display: flex; flex-direction: column;">
                            <span class="step-title"
                                style="font-weight: 600; font-size: 14px; color: rgba(255,255,255,0.7);">Pilih Peran</span>
                            <span class="step-desc" style="font-size: 12px; color: rgba(255,255,255,0.5);">Tentukan hak
                                akses Anda</span>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="step-item" style="display: flex; gap: 16px; align-items: center;">
                        <div class="step-circle"
                            style="width: 32px; height: 32px; border-radius: 50%; background-color: var(--brand-teal, #006a60); display: flex; align-items: center; justify-content: center; font-weight: 600; color: #fff; font-size: 14px;">
                            2</div>
                        <div class="step-text" style="display: flex; flex-direction: column;">
                            <span class="step-title" style="font-weight: 600; font-size: 14px; color: #fff;">Data
                                Diri</span>
                            <span class="step-desc" style="font-size: 12px; color: rgba(255,255,255,0.8);">Informasi
                                identitas resmi</span>
                        </div>
                    </div>
                    <!-- Step 3 -->
                    <div class="step-item" style="display: flex; gap: 16px; align-items: center;">
                        <div class="step-circle"
                            style="width: 32px; height: 32px; border-radius: 50%; background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-weight: 600; color: #fff; font-size: 14px;">
                            3</div>
                        <div class="step-text" style="display: flex; flex-direction: column;">
                            <span class="step-title"
                                style="font-weight: 600; font-size: 14px; color: rgba(255,255,255,0.7);">Masuk</span>
                            <span class="step-desc" style="font-size: 12px; color: rgba(255,255,255,0.5);">Masuk ke
                                halaman</span>
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
                <!-- Progress Line Indicator -->
                <div class="progress-bar-container">
                    <div class="progress-segment active"></div>
                    <div class="progress-segment active"></div>
                    <div class="progress-segment"></div>
                </div>

                <h1 class="form-title">Pendaftaran Relawan / SAR</h1>
                <p class="form-subtitle">Lengkapi informasi Anda untuk bergabung dalam tim respons bencana.</p>

                @if ($errors->any())
                    <div class="error-alert">
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.step2.relawan.submit') }}" method="POST" enctype="multipart/form-data"
                    id="relawanForm">
                    @csrf

                    <!-- Informasi Pribadi Section -->
                    <h3 class="section-title">
                        <i data-lucide="user"></i>
                        <span>Informasi Pribadi</span>
                    </h3>

                    <div class="form-row">
                        <!-- Nama Lengkap -->
                        <div class="form-group" style="flex: 1;">
                            <label for="fullname" class="form-label">Nama Lengkap</label>
                            <input type="text" id="fullname" name="fullname"
                                class="form-input @error('fullname') error @enderror" placeholder="Contoh: Budi Santoso"
                                value="{{ old('fullname') }}" required>
                            @error('fullname')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- NIK -->
                        <div class="form-group" style="flex: 1;">
                            <label for="nik" class="form-label">Nomor Induk Kependudukan (NIK)</label>
                            <input type="text" id="nik" name="nik" maxlength="16"
                                class="form-input @error('nik') error @enderror" placeholder="16 digit NIK Anda"
                                value="{{ old('nik') }}" required>
                            @error('nik')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Nomor HP -->
                    <div class="form-group">
                        <label for="phone" class="form-label">Nomor Telepon Aktif (WhatsApp)</label>
                        <div class="phone-input-wrapper">
                            <span class="phone-prefix">+62</span>
                            <input type="text" id="phone" name="phone"
                                class="form-input @error('phone') error @enderror" placeholder="812xxxxxxx"
                                value="{{ old('phone') ? ltrim(old('phone'), '+62') : '' }}" required>
                        </div>
                        @error('phone')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email & Credentials for registration success (needed by database) -->
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email"
                                class="form-input @error('email') error @enderror" placeholder="contoh@email.com"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <div class="password-wrapper">
                                <input type="password" id="password" name="password"
                                    class="form-input @error('password') error @enderror" placeholder="Minimal 8 karakter"
                                    required>
                                <button type="button" class="password-toggle-btn"
                                    onclick="togglePassword('password', 'eyeIcon1')">
                                    <i data-lucide="eye" id="eyeIcon1"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                            <div class="password-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-input" placeholder="Ulangi kata sandi" required>
                                <button type="button" class="password-toggle-btn"
                                    onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                                    <i data-lucide="eye" id="eyeIcon2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Keahlian Khusus Section -->
                    <h3 class="section-title">
                        <i data-lucide="briefcase"></i>
                        <span>Keahlian Khusus</span>
                    </h3>

                    <div class="keahlian-grid">
                        <!-- Water Rescue Card -->
                        <div class="keahlian-card {{ is_array(old('keahlian')) && in_array('Evakuasi', old('keahlian')) ? 'selected' : '' }}"
                            onclick="toggleKeahlian(this, 'check_evakuasi')">
                            <input type="checkbox" name="keahlian[]" value="Evakuasi" id="check_evakuasi"
                                class="keahlian-card-checkbox"
                                {{ is_array(old('keahlian')) && in_array('Evakuasi', old('keahlian')) ? 'checked' : '' }}
                                onclick="event.stopPropagation(); syncCardState(this);">
                            <div class="keahlian-card-icon">
                                <i data-lucide="droplets"></i>
                            </div>
                            <span class="keahlian-card-title">Water Rescue</span>
                        </div>

                        <!-- Medical First Aid Card -->
                        <div class="keahlian-card {{ is_array(old('keahlian')) && in_array('Medis', old('keahlian')) ? 'selected' : '' }}"
                            onclick="toggleKeahlian(this, 'check_medis')">
                            <input type="checkbox" name="keahlian[]" value="Medis" id="check_medis"
                                class="keahlian-card-checkbox"
                                {{ is_array(old('keahlian')) && in_array('Medis', old('keahlian')) ? 'checked' : '' }}
                                onclick="event.stopPropagation(); syncCardState(this);">
                            <div class="keahlian-card-icon">
                                <i data-lucide="activity"></i>
                            </div>
                            <span class="keahlian-card-title">Medical First Aid</span>
                        </div>

                        <!-- Logistik Card -->
                        <div class="keahlian-card {{ is_array(old('keahlian')) && in_array('Logistik', old('keahlian')) ? 'selected' : '' }}"
                            onclick="toggleKeahlian(this, 'check_logistik')">
                            <input type="checkbox" name="keahlian[]" value="Logistik" id="check_logistik"
                                class="keahlian-card-checkbox"
                                {{ is_array(old('keahlian')) && in_array('Logistik', old('keahlian')) ? 'checked' : '' }}
                                onclick="event.stopPropagation(); syncCardState(this);">
                            <div class="keahlian-card-icon">
                                <i data-lucide="truck"></i>
                            </div>
                            <span class="keahlian-card-title">Logistik</span>
                        </div>
                    </div>

                    <!-- Organisasi -->
                    <div class="form-group">
                        <label for="organisasi" class="form-label">Nama Organisasi / Komunitas (Opsional)</label>
                        <input type="text" id="organisasi" name="organisasi"
                            class="form-input @error('organisasi') error @enderror"
                            placeholder="Contoh: Indonesia Offroad Federation / PMI" value="{{ old('organisasi') }}">
                        @error('organisasi')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Verifikasi Dokumen Section -->
                    <h3 class="section-title">
                        <i data-lucide="cloud-lightning" style="display: none;"></i>
                        <!-- placeholder to make icon available if needed -->
                        <i data-lucide="file-text"></i>
                        <span>Verifikasi Dokumen</span>
                    </h3>

                    <!-- Upload Card -->
                    <div class="upload-area" onclick="document.getElementById('document').click()">
                        <input type="file" id="document" name="document"
                            accept="image/jpeg,image/png,application/pdf" style="display: none;"
                            onchange="handleFileSelected(this)" required>
                        <div class="upload-icon-wrapper">
                            <i data-lucide="file-up"></i>
                        </div>
                        <span class="upload-title" id="uploadTitle">Unggah Identitas / Sertifikasi</span>
                        <span class="upload-subtitle" id="uploadSubtitle">Seret file atau klik untuk mengunggah (Format
                            JPG/PDF, Maks 2MB)</span>
                    </div>
                    @error('document')
                        <span class="error-text" style="display: block; margin-top: 4px;">{{ $message }}</span>
                    @enderror

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
                    <span><i data-lucide="shield-check"
                            style="width: 14px; height: 14px; display: inline; vertical-align: middle; margin-right: 4px;"></i>
                        Data anda dienkripsi secara aman sesuai standar protokol keamanan siber nasional.</span>
                    <div class="bottom-links">
                        <a href="#">Kebijakan Privasi</a>
                        <a href="#">Bantuan</a>
                    </div>
                    <span>© 2024 TitikAman. All Rights Reserved.</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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

        function toggleKeahlian(card, checkboxId) {
            const checkbox = document.getElementById(checkboxId);
            checkbox.checked = !checkbox.checked;
            if (checkbox.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        }

        function syncCardState(checkbox) {
            const card = checkbox.closest('.keahlian-card');
            if (checkbox.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        }

        function handleFileSelected(input) {
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
                document.getElementById('uploadTitle').textContent = "File Terpilih: " + fileName;
                document.getElementById('uploadSubtitle').textContent =
                    `Ukuran File: ${fileSize} MB (Klik kembali untuk mengganti)`;
                document.querySelector('.upload-area').style.borderColor = "var(--brand-teal)";
                document.querySelector('.upload-area').style.backgroundColor = "rgba(0, 106, 96, 0.02)";
            }
        }
    </script>
@endsection
