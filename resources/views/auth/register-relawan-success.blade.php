@extends('layouts.app')

@section('title', 'Pendaftaran Relawan Berhasil - TitikAman')

@section('styles')
<style>
    body { background-color: #f8fafc; }
    .success-container {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }
    .auth-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        text-align: center;
        max-width: 500px;
        width: 100%;
        margin: 0 auto;
        padding: 48px 32px;
    }
    .auth-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 12px;
    }
    .btn-whatsapp {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        width: 100%;
        background-color: #25D366;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-whatsapp:hover {
        background-color: #1ea952;
        color: white;
        transform: translateY(-2px);
    }
    .btn-outline-back {
        display: block;
        width: 100%;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        color: #4b5563;
        background: transparent;
        border: 1px solid #d1d5db;
        transition: all 0.2s ease;
        margin-top: 16px;
    }
    .btn-outline-back:hover {
        background-color: #f3f4f6;
        color: #111827;
    }
</style>
@endsection

@section('content')
<div class="success-container">
    <div class="auth-card">
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
        
        <a href="https://chat.whatsapp.com/dummy_link" target="_blank" class="btn-whatsapp">
            <i data-lucide="message-circle" style="width: 20px; height: 20px;"></i>
            Bergabung ke Grup WhatsApp
        </a>
    </div>

    <a href="{{ url('/') }}" class="btn-outline-back">
        Kembali ke Beranda
    </a>
    </div>
</div>
@endsection
