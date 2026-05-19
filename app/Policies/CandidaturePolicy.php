<?php

namespace App\Policies;

use App\Models\Candidature;
use App\Models\User;

class CandidaturePolicy
{
    public function view(User $user, Candidature $candidature): bool
    {
        return $user->id === $candidature->user_id;
    }

    public function update(User $user, Candidature $candidature): bool
    {
        return $user->id === $candidature->user_id;
    }

    public function delete(User $user, Candidature $candidature): bool
    {
        return $user->id === $candidature->user_id;
    }
}