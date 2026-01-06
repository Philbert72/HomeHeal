<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; 
use Illuminate\Support\Facades\Auth; 

class Protocol extends Model
{
    use HasFactory;

    protected $fillable = [
        'therapist_id',
        'title',
        'description',
    ];

    // CRITICAL: Appends the 'current_progress' attribute when the model is converted to JSON/Array.
    protected $appends = ['current_progress']; 

    // --- Relationships ---

    public function therapist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    public function exercises(): BelongsToMany
    {
        // NOTE: The pivot table name should be 'protocol_exercise' if that is what you are using in your database
        return $this->belongsToMany(Exercise::class, 'protocol_exercise') 
            ->withPivot(['sets', 'reps', 'resistance_amount', 'resistance_original_unit', 'rest_seconds'])
            ->withTimestamps();
    }
    
    /**
     * Relationship to fetch all DailySessionLogs submitted for this protocol.
     */
    public function sessionLogs(): HasMany
    {
        return $this->hasMany(DailySessionLog::class);
    }
    
    /**
     * Relationship to fetch all patients assigned this protocol.
     */
    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'protocol_user')
            ->withTimestamps();
    }

    // --- Accessor: Calculates dynamic progress ---
    
    /**
     * Calculates the current progress percentage for the authenticated patient.
     * Uses a basic metric: Logs completed vs. a simple goal (20).
     */
    public function getCurrentProgressAttribute(): int
    {
        // 1. Must be logged in as a patient to calculate personalized progress
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            return 0;
        }

        $patientId = Auth::id();
        
        // 2. Count the patient's logs specifically for THIS protocol
        $totalLogs = $this->sessionLogs()
                        ->where('patient_id', $patientId)
                        ->count();
                        
        // 3. Define a simple goal for 100% completion (e.g., 20 sessions)
        $maxLogsFor100Percent = 20; 
        
        // 4. Calculate progress, capped at 100%
        $progress = min(100, round(($totalLogs / $maxLogsFor100Percent) * 100));

        return (int) $progress; 
    }
}