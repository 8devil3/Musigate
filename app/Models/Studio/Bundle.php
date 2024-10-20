<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bundle extends Model
{
    protected $fillable = [
        'studio_id',
        'name',
        'cover_path',
        'color',
        'is_visible',
        'is_bookable',
        'description',
        'price_type',
        'fixed_price',
        'has_discounted_fixed_price',
        'discounted_fixed_price',
    ];

    protected $casts = [
        'is_bookable' => 'boolean',
        'is_visible' => 'boolean',
        'has_discounted_fixed_price' => 'boolean',
    ];

    public function fixedPrice(): Attribute
    {
        return Attribute::make(
            get: fn(int|null $value) => $value ? $value / 100 : null,
            set: fn(int|null $value) => $value ? $value * 100 : null
        );
    }

    public function discountedFixedPrice(): Attribute
    {
        return Attribute::make(
            get: fn(int|null $value) => $value ? $value / 100 : null,
            set: fn(int|null $value) => $value ? $value * 100 : null
        );
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(BundlePrice::class);
    }
}
