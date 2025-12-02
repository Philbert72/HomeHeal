<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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
        'remember_token', // Keep this even if the column is absent for future-proofing
    ];

    protected $casts = [
        'password' => 'hashed', // Ensures the password is automatically hashed upon creation/update
    ];

    public function createdProtocols(){
        return $this->hasMany(Protocol::class, 'therapist_id');
    }

    public function dailySessionLogs(){
        return $this->hasMany(DailySessionLog::class, 'patient_id');
    }
}
