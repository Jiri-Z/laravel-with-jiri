<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProgressService
{
    public function courseProgress(User $user, Course $course): float
    {
        $results = $this->courseProgressBatch($user, new Collection([$course]));

        return $results[$course->id] ?? 0.0;
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
            $rawTotal = $totalSteps[$course->id] ?? null;
            $rawCompleted = $completedSteps[$course->id] ?? null;
            $total = is_numeric($rawTotal) ? (int) $rawTotal : 0;
            $completed = is_numeric($rawCompleted) ? (int) $rawCompleted : 0;
            $result[$course->id] = $total > 0 ? round(($completed / $total) * 100, 1) : 0.0;
        }

        return $result;
    }

    public function lessonComplete(User $user, Lesson $lesson): bool
    {
        $results = $this->lessonCompleteBatch($user, new Collection([$lesson]));

        return $results[$lesson->id] ?? false;
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
            $rawTotal = $totalSteps[$lesson->id] ?? null;
            $rawCompleted = $completedSteps[$lesson->id] ?? null;
            $total = is_numeric($rawTotal) ? (int) $rawTotal : 0;
            $completed = is_numeric($rawCompleted) ? (int) $rawCompleted : 0;
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
