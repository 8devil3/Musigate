<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CancelPolicySetting extends Model
{
    protected $fillable = [
        'studio_id',
        'has_cancel_policy',
        'full_refund_hours',
        'partial_refund_hours',
        'partial_refund_percentage',
        'no_refund_hours',
    ];

    protected $casts = [
        'has_cancel_policy' => 'boolean',
    ];

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }
}
