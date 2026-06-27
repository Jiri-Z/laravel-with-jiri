<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminStepList extends Component
{
    use WithPagination;

    public Course $course;

    public Lesson $lesson;

    #[Url(as: 'q')]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function mount(Course $course, Lesson $lesson): void
    {
        $this->authorize('view', $course);
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
        /** @var Step $step */
        $step = Step::findOrFail($stepId);
        $this->authorize('update', $step);
        $previous = Step::where('lesson_id', $this->lesson->id)
            ->where('order', '<', $step->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previous === null) {
            return;
        }

        $stepOrder = $step->order;
        $previousOrder = $previous->order;

        DB::transaction(function () use ($previous, $previousOrder, $step, $stepOrder): void {
            $previous->update(['order' => -1]);
            $step->update(['order' => $previousOrder]);
            $previous->update(['order' => $stepOrder]);
        });
    }

    public function moveDown(int $stepId): void
    {
        /** @var Step $step */
        $step = Step::findOrFail($stepId);
        $this->authorize('update', $step);
        $next = Step::where('lesson_id', $this->lesson->id)
            ->where('order', '>', $step->order)
            ->orderBy('order')
            ->first();

        if ($next === null) {
            return;
        }

        $stepOrder = $step->order;
        $nextOrder = $next->order;

        DB::transaction(function () use ($next, $nextOrder, $step, $stepOrder): void {
            $next->update(['order' => -1]);
            $step->update(['order' => $nextOrder]);
            $next->update(['order' => $stepOrder]);
        });
    }

    public function render(): View
    {
        $query = Step::where('lesson_id', $this->lesson->id);

        if ($this->search !== '') {
            $query->search($this->search);
        }

        return view('livewire.admin-step-list', [
            'steps' => $query->ordered()->paginate(10),
        ]);
    }
}
