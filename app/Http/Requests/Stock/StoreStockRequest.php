<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isFarmer();
    }

    public function rules(): array
    {
        return [
            'farm_id' => ['required', 'exists:farms,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'string', 'max:50'],
            'price_per_unit' => ['required', 'numeric', 'min:1'],
            'available_from' => ['required', 'date', 'after_or_equal:today'],
            'photo_url' => ['nullable', 'string', 'url'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'farm_id.required' => 'L\'exploitation est obligatoire.',
            'farm_id.exists' => 'Cette exploitation n\'existe pas.',
            'product_id.required' => 'Le produit est obligatoire.',
            'product_id.exists' => 'Ce produit n\'existe pas.',
            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.min' => 'La quantité doit être supérieure à 0.',
            'price_per_unit.required' => 'Le prix unitaire est obligatoire.',
            'price_per_unit.min' => 'Le prix doit être supérieur à 0.',
            'available_from.required' => 'La date de disponibilité est obligatoire.',
            'available_from.after_or_equal' => 'La date de disponibilité ne peut pas être dans le passé.',
        ];
    }
}