<?php

namespace App\Http\Requests\Farm;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFarmRequest extends FormRequest
{
    public function authorize(): bool
    {
        $farm = $this->route('farm');
        return $this->user()->id === $farm->user_id;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['sometimes', 'numeric', 'between:-90,90'],
            'longitude' => ['sometimes', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['sometimes', 'string', 'max:100'],
            'department' => ['sometimes', 'string', 'max:100'],
            'photo_url' => ['nullable', 'string', 'url'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}