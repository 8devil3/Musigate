<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Room\RoomTimebandPrice;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Timeband extends Model
{
    protected $fillable = [
        'studio_id',
        'availability_id',
        'weekday',
        'name',
        'start',
        'end',
    ];

    public function start(): Attribute
    {
        return Attribute::make(
            get: fn($value) => substr($value, 0, 5),
        );
    }

    public function end(): Attribute
    {
        return Attribute::make(
            get: fn($value) => substr($value, 0, 5),
        );
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function availability(): BelongsTo
    {
        return $this->belongsTo(Availability::class);
    }

    public function room_prices(): HasMany
    {
        return $this->hasMany(RoomTimebandPrice::class);
    }

    public function bundle_prices(): HasMany
    {
        return $this->hasMany(BundleTimebandPrice::class);
    }
}
