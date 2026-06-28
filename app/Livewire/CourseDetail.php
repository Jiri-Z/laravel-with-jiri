<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Concerns\EnsuresEnrollment;
use App\Models\Course;
use App\Services\ProgressService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CourseDetail extends Component
{
    use EnsuresEnrollment;

    public Course $course;

    /** @var array<int, bool> */
    public array $lessonCompletion = [];

    public float $courseProgress = 0.0;

    public function mount(Course $course, ProgressService $progress): void
    {
        abort_unless($course->published, 404);

        $this->ensureEnrolled($course);

        $course->load(['lessons' => fn ($q) => $q->published()->ordered()]);

        $this->course = $course;
        $this->courseProgress = $progress->courseProgress(auth()->user(), $course);
        $this->lessonCompletion = $progress->lessonCompleteBatch(auth()->user(), $course->lessons);
    }

    public function render(): View
    {
        return view('livewire.course-detail');
    }
}
