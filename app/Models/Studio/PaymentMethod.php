<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'img_name'
    ];

    public function payment_methods(): BelongsToMany
    {
        return $this->belongsToMany(Studio::class);
    }
}
