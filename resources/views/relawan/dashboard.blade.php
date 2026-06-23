@extends('layouts.app')

@section('title', 'Dashboard Relawan - TitikAman')

@section('styles')
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
/* =========================================================
   RELAWAN DASHBOARD — Pixel-faithful Figma implementation
   ========================================================= */



/* =========================================================
   Main Content Canvas
   ========================================================= */
.relawan-main-canvas {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    background: #f1f3f5;
    overflow-y: auto;
    min-height: 100vh;
}

/* Top App Bar */
.relawan-topbar {
    background: #f8f9fa;
    border-bottom: 1px solid #c4c6cf;
    padding: 16px 32px 17px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 5;
}

.topbar-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.topbar-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 24px;
    font-weight: 700;
    color: #031f41;
}

.topbar-sos-badge {
    background: #ffdad6;
    border-radius: 9999px;
    padding: 4px 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 700;
    color: #93000a;
}

.topbar-sos-badge i {
    width: 15px;
    height: 15px;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 24px;
}

.topbar-search {
    background: #f3f4f5;
    border: 1px solid #c4c6cf;
    border-radius: 9999px;
    padding: 10px 17px 11px 41px;
    width: 256px;
    font-size: 14px;
    color: #6b7280;
    outline: none;
    position: relative;
}

.topbar-search-wrap {
    position: relative;
}

.topbar-search-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 15px;
    height: 15px;
    color: #6b7280;
    pointer-events: none;
}

.topbar-icon-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
    border-radius: 9999px;
    color: #031f41;
    cursor: pointer;
    transition: background 0.2s;
}

.topbar-icon-btn:hover {
    background: rgba(0,0,0,0.05);
}

.topbar-icon-btn i {
    width: 20px;
    height: 20px;
}

/* =========================================================
   Content Area
   ========================================================= */
.relawan-content-area {
    padding: 32px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* =========================================================
   Row 1: Stat Cards (5 cards)
   ========================================================= */
.stat-cards-row {
    display: flex;
    gap: 16px;
    align-items: stretch;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 16px 16px 16px 20px;
    flex: 1;
    box-shadow: 0 1px 1px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    gap: 4px;
    border-left: 4px solid transparent;
}

.stat-card.danger  { border-left-color: #ba1a1a; }
.stat-card.warning { border-left-color: #f59e0b; }
.stat-card.success { border-left-color: #006a60; }
.stat-card.navy    { border-left-color: #031f41; }

.stat-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
}

.stat-card-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    line-height: 16px;
}

.stat-card-label.danger  { color: #ba1a1a; }
.stat-card-label.warning { color: #d97706; }
.stat-card-label.success { color: #006a60; }
.stat-card-label.navy    { color: #031f41; }

.stat-card-icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
    color: inherit;
    opacity: 0.7;
}

.stat-card-value {
    font-size: 30px;
    font-weight: 900;
    color: #031f41;
    line-height: 36px;
}

.stat-card-sub {
    font-size: 10px;
    color: #44474e;
    font-style: italic;
    line-height: 15px;
}

/* =========================================================
   Row 2: 3-column layout
   ========================================================= */
.main-grid {
    display: grid;
    grid-template-columns: 35% 40% 25%;
    gap: 24px;
    align-items: start;
}

/* ---- Column 1: SOS Antrian ---- */
.sos-queue-panel {
    background: white;
    border: 1px solid #c4c6cf;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 1px rgba(0,0,0,0.05);
    max-height: 600px;
    display: flex;
    flex-direction: column;
}

.panel-header {
    background: #f3f4f5;
    border-bottom: 1px solid #c4c6cf;
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}

.panel-header-left {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 700;
    color: #031f41;
}

.panel-header-left i {
    width: 15px;
    height: 15px;
}

.panel-count-badge {
    background: #031f41;
    color: white;
    font-size: 10px;
    font-weight: 700;
    border-radius: 9999px;
    padding: 2px 8px;
    line-height: 15px;
}

.panel-body {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* SOS Item */
.sos-item {
    border: 1px solid #c4c6cf;
    border-radius: 8px;
    padding: 17px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    transition: all 0.2s;
}

.sos-item.high {
    background: rgba(255,218,214,0.2);
    border-color: rgba(186,26,26,0.3);
}

.sos-item.medium {
    background: white;
}

.sos-item.low {
    background: white;
}

.sos-item-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 4px;
}

.sos-priority-badge {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    line-height: 15px;
}

.sos-priority-badge.high   { background: #ba1a1a; }
.sos-priority-badge.medium { background: #f59e0b; }
.sos-priority-badge.low    { background: #006a60; }

.sos-time {
    font-size: 11px;
    color: #44474e;
    line-height: 16.5px;
}

.sos-location {
    font-size: 14px;
    font-weight: 700;
    color: #031f41;
    line-height: 20px;
    padding-top: 4px;
}

.sos-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    padding: 4px 0 8px;
}

.sos-tag {
    display: flex;
    align-items: center;
    gap: 4px;
    border: 1px solid #74777f;
    border-radius: 6px;
    padding: 5px 9px;
    font-size: 11px;
    color: #191c1d;
    background: white;
    line-height: 16.5px;
}

.sos-tag.danger {
    font-weight: 700;
    color: #ba1a1a;
    border-color: rgba(186,26,26,0.3);
}

.sos-tag i {
    width: 12px;
    height: 12px;
}

/* Buttons */
.btn-accept-mission {
    width: 100%;
    background: #ba1a1a;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-accept-mission:hover {
    background: #961414;
}

.btn-tinjau {
    width: 100%;
    background: white;
    color: #031f41;
    border: 1px solid #031f41;
    border-radius: 8px;
    padding: 9px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-tinjau:hover {
    background: #031f41;
    color: white;
}

/* ---- Column 2: Peta Operasional ---- */
.map-panel {
    background: white;
    border: 1px solid #c4c6cf;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    height: 600px;
    display: flex;
    flex-direction: column;
}

.map-panel-header {
    background: #f8f9fa;
    border-bottom: 1px solid #c4c6cf;
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}

.map-panel-header-left {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 700;
    color: #031f41;
}

.map-panel-header-left i {
    width: 17px;
    height: 17px;
}

.map-panel-actions {
    display: flex;
    gap: 8px;
}

.map-icon-btn {
    background: #e7e8e9;
    border-radius: 4px;
    padding: 4px 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: none;
    transition: background 0.2s;
}

.map-icon-btn:hover { background: #d4d5d7; }

.map-icon-btn i {
    width: 14px;
    height: 14px;
}

#volunteer-map {
    height: 100%;
    width: 100%;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
}

.map-legend {
    position: absolute;
    bottom: 16px;
    left: 16px;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(2px);
    border: 1px solid #c4c6cf;
    border-radius: 8px;
    padding: 13px;
    display: flex;
    flex-direction: column;
    gap: 7.5px;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
    z-index: 500;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 10px;
    color: #191c1d;
    line-height: 15px;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
}

.map-wrapper {
    flex: 1;
    position: relative;
}

/* ---- Column 3: Right Panel ---- */
.right-panel-col {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Active Mission Card */
.active-mission-card {
    background: rgba(140,245,228,0.2);
    border: 1px solid rgba(0,106,96,0.3);
    border-radius: 12px;
    padding: 21px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.active-mission-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
}

.active-mission-title {
    font-size: 14px;
    font-weight: 700;
    color: #007166;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    line-height: 20px;
}

.status-badge-diproses {
    background: #006a60;
    color: white;
    font-size: 9px;
    font-weight: 700;
    border-radius: 9999px;
    padding: 2px 8px;
    line-height: 13.5px;
    text-transform: uppercase;
}

.mission-info-block {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.mission-info-label {
    font-size: 12px;
    font-weight: 600;
    color: #007166;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    opacity: 0.7;
    line-height: 16px;
}

.mission-info-value {
    font-size: 14px;
    font-weight: 700;
    color: #031f41;
    line-height: 20px;
}

.mission-reporter-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.btn-phone-call {
    background: #006a60;
    border: none;
    border-radius: 9999px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s;
    flex-shrink: 0;
}

.btn-phone-call:hover { background: #005049; }

.btn-phone-call i {
    width: 14px;
    height: 14px;
    color: white;
}

.mission-action-btns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding-top: 8px;
}

.btn-maps {
    background: white;
    border: 1px solid #c4c6cf;
    border-radius: 8px;
    padding: 9px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 9px;
    font-weight: 700;
    color: #031f41;
    text-transform: uppercase;
    text-decoration: none;
}

.btn-maps:hover { background: #f3f4f5; }

.btn-maps i { width: 15px; height: 15px; }

.btn-selesai {
    background: #006a60;
    border: none;
    border-radius: 8px;
    padding: 9px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    cursor: pointer;
    transition: background 0.2s;
    font-size: 9px;
    font-weight: 700;
    color: white;
    text-transform: uppercase;
}

.btn-selesai:hover { background: #005049; }

.btn-selesai i { width: 13px; height: 13px; }

/* Idle state card */
.idle-mission-card {
    background: rgba(140,245,228,0.1);
    border: 1px dashed rgba(0,106,96,0.3);
    border-radius: 12px;
    padding: 21px;
    text-align: center;
    color: #006a60;
    font-size: 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.idle-mission-card i {
    width: 32px;
    height: 32px;
    opacity: 0.6;
}

/* Daily Stats Card */
.daily-stats-card {
    background: white;
    border: 1px solid #c4c6cf;
    border-radius: 12px;
    padding: 21px;
    box-shadow: 0 1px 1px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.daily-stats-title {
    font-size: 14px;
    font-weight: 700;
    color: #031f41;
    line-height: 20px;
}

.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.stat-box {
    background: #f3f4f5;
    border-radius: 8px;
    padding: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.stat-box-value {
    font-size: 20px;
    font-weight: 900;
    color: #031f41;
    text-align: center;
    line-height: 28px;
}

.stat-box-label {
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    color: #44474e;
    text-align: center;
    line-height: 13.5px;
}

/* Activity Timeline */
.activity-section-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 10px;
    font-weight: 700;
    color: #44474e;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding-top: 8px;
    border-top: 1px solid rgba(196,198,207,0.3);
}

.activity-section-label i {
    width: 10px;
    height: 10px;
}

.activity-timeline {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding-left: 4px;
    border-left: 1px solid #c4c6cf;
    margin-left: 7px;
    position: relative;
}

.activity-item {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding-left: 12px;
    position: relative;
}

.activity-dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    flex-shrink: 0;
    position: absolute;
    left: -20px;
    top: 4px;
    box-shadow: 0 0 0 4px white;
}

.activity-dot.teal { background: #006a60; }
.activity-dot.navy { background: #031f41; }

.activity-text-wrap {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.activity-title {
    font-size: 11px;
    font-weight: 700;
    color: #031f41;
    line-height: 16.5px;
}

.activity-meta {
    font-size: 9px;
    color: #44474e;
    line-height: 13.5px;
}

/* =========================================================
   Row 3: Riwayat Misi Table (Full Width)
   ========================================================= */
.history-panel {
    background: white;
    border: 1px solid #c4c6cf;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.history-panel-header {
    background: #f3f4f5;
    border-bottom: 1px solid #c4c6cf;
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.history-panel-header-left {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 700;
    color: #031f41;
}

.history-panel-header-left i {
    width: 15px;
    height: 16.67px;
}

.history-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.history-table thead th {
    background: #f8f9fa;
    border-bottom: 1px solid #c4c6cf;
    padding: 15.5px 16px 16.5px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #44474e;
    text-transform: uppercase;
    letter-spacing: 0.55px;
    white-space: nowrap;
}

.history-table tbody tr {
    border-bottom: 1px solid #c4c6cf;
    transition: background 0.15s;
}

.history-table tbody tr:nth-child(even) {
    background: white;
}

.history-table tbody tr:hover {
    background: #f8f9fa;
}

.history-table tbody td {
    padding: 16.5px 16px 17.5px;
    line-height: 20px;
    vertical-align: middle;
}

.td-time {
    font-weight: 500;
    color: #191c1d;
    white-space: nowrap;
}

.td-location {
    font-weight: 700;
    color: #031f41;
    white-space: nowrap;
}

.td-regular {
    color: #191c1d;
    white-space: nowrap;
}

.td-duration {
    color: #44474e;
    white-space: nowrap;
}

.badge-terkonsepsi {
    background: rgba(0,106,96,0.1);
    color: #006a60;
    font-size: 10px;
    font-weight: 700;
    border-radius: 9999px;
    padding: 0 8px;
    line-height: 20px;
    white-space: nowrap;
}

.empty-history-row td {
    padding: 32px 16px;
    text-align: center;
    color: #6b7280;
    font-size: 14px;
}

/* =========================================================
   Volunteer Marker
   ========================================================= */
.volunteer-marker {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #031f41;
    border: 2px solid white;
    box-shadow: 0 0 0 4px rgba(3,31,65,0.3);
}

@keyframes pulse-ring {
    0%   { transform: scale(1); opacity: 1; }
    100% { transform: scale(1.4); opacity: 0; }
}

.victim-pulse {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #ba1a1a;
    border: 2px solid white;
    box-shadow: 0 0 0 4px rgba(186,26,26,0.4);
    animation: pulse-ring 2s infinite;
}

/* Mobile sidebar toggle */
.mobile-sidebar-toggle {
    display: none;
    position: fixed;
    top: 16px;
    left: 16px;
    z-index: 200;
    background: #1d3557;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px;
    cursor: pointer;
}

@media (max-width: 1024px) {
    .dashboard-sidebar {
        position: fixed;
        left: -250px;
        top: 0;
        bottom: 0;
        height: 100vh;
        transition: left 0.3s ease;
        z-index: 1500;
        box-shadow: 10px 0 20px rgba(0,0,0,0.15);
    }
    .dashboard-sidebar.active {
        left: 0;
    }
    .mobile-sidebar-toggle {
        display: flex !important;
        z-index: 2000;
    }
    .main-grid {
        grid-template-columns: 1fr;
    }
    .stat-cards-row {
        flex-wrap: wrap;
    }
}
</style>
@endsection

@section('content')
<div class="dashboard-container">

    {{-- ===================== SIDEBAR ===================== --}}
    @include('partials.sidebar')

    <button class="mobile-sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
        <i data-lucide="menu" id="toggleIcon"></i>
    </button>

    {{-- ===================== MAIN CANVAS ===================== --}}
    <div class="relawan-main-canvas">

        {{-- Top App Bar --}}
        <div class="relawan-topbar">
            <div class="topbar-left">
                <h1 class="topbar-title">Dashboard Relawan</h1>
                <div class="topbar-sos-badge">
                    <i data-lucide="bell-ring"></i>
                    <span>{{ $sosAntriCount }} SOS Aktif</span>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-search-wrap">
                    <i data-lucide="search"></i>
                    <input type="text" class="topbar-search" placeholder="Cari misi atau koordinat...">
                </div>
                <div class="topbar-icon-btn notification-bell" title="Notifikasi" style="position: relative; cursor: pointer;">
                    <i data-lucide="bell"></i>
                    <div class="notification-dot" style="position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background-color: var(--color-accent-red); border-radius: 50%;"></div>
                </div>
                <div class="topbar-icon-btn">
                    <i data-lucide="user-circle"></i>
                </div>
            </div>
        </div>

        {{-- ============== CONTENT AREA ============== --}}
        <div class="relawan-content-area">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="dashboard-toast" style="margin-bottom: 0;">
                    <i data-lucide="check-circle" style="color: #006a60; width: 20px; height: 20px;"></i>
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.style.display='none'" class="toast-close">
                        <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="dashboard-toast" style="margin-bottom: 0; border-left-color: #ba1a1a;">
                    <i data-lucide="alert-octagon" style="color: #ba1a1a; width: 20px; height: 20px;"></i>
                    <span style="color: #b61722;">{{ session('error') }}</span>
                    <button onclick="this.parentElement.style.display='none'" class="toast-close">
                        <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                    </button>
                </div>
            @endif

            {{-- ============ ROW 1: STAT CARDS ============ --}}
            <div class="stat-cards-row">
                <div class="stat-card danger">
                    <div class="stat-card-header">
                        <span class="stat-card-label danger">SOS Antri</span>
                        <i data-lucide="bell" class="stat-card-icon" style="color:#ba1a1a;"></i>
                    </div>
                    <div class="stat-card-value">{{ str_pad($sosAntriCount, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="stat-card-sub">Permintaan menunggu respons</div>
                </div>
                <div class="stat-card danger">
                    <div class="stat-card-header">
                        <span class="stat-card-label danger">Prioritas Tinggi</span>
                        <i data-lucide="alert-triangle" class="stat-card-icon" style="color:#ba1a1a;"></i>
                    </div>
                    <div class="stat-card-value">{{ str_pad($highPrioritySos, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="stat-card-sub">Butuh evakuasi segera</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-card-header">
                        <span class="stat-card-label warning">Misi Aktifku</span>
                        <i data-lucide="user-check" class="stat-card-icon" style="color:#d97706;"></i>
                    </div>
                    <div class="stat-card-value">{{ $misiAktifku }}</div>
                    <div class="stat-card-sub">{{ $activeMission ? $activeMission->sosRequest->user->kelurahan ?? 'Sedang berjalan' : 'Tidak ada misi aktif' }}</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-card-header">
                        <span class="stat-card-label success">Misi Selesai</span>
                        <i data-lucide="check-circle-2" class="stat-card-icon" style="color:#006a60;"></i>
                    </div>
                    <div class="stat-card-value">{{ str_pad($misiSelesaiCount, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="stat-card-sub">Akumulasi hari ini</div>
                </div>
                <div class="stat-card navy">
                    <div class="stat-card-header">
                        <span class="stat-card-label navy">Relawan Online</span>
                        <i data-lucide="users" class="stat-card-icon" style="color:#031f41;"></i>
                    </div>
                    <div class="stat-card-value">—</div>
                    <div class="stat-card-sub">Sekitar wilayah Bekasi</div>
                </div>
            </div>

            {{-- ============ ROW 2: 3-COLUMN LAYOUT ============ --}}
            <div class="main-grid">

                {{-- Column 1: Antrian SOS --}}
                <div class="sos-queue-panel">
                    <div class="panel-header">
                        <div class="panel-header-left">
                            <i data-lucide="list-ordered"></i>
                            <span>Antrian SOS Terbaru</span>
                        </div>
                        <span class="panel-count-badge">{{ $sosAntriCount }} REQUEST</span>
                    </div>
                    <div class="panel-body" id="sos-queue-container">
                        @forelse ($waitingSos as $sos)
                            @php
                                $priorityLabel = match($sos->priority_level) {
                                    'high'   => 'TINGGI',
                                    'medium' => 'SEDANG',
                                    'low'    => 'RENDAH',
                                    default  => strtoupper($sos->priority_level)
                                };
                            @endphp
                            <div class="sos-item {{ $sos->priority_level }}" data-sos-id="{{ $sos->sos_id }}">
                                <div class="sos-item-header">
                                    <span class="sos-priority-badge {{ $sos->priority_level }}">{{ $priorityLabel }}</span>
                                    <span class="sos-time">{{ $sos->created_at->format('H:i') }} WIB</span>
                                </div>
                                <div class="sos-location">
                                    {{ $sos->user->kelurahan ?? 'Lokasi' }}, {{ $sos->user->kecamatan ?? 'Bekasi' }}
                                </div>
                                <div class="sos-tags">
                                    <span class="sos-tag">
                                        <i data-lucide="users"></i>
                                        {{ $sos->people_trapped }} Orang
                                    </span>
                                    @if($sos->vulnerable_groups_count > 0)
                                        <span class="sos-tag">
                                            <i data-lucide="accessibility"></i>
                                            {{ $sos->vulnerable_groups_count }} Rentan
                                        </span>
                                    @endif
                                    @if($sos->description)
                                        <span class="sos-tag{{ $sos->priority_level === 'high' ? ' danger' : '' }}">
                                            <i data-lucide="alert-circle"></i>
                                            {{ Str::limit($sos->description, 20) }}
                                        </span>
                                    @endif
                                </div>
                                @if(!$activeMission)
                                    <form action="{{ route('relawan.mission.accept') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="sos_id" value="{{ $sos->sos_id }}">
                                        <button type="submit" class="btn-accept-mission">AMBIL MISI INI</button>
                                    </form>
                                @else
                                    <button type="button" class="btn-tinjau" onclick="focusOnSos({{ $sos->latitude }}, {{ $sos->longitude }})">
                                        TINJAU DETAIL
                                    </button>
                                @endif
                            </div>
                        @empty
                            <div style="text-align: center; padding: 32px 16px; color: #6b7280; font-size: 14px; display: flex; flex-direction: column; align-items: center; gap: 12px;">
                                <i data-lucide="smile" style="width: 48px; height: 48px; color: #006a60;"></i>
                                <span>Tidak ada permintaan SOS aktif saat ini. Semua wilayah aman.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Column 2: Peta Operasional --}}
                <div class="map-panel">
                    <div class="map-panel-header">
                        <div class="map-panel-header-left">
                            <i data-lucide="map-pin"></i>
                            <span>Peta Operasional Bekasi</span>
                        </div>
                        <div class="map-panel-actions">
                            <button class="map-icon-btn" title="Refresh peta">
                                <i data-lucide="refresh-cw"></i>
                            </button>
                            <button class="map-icon-btn" title="Fullscreen">
                                <i data-lucide="maximize-2"></i>
                            </button>
                        </div>
                    </div>
                    <div class="map-wrapper">
                        <div id="volunteer-map"></div>
                        <div class="map-legend">
                            <div class="legend-item">
                                <div class="legend-dot" style="background: #ba1a1a;"></div>
                                <span>Prioritas Tinggi</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot" style="background: #f59e0b;"></div>
                                <span>Prioritas Sedang</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot" style="background: #006a60;"></div>
                                <span>Prioritas Rendah</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Column 3: Misi Aktif + Statistik --}}
                <div class="right-panel-col">

                    @if ($activeMission)
                        {{-- Active Mission Card --}}
                        <div class="active-mission-card">
                            <div class="active-mission-header">
                                <div class="active-mission-title">MISI AKTIF SAAT INI</div>
                                <span class="status-badge-diproses">DIPROSES</span>
                            </div>
                            <div>
                                <div class="mission-info-block">
                                    <div class="mission-info-label">LOKASI</div>
                                    <div class="mission-info-value">
                                        {{ $activeMission->sosRequest->user->kelurahan ?? 'Lokasi' }},
                                        {{ $activeMission->sosRequest->user->kecamatan ?? 'Bekasi' }}
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="mission-info-block">
                                    <div class="mission-info-label">PELAPOR</div>
                                    <div class="mission-reporter-row">
                                        <div class="mission-info-value">{{ $activeMission->sosRequest->user->fullname }}</div>
                                        <a href="tel:{{ $activeMission->sosRequest->user->phone }}" class="btn-phone-call">
                                            <i data-lucide="phone"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mission-action-btns">
                                <a href="https://maps.google.com/maps?q={{ $activeMission->sosRequest->latitude }},{{ $activeMission->sosRequest->longitude }}"
                                   target="_blank" class="btn-maps">
                                    <i data-lucide="map"></i>
                                    MAPS
                                </a>
                                <form action="{{ route('relawan.mission.complete', $activeMission->mission_id) }}" method="POST"
                                      onsubmit="return confirm('Konfirmasi: Korban sudah berhasil dievakuasi dengan aman?')">
                                    @csrf
                                    <button type="submit" class="btn-selesai" style="width: 100%;">
                                        <i data-lucide="check-circle-2"></i>
                                        SELESAI
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="idle-mission-card">
                            <i data-lucide="shield-check"></i>
                            <div style="font-weight: 700; color: #006a60;">Siap Bertugas</div>
                            <div style="font-size: 12px; color: #44474e;">Pilih misi SOS dari antrian untuk mulai bertugas</div>
                        </div>
                    @endif

                    {{-- Daily Stats --}}
                    <div class="daily-stats-card">
                        <div class="daily-stats-title">Statistik Hari Ini</div>
                        <div class="stats-grid">
                            <div class="stat-box">
                                <div class="stat-box-value">{{ $misiSelesaiCount }}</div>
                                <div class="stat-box-label">Misi Selesai</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-box-value" style="font-size: 18px;">
                                    {{ $avgResponseMinutes > 0 ? $avgResponseMinutes . 'm' : '—' }}
                                </div>
                                <div class="stat-box-label">Rata Respon</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-box-value">{{ $sosAntriCount + $misiSelesaiCount + $misiAktifku }}</div>
                                <div class="stat-box-label">Total SOS Hari Ini</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-box-value">4.9</div>
                                <div class="stat-box-label">Rating</div>
                            </div>
                        </div>

                        @if($completedMissions->isNotEmpty())
                            <div class="activity-section-label">
                                <i data-lucide="clock"></i>
                                AKTIVITAS TERAKHIR
                            </div>
                            <div class="activity-timeline">
                                @foreach($completedMissions->take(3) as $mission)
                                    <div class="activity-item">
                                        <div class="activity-dot {{ $loop->first ? 'teal' : 'navy' }}"></div>
                                        <div class="activity-text-wrap">
                                            <div class="activity-title">
                                                Misi Selesai: {{ $mission->sosRequest->user->kelurahan ?? 'Lokasi' }}
                                            </div>
                                            <div class="activity-meta">
                                                {{ $mission->resolved_at->format('H:i') }} WIB
                                                • {{ $mission->sosRequest->people_trapped }} Orang terevakuasi
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="text-align: center; padding: 16px 0; color: #6b7280; font-size: 12px;">
                                Belum ada aktivitas hari ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ============ ROW 3: RIWAYAT MISI TABLE ============ --}}
            <div class="history-panel">
                <div class="history-panel-header">
                    <div class="history-panel-header-left">
                        <i data-lucide="clipboard-list"></i>
                        <span>Riwayat Misi Hari Ini</span>
                    </div>
                    <span style="font-size: 11px; font-weight: 700; color: #031f41; cursor: pointer;">LIHAT SEMUA</span>
                </div>
                <div style="overflow-x: auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Waktu Selesai</th>
                                <th>Lokasi</th>
                                <th>Jumlah Orang</th>
                                <th>Kelompok Rentan</th>
                                <th>Durasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($completedMissions as $mission)
                                <tr>
                                    <td class="td-time">{{ $mission->resolved_at->format('H:i') }} WIB</td>
                                    <td class="td-location">
                                        {{ $mission->sosRequest->user->kelurahan ?? 'Lokasi' }},
                                        {{ $mission->sosRequest->user->kecamatan ?? 'Bekasi' }}
                                    </td>
                                    <td class="td-regular">{{ $mission->sosRequest->people_trapped }} Orang</td>
                                    <td class="td-regular">{{ $mission->sosRequest->vulnerable_groups_count }} Orang</td>
                                    <td class="td-duration">
                                        {{ (int) $mission->resolved_at->diffInMinutes($mission->created_at) }} Menit
                                    </td>
                                    <td>
                                        <span class="badge-terkonsepsi">TERKONSEPSI</span>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-history-row">
                                    <td colspan="6">Belum ada misi selesai hari ini. Terus semangat! 💪</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- end .relawan-content-area --}}
    </div>{{-- end .relawan-main-canvas --}}
</div>{{-- end .dashboard-container --}}
@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@php
    // Prepare map data as simple PHP variables to avoid Blade @json() parse errors
    $activeMissionMapData = $activeMission ? [
        'lat'    => (float) $activeMission->sosRequest->latitude,
        'lng'    => (float) $activeMission->sosRequest->longitude,
        'name'   => $activeMission->sosRequest->user->fullname,
        'people' => (int) $activeMission->sosRequest->people_trapped,
    ] : null;

    $sosQueueMapData = $waitingSos->map(function ($s) {
        return [
            'lat'      => (float) $s->latitude,
            'lng'      => (float) $s->longitude,
            'priority' => $s->priority_level,
            'location' => ($s->user->kelurahan ?? '') . ', ' . ($s->user->kecamatan ?? ''),
            'people'   => (int) $s->people_trapped,
        ];
    })->values()->all();
@endphp
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Mobile Sidebar Toggle ----
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.dashboard-sidebar');
    const toggleIcon = document.getElementById('toggleIcon');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('active');
            toggleIcon.setAttribute('data-lucide', sidebar.classList.contains('active') ? 'x' : 'menu');
            lucide.createIcons();
        });
    }

    // ---- Leaflet Map ----
    const DEFAULT_LAT = -6.2383;
    const DEFAULT_LNG = 106.9922;

    const map = L.map('volunteer-map', { zoomControl: false }).setView([DEFAULT_LAT, DEFAULT_LNG], 13);
    L.control.zoom({ position: 'topright' }).addTo(map);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 20
    }).addTo(map);

    // Volunteer marker (navy)
    const volunteerIcon = L.divIcon({
        html: `<div class="volunteer-marker"></div>`,
        className: '',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });
    let volunteerMarker = L.marker([DEFAULT_LAT, DEFAULT_LNG], { icon: volunteerIcon })
        .addTo(map)
        .bindTooltip('Posisi Anda', { permanent: true, direction: 'top', className: 'volunteer-tooltip' });

    // Try geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            volunteerMarker.setLatLng([lat, lng]);
        });
    }

    // Plot active mission victim
    const activeMissionData = @json($activeMissionMapData ?? null);

    if (activeMissionData) {
        const victimIcon = L.divIcon({
            html: `<div class="victim-pulse"></div>`,
            className: '',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });
        L.marker([activeMissionData.lat, activeMissionData.lng], { icon: victimIcon })
            .addTo(map)
            .bindPopup(`<b>KORBAN AKTIF:</b> ${activeMissionData.name}<br>${activeMissionData.people} jiwa terjebak`)
            .openPopup();
        map.setView([activeMissionData.lat, activeMissionData.lng], 14);
    }

    // Plot SOS queue markers
    const sosQueue = @json($sosQueueMapData);

    const priorityColors = { high: '#ba1a1a', medium: '#f59e0b', low: '#006a60' };

    sosQueue.forEach(function (sos) {
        if (!sos.lat || !sos.lng) return;
        const color = priorityColors[sos.priority] || '#031f41';
        const icon = L.divIcon({
            html: `<div style="width:16px;height:16px;border-radius:50%;background:${color};border:2px solid white;box-shadow:0 0 0 3px ${color}44;"></div>`,
            className: '',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });
        L.marker([sos.lat, sos.lng], { icon })
            .addTo(map)
            .bindPopup(`<b>${sos.location}</b><br>${sos.people} jiwa terjebak`);
    });

    // Global helper for "tinjau detail" button
    window.focusOnSos = function(lat, lng) {
        map.setView([lat, lng], 15);
    };

    // ---- SOS Queue Auto-Refresh every 30s ----
    setInterval(function () {
        fetch('{{ route("relawan.sos.data") }}')
            .then(res => res.json())
            .catch(() => {});
    }, 30000);

    lucide.createIcons();
});
</script>
@endsection
