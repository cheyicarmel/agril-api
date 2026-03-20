<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'product_id',
        'quantity',
        'unit',
        'price_per_unit',
        'available_from',
        'status',
        'photo_url',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'float',
            'price_per_unit' => 'float',
            'available_from' => 'date',
        ];
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}