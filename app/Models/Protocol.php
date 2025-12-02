<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Protocol extends Model
{
    protected $fillable = [
        'therapist_id',
        'title',
        'description',
    ];

    // 1 Protocol belong to many therapist
    public function therapist(){
        return $this->belongsTo(User::class, 'therapist_id');
    }

    // 1 protocol for many exercise
    public function exercises(){
        return $this->belongsToMany(Exercise::class, 'protocol_exercises')
                    ->withPivot(['sets', 'reps', 'resistance_amount', 'rest_seconds'])
                    ->using(ProtocolExercise::class);
    }
    // A Protocol can have many DailySessionLogs recorded against it.
    public function sessionLogs(){
        return $this->hasMany(DailySessionLog::class, 'protocol_id');
    }
}
