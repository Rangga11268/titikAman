@extends('layouts.app')

@section('title', 'Dashboard - TitikAman')

@section('content')
<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa;">
    <div style="background: white; padding: 48px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; max-width: 500px; width: 90%;">
        <div style="width: 64px; height: 64px; background-color: #006a60; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px; color: white;">
            <i data-lucide="shield" style="width: 32px; height: 32px;"></i>
        </div>
        <h1 style="color: #031f41; font-size: 28px; margin-bottom: 8px; font-weight: 700;">Dashboard Sementara</h1>
        <p style="color: #6b7280; font-size: 15px; margin-bottom: 32px; line-height: 1.6;">
            Selamat datang, <strong>{{ auth()->user()->fullname }}</strong>!<br>
            Anda berhasil masuk dengan peran: <strong>{{ auth()->user()->role }}</strong>.
        </p>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary" style="width: 100%; border-color: #e63946; color: #e63946; background-color: transparent;">
                <i data-lucide="log-out"></i>
                <span>Keluar dari Akun</span>
            </button>
        </form>
    </div>
</div>
@endsection
