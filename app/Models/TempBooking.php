<?php

namespace App\Models;

use App\Models\Room\Room;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempBooking extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'start',
        'duration',
        'end',
        'guests',
        'qr_code',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
