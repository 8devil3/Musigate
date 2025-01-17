<?php

namespace App\Models\Room;

use App\Models\Booking;
use App\Models\TempBooking;
use App\Models\Studio\Studio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    const STATUSES = [
        'Bozza',
        'In approvazione',
        'Pubblicata',
    ];

    protected $fillable = [
        'studio_id',
        'name',
        'color',
        'min_booking',
        'area',
        'description',
        'max_capacity',
        'price_type',
        'monthly_price',
        'has_discounted_monthly_price',
        'discounted_monthly_price',
        'is_bookable',
        'is_published',
    ];

    protected $casts = [
        'is_bookable' => 'boolean',
        'is_published' => 'boolean',
        'has_discounted_monthly_price' => 'boolean',
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

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(RoomPhoto::class)->orderBy('sort_index');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function temp_bookings(): HasMany
    {
        return $this->hasMany(TempBooking::class);
    }

    public function timeband_prices(): HasMany
    {
        return $this->hasMany(RoomTimebandPrice::class);
    }

    public function hourly_prices(): HasMany
    {
        return $this->hasMany(RoomHourlyPrice::class);
    }
}
