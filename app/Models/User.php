<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'locale',
        'timezone',
    ];
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];


    /**
     * 1 Therapist create many Protocols.
     */
    public function createdProtocols(): HasMany
    {
        return $this->hasMany(Protocol::class, 'therapist_id');
    }

    /**
     * 1 Patient assigned many Protocols.
     */
    public function assignedProtocols(): BelongsToMany
    {
        return $this->belongsToMany(Protocol::class, 'protocol_user')
                    ->withPivot('duration_days')
                    ->withTimestamps();
    }

    public function scopePatient(Builder $query): void
    {
        $query->where('role', 'patient');
    }

    /**
     * 1 Patient have many DailySessionLogs.
     */
    public function dailySessionLogs(): HasMany
    {
        return $this->hasMany(DailySessionLog::class, 'patient_id');
    }

    public function protocols(): BelongsToMany
    {
        return $this->belongsToMany(Protocol::class, 'protocol_user', 'user_id', 'protocol_id')
                    ->withPivot('duration_days')
                    ->withTimestamps();
    }

    public function latestSessionLog()
    {
        return $this->hasOne(DailySessionLog::class, 'patient_id')->latestOfMany('log_date');
    }

}