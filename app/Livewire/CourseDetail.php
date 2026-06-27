<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CourseDetail extends Component
{
    public Course $course;

    public function mount(Course $course): void
    {
        abort_unless($course->published, 404);

        $course->load(['lessons' => fn ($q) => $q->published()->ordered()]);

        $this->course = $course;
    }

    public function render(): View
    {
        return view('livewire.course-detail');
    }
}
