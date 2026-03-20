<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Farm\StoreFarmRequest;
use App\Http\Requests\Farm\UpdateFarmRequest;
use App\Http\Resources\FarmResource;
use App\Models\Farm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FarmController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Farm::with(['user', 'availableStocks.product'])
            ->where('is_active', true);

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('product_id')) {
            $query->whereHas('availableStocks', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        if (
            $request->filled('lat') &&
            $request->filled('lng') &&
            $request->filled('radius')
        ) {
            $lat = (float) $request->lat;
            $lng = (float) $request->lng;
            $radius = (float) $request->radius;

            $query->selectRaw("
                *,
                (6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )) AS distance
            ", [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
        }

        return FarmResource::collection($query->paginate(15));
    }

    public function show(Farm $farm): FarmResource
    {
        $farm->load(['user', 'stocks.product']);

        return new FarmResource($farm);
    }

    public function store(StoreFarmRequest $request): JsonResponse
    {
        $farm = Farm::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Exploitation créée avec succès.',
            'farm' => new FarmResource($farm),
        ], 201);
    }

    public function update(UpdateFarmRequest $request, Farm $farm): JsonResponse
    {
        $farm->update($request->validated());

        return response()->json([
            'message' => 'Exploitation mise à jour.',
            'farm' => new FarmResource($farm->fresh()->load('user')),
        ]);
    }

    public function destroy(Farm $farm, Request $request): JsonResponse
    {
        if ($farm->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Action non autorisée.',
            ], 403);
        }

        $farm->update(['is_active' => false]);

        return response()->json([
            'message' => 'Exploitation désactivée.',
        ]);
    }

    public function myFarms(Request $request): AnonymousResourceCollection
    {
        $farms = Farm::with(['stocks.product'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return FarmResource::collection($farms);
    }
}