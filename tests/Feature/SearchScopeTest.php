<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

test('course search with percent wildcard does not match all rows', function () {
    Course::factory()->create(['title' => 'PHP Basics', 'slug' => 'php-basics', 'user_id' => $this->admin->id]);

    $results = Course::query()->search('a%')->get();

    expect($results)->toHaveCount(0);
});

test('course search with underscore wildcard matches literal underscore', function () {
    Course::factory()->create(['title' => 'A_B Course', 'slug' => 'a-b-course', 'user_id' => $this->admin->id]);

    $results = Course::query()->search('A_B')->get();

    expect($results)->toHaveCount(1);
});

test('lesson search with percent wildcard does not match all rows', function () {
    $course = Course::factory()->create(['user_id' => $this->admin->id]);
    Lesson::factory()->create(['course_id' => $course->id, 'title' => 'Variables']);

    $results = Lesson::query()->search('a%')->get();

    expect($results)->toHaveCount(0);
});

test('step search with percent wildcard does not match all rows', function () {
    $course = Course::factory()->create(['user_id' => $this->admin->id]);
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);
    Step::factory()->create(['lesson_id' => $lesson->id, 'title' => 'Reading']);

    $results = Step::query()->search('a%')->get();

    expect($results)->toHaveCount(0);
});
