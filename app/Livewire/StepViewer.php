<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\MarkStepComplete;
use App\Livewire\Concerns\ValidatesStepContext;
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
    use ValidatesStepContext;

    public Course $course;

    public Lesson $lesson;

    public Step $step;

    public bool $completed = false;

    public function mount(Course $course, Lesson $lesson, Step $step): void
    {
        $this->ensureContextIsValid($course, $lesson, $step);

        $this->course = $course;
        $this->lesson = $lesson;
        $this->step = $step;
        $this->completed = StepCompletion::where('user_id', auth()->id())
            ->where('step_id', $step->id)
            ->exists();
    }

    public function complete(): void
    {
        $this->ensureCurrentContextIsValid();

        (new MarkStepComplete)->handle(auth()->user(), $this->step);

        $this->completed = true;
    }

    public function render(): View
    {
        return view('livewire.step-viewer');
    }
}
