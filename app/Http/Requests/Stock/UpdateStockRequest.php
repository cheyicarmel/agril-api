<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        $stock = $this->route('stock');
        return $this->user()->id === $stock->farm->user_id;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['sometimes', 'numeric', 'min:0.01'],
            'unit' => ['sometimes', 'string', 'max:50'],
            'price_per_unit' => ['sometimes', 'numeric', 'min:1'],
            'available_from' => ['sometimes', 'date'],
            'status' => ['sometimes', 'in:available,reserved,exhausted'],
            'photo_url' => ['nullable', 'string', 'url'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}