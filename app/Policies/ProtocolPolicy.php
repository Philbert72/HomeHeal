<?php

namespace App\Policies;

use App\Models\Protocol;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProtocolPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->id !== null;
    }

    public function view(User $user, Protocol $protocol): bool
    {
        if ($user->role === 'therapist' && $user->id === $protocol->therapist_id) {
            return true;
        }

        if ($user->role === 'patient') {
            return $protocol->patients()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'therapist';
    }

    public function update(User $user, Protocol $protocol): bool
    {
        return $user->role === 'therapist' && $user->id === $protocol->therapist_id;
    }

    public function delete(User $user, Protocol $protocol): bool
    {
        return $user->role === 'therapist' && $user->id === $protocol->therapist_id;
    }

}
