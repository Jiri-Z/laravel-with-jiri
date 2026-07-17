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
use Livewire\Attributes\Validate;
use Livewire\Component;
use RuntimeException;

/** @property-read Collection<int, string> $allTopics */
#[Layout('layouts.app')]
class TriviaQuiz extends Component
{
    public string $screen = 'welcome';

    /** @var array<int, string> */
    #[Validate('array')]
    public array $selectedTopics = [];

    /** @var array<int, array<string, mixed>> */
    public array $questions = [];

    public int $currentIndex = 0;

    public bool $submitted = false;

    public ?int $attemptId = null;

    #[Validate('required|integer|min:1|max:50')]
    public int $questionCount = 0;

    /** @var array<int, string|array<int, string>|null> */
    public array $userAnswers = [];

    public function mount(): void
    {
        $this->selectedTopics = $this->allTopics->all();
        $this->questionCount = $this->availableQuestionCount();
    }

    public function availableQuestionCount(): int
    {
        if (empty($this->selectedTopics)) {
            return 0;
        }

        return TriviaQuestion::where('locale', app()->getLocale())
            ->whereIn('topic', $this->selectedTopics)
            ->count();
    }

    public function updatedSelectedTopics(): void
    {
        $available = $this->availableQuestionCount();

        if ($this->questionCount > $available) {
            $this->questionCount = $available;
        }
    }

    /** @return Collection<int, string> */
    #[Computed]
    public function allTopics(): Collection
    {
        return TriviaQuestion::query()
            ->where('locale', app()->getLocale())
            ->distinct()
            ->orderBy('topic')
            ->pluck('topic')
            ->values()
            ->map(fn (mixed $topic): string => is_string($topic) ? $topic : '');
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, TriviaQuestion> */
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

    #[Computed]
    public function attempt(): ?TriviaAttempt
    {
        if ($this->attemptId === null) {
            return null;
        }

        return TriviaAttempt::find($this->attemptId);
    }

    public function start(): void
    {
        if (empty($this->selectedTopics)) {
            return;
        }

        if ($this->questionCount === 0) {
            session()->flash('error', __('trivia.no_questions_available'));

            return;
        }

        $pool = TriviaQuestion::where('locale', app()->getLocale())
            ->whereIn('topic', $this->selectedTopics)
            ->inRandomOrder()
            ->limit($this->questionCount)
            ->get();

        if ($pool->isEmpty()) {
            session()->flash('error', __('trivia.no_questions_available'));

            return;
        }

        $correctAnswers = [];
        $this->questions = $pool->map(function (TriviaQuestion $q) use (&$correctAnswers) {
            $data = $q->toArray();
            $correctAnswers[] = $q->answer;
            unset($data['answer']);

            return $data;
        })->values()->all();
        session(['trivia_correct_answers' => $correctAnswers]);
        $this->currentIndex = 0;
        $this->submitted = false;
        $this->userAnswers = [];
        foreach ($this->questions as $index => $question) {
            if (is_array($question) && ($question['type'] ?? 'single') === 'multiple') {
                $this->userAnswers[(int) $index] = [];
            }
        }
        $this->attemptId = null;
        $this->screen = 'quiz';
    }

    public function submit(): void
    {
        if ($this->submitted) {
            return;
        }

        // Re-hydrate correct answer for current question so blade can show feedback
        /** @var array<int, string|array<int, string>|null> $correctAnswers */
        $correctAnswers = session('trivia_correct_answers', []);
        $this->questions[$this->currentIndex]['answer'] = $correctAnswers[$this->currentIndex] ?? '';

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
        if (empty($this->questions)) {
            return;
        }

        /** @var array<int, string|array<int, string>|null> $correctAnswers */
        $correctAnswers = session('trivia_correct_answers', []);
        $score = 0;
        $total = count($this->questions);
        $answers = [];

        foreach ($this->questions as $index => $question) {
            $userAnswer = $this->normalizeUserAnswer($this->userAnswers[$index] ?? null);
            $correctAnswer = $correctAnswers[$index] ?? null;
            $isCorrect = $this->checkAnswer($question, $userAnswer, $correctAnswer);

            if ($isCorrect) {
                $score++;
            }

            $answers[] = [
                'question_id' => $question['id'],
                'question' => $question['question'],
                'topic' => $question['topic'],
                'difficulty' => $question['difficulty'],
                'user_answer' => $userAnswer,
                'correct_answer' => $this->getCorrectAnswerDisplay($question, $correctAnswer),
                'is_correct' => $isCorrect,
                'explanation' => $question['explanation'],
                'type' => $question['type'],
            ];
        }

        // Re-hydrate correct answers into questions for results screen display
        /** @var array<int, string|array<int, string>|null> $sessionAnswers */
        $sessionAnswers = session('trivia_correct_answers', []);
        foreach ($this->questions as $index => &$question) {
            $question['answer'] = $sessionAnswers[$index] ?? '';
        }
        unset($question);

        $userId = auth()->id();

        if ($userId === null) {
            throw new RuntimeException('User must be authenticated to submit a trivia attempt.');
        }

        $attempt = TriviaAttempt::create([
            'user_id' => $userId,
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
        $this->selectedTopics = $this->allTopics->all();
        $this->questionCount = $this->availableQuestionCount();
        session()->forget('trivia_correct_answers');
    }

    public function render(): View
    {
        return view('livewire.trivia-quiz');
    }

    /** @param array<string, mixed> $question
     * @param  array<int, string>|null  $userAnswer
     * @param  string|array<int, string>|null  $correctAnswer */
    public function checkAnswer(array $question, string|array|null $userAnswer, string|array|null $correctAnswer = null): bool
    {
        $type = $question['type'];

        $questionWithAnswer = $question;
        if ($correctAnswer !== null) {
            $questionWithAnswer['answer'] = $correctAnswer;
        }

        return (new AnswerChecker)->check(is_string($type) ? $type : 'single', $userAnswer, $questionWithAnswer);
    }

    /** @param string|array<int, string>|bool|null $answer
     * @return string|array<int, string>|null */
    private function normalizeUserAnswer(mixed $answer): string|array|null
    {
        return match (true) {
            is_string($answer) => $answer,
            is_array($answer) => array_values(array_filter($answer, is_string(...))),
            is_int($answer) || is_float($answer) => (string) $answer,
            default => null,
        };
    }

    /** @param array<string, mixed> $question
     * @param  string|array<int, string>|null  $correctAnswer */
    private function getCorrectAnswerDisplay(array $question, string|array|null $correctAnswer): string
    {
        return match ($question['type']) {
            'multiple' => $this->formatMultipleAnswer($correctAnswer),
            default => is_string($correctAnswer) ? $correctAnswer : '',
        };
    }

    private function formatMultipleAnswer(mixed $answer): string
    {
        $parsed = match (true) {
            is_array($answer) => $answer,
            is_string($answer) => is_array(json_decode($answer, true)) ? json_decode($answer, true) : [],
            default => [],
        };

        $parts = [];
        foreach ($parsed as $v) {
            $parts[] = match (true) {
                is_string($v) => $v,
                is_int($v), is_float($v) => (string) $v,
                is_bool($v) => $v ? '1' : '',
                default => '',
            };
        }

        return implode(', ', $parts);
    }
}
