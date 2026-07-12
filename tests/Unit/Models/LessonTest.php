<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Illuminate\Database\QueryException;

test('lesson belongs to a course', function () {
    $course = Course::factory()->create();
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);

    expect($lesson->course)->toBeInstanceOf(Course::class)
        ->and($lesson->course->id)->toEqual($course->id);
});

test('lesson has fillable attributes', function () {
    $course = Course::factory()->create();
    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
        'title' => 'Getting Started',
        'slug' => 'getting-started',
        'description' => 'First lesson',
        'published' => true,
        'order' => 1,
    ]);

    expect($lesson)
        ->title->toBe('Getting Started')
        ->slug->toBe('getting-started')
        ->description->toBe('First lesson')
        ->published->toBeTrue()
        ->order->toBe(1);
});

test('deleting lesson cascades to steps', function () {
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
    Step::factory()->count(2)->sequence(
        ['order' => 1],
        ['order' => 2],
    )->create(['lesson_id' => $lesson->id]);
    $stepIds = $lesson->steps()->pluck('id');

    $lesson->delete();

    expect(Step::whereIn('id', $stepIds)->exists())->toBeFalse();
});

test('lesson has many steps', function () {
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
    Step::factory()->count(3)->sequence(
        ['order' => 1],
        ['order' => 2],
        ['order' => 3],
    )->create(['lesson_id' => $lesson->id]);

    expect($lesson->steps)->toHaveCount(3)
        ->and($lesson->steps->first())->toBeInstanceOf(Step::class);
});

test('lesson slug is unique within the same course', function () {
    $course = Course::factory()->create();
    Lesson::factory()->create(['course_id' => $course->id, 'slug' => 'same-slug']);

    expect(fn () => Lesson::factory()->create(['course_id' => $course->id, 'slug' => 'same-slug']))
        ->toThrow(QueryException::class);
});

test('lesson scopePublished filters by published status', function () {
    $course = Course::factory()->create();
    Lesson::factory()->create(['course_id' => $course->id, 'published' => true]);
    Lesson::factory()->create(['course_id' => $course->id, 'published' => false]);

    expect(Lesson::published()->get())->toHaveCount(1);
});

test('lesson scopeOrdered returns lessons in order', function () {
    $course = Course::factory()->create();
    $a = Lesson::factory()->create(['course_id' => $course->id, 'order' => 2]);
    $b = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);

    expect(Lesson::ordered()->get()->pluck('id')->toArray())->toEqual([$b->id, $a->id]);
});

test('first step is accessible when there is no previous step', function () {
    $user = User::factory()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
    $step = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);

    expect($lesson->hasUserUnlockedPreviousStep($user, $step))->toBeTrue();
});

test('step is accessible when previous step is completed', function () {
    $user = User::factory()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
    $step1 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
    $step2 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);
    StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $step1->id]);

    expect($lesson->hasUserUnlockedPreviousStep($user, $step2))->toBeTrue();
});

test('step is locked when previous step is not completed', function () {
    $user = User::factory()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
    Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
    $step2 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

    expect($lesson->hasUserUnlockedPreviousStep($user, $step2))->toBeFalse();
});
