@extends('layouts.app')

@section('title', 'Manajemen TMA Pintu Air - TitikAman')

@section('styles')
<style>
    :root {
        --navy-sidebar: #1d3557;
        --navy-dark: #031f41;
        --brand-teal: #006a60;
        --brand-teal-hover: #004d46;
        --accent-red: #ba1a1a;
        --bg-light: #f8f9fa;
        --card-border: rgba(196, 198, 207, 0.4);
    }

    body {
        background-color: var(--bg-light);
        margin: 0;
        font-family: 'Inter', sans-serif;
    }

    .dashboard-container {
        display: flex;
        height: 100vh;
        overflow: hidden;
    }



    /* Main Workspace */
    .tma-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow-y: auto;
    }

    /* Topbar */
    .dashboard-topbar {
        padding: 16px 24px;
        background-color: white;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .topbar-left h1 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px;
        font-weight: 700;
        color: var(--navy-dark);
        margin: 0;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .user-profile-widget {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-widget-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: var(--brand-teal);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }

    .user-widget-info {
        display: flex;
        flex-direction: column;
    }

    .user-widget-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--navy-dark);
    }

    .user-widget-role {
        font-size: 11px;
        color: #6c757d;
    }

    /* TMA Grid & Forms */
    .tma-content {
        padding: 24px;
        max-width: 1200px;
        width: 100%;
        margin: 0 auto;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .tma-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 24px;
    }

    .tma-card {
        background-color: white;
        border-radius: 16px;
        border: 1px solid var(--card-border);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        position: relative;
        overflow: hidden;
    }

    .status-stripe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
    }

    .status-stripe.Normal { background-color: var(--brand-teal); }
    .status-stripe.Siaga_3 { background-color: #f59e0b; }
    .status-stripe.Siaga_2 { background-color: #ff781e; }
    .status-stripe.Siaga_1 { background-color: var(--accent-red); }

    .tma-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .gate-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: var(--navy-dark);
        margin: 0 0 4px 0;
    }

    .river-subtitle {
        font-size: 12px;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .status-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 12px;
        text-transform: uppercase;
    }

    .status-badge.Normal { background-color: rgba(0, 106, 96, 0.1); color: var(--brand-teal); }
    .status-badge.Siaga_3 { background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-badge.Siaga_2 { background-color: rgba(255, 120, 30, 0.1); color: #ff781e; }
    .status-badge.Siaga_1 { background-color: rgba(186, 26, 26, 0.1); color: var(--accent-red); }

    .tma-value-box {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid var(--card-border);
    }

    .tma-value-label {
        font-size: 12px;
        color: #879ec6;
        text-transform: uppercase;
        font-weight: 600;
    }

    .tma-value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: var(--navy-dark);
    }

    .tma-unit {
        font-size: 14px;
        font-weight: 500;
        color: #6c757d;
        margin-left: 4px;
    }

    .update-form {
        display: flex;
        flex-direction: column;
        gap: 12px;
        border-top: 1px solid #f1f3f5;
        padding-top: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--navy-dark);
    }

    .input-group {
        display: flex;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        overflow: hidden;
        background-color: white;
    }

    .input-tma {
        flex: 1;
        padding: 10px 14px;
        border: none;
        font-size: 14px;
        font-weight: 500;
        outline: none;
    }

    .input-unit {
        background-color: #e9ecef;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 600;
        color: #495057;
        border-left: 1px solid var(--card-border);
        display: flex;
        align-items: center;
    }

    .btn-update {
        background-color: var(--brand-teal);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background-color 0.2s;
    }

    .btn-update:hover {
        background-color: var(--brand-teal-hover);
    }

    .last-updated-meta {
        font-size: 11px;
        color: #adb5bd;
        text-align: right;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="tma-main">
        <!-- Topbar -->
        <div class="dashboard-topbar">
            <div class="topbar-left">
                <h1>Kelola Tinggi Muka Air (TMA)</h1>
            </div>
            <div class="topbar-right">
                <div class="user-profile-widget">
                    <div class="user-widget-avatar">
                        {{ strtoupper(substr(auth()->user()->fullname, 0, 2)) }}
                    </div>
                    <div class="user-widget-info">
                        <span class="user-widget-name">{{ auth()->user()->fullname }}</span>
                        <span class="user-widget-role">Admin BPBD</span>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div style="background-color: #d1e7dd; color: #0f5132; padding: 12px 24px; font-weight: 500; font-size: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="tma-content">
            <div style="background-color: #fff3cd; color: #664d03; padding: 16px; border-radius: 8px; border: 1px solid #ffe69c; font-size: 13px; font-weight: 500; line-height: 1.5; display: flex; align-items: center; gap: 12px;">
                <i data-lucide="alert-circle" style="color: #f59e0b; flex-shrink: 0;"></i>
                <span><strong>Perhatian:</strong> Mengubah status TMA ke <strong>SIAGA 2</strong> atau <strong>SIAGA 1</strong> akan memicu sistem untuk mengirimkan peringatan dini (early warning notification) otomatis kepada warga di kelurahan/kecamatan yang berada di sepanjang aliran sungai terkait.</span>
            </div>

            <div class="tma-grid">
                @foreach($waterGates as $gate)
                    <div class="tma-card">
                        <div class="status-stripe {{ $gate->danger_status }}"></div>
                        
                        <div class="tma-card-header">
                            <div>
                                <h3 class="gate-title">{{ $gate->gate_name }}</h3>
                                <span class="river-subtitle">
                                    <i data-lucide="waves" style="width: 14px; height: 14px; color: #879ec6;"></i>
                                    {{ $gate->river_name }}
                                </span>
                            </div>
                            <span class="status-badge {{ $gate->danger_status }}">{{ str_replace('_', ' ', $gate->danger_status) }}</span>
                        </div>

                        <div class="tma-value-box">
                            <span class="tma-value-label">Tinggi Muka Air</span>
                            <span class="tma-value">{{ $gate->water_level_cm }}<span class="tma-unit">cm</span></span>
                        </div>

                        <form action="{{ route('admin.tma.update', $gate->gate_id) }}" method="POST" class="update-form">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="water-level-{{ $gate->gate_id }}">Perbarui Ketinggian Air</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="water_level_cm" id="water-level-{{ $gate->gate_id }}" class="input-tma" value="{{ $gate->water_level_cm }}" required min="0">
                                    <span class="input-unit">cm</span>
                                </div>
                            </div>
                            <button type="submit" class="btn-update">
                                <i data-lucide="refresh-cw"></i>
                                <span>Simpan & Kirim Peringatan</span>
                            </button>
                        </form>

                        <div class="last-updated-meta">
                            Update Terakhir: {{ $gate->last_updated ? $gate->last_updated->format('d M Y, H:i') : $gate->updated_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
