<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudioPhoto extends Model
{
    protected $fillable = [
        'studio_id',
        'is_featured',
        'path'
    ];

    protected $casts = [
        'is_featured' => 'boolean'
    ];

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }
}
