<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Collaboration extends Model
{
   use HasFactory;

   protected $fillable = [
      'studio_id',
      'title',
      'desc',
   ];

   public function studio(): BelongsTo
   {
      return $this->belongsTo(Studio::class);
   }
}
