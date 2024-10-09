<?php

namespace App\Models\Room;

use App\Models\Studio\Timeband;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomPrice extends Model
{
    protected $fillable = [
        'room_id',
        'weekday',
        'start',
        'end',
        'price',
        'has_discounted_price',
        'discounted_price',
    ];

    protected $casts = [
        'has_discounted_price' => 'boolean',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function timeband(): BelongsTo
    {
        return $this->belongsTo(Timeband::class);
    }
}
