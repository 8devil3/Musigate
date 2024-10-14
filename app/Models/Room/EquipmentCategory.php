<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentCategory extends Model
{
    protected $fillable = [
        'name'
    ];

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }
}
