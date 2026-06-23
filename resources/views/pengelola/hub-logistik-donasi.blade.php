@extends('layouts.app')

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

<div class="dashboard-container">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Toggle Sidebar (Mobile) -->
    <button class="mobile-sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
        <i data-lucide="menu" id="toggleIcon"></i>
    </button>

    <!-- Main Content Area -->
    <div class="donasi-wrapper">
        <!-- Top Navbar Cari Mockup -->
        <div class="top-nav-bar">
            <div class="search-mockup-wrapper">
                <i data-lucide="search" class="search-mockup-icon" style="width: 18px; height: 18px;"></i>
                <input type="text" class="search-mockup-input" placeholder="Cari logistik atau donatur..." disabled>
            </div>
            <div class="nav-actions-right">
                <button class="btn-emergency-alert">
                    <i data-lucide="alert-triangle" style="width: 14px; height: 14px;"></i>
                    <span>Emergency Alert</span>
                </button>
                <div class="notification-bell" title="Notifikasi" style="position: relative; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background-color: #f1f3f5; color: #44474e; margin-right: 8px;">
                    <i data-lucide="bell" style="width: 20px; height: 20px; margin: 0;"></i>
                    <div class="notification-dot" style="position: absolute; top: 8px; right: 8px; width: 8px; height: 8px; background-color: var(--color-accent-red); border-radius: 50%;"></div>
                </div>
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop" 
                     alt="Profile" 
                     style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1.5px solid var(--color-border-muted); cursor: pointer;">
            </div>
        </div>

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
                    <span class="badge-percent-red">-12%</span>
                </div>
            </div>
            <!-- Stat 2: Fulfilled -->
            <div class="stat-card-widget">
                <span class="stat-card-label">FULFILLED</span>
                <div class="stat-card-value-container">
                    <h2 class="stat-card-value fulfilled-val">{{ $fulfilled }}</h2>
                    <div>
                        <div class="stat-mini-progress">
                            <div class="stat-mini-progress-fill"></div>
                        </div>
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

                                        $emoji = '🍱';
                                        $catLower = strtolower($parsed['category']);
                                        if (str_contains($catLower, 'makan')) { $emoji = '🍱'; }
                                        elseif (str_contains($catLower, 'susu') || str_contains($catLower, 'bayi')) { $emoji = '🍼'; }
                                        elseif (str_contains($catLower, 'selimut') || str_contains($catLower, 'perlengkapan')) { $emoji = '🛌'; }
                                        elseif (str_contains($catLower, 'obat') || str_contains($catLower, 'sehat') || str_contains($catLower, 'medis')) { $emoji = '💊'; }
                                        elseif (str_contains($catLower, 'air') || str_contains($catLower, 'minum')) { $emoji = '💧'; }
                                    @endphp
                                    <div class="urgent-mini-card">
                                        <div class="urgent-card-top">
                                            <div class="urgent-card-icon-box">{{ $emoji }}</div>
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
                                    <div class="urgent-card-icon-box">🍱</div>
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
                                    <div class="urgent-card-icon-box">🍼</div>
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
                                    <div class="urgent-card-icon-box">🛌</div>
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
                        <div style="display: flex; gap: 8px;">
                            <button style="background: none; border: 1px solid var(--color-border-muted); border-radius: 8px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                <i data-lucide="filter" style="width: 14px; height: 14px; color: #44474e;"></i>
                            </button>
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
                                        $statusText = 'DI JALAN';
                                        if ($donation->status === 'accepted' || $donation->status === 'delivered') {
                                            $statusClass = 'status-terima';
                                            $statusText = 'TERIMA';
                                        }

                                        // Category Parsing
                                        $itemName = $donation->shelterNeed ? $donation->shelterNeed->item_name : 'Barang Donasi';
                                        $categoryLabel = 'Logistik';
                                        if (str_contains(strtolower($itemName), 'makan')) {
                                            $categoryLabel = 'Logistik';
                                        } elseif (str_contains(strtolower($itemName), 'obat') || str_contains(strtolower($itemName), 'medis')) {
                                            $categoryLabel = 'Kesehatan';
                                        } elseif (str_contains(strtolower($itemName), 'selimut') || str_contains(strtolower($itemName), 'pakaian')) {
                                            $categoryLabel = 'Sandang';
                                        }
                                    @endphp
                                    <tr>
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
                                                <span class="donation-item-qty">{{ $donation->quantity_donated }} Unit / Box</span>
                                                <span class="badge-category-mini">{{ $categoryLabel }}</span>
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
                <div class="active-post-card">
                    <div class="active-post-image-header" 
                         style="background-image: url('https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=600&auto=format&fit=crop');">
                        <div class="active-post-gradient-overlay"></div>
                        <div class="active-post-image-content">
                            <span class="active-post-lbl">ACTIVE POST</span>
                            <h3 class="active-post-name">Posko Bekasi Timur</h3>
                        </div>
                    </div>
                    <div class="active-post-details-body">
                        <div class="active-post-address-row">
                            <span class="active-post-address">
                                <i data-lucide="map-pin" style="width: 16px; height: 16px; color: var(--color-text-muted);"></i>
                                Gedung Juang '45
                            </span>
                            <span class="active-post-capacity">92% Kapasitas</span>
                        </div>
                        <div class="active-post-capacity-bar-container">
                            <div class="active-post-capacity-bar-fill"></div>
                        </div>
                        <a href="{{ route('posko') }}" class="btn-lihat-detail-posko">Lihat Detail Posko</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Init sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const dashboardSidebar = document.querySelector('.dashboard-sidebar');
        const toggleIcon = document.getElementById('toggleIcon');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                dashboardSidebar.classList.toggle('active');
                if (dashboardSidebar.classList.contains('active')) {
                    toggleIcon.setAttribute('data-lucide', 'x');
                } else {
                    toggleIcon.setAttribute('data-lucide', 'menu');
                }
                lucide.createIcons();
            });
        }
    });
</script>
@endsection
