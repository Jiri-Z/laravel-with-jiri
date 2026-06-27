<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Services\ProgressService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CourseDetail extends Component
{
    public Course $course;

    /** @var array<int, bool> */
    public array $lessonCompletion = [];

    public float $courseProgress = 0.0;

    public function mount(Course $course, ProgressService $progress): void
    {
        abort_unless($course->published, 404);

        $course->load(['lessons' => fn ($q) => $q->published()->ordered()]);

        $this->course = $course;
        $this->courseProgress = $progress->courseProgress(auth()->user(), $course);

        /** @var array<int, bool> $completion */
        $completion = [];
        foreach ($course->lessons as $lesson) {
            $completion[$lesson->id] = $progress->lessonComplete(auth()->user(), $lesson);
        }
        $this->lessonCompletion = $completion;
    }

    public function render(): View
    {
        return view('livewire.course-detail');
    }
}
