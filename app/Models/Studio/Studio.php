<?php

namespace App\Models\Studio;

use App\Models\User;
use App\Models\Room\Room;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Studio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'vat',
        'logo',
        'category',
        'desc',
        'record_label',
        'is_visible',
        'is_complete',
    ];

    protected $casts = [
        'record_label' => 'boolean',
        'is_visible' => 'boolean',
        'is_complete' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(StudioPhoto::class)->orderByDesc('created_at');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(StudioVideo::class);
    }

    public function location(): HasOne
    {
        return $this->hasOne(Location::class);
    }

    public function social(): HasOne
    {
        return $this->hasOne(Social::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function comforts(): BelongsToMany
    {
        return $this->belongsToMany(Comfort::class);
    }

    public function rule(): HasOne
    {
        return $this->hasOne(Rule::class);
    }

    public function contacts(): HasOne
    {
        return $this->hasOne(Contact::class);
    }

    public function collaborations(): HasMany
    {
        return $this->hasMany(Collaboration::class)->orderByDesc('created_at');
    }

    public function payment_methods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class);
    }
}
