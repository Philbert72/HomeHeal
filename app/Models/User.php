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
        'role', // 'patient' or 'therapist'
        'locale',
        'timezone',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed', // Ensures the password is automatically hashed upon creation/update
    ];


    /**
     * A Therapist user can create many Protocols.
     * (Used for Therapist Dashboard)
     */
    public function createdProtocols(): HasMany
    {
        // Links to the 'therapist_id' field on the protocols table
        return $this->hasMany(Protocol::class, 'therapist_id');
    }

    /**
     * A Patient user can have many DailySessionLogs.
     */
    public function dailySessionLogs(): HasMany
    {
        // Links to the 'patient_id' field on the daily_session_logs table
        return $this->hasMany(DailySessionLog::class, 'patient_id');
    }

    /**
     * A Patient is assigned many Protocols.
     * This is the standardized relationship used by controllers and the PatientDashboard component.
     */
    public function protocols(): BelongsToMany
    {
        // Links to Protocol via the 'protocol_user' pivot table.
        // We use withTimestamps() to access the 'pivot_created_at' in the Livewire component.
        return $this->belongsToMany(Protocol::class, 'protocol_user', 'user_id', 'protocol_id')
                    ->withTimestamps();
    }
    
    // NOTE: Removing duplicate definition of assignedProtocols() and protocols()

    /**
     * Scope to return only users with the 'patient' role.
     */
    public function scopePatient(Builder $query): void
    {
        $query->where('role', 'patient');
    }
}