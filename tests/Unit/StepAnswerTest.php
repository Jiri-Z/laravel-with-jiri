<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepAnswer;
use App\Models\User;
use Illuminate\Database\QueryException;

test('step answer belongs to user', function () {
    $user = User::factory()->create();
    $answer = StepAnswer::factory()->create(['user_id' => $user->id]);

    expect($answer->user)->toBeInstanceOf(User::class)
        ->and($answer->user->id)->toBe($user->id);
});

test('step answer belongs to step', function () {
    $step = Step::factory()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);
    $answer = StepAnswer::factory()->create(['step_id' => $step->id]);

    expect($answer->step)->toBeInstanceOf(Step::class)
        ->and($answer->step->id)->toBe($step->id);
});

test('step answer is_correct is cast to boolean', function () {
    $answer = StepAnswer::factory()->create(['is_correct' => true]);

    expect($answer->is_correct)->toBeTrue();
});

test('step answer unique constraint on user_id and step_id', function () {
    $user = User::factory()->create();
    $step = Step::factory()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    StepAnswer::factory()->create([
        'user_id' => $user->id,
        'step_id' => $step->id,
    ]);

    expect(fn () => StepAnswer::factory()->create([
        'user_id' => $user->id,
        'step_id' => $step->id,
    ]))->toThrow(QueryException::class);
});

test('step answer deletes on user cascade', function () {
    $user = User::factory()->create();
    StepAnswer::factory()->create(['user_id' => $user->id]);

    $user->delete();

    expect(StepAnswer::count())->toBe(0);
});

test('step answer deletes on step cascade', function () {
    $step = Step::factory()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);
    StepAnswer::factory()->create(['step_id' => $step->id]);

    $step->delete();

    expect(StepAnswer::count())->toBe(0);
});

test('step has many answers', function () {
    $step = Step::factory()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);
    StepAnswer::factory()->count(3)->create(['step_id' => $step->id]);

    expect($step->answers)->toHaveCount(3);
});

test('user has many step answers', function () {
    $user = User::factory()->create();
    StepAnswer::factory()->count(2)->create(['user_id' => $user->id]);

    expect($user->stepAnswers)->toHaveCount(2);
});
