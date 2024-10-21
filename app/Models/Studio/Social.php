<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Social extends Model
{
    protected $fillable = [
        'studio_id',
        'facebook',
        'instagram',
        'youtube',
        'linkedin',
        'soundcloud',
        'spotify',
        'itunes',
        'website',
    ];

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }
}
