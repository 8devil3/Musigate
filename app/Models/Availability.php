<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Studio\Studio;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    protected $fillable = [
        'studio_id',
        'weekday',
        'start',
        'end',
        'is_open',
    ];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }
}
