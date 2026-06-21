<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SosRequestSubmit extends FormRequest
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
            'people_trapped' => ['required', 'integer', 'min:1'],
            'vulnerable_groups_count' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'people_trapped.required' => 'Jumlah orang terjebak wajib diisi.',
            'people_trapped.integer' => 'Jumlah orang harus berupa angka bulat.',
            'people_trapped.min' => 'Minimal 1 orang terjebak.',
            'vulnerable_groups_count.required' => 'Jumlah kelompok rentan wajib diisi (isi 0 jika tidak ada).',
            'vulnerable_groups_count.integer' => 'Jumlah kelompok rentan harus berupa angka bulat.',
            'vulnerable_groups_count.min' => 'Jumlah kelompok rentan tidak boleh kurang dari 0.',
            'latitude.required' => 'Koordinat lokasi (latitude) wajib diperoleh.',
            'longitude.required' => 'Koordinat lokasi (longitude) wajib diperoleh.',
        ];
    }
}
