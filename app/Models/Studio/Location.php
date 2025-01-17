<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    protected $fillable = [
        'studio_id',
        'complete_address',
        'is_manual_address',
        'address',
        'number',
        'city',
        'cap',
        'province',
        'notes',
        'lon',
        'lat',
    ];

    protected $casts = [
        'is_manual_address' => 'boolean',
        'lon' => 'float',
        'lat' => 'float',
    ];

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }
}
