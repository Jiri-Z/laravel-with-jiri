<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    public function view(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin() || ($user->isInstructor() && $lesson->course()->where('user_id', $user->id)->exists());
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    public function update(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin() || ($user->isInstructor() && $lesson->course()->where('user_id', $user->id)->exists());
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin();
    }
}
