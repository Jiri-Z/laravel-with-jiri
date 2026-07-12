<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Course $course): bool
    {
        return $user->isAdmin() || ($user->isInstructor() && $user->ownsCourse($course));
    }

    public function create(User $user): bool
    {
        return $user->isStaff();
    }

    public function update(User $user, Course $course): bool
    {
        return $user->isAdmin() || ($user->isInstructor() && $user->ownsCourse($course));
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->isAdmin();
    }
}
