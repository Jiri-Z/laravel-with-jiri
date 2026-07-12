<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\LogQuizAttempt;
use App\Models\QuizAttemptLog;
use App\Models\Step;
use App\Models\User;

it('creates a quiz_attempt_log record in handle', function () {
    $user = User::factory()->create();
    $step = Step::factory()->create();

    $job = new LogQuizAttempt(
        userId: $user->id,
        stepId: $step->id,
        score: 3,
        total: 5,
        answers: [0 => 1, 1 => 'Paris', 2 => [0, 2]],
    );

    $job->handle();

    $this->assertDatabaseHas('quiz_attempt_logs', [
        'user_id' => $user->id,
        'step_id' => $step->id,
        'score' => 3,
        'total' => 5,
    ]);

    $record = QuizAttemptLog::where('user_id', $user->id)
        ->where('step_id', $step->id)
        ->first();

    expect($record)->not->toBeNull();
    expect($record->answers)->toBe([0 => 1, 1 => 'Paris', 2 => [0, 2]]);
    expect($record->attempted_at)->not->toBeNull();
});

it('handles zero score correctly', function () {
    $user = User::factory()->create();
    $step = Step::factory()->create();

    $job = new LogQuizAttempt(
        userId: $user->id,
        stepId: $step->id,
        score: 0,
        total: 3,
        answers: [0 => 0, 1 => null, 2 => ''],
    );

    $job->handle();

    $this->assertDatabaseHas('quiz_attempt_logs', [
        'user_id' => $user->id,
        'step_id' => $step->id,
        'score' => 0,
        'total' => 3,
    ]);
});
