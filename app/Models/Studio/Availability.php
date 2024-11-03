<?php

namespace App\Models\Studio;

use App\Models\Studio\Studio;
use App\Models\Room\RoomTimebandPrice;
use App\Models\Room\RoomHourlyPrice;
use App\Models\Bundle\BundleTimebandPrice;
use App\Models\Bundle\BundleWeekdayPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Availability extends Model
{
    protected $fillable = [
        'studio_id',
        'weekday',
        'open_type',
        'open_start',
        'open_end',
        'min_forewarning',
    ];

    public function openStart(): Attribute
    {
        return Attribute::make(
            get: fn($value) => substr($value, 0, 5),
        );
    }

    public function openEnd(): Attribute
    {
        return Attribute::make(
            get: fn($value) => substr($value, 0, 5),
        );
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function timebands(): HasMany
    {
        return $this->hasMany(Timeband::class);
    }

    public function room_hourly_prices(): HasMany
    {
        return $this->hasMany(RoomHourlyPrice::class);
    }

    public function bundle_weekday_prices(): HasMany
    {
        return $this->hasMany(BundleWeekdayPrice::class);
    }
}
