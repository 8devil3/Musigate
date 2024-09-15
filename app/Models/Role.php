<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['role'];

    const SUPERADMIN = 1;
    const STUDIO = 2;
    const ARTIST = 3;

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}

