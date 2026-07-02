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

        return view('livewire.dashboard', compact('courses', 'progressData', 'totalCompleted', 'recentCompletions', 'resumeStep'));
    }

    private function findResumeStep(Collection $courses): ?Step
    {
        $completedIds = StepCompletion::where('user_id', auth()->id())
            ->pluck('step_id');

        foreach ($courses as $course) {
            foreach ($course->lessons as $lesson) {
                foreach ($lesson->steps as $step) {
                    if (! $completedIds->contains($step->id)) {
                        return $step;
                    }
                }
            }
        }

        return null;
    }
}
