<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StepViewer extends Component
{
    public Course $course;

    public Lesson $lesson;

    public Step $step;

    public bool $completed = false;

    public function mount(Course $course, Lesson $lesson, Step $step): void
    {
        abort_unless($course->published, 404);
        abort_unless($lesson->published && $lesson->course_id === $course->id, 404);
        abort_unless($step->lesson_id === $lesson->id, 404);

        $this->course = $course;
        $this->lesson = $lesson;
        $this->step = $step;
        $this->completed = StepCompletion::where('user_id', auth()->id())
            ->where('step_id', $step->id)
            ->exists();
    }

    public function complete(): void
    {
        if ($this->completed) {
            return;
        }

        StepCompletion::create([
            'user_id' => auth()->id(),
            'step_id' => $this->step->id,
            'completed_at' => now(),
        ]);

        $this->completed = true;
    }

    public function render(): View
    {
        return view('livewire.step-viewer');
    }
}
