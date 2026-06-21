@extends('layouts.app')

@section('title', 'Daftar Warga - TitikAman')

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
            <p class="left-panel-desc">Sistem informasi banjir terintegrasi untuk membantu warga Bekasi memantau, melaporkan, dan merespons bencana dengan data akurat.</p>
            
            <div class="stepper-list">
                <!-- Step 1 -->
                <div class="step-item completed">
                    <div class="step-circle">
                        <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-info">
                        <span class="step-title">Pilih Peran</span>
                        <span class="step-desc">Tentukan hak akses Anda</span>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="step-item active">
                    <div class="step-circle">2</div>
                    <div class="step-line"></div>
                    <div class="step-info">
                        <span class="step-title">Data Diri</span>
                        <span class="step-desc">Informasi identitas resmi</span>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="step-item">
                    <div class="step-circle">3</div>
                    <div class="step-info">
                        <span class="step-title">Masuk</span>
                        <span class="step-desc">Masuk ke dashboard</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-box">
            <h4 class="info-box-title">Mengapa harus daftar?</h4>
            <ul class="info-list">
                <li class="info-item">
                    <i data-lucide="bell"></i>
                    <span>Peringatan dini banjir real-time</span>
                </li>
                <li class="info-item">
                    <i data-lucide="map-pin"></i>
                    <span>Lokasi posko terdekat dari koordinat Anda</span>
                </li>
                <li class="info-item">
                    <i data-lucide="heart-pulse"></i>
                    <span>Kirim sinyal SOS dalam keadaan darurat</span>
                </li>
                <li class="info-item">
                    <i data-lucide="waves"></i>
                    <span>Akses data tinggi air sungai resmi</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Right Panel (Registration Form) -->
    <div class="right-panel">
        <div class="card-container">
            <div class="form-header">
                <div class="form-stepper-pills">
                    <div class="stepper-pill active"></div>
                    <div class="stepper-pill active"></div>
                    <div class="stepper-pill"></div>
                </div>
                <h1 class="form-title">Lengkapi Data Dirimu</h1>
                <p class="form-subtitle">Kami memerlukan data ini untuk verifikasi keamanan dan akurasi laporan mitigasi banjir di wilayah Anda. Data Anda tersimpan aman.</p>
            </div>

            <div class="form-body">
                @if ($errors->any())
                    <div class="error-alert">
                        <ul style="list-style: none;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.step2.warga.submit') }}" method="POST">
                    @csrf
                    
                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label for="fullname" class="form-label">Nama Lengkap *</label>
                        <div class="input-wrapper">
                            <i data-lucide="user"></i>
                            <input type="text" 
                                   id="fullname" 
                                   name="fullname" 
                                   class="form-input @error('fullname') error @enderror" 
                                   placeholder="Masukkan nama sesuai KTP" 
                                   value="{{ old('fullname') }}" 
                                   required>
                        </div>
                        @error('fullname')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div class="form-group">
                        <label for="phone" class="form-label">Nomor HP (WhatsApp aktif) *</label>
                        <div class="input-wrapper">
                            <i data-lucide="phone"></i>
                            <input type="text" 
                                   id="phone" 
                                   name="phone" 
                                   class="form-input @error('phone') error @enderror" 
                                   placeholder="0812xxxx" 
                                   value="{{ old('phone') }}" 
                                   required>
                        </div>
                        <div class="form-input-info">
                            <i data-lucide="info"></i>
                            <span>Kode verifikasi akan dikirim ke nomor ini</span>
                        </div>
                        @error('phone')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email (Opsional)</label>
                        <div class="input-wrapper">
                            <i data-lucide="mail"></i>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-input @error('email') error @enderror" 
                                   placeholder="contoh@email.com" 
                                   value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi *</label>
                        <div class="input-wrapper">
                            <i data-lucide="lock"></i>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-input @error('password') error @enderror" 
                                   placeholder="Buat kata sandi minimal 8 karakter" 
                                   required>
                            <button type="button" class="password-toggle" id="togglePasswordBtn1" aria-label="Toggle Password Visibility">
                                <i data-lucide="eye" id="togglePasswordIcon1"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi *</label>
                        <div class="input-wrapper">
                            <i data-lucide="lock"></i>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="form-input" 
                                   placeholder="Ulangi kata sandi Anda" 
                                   required>
                            <button type="button" class="password-toggle" id="togglePasswordBtn2" aria-label="Toggle Password Visibility">
                                <i data-lucide="eye" id="togglePasswordIcon2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Domicile Dropdowns -->
                    <div class="form-row">
                        <!-- Kecamatan -->
                        <div class="form-group">
                            <label for="kecamatan" class="form-label">Kecamatan Domisili *</label>
                            <select id="kecamatan" name="kecamatan" class="form-select @error('kecamatan') error @enderror" required>
                                <option value="" disabled selected>Pilih Kecamatan</option>
                                <option value="Pondok Gede" {{ old('kecamatan') == 'Pondok Gede' ? 'selected' : '' }}>Pondok Gede</option>
                                <option value="Jatiasih" {{ old('kecamatan') == 'Jatiasih' ? 'selected' : '' }}>Jatiasih</option>
                                <option value="Bekasi Timur" {{ old('kecamatan') == 'Bekasi Timur' ? 'selected' : '' }}>Bekasi Timur</option>
                                <option value="Bekasi Selatan" {{ old('kecamatan') == 'Bekasi Selatan' ? 'selected' : '' }}>Bekasi Selatan</option>
                                <option value="Bekasi Barat" {{ old('kecamatan') == 'Bekasi Barat' ? 'selected' : '' }}>Bekasi Barat</option>
                                <option value="Bekasi Utara" {{ old('kecamatan') == 'Bekasi Utara' ? 'selected' : '' }}>Bekasi Utara</option>
                                <option value="Rawalumbu" {{ old('kecamatan') == 'Rawalumbu' ? 'selected' : '' }}>Rawalumbu</option>
                                <option value="Mustikajaya" {{ old('kecamatan') == 'Mustikajaya' ? 'selected' : '' }}>Mustikajaya</option>
                            </select>
                            @error('kecamatan')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Kelurahan -->
                        <div class="form-group">
                            <label for="kelurahan" class="form-label">Kelurahan Domisili *</label>
                            <select id="kelurahan" name="kelurahan" class="form-select @error('kelurahan') error @enderror" required>
                                <option value="" disabled selected>Pilih Kelurahan</option>
                            </select>
                            @error('kelurahan')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" class="form-checkbox" required {{ old('terms') ? 'checked' : '' }}>
                        <label for="terms" class="checkbox-label">
                            Saya menyetujui <a href="#">Syarat & Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> TitikAman dalam pemrosesan data pribadi saya
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('register.step1') }}'">
                            <i data-lucide="arrow-left"></i>
                            <span>Kembali</span>
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <span>Lanjut</span>
                            <i data-lucide="arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Kelurahan mapping database
        const kelurahanDb = {
            'Pondok Gede': ['Jatiwaringin', 'Jatibening', 'Jatibening Baru', 'Jaticempaka', 'Jatimakmur'],
            'Jatiasih': ['Jatiasih', 'Jatikramat', 'Jatiluhur', 'Jatirasa', 'Jatisari', 'Jati Mekar'],
            'Bekasi Timur': ['Aren Jaya', 'Bekasi Jaya', 'Duren Jaya', 'Margahayu'],
            'Bekasi Selatan': ['Jakamulya', 'Jakasetia', 'Kayuringin Jaya', 'Marga Jaya', 'Pekayon Jaya'],
            'Bekasi Barat': ['Bintara', 'Bintara Jaya', 'Jakasampurna', 'Kota Baru', 'Kranji'],
            'Bekasi Utara': ['Harapan Baru', 'Harapan Jaya', 'Kaliabang Tengah', 'Marga Mulya', 'Perwira', 'Teluk Pucung'],
            'Rawalumbu': ['Bojong Rawalumbu', 'Bojong Menteng', 'Pengasinan', 'Sepanjang Jaya'],
            'Mustikajaya': ['Mustikajaya', 'Mustikasari', 'Pedurenan', 'Cimuning']
        };

        const kecamatanSelect = document.getElementById('kecamatan');
        const kelurahanSelect = document.getElementById('kelurahan');

        function updateKelurahan(selectedKecamatan, selectedKelurahan = null) {
            // Clear current options
            kelurahanSelect.innerHTML = '<option value="" disabled selected>Pilih Kelurahan</option>';
            
            if (selectedKecamatan && kelurahanDb[selectedKecamatan]) {
                kelurahanDb[selectedKecamatan].forEach(kel => {
                    const option = document.createElement('option');
                    option.value = kel;
                    option.textContent = kel;
                    if (selectedKelurahan && kel === selectedKelurahan) {
                        option.selected = true;
                    }
                    kelurahanSelect.appendChild(option);
                });
            }
        }

        // Handle change event
        kecamatanSelect.addEventListener('change', function () {
            updateKelurahan(this.value);
        });

        // Initialize kelurahan if old input exists
        const oldKecamatan = "{{ old('kecamatan') }}";
        const oldKelurahan = "{{ old('kelurahan') }}";
        if (oldKecamatan) {
            updateKelurahan(oldKecamatan, oldKelurahan);
        }

        // Toggle Password Visibility
        function setupPasswordToggle(btnId, inputId, iconId) {
            const btn = document.getElementById(btnId);
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            btn.addEventListener('click', function () {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                if (type === 'text') {
                    icon.setAttribute('data-lucide', 'eye-off');
                } else {
                    icon.setAttribute('data-lucide', 'eye');
                }
                lucide.createIcons();
            });
        }

        setupPasswordToggle('togglePasswordBtn1', 'password', 'togglePasswordIcon1');
        setupPasswordToggle('togglePasswordBtn2', 'password_confirmation', 'togglePasswordIcon2');
    });
</script>
@endsection
