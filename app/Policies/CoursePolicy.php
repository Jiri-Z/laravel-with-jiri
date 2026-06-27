<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    public function view(User $user, Course $course): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    public function update(User $user, Course $course): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->isAdmin();
    }
}
