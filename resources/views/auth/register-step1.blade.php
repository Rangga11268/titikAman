@extends('layouts.app')

@section('title', 'Pilih Peran - TitikAman')

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

    <!-- Right Panel (Role Selection) -->
    <div class="right-panel">
        <div class="card-container">
            <div class="form-header">
                <div class="form-stepper-pills">
                    <div class="stepper-pill active"></div>
                    <div class="stepper-pill"></div>
                    <div class="stepper-pill"></div>
                </div>
                <h1 class="form-title">Kamu bergabung sebagai?</h1>
                <p class="form-subtitle">Pilih peran Anda untuk menyesuaikan fitur dan akses yang akan didapatkan di platform ini.</p>
            </div>

            <div class="form-body">
                <form id="roleForm" action="{{ route('register.step2.warga') }}" method="GET">
                    <div class="role-grid">
                        <!-- Card Warga -->
                        <div class="role-card selected" onclick="selectRole('warga')">
                            <div class="role-card-header">
                                <div class="role-icon-box">
                                    <i data-lucide="home"></i>
                                </div>
                                <input type="radio" name="role_select" id="role_warga" value="warga" class="role-radio" checked>
                            </div>
                            <div class="role-info">
                                <span class="role-title">Warga</span>
                                <p class="role-desc">Pantau status banjir dan temukan lokasi pengungsian terdekat.</p>
                            </div>
                            <div class="role-tags">
                                <span class="role-tag">Peringatan Dini</span>
                                <span class="role-tag">Peta Banjir</span>
                                <span class="role-tag">Kirim SOS</span>
                            </div>
                        </div>

                        <!-- Card Relawan -->
                        <div class="role-card" onclick="selectRole('relawan')">
                            <div class="role-card-header">
                                <div class="role-icon-box">
                                    <i data-lucide="users"></i>
                                </div>
                                <input type="radio" name="role_select" id="role_relawan" value="relawan" class="role-radio">
                            </div>
                            <div class="role-info">
                                <span class="role-title">Relawan / SAR</span>
                                <p class="role-desc">Koordinasi di lapangan dan bantu proses evakuasi warga.</p>
                            </div>
                            <div class="role-tags">
                                <span class="role-tag">Terima SOS</span>
                                <span class="role-tag">Koordinasi</span>
                                <span class="role-tag">Laporan</span>
                            </div>
                        </div>

                        <!-- Card Pengelola Posko -->
                        <div class="role-card" onclick="selectRole('pengelola')">
                            <div class="role-card-header">
                                <div class="role-icon-box">
                                    <i data-lucide="clipboard-list"></i>
                                </div>
                                <input type="radio" name="role_select" id="role_pengelola" value="pengelola" class="role-radio">
                            </div>
                            <div class="role-info">
                                <span class="role-title">Pengelola Posko</span>
                                <p class="role-desc">Kelola ketersediaan logistik dan kapasitas tempat pengungsian.</p>
                            </div>
                            <div class="role-tags">
                                <span class="role-tag">Kelola Posko</span>
                                <span class="role-tag">Logistik</span>
                                <span class="role-tag">Verifikasi</span>
                            </div>
                        </div>

                        <!-- Card Admin BPBD -->
                        <div class="role-card" onclick="selectRole('admin')">
                            <div class="role-card-header">
                                <div class="role-icon-box">
                                    <i data-lucide="shield"></i>
                                </div>
                                <input type="radio" name="role_select" id="role_admin" value="admin" class="role-radio">
                            </div>
                            <div class="role-info">
                                <span class="role-title">Admin BPBD</span>
                                <p class="role-desc">Akses penuh dashboard analisis data dan validasi informasi resmi.</p>
                            </div>
                            <div class="role-tags">
                                <span class="role-tag">Akses Penuh</span>
                                <span class="role-tag">Validasi</span>
                                <span class="role-tag">Analitik</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('login') }}'">
                            <i data-lucide="arrow-left"></i>
                            <span>Kembali</span>
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <span>Lanjut</span>
                            <i data-lucide="arrow-right"></i>
                        </button>
                    </div>

                    <!-- Login Redirect -->
                    <div class="login-link-container">
                        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function selectRole(role) {
        // Deselect all cards
        document.querySelectorAll('.role-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Select clicked card
        const radio = document.getElementById('role_' + role);
        radio.checked = true;

        // Add styling to parent card
        radio.closest('.role-card').classList.add('selected');
    }

    document.getElementById('roleForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const selectedRole = document.querySelector('input[name="role_select"]:checked').value;
        window.location.href = '/register/' + selectedRole;
    });
</script>
@endsection
