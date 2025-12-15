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
     * A Protocol belongs to the Therapist who created it.
     */
    public function therapist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    /**
     * A Protocol is assigned to many Patients.
     */
    public function patients(): BelongsToMany
    {
        // Pivot table is 'protocol_user', linked via 'protocol_id' and 'user_id'
        return $this->belongsToMany(User::class, 'protocol_user')
                    ->withTimestamps();
    }

    /**
     * Defines the Exercises relationship.
     * Includes all custom pivot fields used in the migration and controller.
     */
    public function exercises(): BelongsToMany
    {
        // Pivot table is 'protocol_exercise'
        return $this->belongsToMany(Exercise::class, 'protocol_exercise')
                    // Ensure ALL custom pivot fields are listed here
                    ->withPivot([
                        'sets', 
                        'reps', 
                        'resistance_amount', 
                        'rest_seconds', 
                        'resistance_original_unit' // This column is crucial for saving
                    ])
                    ->withTimestamps();
    }
}