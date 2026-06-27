<?php

declare(strict_types=1);

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

    public function moveUp(int $stepId): void
    {
        $step = Step::findOrFail($stepId);
        $previous = Step::where('lesson_id', $this->lesson->id)
            ->where('order', '<', $step->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previous === null) {
            return;
        }

        $stepOrder = $step->order;
        $previousOrder = $previous->order;

        $previous->update(['order' => -1]);
        $step->update(['order' => $previousOrder]);
        $previous->update(['order' => $stepOrder]);
    }

    public function moveDown(int $stepId): void
    {
        $step = Step::findOrFail($stepId);
        $next = Step::where('lesson_id', $this->lesson->id)
            ->where('order', '>', $step->order)
            ->orderBy('order')
            ->first();

        if ($next === null) {
            return;
        }

        $stepOrder = $step->order;
        $nextOrder = $next->order;

        $next->update(['order' => -1]);
        $step->update(['order' => $nextOrder]);
        $next->update(['order' => $stepOrder]);
    }

    public function render(): View
    {
        return view('livewire.admin-step-list', [
            'steps' => Step::where('lesson_id', $this->lesson->id)->ordered()->get(),
        ]);
    }
}
