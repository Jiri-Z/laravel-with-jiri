<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class LessonDetail extends Component
{
    public Course $course;
    public Lesson $lesson;

    public function mount(Course $course, Lesson $lesson): void
    {
        abort_unless($course->published, 404);
        abort_unless($lesson->published && $lesson->course_id === $course->id, 404);

        $lesson->load(['steps' => fn ($q) => $q->orderBy('order')]);

        $this->course = $course;
        $this->lesson = $lesson;
    }

    public function render(): View
    {
        return view('livewire.lesson-detail');
    }
}
