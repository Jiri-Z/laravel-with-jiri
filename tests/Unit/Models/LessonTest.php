<?php

use App\Models\Course;
use App\Models\Lesson;

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

test('lesson slug is unique within the same course', function () {
    $course = Course::factory()->create();
    Lesson::factory()->create(['course_id' => $course->id, 'slug' => 'same-slug']);

    expect(fn () => Lesson::factory()->create(['course_id' => $course->id, 'slug' => 'same-slug']))
        ->toThrow(\Illuminate\Database\QueryException::class);
});
