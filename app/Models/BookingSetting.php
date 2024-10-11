<?php

namespace App\Models;

use App\Models\Studio\Studio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingSetting extends Model
{
    protected $fillable = [
        'studio_id',
        'booking_advance',
        'max_booking_horizon',
        'allow_fractional_durations',
        'has_sync',
        'sync_mode',
        'google_calendar_id',
        'default_calendar_view',
    ];

    protected $casts = [
        'has_sync' => 'boolean',
        'allow_fractional_durations' => 'boolean',
    ];

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }
}
