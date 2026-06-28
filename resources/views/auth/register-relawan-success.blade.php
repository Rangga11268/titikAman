@extends('layouts.auth')

@section('title', 'Pendaftaran Relawan Berhasil - TitikAman')

@section('content')
<div class="auth-card" style="text-align: center; max-width: 500px; margin: 0 auto; padding: 40px 20px;">
    <div style="display: flex; justify-content: center; margin-bottom: 24px;">
        <div style="background-color: #d1f4e0; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="check-circle" style="color: #006a60; width: 40px; height: 40px;"></i>
        </div>
    </div>
    
    <h1 class="auth-title" style="margin-bottom: 16px;">Pendaftaran Berhasil!</h1>
    
    <p class="auth-subtitle" style="margin-bottom: 24px; color: #4b5563; line-height: 1.6;">
        Terima kasih telah mendaftar sebagai anggota Tim Relawan/SAR TitikAman. Data Anda telah kami catat.
    </p>

    <div style="background-color: #f3f4f6; border-radius: 8px; padding: 20px; margin-bottom: 32px; text-align: left;">
        <h3 style="font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 8px;">Langkah Selanjutnya:</h3>
        <p style="font-size: 14px; color: #4b5563; margin-bottom: 16px;">
            Untuk koordinasi lapangan dan pembagian tugas, silakan segera bergabung dengan Grup Komunikasi Tim Relawan kami melalui tautan di bawah ini.
        </p>
        
        <a href="https://chat.whatsapp.com/dummy_link" target="_blank" class="btn-primary" style="display: flex; justify-content: center; align-items: center; gap: 8px; width: 100%; background-color: #25D366; border-color: #25D366;">
            <i data-lucide="message-circle" style="width: 20px; height: 20px;"></i>
            Bergabung ke Grup WhatsApp
        </a>
    </div>

    <a href="{{ url('/') }}" class="btn-outline" style="display: block; width: 100%;">
        Kembali ke Beranda
    </a>
</div>
@endsection
