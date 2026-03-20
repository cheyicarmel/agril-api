<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'is_read' => $this->is_read,
            'created_at' => $this->created_at?->toDateTimeString(),
            'sender' => new UserResource($this->whenLoaded('sender')),
        ];
    }
}