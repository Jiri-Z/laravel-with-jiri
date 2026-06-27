<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminLessonForm extends Component
{
    public Course $course;

    public ?Lesson $lesson = null;

    public string $title = '';

    public string $slug = '';

    public string $description = '';

    public bool $published = false;

    public int $order = 0;

    public function mount(Course $course, ?Lesson $lesson = null): void
    {
        $this->course = $course;
        $this->lesson = $lesson;

        if ($lesson) {
            $this->authorize('update', $lesson);
            $this->title = $lesson->title;
            $this->slug = $lesson->slug;
            $this->description = $lesson->description ?? '';
            $this->published = $lesson->published;
            $this->order = $lesson->order;
        } else {
            $this->authorize('create', Lesson::class);
        }
    }

    public function save(): void
    {
        if ($this->lesson) {
            $this->authorize('update', $this->lesson);
        } else {
            $this->authorize('create', Lesson::class);
        }

        $this->validate([
            'title' => 'required|max:255',
            'slug' => 'required|max:255|unique:lessons,slug,'.($this->lesson?->id ?? 'NULL').',id,course_id,'.$this->course->id,
            'description' => 'nullable',
            'published' => 'boolean',
            'order' => 'required|integer|min:0',
        ]);

        $data = [
            'course_id' => $this->course->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description ?: null,
            'published' => $this->published,
            'order' => $this->order,
        ];

        if ($this->lesson) {
            $this->lesson->update($data);
        } else {
            Lesson::create($data);
        }

        $this->redirect(route('admin.lessons.index', $this->course), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin-lesson-form');
    }
}
