<?php

namespace App\Http\Requests\Farm;

use Illuminate\Foundation\Http\FormRequest;

class StoreFarmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isFarmer();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'department' => ['required', 'string', 'max:100'],
            'photo_url' => ['nullable', 'string', 'url'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de l\'exploitation est obligatoire.',
            'latitude.required' => 'La latitude est obligatoire.',
            'latitude.between' => 'La latitude doit être comprise entre -90 et 90.',
            'longitude.required' => 'La longitude est obligatoire.',
            'longitude.between' => 'La longitude doit être comprise entre -180 et 180.',
            'city.required' => 'La ville est obligatoire.',
            'department.required' => 'Le département est obligatoire.',
        ];
    }
}