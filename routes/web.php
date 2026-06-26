<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RelawanController;
use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\DonasiController;
use Illuminate\Support\Facades\Route;

// Show landing page
Route::get('/', function () {
    return view('welcome');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegisterStep1'])->name('register.step1');
    Route::get('/register/warga', [AuthController::class, 'showRegisterStep2Warga'])->name('register.step2.warga');
    Route::post('/register/warga', [AuthController::class, 'registerWarga'])->name('register.step2.warga.submit');
    
    // Relawan Registration
    Route::get('/register/relawan', [AuthController::class, 'showRegisterStep2Relawan'])->name('register.step2.relawan');
    Route::post('/register/relawan', [AuthController::class, 'registerRelawan'])->name('register.step2.relawan.submit');
    
    // Admin Registration
    Route::get('/register/admin', [AuthController::class, 'showRegisterStep2Admin'])->name('register.step2.admin');
    Route::post('/register/admin', [AuthController::class, 'registerAdmin'])->name('register.step2.admin.submit');
    
    // Pengelola Registration
    Route::get('/register/pengelola', [AuthController::class, 'showRegisterStep2Pengelola'])->name('register.step2.pengelola');
    Route::post('/register/pengelola', [AuthController::class, 'registerPengelola'])->name('register.step2.pengelola.submit');
});

use App\Http\Controllers\WargaController;
use App\Http\Controllers\SharedController;

// Auth Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Utama — all roles
    Route::get('/dashboard', [SharedController::class, 'dashboard'])->name('dashboard');
    Route::get('/warga/dashboard', [WargaController::class, 'dashboard'])->name('warga.dashboard');

    // Shared All-Role Pages
    Route::get('/peta-evakuasi', [SharedController::class, 'petaEvakuasi'])->name('peta.evakuasi');
    Route::get('/data-pintu-air', [SharedController::class, 'dataPintuAir'])->name('pintu.air');
    Route::get('/data-pintu-air/export', [SharedController::class, 'exportWaterGates'])->name('watergate.export');
    Route::get('/laporan/export', [SharedController::class, 'exportLaporan'])->name('laporan.export');
    Route::get('/donasi/export', [DonasiController::class, 'exportDonations'])->name('donasi.export');
    Route::get('/posko', [SharedController::class, 'posko'])->name('posko');
    
    // Donation Hub Routes (Shared to All Roles)
    Route::get('/donasi', [DonasiController::class, 'index'])->name('donasi.hub');
    Route::post('/donasi', [DonasiController::class, 'submitDonation'])->name('donasi.submit');

    // Warga Portal Routes
    Route::middleware('role:Warga')->group(function () {
        Route::get('/warga/lapor', [WargaController::class, 'showLapor'])->name('warga.lapor');
        Route::post('/warga/lapor', [WargaController::class, 'submitLapor'])->name('warga.lapor.submit');
        Route::get('/warga/sos', [WargaController::class, 'showSos'])->name('warga.sos');
        Route::post('/warga/sos', [WargaController::class, 'submitSos'])->name('warga.sos.submit');
    });

    // Relawan Portal Routes
    Route::middleware('role:Relawan')->group(function () {
        Route::get('/relawan/dashboard', [RelawanController::class, 'dashboard'])->name('relawan.dashboard');
        Route::get('/relawan/sos-data', [RelawanController::class, 'getWaitingSosData'])->name('relawan.sos.data');
        Route::post('/relawan/mission/accept', [RelawanController::class, 'acceptMission'])->name('relawan.mission.accept');
        Route::post('/relawan/mission/complete/{id}', [RelawanController::class, 'completeMission'])->name('relawan.mission.complete');
    });

    // Pengelola Posko Portal Routes
    Route::middleware('role:Pengelola_Posko')->group(function () {
        Route::get('/pengelola/dashboard', [PengelolaController::class, 'dashboard'])->name('pengelola.dashboard');
        Route::post('/pengelola/select-shelter', [PengelolaController::class, 'selectShelter'])->name('pengelola.select-shelter');
        Route::post('/pengelola/shelter/update', [PengelolaController::class, 'updateShelter'])->name('pengelola.shelter.update');
        Route::post('/pengelola/need/add', [PengelolaController::class, 'addNeed'])->name('pengelola.need.add');
        Route::post('/pengelola/donation/verify/{id}', [PengelolaController::class, 'updateDonationStatus'])->name('pengelola.donation.verify');
    });

    // Admin BPBD Portal Routes
    Route::middleware('role:Admin_BPBD')->group(function () {
        Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/admin/report/{id}/verify', [\App\Http\Controllers\AdminController::class, 'verifyReport'])->name('admin.report.verify');
        Route::post('/admin/report/{id}/reject', [\App\Http\Controllers\AdminController::class, 'rejectReport'])->name('admin.report.reject');
        Route::get('/admin/tma', [\App\Http\Controllers\AdminController::class, 'tma'])->name('admin.tma');
        Route::post('/admin/tma/{id}/update', [\App\Http\Controllers\AdminController::class, 'updateTma'])->name('admin.tma.update');
        Route::get('/admin/report/export', [\App\Http\Controllers\AdminController::class, 'exportReports'])->name('admin.report.export');
        
        // User Verifications
        Route::get('/admin/verifikasi-pengguna', [\App\Http\Controllers\AdminController::class, 'userVerification'])->name('admin.user-verification');
        Route::post('/admin/user/{id}/approve', [\App\Http\Controllers\AdminController::class, 'approveUser'])->name('admin.user.approve');
        Route::post('/admin/user/{id}/reject', [\App\Http\Controllers\AdminController::class, 'rejectUser'])->name('admin.user.reject');
    });
});
