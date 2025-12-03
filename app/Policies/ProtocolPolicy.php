<?php

namespace App\Policies;

use App\Models\Protocol;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProtocolPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->id !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Protocol $protocol): bool
    {
        // 1. Therapists can view protocols they created.
        if ($user->role === 'therapist' && $user->id === $protocol->therapist_id) {
            return true;
        }

        // 2. Patients can view protocols they are linked to (requires querying the pivot).
        if ($user->role === 'patient') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'therapist';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Protocol $protocol): bool
    {
        return $user->role === 'therapist' && $user->id === $protocol->therapist_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Protocol $protocol): bool
    {
        return $user->role === 'therapist' && $user->id === $protocol->therapist_id;
    }

}
