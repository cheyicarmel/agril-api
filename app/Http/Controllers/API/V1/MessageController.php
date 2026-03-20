<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation): JsonResponse
    {
        $user = $request->user();

        $isBuyer = $conversation->buyer_id === $user->id;
        $isFarmOwner = $conversation->farm->user_id === $user->id;

        if (!$isBuyer && !$isFarmOwner) {
            return response()->json([
                'message' => 'Accès non autorisé à cette conversation.',
            ], 403);
        }

        $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'content' => $request->content,
            'is_read' => false,
            'created_at' => now(),
        ]);

        $message->load('sender');

        return response()->json([
            'message' => 'Message envoyé.',
            'data' => new MessageResource($message),
        ], 201);
    }
}