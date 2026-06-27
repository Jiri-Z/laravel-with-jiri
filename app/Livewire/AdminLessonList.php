<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminLessonList extends Component
{
    use WithPagination;

    public Course $course;

    #[Url(as: 'q')]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function mount(Course $course): void
    {
        $this->authorize('view', $course);
        $this->authorize('viewAny', Lesson::class);
        $this->course = $course->load('lessons');
    }

    public function delete(int $lessonId): void
    {
        $lesson = Lesson::findOrFail($lessonId);
        $this->authorize('delete', $lesson);
        $lesson->delete();
    }

    public function moveUp(int $lessonId): void
    {
        /** @var Lesson $lesson */
        $lesson = Lesson::findOrFail($lessonId);
        $this->authorize('update', $lesson);
        $previous = Lesson::where('course_id', $this->course->id)
            ->where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previous === null) {
            return;
        }

        $lessonOrder = $lesson->order;
        $previousOrder = $previous->order;

        $previous->update(['order' => -1]);
        $lesson->update(['order' => $previousOrder]);
        $previous->update(['order' => $lessonOrder]);
    }

    public function moveDown(int $lessonId): void
    {
        /** @var Lesson $lesson */
        $lesson = Lesson::findOrFail($lessonId);
        $this->authorize('update', $lesson);
        $next = Lesson::where('course_id', $this->course->id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order')
            ->first();

        if ($next === null) {
            return;
        }

        $lessonOrder = $lesson->order;
        $nextOrder = $next->order;

        $next->update(['order' => -1]);
        $lesson->update(['order' => $nextOrder]);
        $next->update(['order' => $lessonOrder]);
    }

    public function render(): View
    {
        $query = Lesson::where('course_id', $this->course->id);

        if ($this->search !== '') {
            $query->search($this->search);
        }

        return view('livewire.admin-lesson-list', [
            'lessons' => $query->ordered()->paginate(10),
        ]);
    }
}
