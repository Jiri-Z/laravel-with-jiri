<?php

use App\Models\Course;

test('course can be created with fillable attributes', function () {
    $course = Course::factory()->create([
        'title' => 'Introduction to Laravel',
        'slug' => 'introduction-to-laravel',
        'description' => 'Learn Laravel from scratch',
        'published' => true,
        'order' => 1,
    ]);

    expect($course)
        ->title->toBe('Introduction to Laravel')
        ->slug->toBe('introduction-to-laravel')
        ->description->toBe('Learn Laravel from scratch')
        ->published->toBeTrue()
        ->order->toBe(1);
});

test('course has many lessons', function () {
    $course = Course::factory()->hasLessons(3)->create();

    expect($course->lessons)->toHaveCount(3)
        ->and($course->lessons->first())->toBeInstanceOf(App\Models\Lesson::class);
});

test('course slug is unique', function () {
    Course::factory()->create(['slug' => 'same-slug']);

    expect(fn () => Course::factory()->create(['slug' => 'same-slug']))
        ->toThrow(\Illuminate\Database\QueryException::class);
});
