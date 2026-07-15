<?php

use App\Enums\StepType;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepAnswer;
use App\Models\StepCompletion;
use Illuminate\Database\QueryException;

test('step belongs to a lesson', function () {
    $course = Course::factory()->create();
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);
    $step = Step::factory()->create(['lesson_id' => $lesson->id]);

    expect($step->lesson)->toBeInstanceOf(Lesson::class)
        ->and($step->lesson->id)->toEqual($lesson->id);
});

test('step has fillable attributes', function () {
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
    $step = Step::factory()->create([
        'lesson_id' => $lesson->id,
        'title' => 'Installation',
        'type' => StepType::Reading,
        'reading_content' => 'Step content here',
        'order' => 1,
    ]);

    expect($step)
        ->title->toBe('Installation')
        ->type->toBe(StepType::Reading)
        ->reading_content->toBe('Step content here')
        ->order->toBe(1);
});

test('step order is unique within the same lesson', function () {
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
    Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);

    expect(fn () => Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]))
        ->toThrow(QueryException::class);
});

test('step order can repeat across different lessons', function () {
    $lessonA = Lesson::factory()->create(['course_id' => Course::factory()]);
    $lessonB = Lesson::factory()->create(['course_id' => Course::factory()]);
    Step::factory()->create(['lesson_id' => $lessonA->id, 'order' => 1]);
    Step::factory()->create(['lesson_id' => $lessonB->id, 'order' => 1]);

    expect(true)->toBeTrue();
});

test('step has many completions', function () {
    $step = Step::factory()->hasCompletions(2)->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    expect($step->completions)->toHaveCount(2)
        ->and($step->completions->first())->toBeInstanceOf(StepCompletion::class);
});

test('step scopeOrdered returns steps in order', function () {
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
    $a = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);
    $b = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);

    expect(Step::ordered()->get()->pluck('id')->toArray())->toEqual([$b->id, $a->id]);
});

test('step has valid type values', function () {
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

    foreach (StepType::cases() as $index => $type) {
        $step = Step::factory()->create([
            'lesson_id' => $lesson->id,
            'type' => $type,
            'order' => $index + 1,
        ]);
        expect($step->type)->toBe($type);
    }
});

test('step getContentAsArray returns array for valid JSON in quiz_content', function () {
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
    $step = Step::factory()->create([
        'lesson_id' => $lesson->id,
        'type' => StepType::Quiz,
        'quiz_content' => '{"key": "value", "number": 42}',
    ]);

    $result = $step->getContentAsArray();

    expect($result)->toBe(['key' => 'value', 'number' => 42]);
});

test('step getContentAsArray normalizes object-shaped quiz content into a single-question list', function () {
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
    $step = Step::factory()->create([
        'lesson_id' => $lesson->id,
        'type' => StepType::Quiz,
        'quiz_content' => '{"question":"What is the answer?","options":["A","B"],"answer":0}',
    ]);

    expect($step->getContentAsArray())->toEqual([[
        'question' => 'What is the answer?',
        'options' => ['A', 'B'],
        'answer' => 0,
    ]]);
});

test('step getContentAsArray returns null for reading steps', function () {
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
    $step = Step::factory()->create([
        'lesson_id' => $lesson->id,
        'type' => StepType::Reading,
        'reading_content' => 'Just some plain text content',
    ]);

    expect($step->getContentAsArray())->toBeNull();
});

test('step getContentAsArray returns null when quiz_content is empty', function () {
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
    $step = Step::factory()->create([
        'lesson_id' => $lesson->id,
        'type' => StepType::Quiz,
        'quiz_content' => '',
    ]);

    expect($step->getContentAsArray())->toBeNull();
});

test('step has many answers', function () {
    $step = Step::factory()->hasAnswers(2)->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    expect($step->answers)->toHaveCount(2)
        ->and($step->answers->first())->toBeInstanceOf(StepAnswer::class);
});
