<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'studio_id',
        'complete_address',
        'address',
        'number',
        'city',
        'cap',
        'province',
        'lon',
        'lat',
        'notes',
    ];

    protected $casts = [
        'lon' => 'float',
        'lat' => 'float',
    ];

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }
}
