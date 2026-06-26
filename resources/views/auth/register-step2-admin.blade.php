@extends('layouts.app')

@section('title', 'Data Diri Admin - TitikAman')

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
                <!-- Steps Top indicator -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h1 class="form-title" style="margin-bottom: 0;">Data Diri Admin</h1>
                    <div style="text-align: right;">
                        <span
                            style="font-size: 11px; font-weight: 700; color: var(--brand-teal); text-transform: uppercase;">LANGKAH
                            02/03</span>
                        <div style="display: flex; gap: 4px; margin-top: 4px;">
                            <div style="width: 16px; height: 4px; background-color: var(--brand-teal); border-radius: 2px;">
                            </div>
                            <div style="width: 16px; height: 4px; background-color: var(--brand-teal); border-radius: 2px;">
                            </div>
                            <div style="width: 16px; height: 4px; background-color: #e0e2ec; border-radius: 2px;"></div>
                        </div>
                    </div>
                </div>

                <p class="form-subtitle">Lengkapi informasi identitas resmi Anda sebagai otoritas BPBD.</p>

                @if ($errors->any())
                    <div class="error-alert">
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.step2.admin.submit') }}" method="POST" enctype="multipart/form-data"
                    id="adminForm">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label for="fullname" class="form-label">Nama Lengkap Sesuai Identitas</label>
                        <input type="text" id="fullname" name="fullname"
                            class="form-input @error('fullname') error @enderror" placeholder="Contoh: Budi Santoso, S.T."
                            value="{{ old('fullname') }}" required>
                        @error('fullname')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <!-- NIP -->
                        <div class="form-group" style="flex: 1;">
                            <label for="nip" class="form-label">Nomor Induk Pegawai (NIP)</label>
                            <input type="text" id="nip" name="nip" maxlength="18"
                                class="form-input @error('nip') error @enderror" placeholder="18 digit angka tanpa spasi"
                                value="{{ old('nip') }}" required>
                            @error('nip')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Jabatan -->
                        <div class="form-group" style="flex: 1;">
                            <label for="jabatan" class="form-label">Jabatan Saat Ini</label>
                            <select id="jabatan" name="jabatan" class="form-select @error('jabatan') error @enderror"
                                required>
                                <option value="" disabled selected>Pilih Jabatan</option>
                                <option value="Kepala Seksi" {{ old('jabatan') == 'Kepala Seksi' ? 'selected' : '' }}>
                                    Kepala Seksi</option>
                                <option value="Petugas Lapangan"
                                    {{ old('jabatan') == 'Petugas Lapangan' ? 'selected' : '' }}>Petugas Lapangan</option>
                                <option value="Staf Administrasi"
                                    {{ old('jabatan') == 'Staf Administrasi' ? 'selected' : '' }}>Staf Administrasi
                                </option>
                                <option value="Analis Bencana" {{ old('jabatan') == 'Analis Bencana' ? 'selected' : '' }}>
                                    Analis Bencana</option>
                            </select>
                            @error('jabatan')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Unit Kerja -->
                    <div class="form-group">
                        <label for="unit_kerja" class="form-label">Unit Kerja / Wilayah Tugas</label>
                        <div class="input-wrapper-prepended">
                            <i data-lucide="map-pin"></i>
                            <input type="text" id="unit_kerja" name="unit_kerja"
                                class="form-input @error('unit_kerja') error @enderror"
                                placeholder="Contoh: BPBD Provinsi DKI Jakarta" value="{{ old('unit_kerja') }}" required>
                        </div>
                        @error('unit_kerja')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email & Phone & Password for registration success (needed by database) -->
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label for="email" class="form-label">Email Dinas / Pribadi</label>
                            <input type="email" id="email" name="email"
                                class="form-input @error('email') error @enderror" placeholder="contoh@bekasikota.go.id"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label for="phone" class="form-label">Nomor HP</label>
                            <input type="text" id="phone" name="phone"
                                class="form-input @error('phone') error @enderror" placeholder="0812xxxx"
                                value="{{ old('phone') }}" required>
                            @error('phone')
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

                    <!-- Dokumen Verifikasi Area -->
                    <div class="form-group">
                        <label class="form-label">Dokumen Verifikasi (Kartu Pegawai/SK)</label>
                        <div class="upload-area" onclick="document.getElementById('document').click()">
                            <input type="file" id="document" name="document"
                                accept="image/jpeg,image/png,application/pdf" style="display: none;"
                                onchange="handleFileSelected(this)" required>
                            <div class="upload-icon-wrapper">
                                <i data-lucide="file-text"></i>
                            </div>
                            <span class="upload-title" id="uploadTitle">Klik untuk unggah atau seret file</span>
                            <span class="upload-subtitle" id="uploadSubtitle">Format PDF, PNG, atau JPG (Maks. 5MB).
                                Pastikan NIP terlihat jelas.</span>
                        </div>
                        @error('document')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
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
