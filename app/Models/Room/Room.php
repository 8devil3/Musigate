<?php

namespace App\Models\Room;

use App\Models\Booking;
use App\Models\TempBooking;
use App\Models\Studio\Studio;
use Illuminate\Database\Eloquent\Model;
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
        'is_bookable',
        'is_visible',
        'price',
        'has_discounted_price',
        'discounted_price',
        'discount_start',
        'discount_end',
        'area',
        'description',
        'max_capacity',
    ];

    protected $casts = [
        'is_bookable' => 'boolean',
        'is_visible' => 'boolean',
        'has_discounted_price' => 'boolean',
    ];

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
}
