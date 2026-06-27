@extends('layouts.dashboard')

@section('title', 'Kelola Kebutuhan Posko - TitikAman')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/pengelola.css') }}">
@endsection

@section('content')
@php
// Category & Unit Helper Function
if (!function_exists('parseNeedItem')) {
    function parseNeedItem($fullName) {
    $category = 'Lain-lain';
    $name = $fullName;
    $unit = 'Unit';

    if (str_contains($fullName, ' - ')) {
        $parts = explode(' - ', $fullName, 2);
        $category = trim($parts[0]);
        $rest = trim($parts[1]);
        if (str_contains($rest, '(') && str_ends_with($rest, ')')) {
            $subParts = explode('(', $rest);
            $name = trim($subParts[0]);
            $unit = trim(str_replace(')', '', $subParts[count($subParts)-1]));
        } else {
            $name = $rest;
        }
    } else {
        if (str_contains($fullName, '(') && str_ends_with($fullName, ')')) {
            $subParts = explode('(', $fullName);
            $name = trim($subParts[0]);
            $unit = trim(str_replace(')', '', $subParts[count($subParts)-1]));
        }
        
        $lower = strtolower($name);
        if (str_contains($lower, 'makanan') || str_contains($lower, 'porsi') || str_contains($lower, 'beras') || str_contains($lower, 'indomie') || str_contains($lower, 'roti')) {
            $category = 'Makanan';
        } elseif (str_contains($lower, 'susu') || str_contains($lower, 'bayi') || str_contains($lower, 'pampers') || str_contains($lower, 'popok')) {
            $category = 'Kebutuhan Bayi';
        } elseif (str_contains($lower, 'obat') || str_contains($lower, 'medis') || str_contains($lower, 'masker') || str_contains($lower, 'p3k')) {
            $category = 'Kesehatan';
        } elseif (str_contains($lower, 'pembalut') || str_contains($lower, 'toilet') || str_contains($lower, 'sabun') || str_contains($lower, 'shampoo')) {
            $category = 'Kebutuhan Wanita';
        } elseif (str_contains($lower, 'selimut') || str_contains($lower, 'tikar') || str_contains($lower, 'baju') || str_contains($lower, 'pakaian')) {
            $category = 'Perlengkapan';
        } elseif (str_contains($lower, 'air') || str_contains($lower, 'mineral') || str_contains($lower, 'minum')) {
            $category = 'Air Bersih';
        }
    }

    return [
        'category' => $category,
        'name' => $name,
        'unit' => strtoupper($unit)
    ];
}
}
@endphp

@section('topbar-left')
    <h1>Kelola Kebutuhan Posko</h1>
@endsection

@section('dashboard-content')
    <!-- Main Content Area -->
    <div class="pengelola-wrapper" style="padding-top: 0; display: flex; flex-direction: column; flex: 1; overflow-y: auto;">
        <!-- Toast Alerts -->
        @if (session('success'))
            <div class="dashboard-toast" id="successToast" style="position: static; transform: none; margin-bottom: 20px; width: 100%; max-width: 100%;">
                <i data-lucide="check-circle" class="text-teal" style="width: 20px; height: 20px;"></i>
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('successToast').style.display='none'" class="toast-close">
                    <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="dashboard-toast" id="errorToast" style="position: static; margin-bottom: 20px; width: 100%; max-width: 100%; border-left-color: var(--color-accent-red);">
                <i data-lucide="alert-circle" class="text-red" style="width: 20px; height: 20px;"></i>
                <span>{{ session('error') }}</span>
                <button onclick="document.getElementById('errorToast').style.display='none'" class="toast-close">
                    <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                </button>
            </div>
        @endif

        @if(!isset($shelter))

            <div class="pengelola-header-top">
                <div class="pengelola-title-block">
                    <h1>Pilih Posko Pengungsian</h1>
                    <p>Pilih salah satu posko pengungsian aktif yang akan Anda kelola.</p>
                </div>
            </div>

            <div class="selection-grid">
                @forelse($shelters as $s)
                    <div class="selection-card">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                <h3 class="selection-card-title">{{ $s->shelter_name }}</h3>
                                <span class="status-badge {{ $s->status === 'active' ? 'active-posko' : ($s->status === 'full' ? 'full-posko' : 'closed-posko') }}">
                                    {{ $s->status === 'active' ? 'Aktif' : ($s->status === 'full' ? 'Penuh' : 'Tutup') }}
                                </span>
                            </div>
                            <span class="selection-card-address">
                                <i data-lucide="map-pin" style="width: 14px; height: 14px;"></i>
                                {{ $s->address }}
                            </span>
                            
                            @php
                                $capPercent = $s->max_capacity > 0 ? ($s->current_occupants / $s->max_capacity) * 100 : 0;
                                $colorFill = 'bg-green';
                                if ($capPercent >= 100) { $colorFill = 'bg-red'; }
                                elseif ($capPercent >= 80) { $colorFill = 'bg-orange'; }
                            @endphp

                            <div class="selection-card-details">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                    <span style="color: var(--color-text-muted); font-weight: 500;">Hunian Pengungsi</span>
                                    <span style="font-weight: 700; color: var(--color-neutral-dark);">{{ $s->current_occupants }} / {{ $s->max_capacity }} Jiwa</span>
                                </div>
                                <div class="progress-container">
                                    <div class="progress-bar-fill {{ $colorFill }}" style="width: {{ min($capPercent, 100) }}%"></div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('pengelola.select-shelter') }}" method="POST">
                            @csrf
                            <input type="hidden" name="shelter_id" value="{{ $s->shelter_id }}">
                            <button type="submit" class="btn btn-primary" style="width: 100%; height: 40px; font-size: 14px; border-radius: 8px;">
                                <i data-lucide="check-square" style="width:16px; height:16px;"></i>
                                <span>Pilih Posko Ini</span>
                            </button>
                        </form>
                    </div>
                @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 48px; background: white; border-radius: 12px; border: 1px solid rgba(196,198,207,0.3);">
                        <i data-lucide="home" style="width: 48px; height: 48px; color: var(--color-text-muted); margin-bottom: 12px;"></i>
                        <p style="font-weight: 600; color: var(--color-neutral-dark);">Tidak ada data posko pengungsian terdaftar saat ini.</p>
                    </div>
                @endforelse
            </div>

        @else
            <!-- ==========================================
                 VIEW B: SHELTER MANAGEMENT DASHBOARD
                 ========================================== -->
            <div class="pengelola-header-top">
                <div class="pengelola-title-block">
                    <h1>Kelola Kebutuhan Posko</h1>
                    <p>{{ $shelter->shelter_name }} — Dikelola oleh: <strong>{{ auth()->user()->fullname }}</strong></p>
                </div>
                <div class="header-right-widgets">
                    @php
                        $pendingCount = $donations->where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <div class="notification-pill-banner">
                            <i data-lucide="alert-circle" style="width: 16px; height: 16px;"></i>
                            <span>{{ $pendingCount }} DONASI MENUNGGU VERIFIKASI</span>
                        </div>
                    @endif
                    <div class="notification-bell" title="Notifikasi" style="position: relative; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                        <i data-lucide="bell" class="icon-btn-widget" style="width: 20px; height: 20px; margin: 0;"></i>
                        <div class="notification-dot" style="position: absolute; top: -4px; right: -4px; width: 8px; height: 8px; background-color: var(--color-accent-red); border-radius: 50%;"></div>
                    </div>
                    <i data-lucide="help-circle" class="icon-btn-widget" style="width: 20px; height: 20px;"></i>
                </div>
            </div>

            <!-- Blue Status Banner Card -->
            <div class="status-banner-card">
                <div class="status-items-row">
                    <!-- Stat 1: Pengungsi -->
                    <div class="status-item-col">
                        <span class="status-item-label">Pengungsi</span>
                        <div class="status-item-value-row">
                            <span class="bold-val">{{ $shelter->current_occupants }}</span>
                            <span style="font-weight: 400; opacity: 0.8;">/ {{ $shelter->max_capacity }} jiwa</span>
                        </div>
                    </div>
                    <!-- Stat 2: Toilet -->
                    <div class="status-item-col">
                        <span class="status-item-label">Fasilitas MCK</span>
                        <div class="status-item-value-row">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px;"></i>
                            <span>{{ $shelter->has_toilet_facilities === 'Yes' ? 'Tersedia' : 'Tidak Ada' }}</span>
                        </div>
                    </div>
                    <!-- Stat 3: Dapur Umum -->
                    <div class="status-item-col">
                        <span class="status-item-label">Dapur Umum</span>
                        <div class="status-item-value-row">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px;"></i>
                            <span>Aktif</span>
                        </div>
                    </div>
                    <!-- Stat 4: Ramah Lansia -->
                    <div class="status-item-col">
                        <span class="status-item-label">Ramah Lansia/Anak</span>
                        <div class="status-item-value-row">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px;"></i>
                            <span>Ya</span>
                        </div>
                    </div>
                    <!-- Stat 5: Update Terakhir -->
                    <div class="status-item-col">
                        <span class="status-item-label">Update Terakhir</span>
                        <div class="status-item-value-row">
                            <i data-lucide="clock" style="width: 16px; height: 16px; color: #fff; fill: none;"></i>
                            <span style="font-size: 13px;">{{ $shelter->updated_at ? $shelter->updated_at->diffForHumans() : '23 min ago' }}</span>
                        </div>
                    </div>
                </div>

                <button class="btn-update-info-posko" onclick="openUpdateModal()">
                    <i data-lucide="edit-3" style="width: 16px; height: 16px;"></i>
                    <span>Update Info Posko</span>
                </button>
            </div>

            <!-- Two Column Layout Grid -->
            <div class="pengelola-grid">
                <!-- Left Column: Active Needs -->
                <div class="section-card">
                    <div class="section-header-row">
                        <h2 class="section-title">Daftar Kebutuhan Aktif</h2>
                        <span class="section-subtitle">Total: {{ $needs->where('quantity_fulfilled', '<', 'quantity_need')->count() }} Kategori</span>
                    </div>

                    <div class="need-list">
                        @forelse($needs->where('quantity_fulfilled', '<', 'quantity_need') as $need)
                            @php
                                $parsed = parseNeedItem($need->item_name);
                                $needPercent = $need->quantity_need > 0 ? ($need->quantity_fulfilled / $need->quantity_need) * 100 : 0;
                                
                                $urgencyClass = 'urgency-rendah';
                                $urgencyLabel = 'RENDAH';
                                if ($need->urgency === 'high') {
                                    $urgencyClass = 'urgency-mendesak';
                                    $urgencyLabel = 'MENDESAK';
                                } elseif ($need->urgency === 'medium') {
                                    $urgencyClass = 'urgency-sedang';
                                    $urgencyLabel = 'SEDANG';
                                }

                                $borderClass = 'border-other';
                                $icon = 'package';
                                $catLower = strtolower($parsed['category']);
                                if (str_contains($catLower, 'makan')) {
                                    $borderClass = 'border-makanan';
                                    $icon = 'utensils';
                                } elseif (str_contains($catLower, 'bayi')) {
                                    $borderClass = 'border-bayi';
                                    $icon = 'baby';
                                } elseif (str_contains($catLower, 'wanita') || str_contains($catLower, 'sanitasi') || str_contains($catLower, 'pembalut')) {
                                    $borderClass = 'border-wanita';
                                    $icon = 'venus'; 
                                } elseif (str_contains($catLower, 'sehat') || str_contains($catLower, 'medi') || str_contains($catLower, 'obat')) {
                                    $borderClass = 'border-medis';
                                    $icon = 'pill'; 
                                } elseif (str_contains($catLower, 'lengkap') || str_contains($catLower, 'perlengkapan') || str_contains($catLower, 'selimut')) {
                                    $borderClass = 'border-perlengkapan';
                                    $icon = 'bed';
                                } elseif (str_contains($catLower, 'air') || str_contains($catLower, 'bersih')) {
                                    $borderClass = 'border-air';
                                    $icon = 'droplet';
                                }
                            @endphp
                            
                            <div class="need-item-card {{ $borderClass }}">
                                <div class="need-card-left-section">
                                    <div class="category-icon-box">
                                        <i data-lucide="{{ $icon }}" style="width: 20px; height: 20px;"></i>
                                    </div>
                                    <div class="need-card-details">
                                        <span class="need-title-text">{{ $parsed['category'] }} - {{ $parsed['name'] }}</span>
                                        <div class="need-progress-bar-container">
                                            <div class="need-progress-bar-fill" style="width: {{ min($needPercent, 100) }}%"></div>
                                        </div>
                                        <div class="need-progress-info-row">
                                            <span>PROGRESS: {{ $need->quantity_fulfilled }}/{{ $need->quantity_need }} {{ $parsed['unit'] }}</span>
                                            <span>{{ number_format($needPercent, 0) }}% TERPENUHI</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <span class="urgency-tag-pill {{ $urgencyClass }}">{{ $urgencyLabel }}</span>
                                </div>
                            </div>
                        @empty
                            <p style="text-align: center; padding: 24px; color: var(--color-text-muted); font-style: italic;">Tidak ada kebutuhan aktif saat ini.</p>
                        @endforelse

                        <button class="btn-add-need-dotted" onclick="focusItemName()">
                            <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                            <span>Tambah Kebutuhan Baru</span>
                        </button>

                        <div class="completed-toggle-container">
                            <button class="completed-toggle-btn" onclick="toggleCompletedSection()">
                                <i data-lucide="chevron-down" id="completedChevron" style="width: 16px; height: 16px;"></i>
                                <span>Kebutuhan yang Sudah Terpenuhi ({{ $needs->where('quantity_fulfilled', '>=', 'quantity_need')->count() }} item)</span>
                            </button>

                            <div id="completedNeedsList" style="display: none; margin-top: 14px;">
                                @foreach($needs->where('quantity_fulfilled', '>=', 'quantity_need') as $need)
                                    @php
                                        $parsed = parseNeedItem($need->item_name);
                                    @endphp
                                    <div class="need-item-card border-air" style="opacity: 0.7;">
                                        <div class="need-card-left-section">
                                            <div class="category-icon-box" style="background-color: #e8f5e9; color: var(--color-accent-green);">
                                                <i data-lucide="check" style="width: 20px; height: 20px;"></i>
                                            </div>
                                            <div class="need-card-details">
                                                <span class="need-title-text" style="text-decoration: line-through;">{{ $parsed['category'] }} - {{ $parsed['name'] }}</span>
                                                <div class="need-progress-info-row">
                                                    <span>Terpenuhi: {{ $need->quantity_fulfilled }}/{{ $need->quantity_need }} {{ $parsed['unit'] }}</span>
                                                    <span style="color: var(--color-accent-green);">100% TERPENUHI</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Form & Verification list -->
                <div class="right-panels-column">
                    <!-- Panel 1: Form Add Need -->
                    <div class="premium-side-panel">
                        <div class="panel-header">
                            <h2 class="panel-title">
                                <i data-lucide="plus-circle" style="width: 18px; height: 18px; color: var(--color-brand-teal);"></i>
                                Tambah Kebutuhan Baru
                            </h2>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('pengelola.need.add') }}" method="POST" id="addNeedForm">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label" for="item_name_input">Nama Barang</label>
                                    <input type="text" id="item_name_input" class="form-input" placeholder="Contoh: Tikar, Masker, Pakaian" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Kategori</label>
                                    <div class="category-selection-tags">
                                        <button type="button" class="category-tag-btn selected" onclick="selectCategoryTag(this, 'Makanan')">Makanan</button>
                                        <button type="button" class="category-tag-btn" onclick="selectCategoryTag(this, 'Kesehatan')">Kesehatan</button>
                                        <button type="button" class="category-tag-btn" onclick="selectCategoryTag(this, 'Sanitasi')">Sanitasi</button>
                                        <button type="button" class="category-tag-btn" onclick="selectCategoryTag(this, 'Bayi')">Bayi</button>
                                    </div>
                                    <input type="hidden" name="category_selected" id="category_selected" value="Makanan">
                                </div>

                                <div class="form-group input-row-flex">
                                    <div>
                                        <label class="form-label" for="quantity_need">Jumlah</label>
                                        <input type="number" name="quantity_need_raw" id="quantity_need_raw" class="form-input" value="0" min="1" required>
                                    </div>
                                    <div>
                                        <label class="form-label" for="quantity_unit">Satuan</label>
                                        <select id="quantity_unit" class="form-select" required>
                                            <option value="Box" selected>Box</option>
                                            <option value="Pcs">Pcs</option>
                                            <option value="Porsi">Porsi</option>
                                            <option value="Kaleng">Kaleng</option>
                                            <option value="Pack">Pack</option>
                                            <option value="Paket">Paket</option>
                                            <option value="Lembar">Lembar</option>
                                            <option value="Botol">Botol</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Tingkat Urgensi</label>
                                    <div class="urgency-selection-buttons">
                                        <button type="button" class="urgency-btn mendesak selected" onclick="selectUrgencyTag(this, 'high')">Mendesak</button>
                                        <button type="button" class="urgency-btn sedang" onclick="selectUrgencyTag(this, 'medium')">Sedang</button>
                                        <button type="button" class="urgency-btn rendah" onclick="selectUrgencyTag(this, 'low')">Rendah</button>
                                    </div>
                                    <input type="hidden" name="urgency" id="urgency_selected_val" value="high">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="notes_textarea">Catatan Tambahan</label>
                                    <textarea id="notes_textarea" class="form-textarea" placeholder="Tulis spesifikasi khusus..." style="height: 80px;"></textarea>
                                </div>

                                <!-- Hidden Final Combined Item Name Field -->
                                <input type="hidden" name="item_name" id="final_item_name_field">
                                <input type="hidden" name="quantity_need" id="final_quantity_need_field">

                                <button type="submit" class="btn-submit-teal" onclick="submitNeedForm(event)">Simpan Kebutuhan</button>
                            </form>
                        </div>
                    </div>

                    <!-- Panel 2: Incoming Donations Verification -->
                    <div class="premium-side-panel">
                        <div class="panel-header">
                            <h2 class="panel-title">
                                <i data-lucide="package" style="width: 18px; height: 18px; color: var(--color-brand-teal);"></i>
                                Donasi Masuk — Verifikasi
                            </h2>
                            <span class="status-badge bg-red" style="font-size: 11px; padding: 2px 8px; border-radius: 12px; color: white;">
                                {{ $pendingCount }}
                            </span>
                        </div>
                        <div class="panel-body">
                            @forelse($donations->where('status', 'pending') as $donation)
                                <div class="verification-donation-item">
                                    <div class="verification-item-header">
                                        <span class="verification-item-title">Donasi #00{{ $donation->donation_id }}: {{ $donation->shelterNeed ? parseNeedItem($donation->shelterNeed->item_name)['name'] : 'Barang' }}</span>
                                        <span class="verification-item-qty">{{ $donation->quantity_donated }} {{ $donation->shelterNeed ? parseNeedItem($donation->shelterNeed->item_name)['unit'] : 'Box' }}</span>
                                    </div>
                                    <p class="verification-item-subtitle">Dari: {{ $donation->donor->fullname }} • {{ $donation->created_at ? $donation->created_at->diffForHumans() : 'Baru saja' }}</p>
                                    
                                    @if($donation->proof_photo)
                                        <button class="btn-wide-action-photo" onclick="openLightbox('{{ asset('storage/' . $donation->proof_photo) }}')">
                                            <i data-lucide="image" style="width: 14px; height: 14px;"></i>
                                            <span>Lihat Foto Bukti</span>
                                        </button>
                                    @endif

                                    <div class="verification-action-buttons">
                                        <form action="{{ route('pengelola.donation.verify', $donation->donation_id) }}" method="POST" style="flex: 1; margin: 0;">
                                            @csrf
                                            <input type="hidden" name="status" value="delivered">
                                            <button type="submit" class="btn-confirm-green">Konfirmasi</button>
                                        </form>

                                        <form action="{{ route('pengelola.donation.verify', $donation->donation_id) }}" method="POST" style="flex: 1; margin: 0;">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn-reject-red-outline">Tolak</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div style="text-align: center; padding: 24px 0; color: var(--color-text-muted);">
                                    <i data-lucide="check-circle" style="width: 32px; height: 32px; color: var(--color-accent-green); margin-bottom: 8px;"></i>
                                    <p style="font-size: 13px; font-weight: 600;">Semua donasi masuk telah terverifikasi!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Update Info Posko -->
@if(isset($shelter))
<div id="updateInfoModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-header-title">
                <div class="alert-icon-circle" style="background-color: rgba(0, 106, 96, 0.1);">
                    <i data-lucide="home" style="color: var(--color-brand-teal); width: 20px; height: 20px;"></i>
                </div>
                <div>
                    <h3 class="modal-title">Update Info Posko</h3>
                    <p class="modal-subtitle">{{ $shelter->shelter_name }}</p>
                </div>
            </div>
            <button onclick="closeUpdateModal()" class="modal-close-btn">&times;</button>
        </div>
        <form action="{{ route('pengelola.shelter.update') }}" method="POST">
            @csrf
            <div class="modal-body" style="display: flex; flex-direction: column; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="modal_current_occupants">Jumlah Pengungsi Aktif (Jiwa)</label>
                    <input type="number" name="current_occupants" id="modal_current_occupants" class="form-input" value="{{ $shelter->current_occupants }}" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="modal_status">Status Posko</label>
                    <select name="status" id="modal_status" class="form-select" required>
                        <option value="active" {{ $shelter->status === 'active' ? 'selected' : '' }}>Aktif (Tersedia Ruang)</option>
                        <option value="full" {{ $shelter->status === 'full' ? 'selected' : '' }}>Penuh</option>
                        <option value="closed" {{ $shelter->status === 'closed' ? 'selected' : '' }}>Tutup</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="modal_has_toilet_facilities">Fasilitas Toilet Sanitasi</label>
                    <select name="has_toilet_facilities" id="modal_has_toilet_facilities" class="form-select" required>
                        <option value="Yes" {{ $shelter->has_toilet_facilities === 'Yes' ? 'selected' : '' }}>Tersedia</option>
                        <option value="No" {{ $shelter->has_toilet_facilities === 'No' ? 'selected' : '' }}>Tidak Ada</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; margin-top: 10px; height: 44px; font-size: 14px; background-color: var(--color-brand-teal); border: none; border-radius: 8px; color: white; font-weight: 700; cursor: pointer;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Photo Lightbox Modal -->
<div id="photoLightbox" class="lightbox-modal" onclick="closeLightbox()">
    <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
    <img class="lightbox-content" id="lightboxImg">
</div>
@endsection

@section('dashboard-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    });

    // --- 2. UPDATE INFO POSKO MODAL ---
    function openUpdateModal() {
        const modal = document.getElementById('updateInfoModal');
        if (modal) modal.classList.add('active');
    }

    function closeUpdateModal() {
        const modal = document.getElementById('updateInfoModal');
        if (modal) modal.classList.remove('active');
    }

    // --- 3. FORM INTERACTION JS ---
    function selectCategoryTag(element, categoryName) {
        // Unselect others
        document.querySelectorAll('.category-tag-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        element.classList.add('selected');
        document.getElementById('category_selected').value = categoryName;
    }

    function selectUrgencyTag(element, urgencyVal) {
        document.querySelectorAll('.urgency-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        element.classList.add('selected');
        document.getElementById('urgency_selected_val').value = urgencyVal;
    }

    function focusItemName() {
        const input = document.getElementById('item_name_input');
        if (input) {
            input.focus();
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function toggleCompletedSection() {
        const list = document.getElementById('completedNeedsList');
        const chevron = document.getElementById('completedChevron');
        if (list.style.display === 'none') {
            list.style.display = 'block';
            chevron.setAttribute('data-lucide', 'chevron-up');
        } else {
            list.style.display = 'none';
            chevron.setAttribute('data-lucide', 'chevron-down');
        }
        lucide.createIcons();
    }

    function submitNeedForm(event) {
        event.preventDefault();
        const category = document.getElementById('category_selected').value;
        const itemNameRaw = document.getElementById('item_name_input').value.trim();
        const quantity = document.getElementById('quantity_need_raw').value;
        const unit = document.getElementById('quantity_unit').value;

        if (itemNameRaw === '' || quantity <= 0) {
            alert('Nama barang dan jumlah harus diisi dengan benar.');
            return;
        }

        // Format: "Category - Item Name (Unit)"
        const finalItemName = `${category} - ${itemNameRaw} (${unit})`;
        
        document.getElementById('final_item_name_field').value = finalItemName;
        document.getElementById('final_quantity_need_field').value = quantity;

        document.getElementById('addNeedForm').submit();
    }

    // --- 4. LIGHTBOX FUNCTIONS ---
    function openLightbox(src) {
        const lightbox = document.getElementById('photoLightbox');
        const img = document.getElementById('lightboxImg');
        lightbox.style.display = 'block';
        img.src = src;
    }

    function closeLightbox() {
        document.getElementById('photoLightbox').style.display = 'none';
    }
</script>
@endsection
