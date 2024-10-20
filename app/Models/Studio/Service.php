<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = [
        'service'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function studios(): BelongsToMany
    {
        return $this->belongsToMany(Studio::class);
    }
}
