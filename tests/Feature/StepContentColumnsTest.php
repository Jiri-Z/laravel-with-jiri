<?php

declare(strict_types=1);

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    app()->setLocale('en');
});

test('reading step stores content in reading_content column', function () {
    $step = Step::factory()->reading()->create();

    expect($step->reading_content)->not->toBeNull();
    expect($step->reading_content)->toBeString();
});

test('quiz step stores content in quiz_content column', function () {
    $step = Step::factory()->quiz()->create();

    expect($step->quiz_content)->not->toBeNull();
    expect($step->quiz_content)->toBeString();
});

test('coding step stores content in coding_content column', function () {
    $step = Step::factory()->coding()->create();

    expect($step->coding_content)->not->toBeNull();
    expect($step->coding_content)->toBeString();
});

test('getContentAsArray returns quiz data from quiz_content column', function () {
    $step = Step::factory()->quiz()->create();

    $result = $step->getContentAsArray();

    expect($result)->toBeArray();
    expect($result[0])->toHaveKey('question');
});

test('getContentAsArray returns null for reading steps', function () {
    $step = Step::factory()->reading()->create();

    expect($step->getContentAsArray())->toBeNull();
});

test('getCodingData returns coding data from coding_content column', function () {
    $step = Step::factory()->coding()->create();

    $result = $step->getCodingData();

    expect($result)->toHaveKeys(['prompt', 'initial_code', 'test_code', 'expected_output']);
    expect($result['prompt'])->not->toBe('');
});

test('getContentAsArray does not fall back to deprecated content column', function () {
    $step = Step::factory()->reading()->create();
    $step->update(['reading_content' => null, 'content' => '["should_not_use_this"]']);

    expect($step->fresh()->getContentAsArray())->toBeNull();
});

test('step viewer renders reading content from reading_content column', function () {
    $user = User::factory()->create(['role' => 'student']);
    $course = Course::factory()->published()->create();
    $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
    $step = Step::factory()->reading()->create([
        'lesson_id' => $lesson->id,
        'reading_content' => '# Hello World',
    ]);
    $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

    $this->actingAs($user)
        ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}")
        ->assertOk()
        ->assertSee('Hello World');
});
