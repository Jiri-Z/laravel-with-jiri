<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\MarkStepComplete;
use App\Exceptions\NotEnrolledException;
use App\Exceptions\StepNotAccessibleException;
use App\Livewire\Concerns\EnsuresEnrollment;
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
    use EnsuresEnrollment;
    use ValidatesStepContext;

    public Course $course;

    public Lesson $lesson;

    public Step $step;

    public bool $completed = false;

    public function mount(Course $course, Lesson $lesson, Step $step): void
    {
        $user = auth()->user();
        abort_unless($user !== null, 403);

        $this->ensureContextIsValid($course, $lesson, $step);
        $this->ensureEnrolled($course);

        if (! $step->isAccessibleBy($user)) {
            session()->flash('error', __('steps.complete_previous'));
            $this->redirect(route('lessons.show', [$course->slug, $lesson->slug]), navigate: true);

            return;
        }

        $this->course = $course;
        $this->lesson = $lesson;
        $this->step = $step;
        $this->completed = StepCompletion::where('user_id', $user->id)
            ->where('step_id', $step->id)
            ->whereNotNull('completed_at')
            ->exists();
    }

    public function toggleComplete(): void
    {
        $user = auth()->user();
        if ($user === null) {
            return;
        }

        if ($this->completed) {
            StepCompletion::where('user_id', $user->id)
                ->where('step_id', $this->step->id)
                ->update(['completed_at' => null]);

            $this->completed = false;

            return;
        }

        try {
            (new MarkStepComplete)->handle($user, $this->step);
        } catch (NotEnrolledException) {
            $this->redirect(route('courses.index'), navigate: true);

            return;
        } catch (StepNotAccessibleException) {
            session()->flash('error', __('steps.complete_previous'));
            $this->redirect(route('lessons.show', [$this->course->slug, $this->lesson->slug]), navigate: true);

            return;
        }

        $this->completed = true;
    }

    public function render(): View
    {
        return view('livewire.step-viewer');
    }
}
