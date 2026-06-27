<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminCourseForm extends Component
{
    public ?Course $course = null;

    public string $title = '';

    public string $slug = '';

    public string $description = '';

    public bool $published = false;

    public int $order = 0;

    public function mount(?Course $course = null): void
    {
        $this->course = $course;

        if ($course) {
            $this->authorize('update', $course);
            $this->title = $course->title;
            $this->slug = $course->slug;
            $this->description = $course->description ?? '';
            $this->published = $course->published;
            $this->order = $course->order;
        } else {
            $this->authorize('create', Course::class);
        }
    }

    /** @return array<string, string> */
    public function validationRules(): array
    {
        $courseId = $this->course?->id;

        return [
            'title' => 'required|max:255',
            'slug' => 'required|max:255|unique:courses,slug,'.($courseId ?? 'NULL'),
            'description' => 'nullable',
            'published' => 'boolean',
            'order' => 'required|integer|min:0',
        ];
    }

    public function save(): void
    {
        if ($this->course) {
            $this->authorize('update', $this->course);
        } else {
            $this->authorize('create', Course::class);
        }

        $this->validate($this->validationRules());

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description ?: null,
            'published' => $this->published,
            'order' => $this->order,
        ];

        if ($this->course) {
            $this->course->update($data);
        } else {
            Course::create($data);
        }

        $this->redirect(route('admin.courses.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin-course-form');
    }
}
