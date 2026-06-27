<?php

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

test('unknown role returns false for all role checks', function () {
    $user = User::factory()->create(['role' => 'superadmin']);

    expect($user->isAdmin())->toBeFalse()
        ->and($user->isInstructor())->toBeFalse()
        ->and($user->isStudent())->toBeFalse();
});
