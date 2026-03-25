<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\StoreStockRequest;
use App\Http\Requests\Stock\UpdateStockRequest;
use App\Http\Resources\StockResource;
use App\Models\Farm;
use App\Models\Stock;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StockController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Stock::with(['farm.user', 'product'])
            ->where('status', 'available');

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_unit', '<=', $request->max_price);
        }

        if ($request->filled('min_quantity')) {
            $query->where('quantity', '>=', $request->min_quantity);
        }

        if ($request->filled('department')) {
            $query->whereHas('farm', function ($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        return StockResource::collection($query->latest()->paginate(20));
    }

    public function show(Stock $stock): StockResource
    {
        $stock->load(['farm.user', 'product']);

        return new StockResource($stock);
    }

    public function store(StoreStockRequest $request): JsonResponse
    {
        $farm = Farm::findOrFail($request->farm_id);

        if ($farm->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Vous ne pouvez publier des stocks que pour vos propres exploitations.',
            ], 403);
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imageService = new ImageUploadService();
            $data['photo_url'] = $imageService->upload($request->file('image'), 'agril/stocks');
        }

        unset($data['image']);

        $stock = Stock::create($data);
        $stock->load(['farm', 'product']);

        return response()->json([
            'message' => 'Stock publié avec succès.',
            'stock' => new StockResource($stock),
        ], 201);
    }

    public function update(UpdateStockRequest $request, Stock $stock): JsonResponse
    {
        $stock->update($request->validated());

        return response()->json([
            'message' => 'Stock mis à jour.',
            'stock' => new StockResource($stock->fresh()->load(['farm', 'product'])),
        ]);
    }

    public function updateStatus(Request $request, Stock $stock): JsonResponse
    {
        if ($stock->farm->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Action non autorisée.',
            ], 403);
        }

        $request->validate([
            'status' => ['required', 'in:available,reserved,exhausted'],
        ]);

        $stock->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Statut mis à jour.',
            'stock' => new StockResource($stock->fresh()->load(['farm', 'product'])),
        ]);
    }

    public function destroy(Stock $stock, Request $request): JsonResponse
    {
        if ($stock->farm->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Action non autorisée.',
            ], 403);
        }

        if ($stock->photo_url) {
            $imageService = new ImageUploadService();
            $imageService->delete($stock->photo_url);
        }

        $stock->delete();

        return response()->json([
            'message' => 'Stock supprimé.',
        ]);
    }

    public function myStocks(Request $request): AnonymousResourceCollection
    {
        $stocks = Stock::with(['farm', 'product'])
            ->whereHas('farm', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->latest()
            ->get();

        return StockResource::collection($stocks);
    }
}