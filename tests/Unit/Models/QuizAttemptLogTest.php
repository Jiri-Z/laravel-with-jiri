<?php

declare(strict_types=1);

use App\Models\QuizAttemptLog;

test('quiz attempt log factory creates valid record', function () {
    $log = QuizAttemptLog::factory()->create();

    expect($log->id)->not->toBeNull();
    expect($log->user_id)->not->toBeNull();
    expect($log->step_id)->not->toBeNull();
    expect($log->score)->toBeInt();
    expect($log->total)->toBeInt();
    expect($log->answers)->toBeArray();
    expect($log->attempted_at)->not->toBeNull();
});
