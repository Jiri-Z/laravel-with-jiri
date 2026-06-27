<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;

trait ValidatesStepContext
{
    protected function ensureContextIsValid(Course $course, Lesson $lesson, Step $step): void
    {
        abort_unless($course->published, 404);
        abort_unless($lesson->published && $lesson->course_id === $course->id, 404);
        abort_unless($step->lesson_id === $lesson->id, 404);
    }

    protected function ensureCurrentContextIsValid(): void
    {
        $this->ensureContextIsValid($this->course, $this->lesson, $this->step);
    }
}
