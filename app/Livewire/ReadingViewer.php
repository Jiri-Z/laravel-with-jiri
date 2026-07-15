<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\StepType;
use App\Livewire\Concerns\EnsuresEnrollment;
use App\Livewire\Concerns\ValidatesStepContext;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ReadingViewer extends Component
{
    use EnsuresEnrollment;
    use ValidatesStepContext;

    public Course $course;

    public Lesson $lesson;

    public Step $step;

    public function mount(Course $course, Lesson $lesson, Step $step): void
    {
        $user = auth()->user();
        abort_unless($user !== null, 403);

        abort_unless($step->type === StepType::Reading, 404);
        abort_unless($step->isAccessibleBy($user), 404);

        $this->ensureEnrolled($course);
        $this->ensureContextIsValid($course, $lesson, $step);

        $this->course = $course;
        $this->lesson = $lesson;
        $this->step = $step;
    }

    public function render(): View
    {
        return view('livewire.reading-viewer');
    }
}
