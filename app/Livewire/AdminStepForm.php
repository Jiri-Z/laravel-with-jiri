<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\StepType;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminStepForm extends Component
{
    public Course $course;

    public Lesson $lesson;

    public ?Step $step = null;

    public string $title = '';

    public string $type = 'reading';

    public string $content = '';

    public int $order = 0;

    public function mount(Course $course, Lesson $lesson, ?Step $step = null): void
    {
        $this->course = $course;
        $this->lesson = $lesson;
        $this->step = $step;

        if ($step) {
            $this->authorize('update', $step);
            $this->title = $step->title;
            $this->type = $step->type->value;
            $this->content = $step->content;
            $this->order = $step->order;
        } else {
            $this->authorize('create', Step::class);
        }
    }

    /** @return array<string, string> */
    public function validationRules(): array
    {
        return [
            'title' => 'required|max:255',
            'type' => 'required|in:'.implode(',', array_map(fn (StepType $t) => $t->value, StepType::cases())),
            'content' => 'required',
            'order' => 'required|integer|min:0',
        ];
    }

    public function save(): void
    {
        if ($this->step) {
            $this->authorize('update', $this->step);
        } else {
            $this->authorize('create', Step::class);
        }

        $this->validate($this->validationRules());

        $data = [
            'lesson_id' => $this->lesson->id,
            'title' => $this->title,
            'type' => $this->type,
            'content' => $this->content,
            'order' => $this->order,
        ];

        if ($this->step) {
            $this->step->update($data);
        } else {
            Step::create($data);
        }

        $this->redirect(route('admin.steps.index', [$this->course, $this->lesson]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin-step-form');
    }
}
