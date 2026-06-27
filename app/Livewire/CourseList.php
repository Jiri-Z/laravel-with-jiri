<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CourseList extends Component
{
    public function render(): View
    {
        return view('livewire.course-list', [
            'courses' => Course::query()->withCount('lessons')->published()->ordered()->get(),
        ]);
    }
}
