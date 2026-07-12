<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\QuizAttemptLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class LogQuizAttempt implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<int, int|string|array<int, int|string>|null>  $answers
     */
    public function __construct(
        public readonly int $userId,
        public readonly int $stepId,
        public readonly int $score,
        public readonly int $total,
        public readonly array $answers,
    ) {}

    public function handle(): void
    {
        QuizAttemptLog::create([
            'user_id' => $this->userId,
            'step_id' => $this->stepId,
            'score' => $this->score,
            'total' => $this->total,
            'answers' => $this->answers,
            'attempted_at' => now(),
        ]);
    }
}
