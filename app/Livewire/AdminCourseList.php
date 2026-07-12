<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Concerns\ManagesOrdering;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminCourseList extends Component
{
    use ManagesOrdering;
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public function mount(): void
    {
        $this->authorize('viewAny', Course::class);
    }

    public function delete(int $courseId): void
    {
        $this->deleteItem(Course::findOrFail($courseId));
    }

    public function moveUp(int $courseId): void
    {
        $this->moveItemUp(Course::findOrFail($courseId));
    }

    public function moveDown(int $courseId): void
    {
        $this->moveItemDown(Course::findOrFail($courseId));
    }

    public function render(): View
    {
        $user = auth()->user();

        $query = Course::ordered();

        if ($user && $user->isInstructor()) {
            $query->where('user_id', $user->id);
        }

        if ($this->search !== '') {
            $query->search($this->search);
        }

        return view('livewire.admin-course-list', [
            'courses' => $query->paginate(10),
        ]);
    }
}
