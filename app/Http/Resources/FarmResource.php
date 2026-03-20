<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => $this->address,
            'city' => $this->city,
            'department' => $this->department,
            'photo_url' => $this->photo_url,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toDateString(),
            'owner' => new UserResource($this->whenLoaded('user')),
            'stocks' => StockResource::collection($this->whenLoaded('stocks')),
            'available_stocks_count' => $this->whenLoaded(
                'availableStocks',
                fn() => $this->availableStocks->count()
            ),
        ];
    }
}