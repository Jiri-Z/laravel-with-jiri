<?php

use App\Models\TriviaAttempt;
use App\Models\User;
use Carbon\Carbon;

test('trivia attempt belongs to user', function () {
    $user = User::factory()->create();
    $attempt = TriviaAttempt::factory()->create(['user_id' => $user->id]);

    expect($attempt->user)->toBeInstanceOf(User::class)
        ->and($attempt->user->id)->toEqual($user->id);
});

test('trivia attempt has fillable attributes', function () {
    $attempt = TriviaAttempt::factory()->create([
        'score' => 7,
        'total' => 10,
        'answers' => ['q1' => 'A', 'q2' => 'B'],
        'completed_at' => now(),
    ]);

    expect($attempt)
        ->score->toBe(7)
        ->total->toBe(10)
        ->answers->toBe(['q1' => 'A', 'q2' => 'B'])
        ->completed_at->toBeInstanceOf(Carbon::class);
});

test('trivia attempt defaults to incomplete', function () {
    $attempt = TriviaAttempt::factory()->create();

    expect($attempt->completed_at)->toBeNull();
});

test('trivia attempt completed factory state sets completed_at and score', function () {
    $attempt = TriviaAttempt::factory()->completed()->create();

    expect($attempt->completed_at)->toBeInstanceOf(Carbon::class)
        ->and($attempt->score)->toBeGreaterThanOrEqual(0)
        ->and($attempt->score)->toBeLessThanOrEqual($attempt->total);
});

test('deleting user cascades to trivia attempts', function () {
    $user = User::factory()->create();
    TriviaAttempt::factory()->count(2)->create(['user_id' => $user->id]);

    $user->delete();

    expect(TriviaAttempt::where('user_id', $user->id)->exists())->toBeFalse();
});
