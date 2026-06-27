<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminStepList extends Component
{
    public Course $course;

    public Lesson $lesson;

    public function mount(Course $course, Lesson $lesson): void
    {
        $this->authorize('viewAny', Step::class);
        $this->course = $course;
        $this->lesson = $lesson;
    }

    public function delete(int $stepId): void
    {
        $step = Step::findOrFail($stepId);
        $this->authorize('delete', $step);
        $step->delete();
    }

    public function render(): View
    {
        return view('livewire.admin-step-list', [
            'steps' => $this->lesson->steps()->ordered()->get(),
        ]);
    }
}
