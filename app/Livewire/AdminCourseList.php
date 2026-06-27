<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminCourseList extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

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
        /** @var Course $course */
        $course = Course::findOrFail($courseId);
        $this->authorize('update', $course);
        $previous = Course::where('order', '<', $course->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previous === null) {
            return;
        }

        $courseOrder = $course->order;
        $previousOrder = $previous->order;

        DB::transaction(function () use ($course, $courseOrder, $previous, $previousOrder): void {
            $previous->update(['order' => -1]);
            $course->update(['order' => $previousOrder]);
            $previous->update(['order' => $courseOrder]);
        });
    }

    public function moveDown(int $courseId): void
    {
        /** @var Course $course */
        $course = Course::findOrFail($courseId);
        $this->authorize('update', $course);
        $next = Course::where('order', '>', $course->order)
            ->orderBy('order')
            ->first();

        if ($next === null) {
            return;
        }

        $courseOrder = $course->order;
        $nextOrder = $next->order;

        DB::transaction(function () use ($course, $courseOrder, $next, $nextOrder): void {
            $next->update(['order' => -1]);
            $course->update(['order' => $nextOrder]);
            $next->update(['order' => $courseOrder]);
        });
    }

    public function render(): View
    {
        $query = Course::ordered();

        if (auth()->user()->isInstructor()) {
            $query->where('user_id', auth()->id());
        }

        if ($this->search !== '') {
            $query->search($this->search);
        }

        return view('livewire.admin-course-list', [
            'courses' => $query->paginate(10),
        ]);
    }
}
