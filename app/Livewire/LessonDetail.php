<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Concerns\EnsuresEnrollment;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\StepCompletion;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class LessonDetail extends Component
{
    use EnsuresEnrollment;

    public Course $course;

    public Lesson $lesson;

    /** @var array<int, bool> */
    public array $stepCompletion = [];

    /** @var array<int, bool> */
    public array $stepLocked = [];

    public function mount(Course $course, Lesson $lesson): void
    {
        abort_unless($course->published, 404);
        abort_unless($lesson->published && $lesson->course_id === $course->id, 404);

        $this->ensureEnrolled($course);

        $lesson->load(['steps' => fn ($q) => $q->ordered()]);

        $this->course = $course;
        $this->lesson = $lesson;

        $completedStepIds = StepCompletion::where('user_id', auth()->id())
            ->whereIn('step_id', $lesson->steps->pluck('id'))
            ->pluck('step_id')
            ->all();

        $completion = [];
        $locked = [];
        $previousCompleted = true;
        foreach ($lesson->steps as $step) {
            $stepDone = in_array($step->id, $completedStepIds, true);
            $completion[$step->id] = $stepDone;
            $locked[$step->id] = ! $previousCompleted;
            if (! $stepDone) {
                $previousCompleted = false;
            }
        }
        $this->stepCompletion = $completion;
        $this->stepLocked = $locked;
    }

    public function render(): View
    {
        return view('livewire.lesson-detail');
    }
}
