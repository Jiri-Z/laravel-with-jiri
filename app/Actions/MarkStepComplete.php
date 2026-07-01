<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\CourseEnrollment;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Illuminate\Database\QueryException;

class MarkStepComplete
{
    public function handle(User $user, Step $step): bool
    {
        $course = $step->lesson->course;

        abort_unless($course->published, 403);

        $enrolled = CourseEnrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        abort_unless($enrolled, 403);
        abort_unless($step->isAccessibleBy($user), 403);

        try {
            StepCompletion::create([
                'user_id' => $user->id,
                'step_id' => $step->id,
                'completed_at' => now(),
            ]);

            return true;
        } catch (QueryException) {
            return false;
        }
    }
}
