<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;

class EnrollInCourse
{
    public function handle(User $user, Course $course): void
    {
        abort_unless($course->published, 404);

        CourseEnrollment::firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ], [
            'enrolled_at' => now(),
        ]);
    }
}
