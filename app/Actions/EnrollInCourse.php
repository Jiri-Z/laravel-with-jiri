<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Database\QueryException;

class EnrollInCourse
{
    public function handle(User $user, Course $course): void
    {
        if (! $course->published) {
            abort(404);
        }

        try {
            CourseEnrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'enrolled_at' => now(),
            ]);
        } catch (QueryException) {
            // Already enrolled — idempotent
        }
    }
}
