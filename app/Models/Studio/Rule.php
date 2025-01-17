<?php

namespace App\Models\Studio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rule extends Model
{
   protected $fillable = [
      'studio_id',
      'before',
      'during',
      'after'
   ];

   public function studio(): BelongsTo
   {
      return $this->belongsTo(Studio::class);
   }
}
