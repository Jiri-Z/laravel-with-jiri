<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\NotEnrolledException;
use App\Exceptions\OrphanedStepException;
use App\Exceptions\StepNotAccessibleException;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarkStepComplete
{
    public function handle(User $user, Step $step, ?Lesson $lesson = null, ?Course $course = null): bool
    {
        $lesson ??= $step->lesson;

        if ($lesson === null) {
            throw new OrphanedStepException;
        }

        $course ??= $lesson->course;

        if (! $step->published) {
            throw new StepNotAccessibleException;
        }

        if (! $course || ! $course->published) {
            throw new NotEnrolledException;
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

        try {
            return DB::transaction(function () use ($user, $step, $lesson): bool {
                $existing = StepCompletion::where('user_id', $user->id)
                    ->where('step_id', $step->id)
                    ->lockForUpdate()
                    ->first();

                if ($existing !== null && $existing->completed_at !== null) {
                    return false;
                }

                StepCompletion::updateOrCreate(
                    ['user_id' => $user->id, 'step_id' => $step->id],
                    ['completed_at' => now(), 'unlocked_at' => now()],
                );

                $this->unlockNextStep($user, $step, $lesson);

                return true;
            });
        } catch (QueryException $e) {
            if (! in_array((string) $e->getCode(), ['23000', '23505'], true)) {
                throw $e;
            }

            Log::warning('Failed to mark step complete', [
                'user_id' => $user->id,
                'step_id' => $step->id,
                'error' => $e->getMessage(),
            ]);

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
        } catch (QueryException $e) {
            if (! in_array((string) $e->getCode(), ['23000', '23505'], true)) {
                throw $e;
            }

            Log::warning('Failed to unlock next step', [
                'user_id' => $user->id,
                'step_id' => $step->id,
                'next_step_id' => $nextStep->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
