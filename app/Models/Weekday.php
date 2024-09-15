<?php

namespace App\Models;

use App\Models\Studio\Availability;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Weekday extends Model
{
    protected $fillable = [
        'name',
        'name_short',
        'name_en',
        'rrule_name'
    ];

    public function availabilities(): MorphMany
    {
        return $this->morphMany(Availability::class, 'available');
    }
}
