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

    public function patients() : BelongsToMany{
        // Links to Users via the 'protocol_user' pivot table
        return $this->belongsToMany(User::class, 'protocol_user', 'protocol_id', 'user_id');
    }

    // A Protocol can have many DailySessionLogs recorded against it.
    public function sessionLogs(){
        return $this->hasMany(DailySessionLog::class, 'protocol_id');
    }
}
