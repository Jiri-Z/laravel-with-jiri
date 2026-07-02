<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProgressService
{
    public function courseProgress(User $user, Course $course): float
    {
        $totalSteps = Step::whereIn('lesson_id', function ($q) use ($course) {
            $q->select('id')->from('lessons')
                ->where('course_id', $course->id)
                ->where('published', true);
        })->where('published', true)->count();

        if ($totalSteps === 0) {
            return 0.0;
        }

        $completedSteps = StepCompletion::where('user_id', $user->id)
            ->whereIn('step_id', function ($q) use ($course) {
                $q->select('steps.id')->from('steps')
                    ->where('steps.published', true)
                    ->whereIn('steps.lesson_id', function ($q) use ($course) {
                        $q->select('id')->from('lessons')
                            ->where('course_id', $course->id)
                            ->where('published', true);
                    });
            })
            ->count();

        return round(($completedSteps / $totalSteps) * 100, 1);
    }

    /**
     * @param  Collection<int, Course>  $courses
     * @return array<int, float>
     */
    public function courseProgressBatch(User $user, Collection $courses): array
    {
        $courseIds = $courses->pluck('id')->all();

        $totalSteps = DB::table('steps')
            ->join('lessons', 'steps.lesson_id', '=', 'lessons.id')
            ->whereIn('lessons.course_id', $courseIds)
            ->where('lessons.published', true)
            ->where('steps.published', true)
            ->selectRaw('lessons.course_id, count(*) as total')
            ->groupBy('lessons.course_id')
            ->pluck('total', 'course_id');

        $completedSteps = DB::table('step_completions')
            ->join('steps', 'step_completions.step_id', '=', 'steps.id')
            ->join('lessons', 'steps.lesson_id', '=', 'lessons.id')
            ->where('step_completions.user_id', $user->id)
            ->whereIn('lessons.course_id', $courseIds)
            ->where('lessons.published', true)
            ->where('steps.published', true)
            ->selectRaw('lessons.course_id, count(*) as total')
            ->groupBy('lessons.course_id')
            ->pluck('total', 'course_id');

        $result = [];
        foreach ($courses as $course) {
            $total = (int) ($totalSteps[$course->id] ?? 0);
            $completed = (int) ($completedSteps[$course->id] ?? 0);
            $result[$course->id] = $total > 0 ? round(($completed / $total) * 100, 1) : 0.0;
        }

        return $result;
    }

    public function lessonComplete(User $user, Lesson $lesson): bool
    {
        $totalSteps = $lesson->steps()->where('published', true)->count();

        if ($totalSteps === 0) {
            return false;
        }

        $completedSteps = StepCompletion::where('user_id', $user->id)
            ->whereIn('step_id', $lesson->steps()->where('published', true)->pluck('id'))
            ->count();

        return $completedSteps === $totalSteps;
    }

    /**
     * @param  Collection<int, Lesson>  $lessons
     * @return array<int, bool>
     */
    public function lessonCompleteBatch(User $user, Collection $lessons): array
    {
        $lessonIds = $lessons->pluck('id')->all();

        $totalSteps = DB::table('steps')
            ->join('lessons', 'steps.lesson_id', '=', 'lessons.id')
            ->whereIn('steps.lesson_id', $lessonIds)
            ->where('steps.published', true)
            ->selectRaw('steps.lesson_id, count(*) as total')
            ->groupBy('steps.lesson_id')
            ->pluck('total', 'lesson_id');

        $completedSteps = DB::table('step_completions')
            ->join('steps', 'step_completions.step_id', '=', 'steps.id')
            ->where('step_completions.user_id', $user->id)
            ->whereIn('steps.lesson_id', $lessonIds)
            ->where('steps.published', true)
            ->selectRaw('steps.lesson_id, count(*) as total')
            ->groupBy('steps.lesson_id')
            ->pluck('total', 'lesson_id');

        $result = [];
        foreach ($lessons as $lesson) {
            $total = (int) ($totalSteps[$lesson->id] ?? 0);
            $completed = (int) ($completedSteps[$lesson->id] ?? 0);
            $result[$lesson->id] = $total > 0 && $completed === $total;
        }

        return $result;
    }

    /**
     * @param  Collection<int, Step>  $steps
     * @return array<int, bool>
     */
    public function stepCompleteBatch(User $user, Collection $steps): array
    {
        $stepIds = $steps->pluck('id')->all();

        $completedIds = DB::table('step_completions')
            ->where('user_id', $user->id)
            ->whereIn('step_id', $stepIds)
            ->pluck('step_id')
            ->all();

        $result = [];
        foreach ($steps as $step) {
            $result[$step->id] = in_array($step->id, $completedIds, true);
        }

        return $result;
    }
}
