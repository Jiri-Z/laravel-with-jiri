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
                $decoded = json_decode((string) $step->quiz_content, true);

                if (is_array($decoded)) {
                    $this->questions = [];
                    foreach ($decoded as $q) {
                        if (! is_array($q)) {
                            continue;
                        }
                        $rawOptions = is_array($q['options'] ?? null) ? $q['options'] : [];
                        $options = [];
                        foreach ($rawOptions as $opt) {
                            $options[] = is_string($opt) ? $opt : '';
                        }
                        $this->questions[] = [
                            'type' => is_string($q['type'] ?? null) ? $q['type'] : 'single',
                            'question' => is_string($q['question'] ?? null) ? $q['question'] : '',
                            'options' => $options,
                            'answer' => is_int($q['answer'] ?? null) ? $q['answer'] : 0,
                            'explanation' => is_string($q['explanation'] ?? null) ? $q['explanation'] : '',
                            'difficulty' => is_string($q['difficulty'] ?? null) ? $q['difficulty'] : 'easy',
                            'topic' => is_string($q['topic'] ?? null) ? $q['topic'] : 'general',
                        ];
                    }
                } else {
                    $this->questions = [];
                }
            } else {
                $this->content = $step->reading_content ?? '';
            }
        } else {
            $this->authorize('create', Step::class);
            $maxOrder = $this->lesson->steps()->max('order');
            $this->order = (is_numeric($maxOrder) ? (int) $maxOrder : -1) + 1;
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
                'questions.*.answer' => 'required',
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
            'title' => $this->title,
            'type' => $this->type,
            'order' => $this->order,
        ];

        $data[match ($this->type) {
            StepType::Coding->value => 'coding_content',
            StepType::Quiz->value => 'quiz_content',
            default => 'reading_content',
        }] = match ($this->type) {
            StepType::Coding->value => json_encode([
                'prompt' => $this->prompt,
                'initial_code' => $this->initialCode,
                'test_code' => $this->testCode,
                'expected_output' => $this->expectedOutput,
            ]),
            StepType::Quiz->value => json_encode($this->questions),
            default => $this->content,
        };

        if ($this->step) {
            $this->step->update($data);
        } else {
            Step::create(array_merge($data, ['lesson_id' => $this->lesson->id]));
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
        array_splice($this->questions, $index, 1);
    }

    public function addOption(int $questionIndex): void
    {
        $this->questions[$questionIndex]['options'][] = '';
    }

    public function removeOption(int $questionIndex, int $optionIndex): void
    {
        $options = $this->questions[$questionIndex]['options'];
        unset($options[$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($options);
    }

    public function render(): View
    {
        return view('livewire.admin-step-form');
    }
}
