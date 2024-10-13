<?php

namespace App\Models\Room;

use App\Models\Studio\Timeband;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomPrice extends Model
{
    const PRICE_TYPES = [
        'no_price' => 'Nessuna tariffa',
        'fixed_price' => 'Tariffa fissa',
        'timebands_price' => 'Tariffe a fasce orarie',
    ];

    protected $fillable = [
        'room_id',
        'timeband_id',
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
