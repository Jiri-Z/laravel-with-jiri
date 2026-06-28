<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\SubmitQuizAnswer;
use App\Enums\StepType;
use App\Livewire\Concerns\ValidatesStepContext;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepAnswer;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuizViewer extends Component
{
    use ValidatesStepContext;

    public Course $course;

    public Lesson $lesson;

    public Step $step;

    public bool $submitted = false;

    public bool $isCorrect = false;

    public int|string|null $selectedAnswer = null;

    /** @var array<int, int|string> */
    public array $selectedAnswers = [];

    public string $textAnswer = '';

    /** @var array<int, int|string|array<int, int|string>|null> */
    public array $answers = [];

    public function mount(Course $course, Lesson $lesson, Step $step): void
    {
        $this->ensureContextIsValid($course, $lesson, $step);

        $this->course = $course;
        $this->lesson = $lesson;
        $this->step = $step;

        if ($step->type === StepType::Quiz) {
            $existing = StepAnswer::where('user_id', auth()->id())
                ->where('step_id', $this->step->id)
                ->get()
                ->keyBy('question_index');

            if ($existing->isNotEmpty()) {
                $this->submitted = true;

                $allCorrect = true;
                foreach ($existing as $entry) {
                    if (! $entry->is_correct) {
                        $allCorrect = false;
                    }
                }
                $this->isCorrect = $allCorrect;
            }

            return;
        }

        $existing = StepAnswer::where('user_id', auth()->id())
            ->where('step_id', $this->step->id)
            ->first();

        if ($existing) {
            $this->submitted = true;
            $this->isCorrect = $existing->is_correct;
        }
    }

    public function submit(): void
    {
        $this->ensureCurrentContextIsValid();

        if ($this->submitted) {
            return;
        }

        if ($this->step->type === StepType::Quiz) {
            $this->submitQuizType();

            return;
        }

        $result = (new SubmitQuizAnswer)->handle(
            auth()->user(),
            $this->step,
            match ($this->step->type) {
                StepType::QuizSingle => $this->selectedAnswer,
                StepType::QuizMultiple => $this->selectedAnswers,
                StepType::QuizText => $this->textAnswer,
                default => null,
            },
        );

        $this->submitted = true;
        $this->isCorrect = $result->isCorrect;
    }

    private function submitQuizType(): void
    {
        /** @var array<int, array<string, mixed>> $questions */
        $questions = $this->step->getContentAsArray();
        $allCorrect = true;

        foreach ($questions as $index => $question) {
            $answer = $this->answers[$index] ?? null;

            $result = (new SubmitQuizAnswer)->handle(
                auth()->user(),
                $this->step,
                $answer,
                questionIndex: $index,
            );

            if (! $result->isCorrect) {
                $allCorrect = false;
            }
        }

        $this->submitted = true;
        $this->isCorrect = $allCorrect;
    }

    public function render(): View
    {
        return view('livewire.quiz-viewer');
    }
}
