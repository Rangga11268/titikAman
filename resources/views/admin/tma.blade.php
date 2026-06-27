@extends('layouts.dashboard')

@section('title', 'Manajemen TMA Pintu Air - TitikAman')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin-tma.css') }}">
@endsection

@section('topbar-left')
    <h1>Kelola Tinggi Muka Air (TMA)</h1>
@endsection

@section('dashboard-content')
    <!-- Main Content -->
    <div class="tma-main" style="padding-top: 0; display: flex; flex-direction: column; flex: 1; overflow-y: auto;">
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
@endsection

@section('dashboard-scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Status color logicn
    });
</script>
@endsection
