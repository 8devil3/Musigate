<?php

namespace App\Models\Studio;

use App\Models\Studio\Studio;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
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
}
