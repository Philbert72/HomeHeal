<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'name',
        'instructions',
    ];

    // One Exercise for many Protocol
    public function protocols()
    {
        return $this->belongsToMany(Protocol::class, 'protocol_exercise')
                    ->withPivot(['sets', 'reps', 'resistance_amount', 'rest_seconds'])
                    ->using(ProtocolExercise::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('instructional_media')
             ->singleFile(); // Ensures only one instructional file per exercise
    }
}
