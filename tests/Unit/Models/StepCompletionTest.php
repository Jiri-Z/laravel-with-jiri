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

test('deleting user cascades to completions', function () {
    $user = User::factory()->hasStepCompletions(2)->create();

    $user->delete();

    expect(StepCompletion::where('user_id', $user->id)->exists())->toBeFalse();
});

test('deleting step cascades to completions', function () {
    $step = Step::factory()->hasCompletions(2)->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    $step->delete();

    expect(StepCompletion::where('step_id', $step->id)->exists())->toBeFalse();
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
