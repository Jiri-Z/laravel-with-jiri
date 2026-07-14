<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Models\Course;
use App\Models\CourseEnrollment;

trait EnsuresEnrollment
{
    protected function ensureEnrolled(Course $course): void
    {
        $enrolled = CourseEnrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->exists();

        if (! $enrolled) {
            $this->redirect(route('courses.index'), navigate: true);
        }
    }
}
