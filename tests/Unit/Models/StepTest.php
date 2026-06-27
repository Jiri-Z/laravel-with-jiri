<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
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
        'type' => 'reading',
        'content' => 'Step content here',
        'order' => 1,
    ]);

    expect($step)
        ->title->toBe('Installation')
        ->type->toBe('reading')
        ->content->toBe('Step content here')
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

test('step has valid type values', function () {
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

    $types = ['reading', 'quiz_single', 'quiz_multiple', 'quiz_text', 'coding'];
    foreach ($types as $index => $type) {
        $step = Step::factory()->create([
            'lesson_id' => $lesson->id,
            'type' => $type,
            'order' => $index + 1,
        ]);
        expect($step->type)->toBe($type);
    }
});
