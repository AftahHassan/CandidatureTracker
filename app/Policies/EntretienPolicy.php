<?php

namespace App\Policies;

use App\Models\Entretien;
use App\Models\User;

class EntretienPolicy
{
    public function update(User $user, Entretien $entretien): bool
    {
        return $user->id === $entretien->candidature->user_id;
    }

    public function delete(User $user, Entretien $entretien): bool
    {
        return $user->id === $entretien->candidature->user_id;
    }
}