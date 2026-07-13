<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Services\ProgressService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render(ProgressService $progress): View
    {
        $user = auth()->user();

        if ($user === null) {
            abort(403);
        }

        $courses = Course::published()->ordered()->withCount('lessons')
            ->whereHas('enrollments', fn ($q) => $q->where('user_id', $user->id))
            ->with([
                'lessons' => fn ($q) => $q->published()->ordered(),
                'lessons.steps' => fn ($q) => $q->where('published', true)->ordered(),
            ])
            ->get();
        $progressData = $progress->courseProgressBatch($user, $courses);

        $totalCompleted = StepCompletion::where('user_id', $user->id)->count();

        $recentCompletions = StepCompletion::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->with(['step.lesson.course'])
            ->get();

        $resumeStep = $this->findResumeStep($courses);

        return view('livewire.dashboard', [
            'courses' => $courses,
            'progressData' => $progressData,
            'totalCompleted' => $totalCompleted,
            'recentCompletions' => $recentCompletions,
            'resumeStep' => $resumeStep,
        ]);
    }

    /**
     * @param  Collection<int, Course>  $courses
     */
    private function findResumeStep(Collection $courses): ?Step
    {
        $courseIds = $courses->pluck('id');

        if ($courseIds->isEmpty()) {
            return null;
        }

        return Step::query()
            ->where('steps.published', true)
            ->whereHas('lesson', fn ($q) => $q
                ->where('published', true)
                ->whereIn('course_id', $courseIds)
            )
            ->whereDoesntHave('completions', fn ($q) => $q->where('user_id', auth()->id()))
            ->join('lessons', 'steps.lesson_id', '=', 'lessons.id')
            ->join('courses', 'lessons.course_id', '=', 'courses.id')
            ->orderBy('courses.order')
            ->orderBy('lessons.order')
            ->orderBy('steps.order')
            ->select('steps.*')
            ->with('lesson.course')
            ->first();
    }
}
