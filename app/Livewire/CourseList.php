<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Services\ProgressService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CourseList extends Component
{
    public function render(ProgressService $progress): View
    {
        $courses = Course::query()->withCount('lessons')->published()->ordered()->get();

        $user = auth()->user();

        $progressData = $user
            ? $progress->courseProgressBatch($user, $courses)
            : $courses->mapWithKeys(fn (Course $course) => [$course->id => 0.0]);

        return view('livewire.course-list', [
            'courses' => $courses,
            'progressData' => $progressData,
        ]);
    }
}
