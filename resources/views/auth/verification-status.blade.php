@extends('layouts.app')

@section('title', 'Status Verifikasi Akun - TitikAman')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/auth-registration.css') }}">
<style>
    .verif-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        width: 100%;
        padding: 48px 32px;
        text-align: center;
    }
    .verif-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }
    .verif-icon.pending { background: rgba(245,158,11,0.1); }
    .verif-icon.rejected { background: rgba(220,38,38,0.1); }
    .verif-icon i { width: 40px; height: 40px; }
    .verif-icon.pending i { color: #d97706; }
    .verif-icon.rejected i { color: #dc2626; }
    .verif-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: #031f41;
        margin-bottom: 12px;
    }
    .verif-subtitle {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 24px;
    }
    .verif-info-box {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 24px;
        text-align: left;
    }
    .verif-info-box h3 {
        font-size: 15px;
        font-weight: 700;
        color: #031f41;
        margin-bottom: 8px;
    }
    .verif-info-box p {
        font-size: 14px;
        color: #44474e;
        line-height: 1.6;
        margin-bottom: 12px;
    }
    .verif-info-box p:last-child { margin-bottom: 0; }
    .btn-wa {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        height: 48px;
        background: #25D366;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        text-decoration: none;
        transition: background 0.2s ease;
        cursor: pointer;
    }
    .btn-wa:hover {
        background: #1ea952;
        color: white;
    }
    .btn-wa i { width: 20px; height: 20px; }
    .btn-logout {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        height: 48px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        color: #4b5563;
        background: transparent;
        border: 2px solid #d1d5db;
        transition: all 0.2s ease;
        cursor: pointer;
        margin-top: 12px;
    }
    .btn-logout:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
        color: #111827;
    }
    .btn-logout i { width: 18px; height: 18px; }
</style>
@endsection

@section('content')
@php $status = auth()->user()->status ?? 'pending'; @endphp
<div class="split-container">
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
                <i data-lucide="shield-check"></i>
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
                <li class="info-item"><i data-lucide="bell"></i><span>Peringatan dini banjir real-time</span></li>
                <li class="info-item"><i data-lucide="map-pin"></i><span>Lokasi posko terdekat dari koordinat Anda</span></li>
                <li class="info-item"><i data-lucide="heart-pulse"></i><span>Kirim sinyal SOS dalam keadaan darurat</span></li>
                <li class="info-item"><i data-lucide="waves"></i><span>Akses data tinggi air sungai resmi</span></li>
            </ul>
        </div>
    </div>

    <div class="right-panel">
        <div class="verif-card">
            @if($status === 'pending')
                <div class="verif-icon pending">
                    <i data-lucide="clock"></i>
                </div>
                <h1 class="verif-title">Akun Sedang Diverifikasi</h1>
                <p class="verif-subtitle">
                    Terima kasih sudah mendaftar sebagai Relawan. Saat ini berkas pendaftaran dan dokumen KTP/SK Anda sedang diperiksa oleh Admin BPBD Kota Bekasi.
                </p>
                <div class="verif-info-box">
                    <h3>Proses Verifikasi</h3>
                    <p>Proses verifikasi biasanya memakan waktu maksimal 1x24 jam. Anda akan segera bisa masuk ke dashboard relawan setelah akun disetujui oleh Admin.</p>
                    <p>Jika dalam 24 jam belum ada perubahan status, silakan hubungi Admin BPBD melalui tombol di bawah ini untuk informasi lebih lanjut.</p>
                </div>
                <a href="https://wa.me/6281212345678?text=Halo%20Admin%20BPBD%2C%20saya%20ingin%20menanyakan%20status%20verifikasi%20akun%20Relawan%20saya." target="_blank" class="btn-wa">
                    <i data-lucide="message-circle"></i>
                    Hubungi Admin BPBD
                </a>
            @elseif($status === 'rejected')
                <div class="verif-icon rejected">
                    <i data-lucide="x-circle"></i>
                </div>
                <h1 class="verif-title">Pendaftaran Ditolak</h1>
                <p class="verif-subtitle">
                    Mohon maaf, pendaftaran akun Relawan Anda belum dapat disetujui oleh Admin BPBD. Hal ini biasanya dikarenakan dokumen identitas yang diunggah tidak jelas atau tidak valid.
                </p>
                <div class="verif-info-box">
                    <h3>Apa yang Harus Dilakukan?</h3>
                    <p>Silakan hubungi Admin BPBD Kota Bekasi melalui tombol di bawah untuk menanyakan alasan penolakan dan langkah selanjutnya yang dapat Anda ambil.</p>
                </div>
                <a href="https://wa.me/6281212345678?text=Halo%20Admin%20BPBD%2C%20saya%20ingin%20menanyakan%20alasan%20penolakan%20pendaftaran%20akun%20Relawan%20saya." target="_blank" class="btn-wa">
                    <i data-lucide="message-circle"></i>
                    Hubungi Admin BPBD
                </a>
            @elseif($status === 'approved')
                @php
                    $waNumber = preg_replace('/[^0-9]/', '', auth()->user()->phone ?? '');
                    $waText = "Halo! Saya *" . (auth()->user()->fullname ?? 'Relawan') . "* telah diterima sebagai anggota *{$team_name}*. \n\nBergabung ke grup tim saya melalui link berikut:\n{$group_link}\n\nTerima kasih.";
                    $waSelfUrl = $waNumber ? "https://wa.me/{$waNumber}?text=" . urlencode($waText) : '#';
                @endphp
                <div class="verif-icon" style="background: rgba(0,106,96,0.1);">
                    <i data-lucide="check-circle" style="width: 40px; height: 40px; color: #006a60;"></i>
                </div>
                <h1 class="verif-title">Akun Berhasil Diverifikasi!</h1>
                <p class="verif-subtitle">
                    Selamat! Akun Relawan Anda telah disetujui oleh Admin BPBD. Anda sekarang terdaftar sebagai bagian dari <strong>{{ $team_name ?? 'Tim Reguler' }}</strong>.
                </p>
                <div class="verif-info-box">
                    <h3>Gabung ke Grup Tim</h3>
                    <p>Untuk menerima informasi dan koordinasi evakuasi, silakan bergabung ke grup WhatsApp tim Anda melalui tombol di bawah ini.</p>
                    <a href="{{ $group_link ?? '#' }}" target="_blank" class="btn-wa" style="font-size: 13px; padding: 10px 16px; box-sizing: border-box; margin-bottom: 10px;">
                        <i data-lucide="message-circle"></i>
                        Gabung Grup WhatsApp {{ $team_name ?? 'Tim' }}
                    </a>
                    <a href="{{ $waSelfUrl }}" target="_blank" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px 16px; border-radius: 8px; font-weight: 600; font-size: 13px; text-decoration: none; background: #25D366; color: white; box-sizing: border-box;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Kirim Info ke WhatsApp Saya
                    </a>
                </div>
                <a href="{{ route('dashboard') }}" style="display: block; width: 100%; padding: 12px 24px; border-radius: 8px; font-weight: 600; text-decoration: none; background: #006a60; color: white; text-align: center; box-sizing: border-box;">
                    Lanjut ke Dashboard
                </a>
            @endif

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i data-lucide="log-out"></i>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection