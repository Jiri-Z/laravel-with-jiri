<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\TriviaAttempt;
use App\Models\TriviaQuestion;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Laravel Trivia')]
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
            ->groupBy('topic')
            ->orderBy('topic')
            ->get();
    }

    public function start(): void
    {
        if (empty($this->selectedTopics)) {
            return;
        }

        $pool = TriviaQuestion::whereIn('topic', $this->selectedTopics)->get();

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

        return match ($question['type']) {
            'single' => $userAnswer === $question['answer'],
            'multiple' => $this->checkMultipleAnswer($userAnswer, $question['answer']),
            'text' => $this->checkTextAnswer($userAnswer, $question['answer'], $question['alternatives'] ?? []),
            default => false,
        };
    }

    private function checkMultipleAnswer(string|array|null $userAnswer, mixed $correctAnswer): bool
    {
        if (! is_array($userAnswer)) {
            return false;
        }

        $correct = $this->parseCorrectAnswers($correctAnswer);
        $userSet = array_unique($userAnswer);
        $correctSet = array_unique($correct);

        sort($userSet);
        sort($correctSet);

        return $userSet === $correctSet;
    }

    /** @return array<int, string> */
    private function parseCorrectAnswers(mixed $answer): array
    {
        if (is_array($answer)) {
            return $answer;
        }

        $decoded = json_decode((string) $answer, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function checkTextAnswer(string $userAnswer, string $correctAnswer, array $alternatives): bool
    {
        $normalize = fn (string $s): string => mb_strtolower(trim($s));

        if ($normalize($userAnswer) === $normalize($correctAnswer)) {
            return true;
        }

        foreach ($alternatives as $alt) {
            if ($normalize($userAnswer) === $normalize($alt)) {
                return true;
            }
        }

        return false;
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
        $parsed = $this->parseCorrectAnswers($answer);

        return implode(', ', $parsed);
    }

    public function render(): View
    {
        return view('livewire.trivia-quiz');
    }
}
