<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\CourseNotPublishedException;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;

class EnrollInCourse
{
    public function handle(User $user, Course $course): void
    {
        if (! $course->published) {
            throw new CourseNotPublishedException;
        }

        if ($course->locale !== app()->getLocale()) {
            throw new CourseNotPublishedException;
        }

        CourseEnrollment::firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ], [
            'enrolled_at' => now(),
        ]);
    }
}
