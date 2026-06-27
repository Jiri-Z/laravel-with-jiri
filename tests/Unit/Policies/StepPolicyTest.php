<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use App\Policies\StepPolicy;

test('admin can view any steps', function () {
    $admin = User::factory()->admin()->create();

    expect((new StepPolicy)->viewAny($admin))->toBeTrue();
});

test('instructor can view any steps', function () {
    $instructor = User::factory()->instructor()->create();

    expect((new StepPolicy)->viewAny($instructor))->toBeTrue();
});

test('student cannot view any steps in admin', function () {
    $student = User::factory()->create();

    expect((new StepPolicy)->viewAny($student))->toBeFalse();
});

test('admin can view a step', function () {
    $admin = User::factory()->admin()->create();
    $step = Step::factory()->create(['lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()])]);

    expect((new StepPolicy)->view($admin, $step))->toBeTrue();
});

test('instructor can view own step', function () {
    $instructor = User::factory()->instructor()->create();
    $step = Step::factory()->create(['lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()->create(['user_id' => $instructor->id])])]);

    expect((new StepPolicy)->view($instructor, $step))->toBeTrue();
});

test('instructor cannot view another instructors step', function () {
    $instructor = User::factory()->instructor()->create();
    $step = Step::factory()->create(['lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()])]);

    expect((new StepPolicy)->view($instructor, $step))->toBeFalse();
});

test('student cannot view a step in admin', function () {
    $student = User::factory()->create();
    $step = Step::factory()->create(['lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()])]);

    expect((new StepPolicy)->view($student, $step))->toBeFalse();
});

test('admin can create a step', function () {
    $admin = User::factory()->admin()->create();

    expect((new StepPolicy)->create($admin))->toBeTrue();
});

test('instructor can create a step', function () {
    $instructor = User::factory()->instructor()->create();

    expect((new StepPolicy)->create($instructor))->toBeTrue();
});

test('student cannot create a step', function () {
    $student = User::factory()->create();

    expect((new StepPolicy)->create($student))->toBeFalse();
});

test('admin can update any step', function () {
    $admin = User::factory()->admin()->create();
    $step = Step::factory()->create(['lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()])]);

    expect((new StepPolicy)->update($admin, $step))->toBeTrue();
});

test('instructor can update own step', function () {
    $instructor = User::factory()->instructor()->create();
    $step = Step::factory()->create(['lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()->create(['user_id' => $instructor->id])])]);

    expect((new StepPolicy)->update($instructor, $step))->toBeTrue();
});

test('instructor cannot update another instructors step', function () {
    $instructor = User::factory()->instructor()->create();
    $step = Step::factory()->create(['lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()])]);

    expect((new StepPolicy)->update($instructor, $step))->toBeFalse();
});

test('student cannot update a step', function () {
    $student = User::factory()->create();
    $step = Step::factory()->create(['lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()])]);

    expect((new StepPolicy)->update($student, $step))->toBeFalse();
});

test('admin can delete a step', function () {
    $admin = User::factory()->admin()->create();
    $step = Step::factory()->create(['lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()])]);

    expect((new StepPolicy)->delete($admin, $step))->toBeTrue();
});

test('instructor cannot delete a step', function () {
    $instructor = User::factory()->instructor()->create();
    $step = Step::factory()->create(['lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()])]);

    expect((new StepPolicy)->delete($instructor, $step))->toBeFalse();
});

test('student cannot delete a step', function () {
    $student = User::factory()->create();
    $step = Step::factory()->create(['lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()])]);

    expect((new StepPolicy)->delete($student, $step))->toBeFalse();
});
