<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'log_date' => 'date', // Ensures Carbon instances are used
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function protocol()
    {
        return $this->belongsTo(Protocol::class, 'protocol_id');
    }
}