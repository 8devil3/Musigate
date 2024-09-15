<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'studio_id',
        'studio_name',
        'title',
        'message'
    ];
}
