<?php

namespace App\Livewire;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminCourseList extends Component
{
    public function mount(): void
    {
        $this->authorize('viewAny', Course::class);
    }

    public function delete(int $courseId): void
    {
        $course = Course::findOrFail($courseId);
        $this->authorize('delete', $course);
        $course->delete();
    }

    public function render(): View
    {
        return view('livewire.admin-course-list', [
            'courses' => Course::orderBy('order')->get(),
        ]);
    }
}
