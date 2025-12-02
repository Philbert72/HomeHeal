<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProtocolExercise extends Model
{
    protected $table = 'protocol_exercise';

    protected $fillable = [
        'sets',
        'reps',
        'resistance_amount',
        'rest_seconds',
    ];

    protected $casts = [
        'sets' => 'integer',
        'reps' => 'integer',
        'resistance_amount' => 'decimal:2',
        'rest_seconds' => 'integer',
    ];
}
