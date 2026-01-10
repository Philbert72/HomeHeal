<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailySessionLog extends Model
{
    protected $table = 'daily_session_logs';

    protected $fillable = [
        'patient_id',
        'protocol_id',
        'log_date',
        'pain_score',
        'difficulty_rating',
        'notes',
    ];
    
    protected $casts = [
        'log_date' => 'date',
        'pain_score' => 'integer',
        'difficulty_rating' => 'integer',
    ];

    /**
     * A session log belongs to one patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * A session log belongs to one protocol.
     */
    public function protocol(): BelongsTo
    {
        return $this->belongsTo(Protocol::class, 'protocol_id');
    }
}