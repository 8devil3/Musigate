<?php

namespace App\Models;

use App\Models\Studio\Studio;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'avatar',
        'email',
        'password',
        'tos',
        'privacy',
        'google_id',
        'google_token',
        'google_refresh_token',
        'google_token_expires_in',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tos' => 'boolean',
        'privacy' => 'boolean',
    ];

    /**
     * Filtra gli utenti tipo Superadmin
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeSuperadminUser(Builder $query): void
    {
        $query->where('role_id', Role::SUPERADMIN);
    }

    
    /**
     * Filtra gli utenti tipo Studio
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeStudioUser(Builder $query): void
    {
        $query->where('role_id', Role::STUDIO);
    }

    
    /**
     * Filtra gli utenti tipo Artista
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeArtistUser(Builder $query): void
    {
        $query->where('role_id', Role::ARTIST);
    }

    public function studio(): HasOne
    {
        return $this->hasOne(Studio::class);
    }

    //mostro il cognome come iniziale puntata (es: Aldo C.)
    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => substr($value, 0, 1) . '.',
            // set: fn (string $value) => strtolower($value),
        );
    }
}
