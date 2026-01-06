<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Protocol extends Model
{
    use HasFactory;

    protected $fillable = [
        'therapist_id',
        'title',
        'description',
    ];

    /**
     * 1 Protocol belongs to 1 Therapist.
     */
    public function therapist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    /**
     * 1 Protocol assigned to many Patients.
     */
    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'protocol_user')
                    ->withTimestamps();
    }

    /**
     * Define Exercises relationship.
     */
    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'protocol_exercise')
                    ->withPivot([
                        'sets', 
                        'reps', 
                        'resistance_amount', 
                        'rest_seconds', 
                        'resistance_original_unit'
                    ])
                    ->withTimestamps();
    }
}