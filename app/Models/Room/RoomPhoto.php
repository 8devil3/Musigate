<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomPhoto extends Model
{
    protected $fillable = [
        'room_id',
        'filename',
        'path',
        'sort_index',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
