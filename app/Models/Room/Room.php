<?php

namespace App\Models\Room;

use App\Models\Studio\Studio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'studio_id',
        'room_type_id',
        'room_status_id',
        'name',
        'color', 7,
        'min_booking',
        'min_price',
        'area',
        'desc',
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
        return $this->hasMany(RoomPhoto::class)->orderByDesc('created_at');
    }
}
