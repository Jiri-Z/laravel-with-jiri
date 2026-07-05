<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\TriviaAttempt;
use App\Models\TriviaQuestion;
use App\Services\AnswerChecker;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TriviaQuiz extends Component
{
    public string $screen = 'welcome';

    /** @var array<int, string> */
    public array $selectedTopics = [];

    /** @var array<int, array<string, mixed>> */
    public array $questions = [];

    public int $currentIndex = 0;

    public bool $submitted = false;

    public ?int $attemptId = null;

    /** @var array<string, string|array<int, string>|null> */
    public array $userAnswers = [];

    public function mount(): void
    {
        $this->selectedTopics = $this->allTopics->toArray();
    }

    /** @return Collection<int, string> */
    #[Computed]
    public function allTopics(): Collection
    {
        return TriviaQuestion::query()
            ->select('topic')
            ->where('locale', app()->getLocale())
            ->distinct()
            ->orderBy('topic')
            ->pluck('topic');
    }

    /** @return Collection<int, array<string, mixed>> */
    #[Computed]
    public function topicQuestionCounts(): Collection
    {
        return TriviaQuestion::query()
            ->select('topic')
            ->selectRaw('count(*) as count')
            ->where('locale', app()->getLocale())
            ->groupBy('topic')
            ->orderBy('topic')
            ->get();
    }

    public function start(): void
    {
        if (empty($this->selectedTopics)) {
            return;
        }

        $pool = TriviaQuestion::where('locale', app()->getLocale())
            ->whereIn('topic', $this->selectedTopics)
            ->get();

        if ($pool->isEmpty()) {
            return;
        }

        $this->questions = $this->selectQuestions($pool, min(20, $pool->count()));
        $this->currentIndex = 0;
        $this->submitted = false;
        $this->userAnswers = [];
        $this->attemptId = null;
        $this->screen = 'quiz';
    }

    public function submit(): void
    {
        if ($this->submitted) {
            return;
        }

        $this->submitted = true;
    }

    public function nextQuestion(): void
    {
        $this->currentIndex++;

        if ($this->currentIndex >= count($this->questions)) {
            $this->finish();
        } else {
            $this->submitted = false;
        }
    }

    public function finish(): void
    {
        $score = 0;
        $total = count($this->questions);
        $answers = [];

        foreach ($this->questions as $index => $question) {
            $userAnswer = $this->userAnswers[$index] ?? null;
            $isCorrect = $this->checkAnswer($question, $userAnswer);

            if ($isCorrect) {
                $score++;
            }

            $answers[] = [
                'question_id' => $question['id'],
                'question' => $question['question'],
                'topic' => $question['topic'],
                'difficulty' => $question['difficulty'],
                'user_answer' => $userAnswer,
                'correct_answer' => $this->getCorrectAnswerDisplay($question),
                'is_correct' => $isCorrect,
                'explanation' => $question['explanation'],
                'type' => $question['type'],
            ];
        }

        $attempt = TriviaAttempt::create([
            'user_id' => auth()->id(),
            'score' => $score,
            'total' => $total,
            'answers' => $answers,
            'completed_at' => now(),
        ]);

        $this->attemptId = $attempt->id;
        $this->screen = 'results';
    }

    public function resetQuiz(): void
    {
        $this->screen = 'welcome';
        $this->questions = [];
        $this->currentIndex = 0;
        $this->submitted = false;
        $this->userAnswers = [];
        $this->attemptId = null;
        $this->selectedTopics = $this->allTopics->toArray();
    }

    /** @return array<int, array<string, mixed>> */
    private function selectQuestions(Collection $pool, int $count): array
    {
        $byTopic = $pool->groupBy('topic');
        $topics = $byTopic->keys()->toArray();
        $perTopic = (int) floor($count / count($topics));
        $remainder = $count % count($topics);

        $selected = [];

        foreach ($topics as $i => $topic) {
            $take = $perTopic + ($i < $remainder ? 1 : 0);
            $questions = $byTopic[$topic];
            $shuffled = $questions->shuffle();
            $picked = $shuffled->take($take);

            foreach ($picked as $q) {
                $selected[] = $q->toArray();
            }
        }

        $collected = collect($selected);
        $shuffledSelected = $collected->shuffle();

        return $shuffledSelected->take($count)->values()->toArray();
    }

    private function checkAnswer(array $question, string|array|null $userAnswer): bool
    {
        if ($userAnswer === null || $userAnswer === '') {
            return false;
        }

        $checker = new AnswerChecker;

        return match ($question['type']) {
            'single' => $checker->checkSingle($userAnswer, $question['answer']),
            'multiple' => $checker->checkMultiple($userAnswer, $question['answer']),
            'text' => $checker->checkText($userAnswer, $question['answer'], $question['alternatives'] ?? []),
            default => false,
        };
    }

    private function getCorrectAnswerDisplay(array $question): string
    {
        return match ($question['type']) {
            'multiple' => $this->formatMultipleAnswer($question['answer']),
            default => (string) $question['answer'],
        };
    }

    private function formatMultipleAnswer(mixed $answer): string
    {
        $parsed = match (true) {
            is_array($answer) => $answer,
            is_string($answer) => json_decode($answer, true) ?? [],
            default => [],
        };

        return implode(', ', $parsed);
    }

    public function render(): View
    {
        return view('livewire.trivia-quiz');
    }
}
