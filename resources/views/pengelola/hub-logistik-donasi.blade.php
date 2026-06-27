@extends('layouts.dashboard')

@section('title', 'Hub Logistik & Donasi - TitikAman')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/pengelola.css') }}">
@endsection

@section('content')
@php
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
    <div class="search-mockup-wrapper" style="width: 300px; display: flex; align-items: center; gap: 8px; background: #fff; border: 1px solid var(--color-border-muted); border-radius: 8px; padding: 6px 12px;">
        <i data-lucide="search" class="search-mockup-icon" style="width: 18px; height: 18px; color: var(--color-text-muted);"></i>
        <input type="text" class="search-mockup-input" placeholder="Cari logistik atau donatur..." disabled style="border: none; outline: none; background: transparent; width: 100%; font-size: 13px;">
    </div>
@endsection

@section('topbar-right')
    <button class="btn-emergency-alert" style="display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: #ffebee; color: #d32f2f; border: 1px solid #ffcdd2; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer;">
        <i data-lucide="alert-triangle" style="width: 14px; height: 14px;"></i>
        <span>Emergency Alert</span>
    </button>
@endsection

@section('dashboard-content')
    <!-- Main Content Area -->
    <div class="donasi-wrapper" style="padding-top: 0; display: flex; flex-direction: column; flex: 1; overflow-y: auto;">

        <div class="donasi-header-block">
            <h1>Hub Logistik & Donasi</h1>
            <p>Monitoring kebutuhan posko dan alur distribusi bantuan secara real-time.</p>
        </div>

        <!-- Stats Row -->
        <div class="stats-grid-row">
<!-- Stat 1: Total Needed -->
            <div class="stat-card-widget">
                <span class="stat-card-label">TOTAL NEEDED</span>
                <div class="stat-card-value-container">
                    <h2 class="stat-card-value">{{ $totalNeeded }}</h2>
                    @if($totalNeeded == 0)
                        <span class="badge-percent-red" style="background:#e2e8f0;color:#64748b;">No Data</span>
                    @else
                        <span class="badge-priority-orange">Logistik</span>
                    @endif
                </div>
            </div>
            <!-- Stat 2: Fulfilled -->
            <div class="stat-card-widget">
                <span class="stat-card-label">FULFILLED</span>
<div class="stat-card-value-container">
                    <h2 class="stat-card-value fulfilled-val">{{ $fulfilled }}</h2>
                    <div>
                        <div class="stat-mini-progress">
                            <div class="stat-mini-progress-fill" style="width: {{ $fulfillmentPercent }}%;"></div>
                        </div>
                        <span style="font-size:10px;color:var(--color-text-muted);font-weight:600;">{{ $fulfillmentPercent }}%</span>
                    </div>
                </div>
            </div>
            <!-- Stat 3: Remaining -->
            <div class="stat-card-widget">
                <span class="stat-card-label">REMAINING</span>
                <div class="stat-card-value-container">
                    <h2 class="stat-card-value">{{ $remaining }}</h2>
                    <span class="badge-priority-orange">Priority</span>
                </div>
            </div>
            <!-- Stat 4: Active Donors -->
            <div class="stat-card-widget">
                <span class="stat-card-label">ACTIVE DONORS</span>
                <div class="stat-card-value-container">
                    <h2 class="stat-card-value">{{ $activeDonors }}</h2>
                    <div class="overlapping-avatars-row">
                        <div class="overlap-avatar-circle c1"></div>
                        <div class="overlap-avatar-circle c2"></div>
                        <div class="overlap-avatar-circle c3"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid Layout Columns -->
        <div class="donasi-grid-columns">
            <!-- Left Column -->
            <div>
                <!-- Urgent Needs Panel -->
                <div class="section-white-card">
                    <div class="section-card-header">
                        <h2 class="section-card-title">
                            <span class="section-card-title-indicator"></span>
                            Kebutuhan Mendesak
                        </h2>
                        <a href="#" class="btn-link-action">Lihat Semua</a>
                    </div>

                    <div class="urgent-mini-grid">
                        @php $cardCount = 0; @endphp
                        @foreach($shelters as $s)
                            @foreach($s->shelterNeeds as $need)
                                @if($cardCount < 3)
                                    @php
                                        $cardCount++;
                                        $parsed = parseNeedItem($need->item_name);
                                        $needPercent = $need->quantity_need > 0 ? ($need->quantity_fulfilled / $need->quantity_need) * 100 : 0;
                                        
                                        $siagaClass = 'siaga-1';
                                        $siagaLabel = 'SIAGA 1';
                                        $fillClass = 'fill-siaga-1';
                                        if ($need->urgency === 'high') {
                                            $siagaClass = 'critical';
                                            $siagaLabel = 'CRITICAL';
                                            $fillClass = 'fill-critical';
                                        } elseif ($need->urgency === 'medium') {
                                            $siagaClass = 'siaga-2';
                                            $siagaLabel = 'SIAGA 2';
                                            $fillClass = 'fill-siaga-2';
                                        }

                                        $icon = 'package';
                                        $catLower = strtolower($parsed['category']);
                                        if (str_contains($catLower, 'makan')) { $icon = 'utensils'; }
                                        elseif (str_contains($catLower, 'susu') || str_contains($catLower, 'bayi')) { $icon = 'baby'; }
                                        elseif (str_contains($catLower, 'selimut') || str_contains($catLower, 'perlengkapan') || str_contains($catLower, 'sandang')) { $icon = 'shirt'; }
                                        elseif (str_contains($catLower, 'obat') || str_contains($catLower, 'sehat') || str_contains($catLower, 'medis') || str_contains($catLower, 'kesehatan')) { $icon = 'pill'; }
                                        elseif (str_contains($catLower, 'air') || str_contains($catLower, 'minum')) { $icon = 'droplet'; }
                                    @endphp
                                    <div class="urgent-mini-card">
                                        <div class="urgent-card-top">
                                            <div class="urgent-card-icon-box">
                                                <i data-lucide="{{ $icon }}" style="width: 18px; height: 18px; color: var(--color-brand-teal);"></i>
                                            </div>
                                            <span class="badge-siaga {{ $siagaClass }}">{{ $siagaLabel }}</span>
                                        </div>
                                        <h3 class="urgent-card-title">{{ $parsed['name'] }}</h3>
                                        <p class="urgent-card-subtitle">Target: {{ number_format($need->quantity_need, 0, ',', '.') }} {{ $parsed['unit'] }}</p>
                                        <div class="urgent-card-progress-section">
                                            <div class="urgent-card-progress-numbers">
                                                <span>{{ number_format($needPercent, 0) }}%</span>
                                                <span>{{ number_format($need->quantity_fulfilled, 0, ',', '.') }}/{{ number_format($need->quantity_need, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="urgent-progress-bar">
                                                <div class="urgent-progress-bar-fill {{ $fillClass }}" style="width: {{ $needPercent }}%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach

                        @if($cardCount === 0)
                            <!-- Static/Mock fallbacks exactly matching Figma design if DB is empty -->
                            <div class="urgent-mini-card">
                                <div class="urgent-card-top">
                                    <div class="urgent-card-icon-box">
                                        <i data-lucide="utensils" style="width: 18px; height: 18px; color: var(--color-brand-teal);"></i>
                                    </div>
                                    <span class="badge-siaga siaga-1">SIAGA 1</span>
                                </div>
                                <h3 class="urgent-card-title">Makanan</h3>
                                <p class="urgent-card-subtitle">Target: 2.000 Porsi</p>
                                <div class="urgent-card-progress-section">
                                    <div class="urgent-card-progress-numbers">
                                        <span>75%</span>
                                        <span>1.500/2k</span>
                                    </div>
                                    <div class="urgent-progress-bar">
                                        <div class="urgent-progress-bar-fill fill-siaga-1" style="width: 75%;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="urgent-mini-card">
                                <div class="urgent-card-top">
                                    <div class="urgent-card-icon-box">
                                        <i data-lucide="baby" style="width: 18px; height: 18px; color: var(--color-brand-teal);"></i>
                                    </div>
                                    <span class="badge-siaga siaga-2">SIAGA 2</span>
                                </div>
                                <h3 class="urgent-card-title">Susu Formula</h3>
                                <p class="urgent-card-subtitle">Target: 500 Dus</p>
                                <div class="urgent-card-progress-section">
                                    <div class="urgent-card-progress-numbers">
                                        <span>40%</span>
                                        <span>200/500</span>
                                    </div>
                                    <div class="urgent-progress-bar">
                                        <div class="urgent-progress-bar-fill fill-siaga-2" style="width: 40%;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="urgent-mini-card">
                                <div class="urgent-card-top">
                                    <div class="urgent-card-icon-box">
                                        <i data-lucide="shirt" style="width: 18px; height: 18px; color: var(--color-brand-teal);"></i>
                                    </div>
                                    <span class="badge-siaga critical">CRITICAL</span>
                                </div>
                                <h3 class="urgent-card-title">Selimut Hangat</h3>
                                <p class="urgent-card-subtitle">Target: 1.200 Pcs</p>
                                <div class="urgent-card-progress-section">
                                    <div class="urgent-card-progress-numbers">
                                        <span>20%</span>
                                        <span>240/1.2k</span>
                                    </div>
                                    <div class="urgent-progress-bar">
                                        <div class="urgent-progress-bar-fill fill-critical" style="width: 20%;"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

    <!-- Recent Donations Table Panel -->
                <div class="section-white-card" style="padding: 0; overflow: hidden;">
                    <div class="section-card-header" style="padding: 24px 24px 16px 24px; margin-bottom: 0;">
                        <h2 class="section-card-title">Donasi Terbaru</h2>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <!-- Filter Dropdown -->
                            <div style="position: relative;" id="filterDropdownWrapper">
                                <button id="filterToggleBtn" style="background: none; border: 1px solid var(--color-border-muted); border-radius: 8px; padding: 0 12px; height: 32px; display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 12px; font-weight: 600; color: #44474e;">
                                    <i data-lucide="filter" style="width: 13px; height: 13px;"></i>
                                    <span id="filterLabel">Semua</span>
                                </button>
                                <div id="filterDropdownMenu" style="display:none; position: absolute; right: 0; top: 38px; background: #fff; border: 1px solid var(--color-border-muted); border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); z-index: 100; min-width: 180px; overflow: hidden;">
                                    <div class="filter-opt" data-filter="all" style="padding: 10px 16px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center; color: var(--color-brand-teal); background: rgba(0,106,96,0.04);">
                                        <span>Semua</span>
                                        <span style="background:#e2e8f0;padding:2px 8px;border-radius:99px;font-size:11px;color:#44474e;">{{ $recentDonations->count() }}</span>
                                    </div>
                                    <div class="filter-opt" data-filter="pending" style="padding: 10px 16px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                                        <span>Pending</span>
                                        <span style="background:#ffdcc5;padding:2px 8px;border-radius:99px;font-size:11px;color:#713700;">{{ $donationStats['pending'] }}</span>
                                    </div>
                                    <div class="filter-opt" data-filter="accepted" style="padding: 10px 16px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                                        <span>Diterima</span>
                                        <span style="background:#8af5be;padding:2px 8px;border-radius:99px;font-size:11px;color:#00714b;">{{ $donationStats['accepted'] }}</span>
                                    </div>
                                    <div class="filter-opt" data-filter="rejected" style="padding: 10px 16px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                                        <span>Ditolak</span>
                                        <span style="background:#ffdad6;padding:2px 8px;border-radius:99px;font-size:11px;color:#93000a;">{{ $donationStats['rejected'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <button onclick="window.location.href='{{ route('donasi.export') }}'" style="background: none; border: 1px solid var(--color-border-muted); border-radius: 8px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer;" title="Unduh CSV Donasi">
                                <i data-lucide="download" style="width: 14px; height: 14px; color: #44474e;"></i>
                            </button>
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="donations-table">
                            <thead>
                                <tr>
                                    <th>DONATUR</th>
                                    <th>ITEM / KATEGORI</th>
                                    <th>STATUS</th>
                                    <th>WAKTU</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDonations as $donation)
                                    @php
                                        // Dynamic avatar color & initials
                                        $initials = 'AN';
                                        $avatarColor = 'blue';
                                        $roleLabel = 'Individual';
                                        
                                        if ($donation->donor) {
                                            $nameParts = explode(' ', $donation->donor->fullname);
                                            $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                                            
                                            if ($donation->donor->role === 'BPBD' || $donation->donor->role === 'Admin_BPBD') {
                                                $roleLabel = 'BPBD / Corporate';
                                                $avatarColor = 'blue';
                                            } elseif ($donation->donor->role === 'Relawan') {
                                                $roleLabel = 'Relawan / Foundation';
                                                $avatarColor = 'green';
                                            }
                                        }

// Status Pill Mapping
                                        $statusClass = 'status-jalan';
                                        $statusText = 'PENDING';
                                        if ($donation->status === 'pending') {
                                            $statusClass = 'status-pending';
                                            $statusText = 'PENDING';
                                        } elseif ($donation->status === 'accepted' || $donation->status === 'delivered') {
                                            $statusClass = 'status-terima';
                                            $statusText = 'DITERIMA';
                                        } elseif ($donation->status === 'rejected') {
                                            $statusClass = 'status-ditolak';
                                            $statusText = 'DITOLAK';
                                        }

                                        // Category Parsing
                                        $itemName = $donation->shelterNeed ? $donation->shelterNeed->item_name : 'Barang Donasi';
                                        $itemLower = strtolower($itemName);
                                        $categoryLabel = 'Logistik';
                                        if (str_contains($itemLower, 'makan') || str_contains($itemLower, 'beras') || str_contains($itemLower, 'makanan siap')) {
                                            $categoryLabel = 'Logistik';
                                        } elseif (str_contains($itemLower, 'obat') || str_contains($itemLower, 'medis') || str_contains($itemLower, 'masker') || str_contains($itemLower, 'p3k')) {
                                            $categoryLabel = 'Kesehatan';
                                        } elseif (str_contains($itemLower, 'selimut') || str_contains($itemLower, 'pakaian') || str_contains($itemLower, 'baju') || str_contains($itemLower, 'tikar')) {
                                            $categoryLabel = 'Sandang';
                                        } elseif (str_contains($itemLower, 'susu') || str_contains($itemLower, 'bayi') || str_contains($itemLower, 'pampers')) {
                                            $categoryLabel = 'Kebutuhan Bayi';
                                        } elseif (str_contains($itemLower, 'air') || str_contains($itemLower, 'mineral') || str_contains($itemLower, 'minum')) {
                                            $categoryLabel = 'Air Bersih';
                                        }
                                    @endphp
                                    <tr data-status="{{ $donation->status }}">
                                        <td>
                                            <div class="donatur-profile-cell">
                                                <div class="initials-avatar-circle {{ $avatarColor }}">
                                                    {{ $initials }}
                                                </div>
                                                <div class="donatur-name-block">
                                                    <span class="donatur-fullname">{{ $donation->donor ? $donation->donor->fullname : 'Anonim' }}</span>
                                                    <span class="donatur-role-type">{{ $roleLabel }}</span>
                                                </div>
                                            </div>
                                        </td>
<td>
                                            <div class="item-category-cell">
                                                <span class="donation-item-qty">{{ $donation->quantity_donated }} {{ $donation->shelterNeed?->item_name ? parseNeedItem($donation->shelterNeed->item_name)['unit'] : 'Unit' }}</span>
                                                <span class="badge-category-mini" data-filter-category="{{ $donation->status }}">{{ $categoryLabel }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-pill-dot {{ $statusClass }}">
                                                <span class="dot"></span>
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="waktu-cell-text">{{ $donation->created_at->diffForHumans() }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Static/Mock fallbacks exactly matching Figma design if DB is empty -->
                                    <tr>
                                        <td>
                                            <div class="donatur-profile-cell">
                                                <div class="initials-avatar-circle blue">PT</div>
                                                <div class="donatur-name-block">
                                                    <span class="donatur-fullname">Pangan Terpadu</span>
                                                    <span class="donatur-role-type">Corporate</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="item-category-cell">
                                                <span class="donation-item-qty">500 Box Makanan</span>
                                                <span class="badge-category-mini">Logistik</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-pill-dot status-terima">
                                                <span class="dot"></span>
                                                TERIMA
                                            </span>
                                        </td>
                                        <td>
                                            <span class="waktu-cell-text">2 Menit Lalu</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="donatur-profile-cell">
                                                <div class="initials-avatar-circle orange">AR</div>
                                                <div class="donatur-name-block">
                                                    <span class="donatur-fullname">Andi Ramadhan</span>
                                                    <span class="donatur-role-type">Individual</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="item-category-cell">
                                                <span class="donation-item-qty">10 Dus Masker Medis</span>
                                                <span class="badge-category-mini">Kesehatan</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-pill-dot status-jalan">
                                                <span class="dot"></span>
                                                DI JALAN
                                            </span>
                                        </td>
                                        <td>
                                            <span class="waktu-cell-text">15 Menit Lalu</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="donatur-profile-cell">
                                                <div class="initials-avatar-circle green">YK</div>
                                                <div class="donatur-name-block">
                                                    <span class="donatur-fullname">Yayasan Kasih</span>
                                                    <span class="donatur-role-type">Foundation</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="item-category-cell">
                                                <span class="donation-item-qty">200 Pcs Selimut</span>
                                                <span class="badge-category-mini">Sandang</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-pill-dot status-terima">
                                                <span class="dot"></span>
                                                TERIMA
                                            </span>
                                        </td>
                                        <td>
                                            <span class="waktu-cell-text">45 Menit Lalu</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

<!-- Right Column -->
            <div>
                <!-- Active Post visual panel card -->
                @if($topShelter)
                @php
                    $capPct = $topShelter->max_capacity > 0
                        ? round(($topShelter->current_occupants / $topShelter->max_capacity) * 100)
                        : 0;
                    $capColor = $capPct >= 90 ? 'var(--color-accent-red)' : ($capPct >= 70 ? 'var(--color-accent-orange)' : 'var(--color-accent-green)');
                @endphp
                <div class="active-post-card">
                    <div class="active-post-image-header" 
                         style="background-image: url('https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=600&auto=format&fit=crop');">
                        <div class="active-post-gradient-overlay"></div>
                        <div class="active-post-image-content">
                            <span class="active-post-lbl">{{ strtoupper($topShelter->status) }}</span>
                            <h3 class="active-post-name">{{ $topShelter->shelter_name }}</h3>
                        </div>
                    </div>
                    <div class="active-post-details-body">
                        <div class="active-post-address-row">
                            <span class="active-post-address">
                                <i data-lucide="map-pin" style="width: 16px; height: 16px; color: var(--color-text-muted);"></i>
                                {{ $topShelter->address }}
                            </span>
                            <span class="active-post-capacity" style="color: {{ $capColor }};">{{ $capPct }}% Kapasitas</span>
                        </div>
                        <div class="active-post-capacity-bar-container">
                            <div class="active-post-capacity-bar-fill" style="width: {{ $capPct }}%; background-color: {{ $capColor }};"></div>
                        </div>
                        <div style="font-size: 12px; color: var(--color-text-muted); font-weight: 600; margin: 8px 0;">
                            {{ number_format($topShelter->current_occupants) }} / {{ number_format($topShelter->max_capacity) }} Jiwa
                        </div>
                        <a href="{{ route('posko') }}" class="btn-lihat-detail-posko">Lihat Detail Posko</a>
                    </div>
                </div>
                @else
                <div class="active-post-card" style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:200px;background:#f5f3f6;border-radius:16px;gap:12px;">
                    <i data-lucide="map-pin-off" style="width:36px;height:36px;color:var(--color-text-muted);"></i>
                    <p style="font-size:13px;color:var(--color-text-muted);font-weight:600;margin:0;">Tidak ada posko aktif saat ini</p>
                    <a href="{{ route('posko') }}" class="btn-lihat-detail-posko">Lihat Semua Posko</a>
                </div>
                @endif
            </div>
        </div>
@endsection

@section('dashboard-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ─── Donation Status Filter ────────────────────────────────────────
        const filterToggleBtn  = document.getElementById('filterToggleBtn');
        const filterDropdownMenu = document.getElementById('filterDropdownMenu');
        const filterLabel      = document.getElementById('filterLabel');
        const filterOpts       = document.querySelectorAll('.filter-opt');
        const tableBody        = document.querySelector('.donations-table tbody');

        if (!filterToggleBtn || !filterDropdownMenu || !tableBody) return;

        // Toggle dropdown open/close
        filterToggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = filterDropdownMenu.style.display === 'block';
            filterDropdownMenu.style.display = isOpen ? 'none' : 'block';
        });

        // Close on outside click
        document.addEventListener('click', function () {
            filterDropdownMenu.style.display = 'none';
        });
        filterDropdownMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        // Filter rows
        filterOpts.forEach(function (opt) {
            opt.addEventListener('click', function () {
                const filter = opt.getAttribute('data-filter');
                const label  = opt.querySelector('span:first-child').textContent;

                // Highlight active option
                filterOpts.forEach(o => {
                    o.style.background = '';
                    o.style.color = '';
                });
                opt.style.background = 'rgba(0,106,96,0.08)';
                opt.style.color = 'var(--color-brand-teal)';

                filterLabel.textContent = label;
                filterDropdownMenu.style.display = 'none';

                const rows = tableBody.querySelectorAll('tr[data-status]');
                let visibleCount = 0;

                rows.forEach(function (row) {
                    const rowStatus = row.getAttribute('data-status');
                    const show = filter === 'all'
                        || rowStatus === filter
                        || (filter === 'accepted' && (rowStatus === 'accepted' || rowStatus === 'delivered'));

                    row.style.display = show ? '' : 'none';
                    if (show) visibleCount++;
                });

                // Empty state row
                let emptyRow = tableBody.querySelector('.filter-empty-row');
                if (visibleCount === 0) {
                    if (!emptyRow) {
                        emptyRow = document.createElement('tr');
                        emptyRow.className = 'filter-empty-row';
                        emptyRow.innerHTML = '<td colspan="4" style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;font-weight:600;">Tidak ada donasi dengan status ini</td>';
                        tableBody.appendChild(emptyRow);
                    }
                    emptyRow.style.display = '';
                } else if (emptyRow) {
                    emptyRow.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
