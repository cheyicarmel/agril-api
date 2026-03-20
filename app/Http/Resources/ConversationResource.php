<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->toDateTimeString(),
            'farm' => new FarmResource($this->whenLoaded('farm')),
            'buyer' => new UserResource($this->whenLoaded('buyer')),
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'last_message' => new MessageResource($this->whenLoaded('lastMessage')),
            'unread_count' => $this->whenLoaded(
                'messages',
                fn() => $this->messages->where('is_read', false)->count()
            ),
        ];
    }
}