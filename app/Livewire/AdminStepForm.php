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

    public string $prompt = '';

    public string $initialCode = '';

    public string $testCode = '';

    public string $expectedOutput = '';

    public int $order = 0;

    public function mount(Course $course, Lesson $lesson, ?Step $step = null): void
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        if ($step && $step->lesson_id !== $lesson->id) {
            abort(404);
        }

        $this->authorize('view', $course);
        $this->course = $course;
        $this->lesson = $lesson;
        $this->step = $step;

        if ($step) {
            $this->authorize('update', $step);
            $this->title = $step->title;
            $this->type = $step->type->value;
            $this->order = $step->order;

            if ($step->type === StepType::Coding) {
                $data = $step->getCodingData();
                $this->prompt = $data['prompt'];
                $this->initialCode = $data['initial_code'];
                $this->testCode = $data['test_code'];
                $this->expectedOutput = $data['expected_output'];
            } else {
                $this->content = $step->content;
            }
        } else {
            $this->authorize('create', Step::class);
            $this->order = ($this->lesson->steps()->max('order') ?? -1) + 1;
        }
    }

    public function validationRules(): array
    {
        $base = [
            'title' => 'required|max:255',
            'type' => 'required|in:reading,quiz,coding',
            'order' => 'required|integer|min:0',
        ];

        if ($this->type === StepType::Coding->value) {
            return $base + [
                'prompt' => 'required|string',
                'initialCode' => 'nullable|string',
                'testCode' => 'nullable|string',
                'expectedOutput' => 'nullable|string',
            ];
        }

        return $base + ['content' => 'required'];
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
            'content' => $this->type === StepType::Coding->value
                ? json_encode([
                    'prompt' => $this->prompt,
                    'initial_code' => $this->initialCode,
                    'test_code' => $this->testCode,
                    'expected_output' => $this->expectedOutput,
                ])
                : $this->content,
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
