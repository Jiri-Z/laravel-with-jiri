<?php

use App\Models\Course;
use App\Models\User;
use App\Policies\CoursePolicy;

test('admin can view any courses', function () {
    $admin = User::factory()->admin()->create();

    expect((new CoursePolicy)->viewAny($admin))->toBeTrue();
});

test('instructor can view any courses', function () {
    $instructor = User::factory()->instructor()->create();

    expect((new CoursePolicy)->viewAny($instructor))->toBeTrue();
});

test('student cannot view any courses in admin', function () {
    $student = User::factory()->create();

    expect((new CoursePolicy)->viewAny($student))->toBeFalse();
});

test('admin can view a course', function () {
    $admin = User::factory()->admin()->create();
    $course = Course::factory()->create();

    expect((new CoursePolicy)->view($admin, $course))->toBeTrue();
});

test('instructor can view a course', function () {
    $instructor = User::factory()->instructor()->create();
    $course = Course::factory()->create();

    expect((new CoursePolicy)->view($instructor, $course))->toBeTrue();
});

test('student cannot view a course in admin', function () {
    $student = User::factory()->create();
    $course = Course::factory()->create();

    expect((new CoursePolicy)->view($student, $course))->toBeFalse();
});

test('admin can create a course', function () {
    $admin = User::factory()->admin()->create();

    expect((new CoursePolicy)->create($admin))->toBeTrue();
});

test('instructor can create a course', function () {
    $instructor = User::factory()->instructor()->create();

    expect((new CoursePolicy)->create($instructor))->toBeTrue();
});

test('student cannot create a course', function () {
    $student = User::factory()->create();

    expect((new CoursePolicy)->create($student))->toBeFalse();
});

test('admin can update any course', function () {
    $admin = User::factory()->admin()->create();
    $course = Course::factory()->create();

    expect((new CoursePolicy)->update($admin, $course))->toBeTrue();
});

test('instructor can update any course', function () {
    $instructor = User::factory()->instructor()->create();
    $course = Course::factory()->create();

    expect((new CoursePolicy)->update($instructor, $course))->toBeTrue();
});

test('student cannot update a course', function () {
    $student = User::factory()->create();
    $course = Course::factory()->create();

    expect((new CoursePolicy)->update($student, $course))->toBeFalse();
});

test('admin can delete a course', function () {
    $admin = User::factory()->admin()->create();
    $course = Course::factory()->create();

    expect((new CoursePolicy)->delete($admin, $course))->toBeTrue();
});

test('instructor cannot delete a course', function () {
    $instructor = User::factory()->instructor()->create();
    $course = Course::factory()->create();

    expect((new CoursePolicy)->delete($instructor, $course))->toBeFalse();
});

test('student cannot delete a course', function () {
    $student = User::factory()->create();
    $course = Course::factory()->create();

    expect((new CoursePolicy)->delete($student, $course))->toBeFalse();
});
