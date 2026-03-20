<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'price_per_unit' => $this->price_per_unit,
            'available_from' => $this->available_from->toDateString(),
            'status' => $this->status,
            'photo_url' => $this->photo_url,
            'notes' => $this->notes,
            'created_at' => $this->created_at->toDateString(),
            'product' => new ProductResource($this->whenLoaded('product')),
            'farm' => new FarmResource($this->whenLoaded('farm')),
        ];
    }
}