<?php

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\QueryException;

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

test('course casts', function () {
    $course = Course::factory()->create(['published' => true, 'order' => 5, 'description' => null]);

    expect($course->published)->toBeTrue()
        ->and($course->order)->toBe(5)
        ->and($course->description)->toBeNull();
});

test('course description is nullable', function () {
    $course = Course::factory()->create(['description' => null]);

    expect($course->description)->toBeNull();
});

test('deleting course cascades to lessons and steps', function () {
    $course = Course::factory()->hasLessons(2)->create();
    $lessonIds = $course->lessons()->pluck('id');

    $course->delete();

    expect(Lesson::whereIn('id', $lessonIds)->exists())->toBeFalse();
});

test('course has many lessons', function () {
    $course = Course::factory()->hasLessons(3)->create();

    expect($course->lessons)->toHaveCount(3)
        ->and($course->lessons->first())->toBeInstanceOf(Lesson::class);
});

test('course slug is unique', function () {
    Course::factory()->create(['slug' => 'same-slug']);

    expect(fn () => Course::factory()->create(['slug' => 'same-slug']))
        ->toThrow(QueryException::class);
});

test('course scopePublished filters by published status', function () {
    Course::factory()->create(['published' => true]);
    Course::factory()->create(['published' => false]);

    expect(Course::published()->get())->toHaveCount(1);
});

test('course scopeOrdered returns courses in order', function () {
    $a = Course::factory()->create(['order' => 2]);
    $b = Course::factory()->create(['order' => 1]);

    expect(Course::ordered()->get()->pluck('id')->toArray())->toEqual([$b->id, $a->id]);
});
