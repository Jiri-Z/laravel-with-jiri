<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Policies\LessonPolicy;

test('admin can view any lessons', function () {
    $admin = User::factory()->admin()->create();

    expect((new LessonPolicy)->viewAny($admin))->toBeTrue();
});

test('instructor can view any lessons', function () {
    $instructor = User::factory()->instructor()->create();

    expect((new LessonPolicy)->viewAny($instructor))->toBeTrue();
});

test('student cannot view any lessons in admin', function () {
    $student = User::factory()->create();

    expect((new LessonPolicy)->viewAny($student))->toBeFalse();
});

test('admin can view a lesson', function () {
    $admin = User::factory()->admin()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

    expect((new LessonPolicy)->view($admin, $lesson))->toBeTrue();
});

test('instructor can view own lesson', function () {
    $instructor = User::factory()->instructor()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()->create(['user_id' => $instructor->id])]);

    expect((new LessonPolicy)->view($instructor, $lesson))->toBeTrue();
});

test('instructor cannot view another instructors lesson', function () {
    $instructor = User::factory()->instructor()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

    expect((new LessonPolicy)->view($instructor, $lesson))->toBeFalse();
});

test('student cannot view a lesson in admin', function () {
    $student = User::factory()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

    expect((new LessonPolicy)->view($student, $lesson))->toBeFalse();
});

test('admin can create a lesson', function () {
    $admin = User::factory()->admin()->create();

    expect((new LessonPolicy)->create($admin))->toBeTrue();
});

test('instructor can create a lesson', function () {
    $instructor = User::factory()->instructor()->create();

    expect((new LessonPolicy)->create($instructor))->toBeTrue();
});

test('student cannot create a lesson', function () {
    $student = User::factory()->create();

    expect((new LessonPolicy)->create($student))->toBeFalse();
});

test('admin can update any lesson', function () {
    $admin = User::factory()->admin()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

    expect((new LessonPolicy)->update($admin, $lesson))->toBeTrue();
});

test('instructor can update own lesson', function () {
    $instructor = User::factory()->instructor()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()->create(['user_id' => $instructor->id])]);

    expect((new LessonPolicy)->update($instructor, $lesson))->toBeTrue();
});

test('instructor cannot update another instructors lesson', function () {
    $instructor = User::factory()->instructor()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

    expect((new LessonPolicy)->update($instructor, $lesson))->toBeFalse();
});

test('student cannot update a lesson', function () {
    $student = User::factory()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

    expect((new LessonPolicy)->update($student, $lesson))->toBeFalse();
});

test('admin can delete a lesson', function () {
    $admin = User::factory()->admin()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

    expect((new LessonPolicy)->delete($admin, $lesson))->toBeTrue();
});

test('instructor cannot delete a lesson', function () {
    $instructor = User::factory()->instructor()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

    expect((new LessonPolicy)->delete($instructor, $lesson))->toBeFalse();
});

test('student cannot delete a lesson', function () {
    $student = User::factory()->create();
    $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

    expect((new LessonPolicy)->delete($student, $lesson))->toBeFalse();
});
