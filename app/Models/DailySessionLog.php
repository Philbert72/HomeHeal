<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailySessionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 
        'protocol_id',
        'log_date', // CRITICAL FIX: Added log_date to allow mass assignment
        'pain_score',
        'difficulty_rating', 
        'notes',
        'completed_exercises',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'completed_exercises' => 'array',
        'pain_score' => 'integer',
        'difficulty_rating' => 'integer',
        // Optional but recommended for date integrity:
        'log_date' => 'date', 
    ];

    /**
     * Get the user (patient) who created the log.
     */
    public function patient(): BelongsTo 
    {
        // Specifying the foreign key 'patient_id'
        return $this->belongsTo(User::class, 'patient_id'); 
    }

    /**
     * Get the protocol the session was logged against.
     */
    public function protocol(): BelongsTo
    {
        return $this->belongsTo(Protocol::class);
    }
}