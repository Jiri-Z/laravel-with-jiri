<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\CourseNotPublishedException;
use App\Exceptions\NotEnrolledException;
use App\Exceptions\StepNotAccessibleException;
use App\Models\CourseEnrollment;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Illuminate\Database\QueryException;

class MarkStepComplete
{
    public function handle(User $user, Step $step): bool
    {
        $lesson = $step->lesson;

        if ($lesson === null) {
            throw new CourseNotPublishedException;
        }

        $course = $lesson->course;

        if (! $course || ! $course->published) {
            throw new CourseNotPublishedException;
        }

        $enrolled = CourseEnrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if (! $enrolled) {
            throw new NotEnrolledException;
        }

        if (! $step->isAccessibleBy($user)) {
            throw new StepNotAccessibleException;
        }

        $alreadyCompleted = StepCompletion::where('user_id', $user->id)
            ->where('step_id', $step->id)
            ->whereNotNull('completed_at')
            ->exists();

        if ($alreadyCompleted) {
            return false;
        }

        try {
            StepCompletion::updateOrCreate(
                ['user_id' => $user->id, 'step_id' => $step->id],
                ['completed_at' => now(), 'unlocked_at' => now()],
            );

            $this->unlockNextStep($user, $step, $lesson);

            return true;
        } catch (QueryException) {
            return false;
        }
    }

    private function unlockNextStep(User $user, Step $step, Lesson $lesson): void
    {
        $nextStep = $lesson->steps()
            ->where('order', '>', $step->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextStep === null) {
            return;
        }

        try {
            StepCompletion::updateOrCreate(
                ['user_id' => $user->id, 'step_id' => $nextStep->id],
                ['unlocked_at' => now()],
            );
        } catch (QueryException) {

        }
    }
}
