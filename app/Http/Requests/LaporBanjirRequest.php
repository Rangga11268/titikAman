<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporBanjirRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'Warga';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'water_height_cm' => ['required', 'integer', 'min:1'],
            'street_name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'photo_evidence' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'], // Max 5MB
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'water_height_cm.required' => 'Tinggi air wajib diisi.',
            'water_height_cm.integer' => 'Tinggi air harus berupa angka bulat.',
            'water_height_cm.min' => 'Tinggi air minimal 1 cm.',
            'street_name.required' => 'Nama jalan/lokasi wajib diisi.',
            'latitude.required' => 'Koordinat lintang (latitude) wajib diisi.',
            'latitude.between' => 'Koordinat lintang tidak valid.',
            'longitude.required' => 'Koordinat bujur (longitude) wajib diisi.',
            'longitude.between' => 'Koordinat bujur tidak valid.',
            'photo_evidence.required' => 'Foto bukti genangan wajib diunggah.',
            'photo_evidence.image' => 'File harus berupa gambar.',
            'photo_evidence.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'photo_evidence.max' => 'Ukuran gambar maksimal 5 MB.',
        ];
    }
}
