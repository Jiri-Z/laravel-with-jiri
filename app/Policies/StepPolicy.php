<?php

namespace App\Policies;

use App\Models\Step;
use App\Models\User;

class StepPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    public function view(User $user, Step $step): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    public function update(User $user, Step $step): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    public function delete(User $user, Step $step): bool
    {
        return $user->isAdmin();
    }
}
