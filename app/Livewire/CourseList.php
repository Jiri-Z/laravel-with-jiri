<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\EnrollInCourse;
use App\Exceptions\CourseNotPublishedException;
use App\Models\Course;
use App\Services\ProgressService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CourseList extends Component
{
    /** @var array<int, bool> */
    public array $enrolled = [];

    public function mount(): void
    {
        $user = auth()->user();
        if ($user) {
            /** @var array<int, int> $enrolledCourseIds */
            $enrolledCourseIds = $user->enrollments()->pluck('course_id')->map(fn ($id): int => is_numeric($id) ? (int) $id : 0)->all();
            $this->enrolled = array_fill_keys($enrolledCourseIds, true);
        }
    }

    public function enroll(int $courseId, EnrollInCourse $action): void
    {
        $user = auth()->user();
        if ($user === null) {
            return;
        }

        try {
            $course = Course::published()->findOrFail($courseId);
            $action->handle($user, $course);
        } catch (CourseNotPublishedException) {
            session()->flash('error', __('exceptions.course_not_published'));
            $this->redirect(route('courses.index'), navigate: true);

            return;
        }

        $this->enrolled[$courseId] = true;

        $this->redirect(route('courses.show', $course->slug), navigate: true);
    }

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
