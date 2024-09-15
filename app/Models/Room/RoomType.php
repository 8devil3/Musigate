<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    protected $fillable = ['name'];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
