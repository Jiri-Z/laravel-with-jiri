<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\StepType;
use App\Models\Step;
use App\Models\StepAnswer;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuizViewer extends Component
{
    public Step $step;

    public bool $submitted = false;

    public bool $isCorrect = false;

    public int|string|null $selectedAnswer = null;

    public array $selectedAnswers = [];

    public string $textAnswer = '';

    public function mount(): void
    {
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
        if ($this->submitted) {
            return;
        }

        $content = $this->step->getContentAsArray();

        $isCorrect = match ($this->step->type) {
            StepType::QuizSingle => $this->selectedAnswer == ($content['correct_answer'] ?? null),
            StepType::QuizMultiple => ! array_diff(
                $this->selectedAnswers,
                $content['correct_answers'] ?? []
            ) && ! array_diff(
                $content['correct_answers'] ?? [],
                $this->selectedAnswers
            ),
            StepType::QuizText => strcasecmp(
                trim($this->textAnswer),
                trim($content['correct_answer'] ?? '')
            ) === 0,
            default => false,
        };

        $answer = match ($this->step->type) {
            StepType::QuizSingle => (string) $this->selectedAnswer,
            StepType::QuizMultiple => json_encode($this->selectedAnswers),
            StepType::QuizText => $this->textAnswer,
            default => '',
        };

        StepAnswer::create([
            'user_id' => auth()->id(),
            'step_id' => $this->step->id,
            'answer' => $answer,
            'is_correct' => $isCorrect,
            'created_at' => now(),
        ]);

        $this->submitted = true;
        $this->isCorrect = $isCorrect;
    }

    public function render(): View
    {
        return view('livewire.quiz-viewer');
    }
}
