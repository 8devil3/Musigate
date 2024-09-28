<?php

namespace App\Models\Room;

use App\Models\Booking;
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
        'type',
        'status',
        'name',
        'color', 7,
        'price',
        'discounted_price',
        'discount_start',
        'discount_end',
        'area',
        'description',
        'max_capacity',
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
        return $this->hasMany(RoomPhoto::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
