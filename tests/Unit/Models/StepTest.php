<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;

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

test('step has valid type values', function () {
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

    $types = ['reading', 'quiz_single', 'quiz_multiple', 'quiz_text', 'coding'];
    foreach ($types as $type) {
        $step = Step::factory()->create([
            'lesson_id' => $lesson->id,
            'type' => $type,
        ]);
        expect($step->type)->toBe($type);
    }
});
