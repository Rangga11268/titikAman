@extends('layouts.app')

@section('title', 'Masuk - TitikAman')

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

    <!-- Right Panel (Login Form) -->
    <div class="right-panel">
        <div class="card-container">
            <div class="form-header">
                <h1 class="form-title">Masuk ke Akun Anda</h1>
                <p class="form-subtitle">Akses dashboard untuk memantau status banjir dan berkoordinasi.</p>
            </div>

            <div class="form-body">
                @if (session('success'))
                    <div class="error-alert" style="background-color: #eafaf1; border-color: #c3e6cb; color: #155724;">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error-alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <!-- Login ID Field -->
                    <div class="form-group">
                        <label for="login_id" class="form-label">Email atau Nomor HP *</label>
                        <div class="input-wrapper">
                            <i data-lucide="user"></i>
                            <input type="text" 
                                   id="login_id" 
                                   name="login_id" 
                                   class="form-input @error('login_id') error @enderror" 
                                   placeholder="contoh@email.com / 0812xxxx" 
                                   value="{{ old('login_id') }}" 
                                   required 
                                   autocomplete="username">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi *</label>
                        <div class="input-wrapper">
                            <i data-lucide="lock"></i>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-input @error('password') error @enderror" 
                                   placeholder="Masukkan kata sandi Anda" 
                                   required 
                                   autocomplete="current-password">
                            <button type="button" class="password-toggle" id="togglePasswordBtn" aria-label="Toggle Password Visibility">
                                <i data-lucide="eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember" class="form-checkbox">
                        <label for="remember" class="checkbox-label">Ingat saya di perangkat ini</label>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <span>Masuk</span>
                            <i data-lucide="arrow-right"></i>
                        </button>
                    </div>

                    <!-- Register Redirect -->
                    <div class="login-link-container">
                        Belum punya akun? <a href="{{ route('register.step1') }}">Daftar di sini</a>
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
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');

        togglePasswordBtn.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle eye icon
            if (type === 'text') {
                togglePasswordIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                togglePasswordIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        });
    });
</script>
@endsection
