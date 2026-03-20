<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Models\Farm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ConversationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        if ($user->isFarmer()) {
            $conversations = Conversation::with(['buyer', 'farm', 'lastMessage'])
                ->whereHas('farm', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->latest('created_at')
                ->get();
        } else {
            $conversations = Conversation::with(['farm.user', 'lastMessage'])
                ->where('buyer_id', $user->id)
                ->latest('created_at')
                ->get();
        }

        return ConversationResource::collection($conversations);
    }

    public function show(Request $request, Conversation $conversation): ConversationResource
    {
        $this->authorizeConversation($request->user(), $conversation);

        $conversation->load(['farm.user', 'buyer', 'messages.sender']);

        $conversation->messages()
            ->where('sender_id', '!=', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return new ConversationResource($conversation);
    }

    public function findOrCreate(Request $request): JsonResponse
    {
        $request->validate([
            'farm_id' => ['required', 'exists:farms,id'],
        ]);

        $farm = Farm::findOrFail($request->farm_id);

        if ($farm->user_id === $request->user()->id) {
            return response()->json([
                'message' => 'Vous ne pouvez pas contacter votre propre exploitation.',
            ], 422);
        }

        $conversation = Conversation::firstOrCreate([
            'farm_id' => $request->farm_id,
            'buyer_id' => $request->user()->id,
        ]);

        $conversation->load(['farm.user', 'buyer', 'messages.sender']);

        return response()->json([
            'conversation' => new ConversationResource($conversation),
        ], $conversation->wasRecentlyCreated ? 201 : 200);
    }

    private function authorizeConversation($user, Conversation $conversation): void
    {
        $isBuyer = $conversation->buyer_id === $user->id;
        $isFarmOwner = $conversation->farm->user_id === $user->id;

        if (!$isBuyer && !$isFarmOwner) {
            abort(403, 'Accès non autorisé à cette conversation.');
        }
    }
}