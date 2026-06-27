<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

test('step completion belongs to user and step', function () {
    $user = User::factory()->create();
    $step = Step::factory()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    $completion = StepCompletion::factory()->create([
        'user_id' => $user->id,
        'step_id' => $step->id,
    ]);

    expect($completion->user)->toBeInstanceOf(User::class)
        ->and($completion->step)->toBeInstanceOf(Step::class);
});

test('step completion records completed_at', function () {
    $completion = StepCompletion::factory()->create();

    expect($completion->completed_at)->toBeInstanceOf(Carbon::class);
});

test('user cannot complete the same step twice', function () {
    $user = User::factory()->create();
    $step = Step::factory()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    StepCompletion::factory()->create([
        'user_id' => $user->id,
        'step_id' => $step->id,
    ]);

    expect(fn () => StepCompletion::factory()->create([
        'user_id' => $user->id,
        'step_id' => $step->id,
    ]))->toThrow(QueryException::class);
});
