<?php

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

test('course enrollment belongs to user', function () {
    $user = User::factory()->create();
    $enrollment = CourseEnrollment::factory()->create(['user_id' => $user->id]);

    expect($enrollment->user)->toBeInstanceOf(User::class)
        ->and($enrollment->user->id)->toEqual($user->id);
});

test('course enrollment belongs to course', function () {
    $course = Course::factory()->create();
    $enrollment = CourseEnrollment::factory()->create(['course_id' => $course->id]);

    expect($enrollment->course)->toBeInstanceOf(Course::class)
        ->and($enrollment->course->id)->toEqual($course->id);
});

test('course enrollment has fillable attributes', function () {
    $enrollment = CourseEnrollment::factory()->create([
        'enrolled_at' => now(),
    ]);

    expect($enrollment)
        ->user_id->not->toBeNull()
        ->course_id->not->toBeNull()
        ->enrolled_at->toBeInstanceOf(Carbon::class);
});

test('deleting user cascades to enrollments', function () {
    $user = User::factory()->hasEnrollments(2)->create();

    $user->delete();

    expect(CourseEnrollment::where('user_id', $user->id)->exists())->toBeFalse();
});

test('deleting course cascades to enrollments', function () {
    $course = Course::factory()->hasEnrollments(2)->create();

    $course->delete();

    expect(CourseEnrollment::where('course_id', $course->id)->exists())->toBeFalse();
});

test('same user cannot enroll in same course twice', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();

    CourseEnrollment::factory()->create([
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);

    expect(fn () => CourseEnrollment::factory()->create([
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]))->toThrow(QueryException::class);
});
