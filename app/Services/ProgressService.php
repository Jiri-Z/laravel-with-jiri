<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;

class ProgressService
{
    public function courseProgress(User $user, Course $course): float
    {
        $totalSteps = Step::whereIn('lesson_id', function ($q) use ($course) {
            $q->select('id')->from('lessons')->where('course_id', $course->id);
        })->count();

        if ($totalSteps === 0) {
            return 0.0;
        }

        $completedSteps = StepCompletion::where('user_id', $user->id)
            ->whereIn('step_id', function ($q) use ($course) {
                $q->select('steps.id')->from('steps')
                    ->whereIn('steps.lesson_id', function ($q) use ($course) {
                        $q->select('id')->from('lessons')->where('course_id', $course->id);
                    });
            })
            ->count();

        return round(($completedSteps / $totalSteps) * 100, 1);
    }

    public function lessonComplete(User $user, Lesson $lesson): bool
    {
        $totalSteps = $lesson->steps()->count();

        if ($totalSteps === 0) {
            return false;
        }

        $completedSteps = StepCompletion::where('user_id', $user->id)
            ->whereIn('step_id', $lesson->steps()->pluck('id'))
            ->count();

        return $completedSteps === $totalSteps;
    }
}
