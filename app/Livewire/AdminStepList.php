<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Concerns\ManagesOrdering;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminStepList extends Component
{
    use ManagesOrdering;
    use WithPagination;

    public Course $course;

    public Lesson $lesson;

    #[Url(as: 'q')]
    public string $search = '';

    public function mount(Course $course, Lesson $lesson): void
    {
        abort_unless($lesson->course_id === $course->id, 404);
        $this->authorize('view', $course);
        $this->authorize('viewAny', Step::class);
        $this->course = $course;
        $this->lesson = $lesson;
    }

    public function delete(int $stepId): void
    {
        $this->deleteItem(Step::findOrFail($stepId));
    }

    public function moveUp(int $stepId): void
    {
        $this->moveItemUp(Step::findOrFail($stepId), 'lesson_id');
    }

    public function moveDown(int $stepId): void
    {
        $this->moveItemDown(Step::findOrFail($stepId), 'lesson_id');
    }

    public function render(): View
    {
        $user = auth()->user();
        $query = Step::where('lesson_id', $this->lesson->id);

        if ($user !== null && $user->isInstructor()) {
            $query->ownedBy($user);
        }

        if ($this->search !== '') {
            $query->search($this->search);
        }

        return view('livewire.admin-step-list', [
            'steps' => $query->ordered()->paginate(10),
        ]);
    }
}
