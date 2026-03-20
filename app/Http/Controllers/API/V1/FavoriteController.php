<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FarmResource;
use App\Models\Farm;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FavoriteController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $farms = Farm::with(['availableStocks.product'])
            ->whereHas('favoritedBy', function ($q) use ($request) {
                $q->where('buyer_id', $request->user()->id);
            })
            ->where('is_active', true)
            ->get();

        return FarmResource::collection($farms);
    }

    public function store(Request $request, Farm $farm): JsonResponse
    {
        if (!$request->user()->isBuyer()) {
            return response()->json([
                'message' => 'Seuls les acheteurs peuvent ajouter des favoris.',
            ], 403);
        }

        Favorite::firstOrCreate([
            'buyer_id' => $request->user()->id,
            'farm_id' => $farm->id,
        ]);

        return response()->json([
            'message' => 'Exploitation ajoutée aux favoris.',
        ], 201);
    }

    public function destroy(Request $request, Farm $farm): JsonResponse
    {
        Favorite::where('buyer_id', $request->user()->id)
            ->where('farm_id', $farm->id)
            ->delete();

        return response()->json([
            'message' => 'Exploitation retirée des favoris.',
        ]);
    }
}