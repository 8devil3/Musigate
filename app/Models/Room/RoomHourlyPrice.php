<?php

namespace App\Models\Room;

use App\Models\Room\Room;
use App\Models\Studio\Availability;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomHourlyPrice extends Model
{
    protected $fillable = [
        'room_id',
        'availability_id',
        'weekday',
        'price',
        'has_discounted_price',
        'discounted_price',
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

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function availability(): BelongsTo
    {
        return $this->belongsTo(Availability::class);
    }
}
