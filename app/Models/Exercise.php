<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exercise extends Model 
{
    use HasFactory; 

    protected $fillable = [
        'name',
        'instructions',
    ];

    /**
     * An Exercise belongs to many Protocols.
     */
    public function protocols(): BelongsToMany
    {
        return $this->belongsToMany(Protocol::class, 'protocol_exercise')
                    ->withPivot(['sets', 'reps', 'resistance_base_unit_value', 'resistance_original_unit'])
                    ->withTimestamps();
    }
}