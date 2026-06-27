<?php

declare(strict_types=1);

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
        return $user->isAdmin() || ($user->isInstructor() && $course->user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    public function update(User $user, Course $course): bool
    {
        return $user->isAdmin() || ($user->isInstructor() && $course->user_id === $user->id);
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->isAdmin();
    }
}
