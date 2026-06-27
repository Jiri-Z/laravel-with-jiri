<?php

declare(strict_types=1);

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

    public function moveUp(int $courseId): void
    {
        $course = Course::findOrFail($courseId);
        $previous = Course::where('order', '<', $course->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previous === null) {
            return;
        }

        $courseOrder = $course->order;
        $previousOrder = $previous->order;

        $previous->update(['order' => -1]);
        $course->update(['order' => $previousOrder]);
        $previous->update(['order' => $courseOrder]);
    }

    public function moveDown(int $courseId): void
    {
        $course = Course::findOrFail($courseId);
        $next = Course::where('order', '>', $course->order)
            ->orderBy('order')
            ->first();

        if ($next === null) {
            return;
        }

        $courseOrder = $course->order;
        $nextOrder = $next->order;

        $next->update(['order' => -1]);
        $course->update(['order' => $nextOrder]);
        $next->update(['order' => $courseOrder]);
    }

    public function render(): View
    {
        return view('livewire.admin-course-list', [
            'courses' => Course::ordered()->get(),
        ]);
    }
}
