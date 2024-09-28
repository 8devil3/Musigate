<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $fillable = [
        'studio_id',
        'name',
        'description',
        'price',
        'discounted_price',
        'thumbnail_filename',
        'thumbnail_path',
    ];

    public function studios(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }
}
