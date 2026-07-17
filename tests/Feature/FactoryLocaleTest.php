<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    App::setLocale('en');
});

test('course factory uses czech description when locale is cs', function () {
    App::setLocale('cs');
    $course = Course::factory()->create();
    App::setLocale('en');

    expect($course->description)->not->toStartWith('CS:');
});

test('lesson factory uses czech description when locale is cs', function () {
    App::setLocale('cs');
    $lesson = Lesson::factory()->create();
    App::setLocale('en');

    expect($lesson->description)->not->toStartWith('CS:');
});

test('step factory reading default uses czech content when locale is cs', function () {
    App::setLocale('cs');
    $step = Step::factory()->create();
    App::setLocale('en');

    expect($step->reading_content)->not->toStartWith('CS:');
});

test('step factory reading state uses czech content when locale is cs', function () {
    App::setLocale('cs');
    $step = Step::factory()->reading()->create();
    App::setLocale('en');

    expect($step->reading_content)->not->toStartWith('CS:');
});

test('step factory quiz single state uses czech question when locale is cs', function () {
    App::setLocale('cs');
    $step = Step::factory()->quizSingle()->create();
    App::setLocale('en');

    $content = json_decode((string) $step->quiz_content, true);
    expect($content[0]['question'])->not->toStartWith('CS:');
});

test('step factory quiz multiple state uses czech question when locale is cs', function () {
    App::setLocale('cs');
    $step = Step::factory()->quizMultiple()->create();
    App::setLocale('en');

    $content = json_decode((string) $step->quiz_content, true);
    expect($content[0]['question'])->not->toStartWith('CS:');
});

test('step factory quiz text state uses czech question when locale is cs', function () {
    App::setLocale('cs');
    $step = Step::factory()->quizText()->create();
    App::setLocale('en');

    $content = json_decode((string) $step->quiz_content, true);
    expect($content[0]['question'])->not->toStartWith('CS:');
});
