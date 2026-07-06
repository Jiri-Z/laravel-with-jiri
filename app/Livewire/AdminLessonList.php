<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Concerns\ManagesOrdering;
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
    use ManagesOrdering;
    use WithPagination;

    public Course $course;

    #[Url(as: 'q')]
    public string $search = '';

    public function mount(Course $course): void
    {
        $this->authorize('view', $course);
        $this->authorize('viewAny', Lesson::class);
        $this->course = $course;
    }

    public function delete(int $lessonId): void
    {
        $this->deleteItem(Lesson::findOrFail($lessonId));
    }

    public function moveUp(int $lessonId): void
    {
        $this->moveItemUp(Lesson::findOrFail($lessonId), 'course_id');
    }

    public function moveDown(int $lessonId): void
    {
        $this->moveItemDown(Lesson::findOrFail($lessonId), 'course_id');
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
