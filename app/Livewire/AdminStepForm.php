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

    /** @var list<array{type: string, question: string, options: list<string>, answer: int, explanation: string, difficulty: string, topic: string}> */
    public array $questions = [];

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

            /** @var StepType $stepType */
            $stepType = $step->type;
            $this->type = $stepType->value;
            $this->order = $step->order;

            if ($stepType === StepType::Coding) {
                $data = $step->getCodingData();
                $this->prompt = $data['prompt'];
                $this->initialCode = $data['initial_code'];
                $this->testCode = $data['test_code'];
                $this->expectedOutput = $data['expected_output'];
            } elseif ($stepType === StepType::Quiz) {
                $this->questions = json_decode($step->content, true) ?? [];
            } else {
                $this->content = $step->content;
            }
        } else {
            $this->authorize('create', Step::class);
            $this->order = ($this->lesson->steps()->max('order') ?? -1) + 1;
        }
    }

    /** @return array<string, list<string>|string> */
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

        if ($this->type === StepType::Quiz->value) {
            return $base + [
                'questions' => 'required|array|min:1',
                'questions.*.type' => 'required|in:single,multiple',
                'questions.*.question' => 'required|string',
                'questions.*.options' => 'required|array|min:2',
                'questions.*.options.*' => 'required|string',
                'questions.*.answer' => 'required|integer|min:0',
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

        $content = match ($this->type) {
            StepType::Coding->value => json_encode([
                'prompt' => $this->prompt,
                'initial_code' => $this->initialCode,
                'test_code' => $this->testCode,
                'expected_output' => $this->expectedOutput,
            ]),
            StepType::Quiz->value => json_encode($this->questions),
            default => $this->content,
        };

        $data = [
            'lesson_id' => $this->lesson->id,
            'title' => $this->title,
            'type' => $this->type,
            'content' => $content,
            'order' => $this->order,
        ];

        if ($this->step) {
            $this->step->update($data);
        } else {
            Step::create($data);
        }

        $this->redirect(route('admin.steps.index', [$this->course, $this->lesson]), navigate: true);
    }

    public function addQuestion(): void
    {
        $this->questions[] = [
            'type' => 'single',
            'question' => '',
            'options' => ['', ''],
            'answer' => 0,
            'explanation' => '',
            'difficulty' => 'easy',
            'topic' => 'general',
        ];
    }

    public function removeQuestion(int $index): void
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function addOption(int $questionIndex): void
    {
        $this->questions[$questionIndex]['options'][] = '';
    }

    public function removeOption(int $questionIndex, int $optionIndex): void
    {
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);
    }

    public function render(): View
    {
        return view('livewire.admin-step-form');
    }
}
