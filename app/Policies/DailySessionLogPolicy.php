<?php

namespace App\Policies;

use App\Models\DailySessionLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DailySessionLogPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DailySessionLog $sessionLog): bool
    {

        // 1. Patient can always view their own log.
        if ($user->id === $sessionLog->patient_id) {
            return true;
        }

        // 2. Therapist must be the owner of the Protocol assigned to this log.
        if ($user->role === 'therapist') {
            return $user->id === $sessionLog->protocol->therapist_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'patient';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DailySessionLog $sessionLog): bool
    {
        return $user->id === $sessionLog->patient_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DailySessionLog $dailySessionLog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DailySessionLog $sessionLog): bool
    {
        return $user->id === $sessionLog->patient_id;
    }

}
