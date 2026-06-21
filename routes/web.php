<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegisterStep1'])->name('register.step1');
    Route::get('/register/warga', [AuthController::class, 'showRegisterStep2Warga'])->name('register.step2.warga');
    Route::post('/register/warga', [AuthController::class, 'registerWarga'])->name('register.step2.warga.submit');
});

use App\Http\Controllers\WargaController;

// Auth Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'Warga') {
            return redirect()->route('warga.dashboard');
        }
        // Fallback for other roles (which will be added in future phases)
        return view('dashboard');
    })->name('dashboard');

    // Warga Portal Routes
    Route::middleware('role:Warga')->group(function () {
        Route::get('/warga/dashboard', [WargaController::class, 'dashboard'])->name('warga.dashboard');
        Route::get('/warga/lapor', [WargaController::class, 'showLapor'])->name('warga.lapor');
        Route::post('/warga/lapor', [WargaController::class, 'submitLapor'])->name('warga.lapor.submit');
        Route::post('/warga/sos', [WargaController::class, 'submitSos'])->name('warga.sos.submit');
    });
});
