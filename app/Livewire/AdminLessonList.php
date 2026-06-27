<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminLessonList extends Component
{
    public Course $course;

    public function mount(Course $course): void
    {
        $this->authorize('viewAny', Lesson::class);
        $this->course = $course->load('lessons');
    }

    public function delete(int $lessonId): void
    {
        $lesson = Lesson::findOrFail($lessonId);
        $this->authorize('delete', $lesson);
        $lesson->delete();
    }

    public function render(): View
    {
        return view('livewire.admin-lesson-list', [
            'lessons' => Lesson::where('course_id', $this->course->id)->ordered()->get(),
        ]);
    }
}
