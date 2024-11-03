<?php

namespace App\Models\Bundle;

use App\Models\Studio\Availability;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BundleWeekdayPrice extends Model
{
    protected $fillable = [
        'bundle_id',
        'availability_id',
        'weekday',
        'price',
        'has_discounted_price',
        'discounted_price',
    ];

    protected $casts = [
        'has_discounted_price' => 'boolean',
    ];

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn(int|null $value) => $value ? $value / 100 : null,
            set: fn(int|null $value) => $value ? $value * 100 : null
        );
    }

    public function discountedPrice(): Attribute
    {
        return Attribute::make(
            get: fn(int|null $value) => $value ? $value / 100 : null,
            set: fn(int|null $value) => $value ? $value * 100 : null
        );
    }

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class);
    }

    public function availability(): BelongsTo
    {
        return $this->belongsTo(Availability::class);
    }
}
