<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Tampilkan Halaman Login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses Login (Email atau nomor HP).
     */
    public function login(Request $request)
    {
        $request->validate([
            'login_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login_id.required' => 'Email atau Nomor HP wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Cari user berdasarkan email atau phone
        $user = User::where('email', $request->login_id)
            ->orWhere('phone', $request->login_id)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            if ($user->status !== 'approved') {
                return redirect()->route('verification.status');
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'login_id' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('login_id');
    }

    /**
     * Tampilkan Halaman Pilih Peran (Register Step 1).
     */
    public function showRegisterStep1()
    {
        return view('auth.register-step1');
    }

    /**
     * Tampilkan Form Registrasi Warga (Register Step 2 Warga).
     */
    public function showRegisterStep2Warga()
    {
        return view('auth.register-step2-warga');
    }

    /**
     * Proses Pendaftaran Warga.
     */
    public function registerWarga(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'role' => 'Warga',
            'kecamatan' => $validated['kecamatan'],
            'kelurahan' => $validated['kelurahan'],
            'status' => 'approved',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di TitikAman.');
    }

    /**
     * Tampilkan Form Registrasi Relawan.
     */
    public function showRegisterStep2Relawan()
    {
        return view('auth.register-step2-relawan');
    }

    /**
     * Proses Pendaftaran Relawan.
     */
    public function registerRelawan(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'nik' => 'required|string|size:16|unique:users,nik',
            'keahlian' => 'required|array',
            'organisasi' => 'nullable|string|max:100',
            'kecamatan' => [
                'required',
                'string',
                'max:100',
                Rule::in([
                    'Pondok Gede', 'Jatiasih', 'Bekasi Timur', 'Bekasi Selatan',
                    'Bekasi Barat', 'Bekasi Utara', 'Rawalumbu', 'Mustikajaya',
                    'Bantargebang', 'Medansatria', 'Jatisampurna',
                ]),
            ],
            'kelurahan' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'document' => 'required|file|mimes:jpeg,png,pdf|max:5120',
        ], [
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.unique' => 'Nomor HP sudah terdaftar.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus berukuran 16 karakter.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'keahlian.required' => 'Pilih minimal satu keahlian.',
            'kecamatan.required' => 'Kecamatan domisili wajib dipilih.',
            'kecamatan.in' => 'Kecamatan domisili harus berada di wilayah Kota Bekasi.',
            'kelurahan.required' => 'Kelurahan domisili wajib dipilih.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'document.required' => 'Dokumen verifikasi (KTP) wajib diunggah.',
            'document.mimes' => 'Dokumen harus berupa file JPEG, PNG, atau PDF.',
            'document.max' => 'Ukuran file dokumen maksimal 5MB.',
        ]);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
        }

        User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'Relawan',
            'nik' => $request->nik,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'keahlian' => implode(', ', $request->keahlian),
            'organisasi' => $request->organisasi,
            'document_path' => $documentPath,
            'status' => 'pending',
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran sebagai anggota Relawan/SAR berhasil! Silakan login untuk melihat status verifikasi akun Anda.');
    }



    /**
     * Tampilkan Form Registrasi Pengelola Posko.
     */
    public function showRegisterStep2Pengelola()
    {
        return view('auth.register-step2-pengelola');
    }

    /**
     * Proses Pendaftaran Pengelola Posko.
     */
    public function registerPengelola(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'fullname' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'shelter_name' => 'required|string|max:100',
            'max_capacity' => 'required|integer|min:1',
            'address' => 'required|string',
            'facilities' => 'required|array',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|image|max:5120',
        ], [
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.unique' => 'Nomor HP sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'shelter_name.required' => 'Nama posko wajib diisi.',
            'max_capacity.required' => 'Kapasitas posko wajib diisi.',
            'address.required' => 'Alamat posko wajib diisi.',
            'facilities.required' => 'Pilih minimal satu fasilitas posko.',
            'latitude.required' => 'Pilih lokasi posko pada peta.',
            'longitude.required' => 'Pilih lokasi posko pada peta.',
            'photo.image' => 'Foto posko harus berupa file gambar.',
            'photo.max' => 'Ukuran foto posko maksimal 5MB.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $lat = $request->input('latitude');
            $lng = $request->input('longitude');

            $minLat = -6.350;
            $maxLat = -6.100;
            $minLng = 106.800;
            $maxLng = 107.100;

            if ($lat && $lng) {
                if ($lat < $minLat || $lat > $maxLat || $lng < $minLng || $lng > $maxLng) {
                    $validator->errors()->add('latitude', 'Lokasi posko berada di luar wilayah Kota Bekasi. TitikAman hanya melayani wilayah Kota Bekasi dan sekitarnya.');
                }
            }
        });

        $validator->validate();

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('shelters', 'public');
        }

        // Create the shelter first
        $shelter = \App\Models\Shelter::create([
            'shelter_name' => $request->shelter_name,
            'address' => $request->address,
            'max_capacity' => $request->max_capacity,
            'current_occupants' => 0,
            'has_toilet_facilities' => in_array('Toilet', $request->facilities) ? 'Yes' : 'No',
            'status' => 'active',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'facilities' => $request->facilities,
            'photo' => $photoPath,
        ]);

        // Create the user and link to shelter
        User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'Pengelola_Posko',
            'shelter_id' => $shelter->shelter_id,
            'status' => 'pending',
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun dan Posko Anda sedang menunggu verifikasi oleh Admin BPBD.');
    }

    /**
     * Proses Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
