<?php

namespace App\Policies;

use App\Models\DailySessionLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DailySessionLogPolicy
{
    public function view(User $user, DailySessionLog $sessionLog): bool
    {

        if ($user->id === $sessionLog->patient_id) {
            return true;
        }

        if ($user->role === 'therapist') {
            return $user->id === $sessionLog->protocol->therapist_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'patient';
    }

    public function update(User $user, DailySessionLog $sessionLog): bool
    {
        return $user->id === $sessionLog->patient_id;
    }

    public function delete(User $user, DailySessionLog $dailySessionLog): bool
    {
        return false;
    }

    public function restore(User $user, DailySessionLog $sessionLog): bool
    {
        return $user->id === $sessionLog->patient_id;
    }

}
