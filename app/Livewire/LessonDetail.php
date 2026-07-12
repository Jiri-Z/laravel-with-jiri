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

        $stepIds = $lesson->steps->pluck('id');

        $completedStepIds = StepCompletion::where('user_id', auth()->id())
            ->whereIn('step_id', $stepIds)
            ->whereNotNull('completed_at')
            ->pluck('step_id')
            ->all();

        $unlockedStepIds = StepCompletion::where('user_id', auth()->id())
            ->whereIn('step_id', $stepIds)
            ->whereNotNull('unlocked_at')
            ->pluck('step_id')
            ->all();

        $completion = [];
        $locked = [];
        foreach ($lesson->steps as $step) {
            $completion[$step->id] = in_array($step->id, $completedStepIds, true);
            $locked[$step->id] = $step->order !== 1 && ! in_array($step->id, $unlockedStepIds, true);
        }
        $this->stepCompletion = $completion;
        $this->stepLocked = $locked;
    }

    public function render(): View
    {
        return view('livewire.lesson-detail');
    }
}
