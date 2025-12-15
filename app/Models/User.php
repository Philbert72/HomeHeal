<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // CRITICAL IMPORT

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable, including new custom fields.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'patient' or 'therapist'
        'locale',
        'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * This is CRITICAL for security.
     */
    protected $hidden = [
        'password',
        // 'remember_token' is removed since the migration doesn't include it.
    ];

    /**
     * The attributes that should be cast.
     * Only keeping the essential 'password' hashing cast.
     */
    protected $casts = [
        'password' => 'hashed', // Ensures the password is automatically hashed upon creation/update
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships (Therapist/Patient perspective)
    |--------------------------------------------------------------------------
    */

    /**
     * A Therapist user can create many Protocols.
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
     * FIX: Defines the Protocols assigned to a Patient via the protocol_user pivot table.
     * This relationship is used by the PatientDashboard component.
     */
    public function protocols(): BelongsToMany
    {
        // Links to Protocol via the 'protocol_user' pivot table.
        // We use withTimestamps() to access the 'pivot_created_at' in the Livewire component.
        return $this->belongsToMany(Protocol::class, 'protocol_user', 'user_id', 'protocol_id')
                    ->withTimestamps();
    }
}