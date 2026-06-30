<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
            'kelurahan' => ['required', 'string', 'max:100'],
            'terms' => ['required', 'accepted'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'fullname.max' => 'Nama lengkap tidak boleh lebih dari 100 karakter.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.unique' => 'Nomor HP sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'kecamatan.required' => 'Kecamatan domisili wajib dipilih.',
            'kecamatan.in' => 'Kecamatan domisili harus berada di wilayah Kota Bekasi.',
            'kelurahan.required' => 'Kelurahan domisili wajib dipilih.',
            'terms.required' => 'Anda harus menyetujui Syarat & Ketentuan.',
            'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan.',
        ];
    }
}
