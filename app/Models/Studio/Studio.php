<?php

namespace App\Models\Studio;

use App\Models\User;
use App\Models\BookingSetting;
use App\Models\Room\Room;
use App\Models\Studio\Availability;
use App\Models\Studio\CancelPolicySetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Studio extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'vat',
        'logo',
        'category',
        'description',
        'record_label',
        'is_visible',
        'is_open_24_7',
        'is_complete',
    ];

    protected $casts = [
        'record_label' => 'boolean',
        'is_visible' => 'boolean',
        'is_open_24_7' => 'boolean',
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
        return $this->hasMany(StudioPhoto::class)->orderBy('sort_index');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function location(): HasOne
    {
        return $this->hasOne(Location::class);
    }

    public function social(): HasOne
    {
        return $this->hasOne(Social::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
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

    public function availability(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    public function booking_settings(): HasOne
    {
        return $this->hasOne(BookingSetting::class);
    }

    public function cancel_settings(): HasOne
    {
        return $this->hasOne(CancelPolicySetting::class);
    }

    public function timebands(): HasMany
    {
        return $this->hasMany(Timeband::class);
    }
}
