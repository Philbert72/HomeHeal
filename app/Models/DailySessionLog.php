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

    // Define the data types for casting
    protected $casts = [
        'log_date' => 'date',
        'pain_score' => 'integer',
        'difficulty_rating' => 'integer',
    ];

    // A session log belong to 1 patient
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // A session log belong to 1 protocol
    public function protocol()
    {
        return $this->belongsTo(Protocol::class, 'protocol_id');
    }

    // Define the media collection for patient feedback videos/photos.
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('patient_feedback');
    }
}
