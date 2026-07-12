<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;

test('scopeOwnedBy filters courses by user ownership', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Course::factory()->create(['user_id' => $user->id, 'title' => 'My Course']);
    Course::factory()->create(['user_id' => $otherUser->id, 'title' => 'Other Course']);

    $owned = Course::ownedBy($user)->get();

    expect($owned)->toHaveCount(1);
    expect($owned->first()->title)->toBe('My Course');
});

test('scopeOwnedBy on lessons filters by course owner', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $myCourse = Course::factory()->create(['user_id' => $user->id]);
    $otherCourse = Course::factory()->create(['user_id' => $otherUser->id]);

    Lesson::factory()->create(['course_id' => $myCourse->id, 'title' => 'My Lesson']);
    Lesson::factory()->create(['course_id' => $otherCourse->id, 'title' => 'Other Lesson']);

    $owned = Lesson::ownedBy($user)->get();

    expect($owned)->toHaveCount(1);
    expect($owned->first()->title)->toBe('My Lesson');
});

test('scopeOwnedBy on steps filters by course owner', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $myCourse = Course::factory()->create(['user_id' => $user->id]);
    $otherCourse = Course::factory()->create(['user_id' => $otherUser->id]);
    $myLesson = Lesson::factory()->create(['course_id' => $myCourse->id]);
    $otherLesson = Lesson::factory()->create(['course_id' => $otherCourse->id]);

    Step::factory()->create(['lesson_id' => $myLesson->id, 'title' => 'My Step']);
    Step::factory()->create(['lesson_id' => $otherLesson->id, 'title' => 'Other Step']);

    $owned = Step::ownedBy($user)->get();

    expect($owned)->toHaveCount(1);
    expect($owned->first()->title)->toBe('My Step');
});
