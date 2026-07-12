<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepAnswer;
use App\Models\StepCompletion;
use App\Models\User;

test('user has default student role', function () {
    $user = User::factory()->create();

    expect($user->role)->toBe('student');
});

test('user can be an admin', function () {
    $user = User::factory()->create(['role' => 'admin']);

    expect($user->role)->toBe('admin')
        ->and($user->isAdmin())->toBeTrue()
        ->and($user->isInstructor())->toBeFalse()
        ->and($user->isStudent())->toBeFalse();
});

test('user can be an instructor', function () {
    $user = User::factory()->create(['role' => 'instructor']);

    expect($user->role)->toBe('instructor')
        ->and($user->isInstructor())->toBeTrue()
        ->and($user->isAdmin())->toBeFalse()
        ->and($user->isStudent())->toBeFalse();
});

test('user can be a student', function () {
    $user = User::factory()->create(['role' => 'student']);

    expect($user->role)->toBe('student')
        ->and($user->isStudent())->toBeTrue()
        ->and($user->isAdmin())->toBeFalse()
        ->and($user->isInstructor())->toBeFalse();
});

test('user has many step completions', function () {
    $user = User::factory()->hasStepCompletions(2)->create();

    expect($user->stepCompletions)->toHaveCount(2)
        ->and($user->stepCompletions->first())->toBeInstanceOf(StepCompletion::class);
});

test('user has many step answers', function () {
    $user = User::factory()->hasStepAnswers(2)->create();

    expect($user->stepAnswers)->toHaveCount(2)
        ->and($user->stepAnswers->first())->toBeInstanceOf(StepAnswer::class);
});

test('isStaff returns true for admin and instructor, false for student', function () {
    $admin = User::factory()->admin()->create();
    $instructor = User::factory()->instructor()->create();
    $student = User::factory()->create(['role' => 'student']);

    expect($admin->isStaff())->toBeTrue()
        ->and($instructor->isStaff())->toBeTrue()
        ->and($student->isStaff())->toBeFalse();
});

test('unknown role returns false for all role checks', function () {
    $user = User::factory()->create(['role' => 'superadmin']);

    expect($user->isAdmin())->toBeFalse()
        ->and($user->isInstructor())->toBeFalse()
        ->and($user->isStudent())->toBeFalse();
});

test('instructor owns their own course', function () {
    $instructor = User::factory()->instructor()->create();
    $course = Course::factory()->create(['user_id' => $instructor->id]);

    expect($instructor->ownsCourse($course))->toBeTrue();
});

test('instructor does not own another instructors course', function () {
    $instructor = User::factory()->instructor()->create();
    $course = Course::factory()->create();

    expect($instructor->ownsCourse($course))->toBeFalse();
});

test('student does not own any course', function () {
    $student = User::factory()->create();
    $course = Course::factory()->create();

    expect($student->ownsCourse($course))->toBeFalse();
});

test('admin does not own a course by default', function () {
    $admin = User::factory()->admin()->create();
    $course = Course::factory()->create();

    expect($admin->ownsCourse($course))->toBeFalse();
});

test('deleting an instructor does not delete owned courses and lessons', function () {
    $instructor = User::factory()->create(['role' => 'instructor']);
    $course = Course::factory()->create(['user_id' => $instructor->id]);
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);
    $step = Step::factory()->create(['lesson_id' => $lesson->id]);

    $instructor->delete();

    expect(Course::find($course->id))->not->toBeNull()
        ->and($course->refresh()->user_id)->toBeNull()
        ->and(Lesson::find($lesson->id))->not->toBeNull()
        ->and(Step::find($step->id))->not->toBeNull();
});
