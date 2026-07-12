<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\ImportCourseFromYaml;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();

    $this->validYaml = <<<'YAML'
course:
  title: "Introduction to PHP"
  description: "Learn PHP fundamentals"

lessons:
  - title: "Variables"
    description: "Understanding PHP variables"
    steps:
      - title: "What are Variables?"
        type: reading
        content: |
          Variables store data in PHP.

      - title: "Variables Quiz"
        type: quiz
        questions:
          - type: single
            question: "What symbol starts a variable?"
            options:
              - "$"
              - "@"
            answer: 0

      - title: "Write a Variable"
        type: coding
        prompt: "Write a PHP variable assignment"
        initial_code: "<?php\n\n"
        test_code: "assert(true);"
        expected_output: ""

  - title: "Arrays"
    steps:
      - title: "Array Basics"
        type: reading
        content: |
          Arrays hold multiple values.
YAML;
});

test('action creates course with auto-generated slug', function () {
    $action = app(ImportCourseFromYaml::class);
    $result = $action->handle($this->admin, $this->validYaml);

    expect($result->course)->toBeInstanceOf(Course::class)
        ->title->toBe('Introduction to PHP')
        ->slug->toBe('introduction-to-php')
        ->description->toBe('Learn PHP fundamentals')
        ->published->toBeFalse()
        ->order->toBe(1)
        ->user_id->toBe($this->admin->id);
});

test('action creates all lessons and steps', function () {
    $action = app(ImportCourseFromYaml::class);
    $result = $action->handle($this->admin, $this->validYaml);

    expect($result->lessons)->toHaveCount(2);

    $lesson1 = $result->lessons[0];
    expect($lesson1->title)->toBe('Variables');
    expect($lesson1->slug)->toBe('variables');
    expect($lesson1->course_id)->toBe($result->course->id);
    expect($lesson1->order)->toBe(1);

    expect($lesson1->steps)->toHaveCount(3);
    expect($lesson1->steps[0]->type->value)->toBe('reading');
    expect($lesson1->steps[1]->type->value)->toBe('quiz');
    expect($lesson1->steps[2]->type->value)->toBe('coding');

    $lesson2 = $result->lessons[1];
    expect($lesson2->title)->toBe('Arrays');
    expect($lesson2->slug)->toBe('arrays');
    expect($lesson2->order)->toBe(2);

    expect($lesson2->steps)->toHaveCount(1);
    expect($lesson2->steps[0]->type->value)->toBe('reading');
});

test('action is idempotent — skips existing course with same slug', function () {
    $action = app(ImportCourseFromYaml::class);
    $first = $action->handle($this->admin, $this->validYaml);

    $second = $action->handle($this->admin, $this->validYaml);

    expect($second->course->id)->toBe($first->course->id);
    expect(Course::count())->toBe(1);
});

test('action rejects YAML without course title', function () {
    $action = app(ImportCourseFromYaml::class);

    $invalidYaml = <<<'YAML'
course:
  description: "No title here"
lessons:
  - title: "Lesson"
    steps:
      - title: "Step"
        type: reading
        content: "test"
YAML;

    expect(fn () => $action->handle($this->admin, $invalidYaml))
        ->toThrow(\RuntimeException::class, 'title');
});

test('action rejects YAML without lessons', function () {
    $action = app(ImportCourseFromYaml::class);

    $invalidYaml = <<<'YAML'
course:
  title: "No lessons"
YAML;

    expect(fn () => $action->handle($this->admin, $invalidYaml))
        ->toThrow(\RuntimeException::class, 'lessons');
});

test('action rejects unknown step type', function () {
    $action = app(ImportCourseFromYaml::class);

    $badYaml = <<<'YAML'
course:
  title: "Bad Type"
lessons:
  - title: "Lesson"
    steps:
      - title: "Unknown"
        type: video
        content: "test"
YAML;

    expect(fn () => $action->handle($this->admin, $badYaml))
        ->toThrow(\RuntimeException::class);
});

test('action sanitizes XSS in reading content', function () {
    $action = app(ImportCourseFromYaml::class);

    $xssYaml = <<<'YAML'
course:
  title: "XSS Test"
lessons:
  - title: "Lesson"
    steps:
      - title: "XSS Step"
        type: reading
        content: "<script>alert('xss')</script>Hello"
YAML;

    $result = $action->handle($this->admin, $xssYaml);
    expect($result->lessons[0]->steps[0]->reading_content)->not->toContain('<script>');
});

test('action rejects deeply nested YAML', function () {
    $action = app(ImportCourseFromYaml::class);

    $yamlParts = [
        'course:',
        '  title: Deep',
        'lessons:',
        '  - title: Lesson',
        '    steps:',
        '      - title: Step',
        '        type: reading',
        '        content: ok',
    ];
    for ($i = 0; $i < 30; $i++) {
        $yamlParts[] = str_repeat('  ', $i + 5).'nested: value';
    }
    $deepYaml = implode("\n", $yamlParts);

    expect(fn () => $action->handle($this->admin, $deepYaml))
        ->toThrow(\RuntimeException::class);
});

test('action rejects yaml with php object tag', function () {
    $action = app(ImportCourseFromYaml::class);

    $maliciousYaml = <<<'YAML'
course:
  title: "Malicious"
lessons:
  - title: "Lesson"
    steps:
      - title: "Step"
        type: reading
        content: "test"
YAML;

    $maliciousYaml = str_replace('content: "test"', 'content: !php/object "O:1:\"A\":0:{}"', $maliciousYaml);

    expect(fn () => $action->handle($this->admin, $maliciousYaml))
        ->toThrow(\RuntimeException::class);
});

test('course import artisan command creates course', function () {
    $path = tempnam(sys_get_temp_dir(), 'course_').'.yaml';
    file_put_contents($path, $this->validYaml);

    $this->artisan('course:import', [
        'file' => $path,
        '--user' => (string) $this->admin->id,
    ])->assertExitCode(0);

    expect(Course::count())->toBe(1);
    expect(Lesson::count())->toBe(2);
    expect(Step::count())->toBe(4);

    unlink($path);
});

test('course import command defaults to first admin when no user option', function () {
    $path = tempnam(sys_get_temp_dir(), 'course_').'.yaml';
    file_put_contents($path, $this->validYaml);

    $this->artisan('course:import', [
        'file' => $path,
    ])->assertExitCode(0);

    expect(Course::first()->user_id)->toBe($this->admin->id);

    unlink($path);
});
