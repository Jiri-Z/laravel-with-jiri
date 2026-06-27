<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\SubmitQuizAnswer;
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

    /** @var array<int, int|string> */
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

    public function render(): View
    {
        return view('livewire.quiz-viewer');
    }
}
