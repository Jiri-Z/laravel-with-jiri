<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\ImportLessonFromYaml;
use App\Livewire\AdminLessonImport;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->course = Course::factory()->create(['user_id' => $this->admin->id]);
    $this->validYaml = <<<'YAML'
lesson:
  title: "Introduction to PHP Variables"
  description: "Learn the basics of PHP variables"

steps:
  - title: "What are Variables?"
    type: reading
    content: |
      # Variables

      In PHP, variables start with a `$` sign.

      ```php
      $name = "John";
      $age = 25;
      ```

  - title: "Variable Types Quiz"
    type: quiz
    questions:
      - type: single
        question: "What symbol starts a PHP variable?"
        options:
          - "$"
          - "@"
          - "#"
          - "&"
        answer: 0
        explanation: "PHP variables always start with $."
        difficulty: easy
        topic: php-basics

      - type: text
        question: "What function outputs text in PHP?"
        answer: echo
        alternatives:
          - print
          - printf
        explanation: "echo is the most common output function."
        difficulty: easy
        topic: php-basics

  - title: "Write Your First PHP Code"
    type: coding
    prompt: "Write a PHP script that outputs 'Hello, World!'"
    initial_code: "<?php\n\n// Your code here\n"
    test_code: "<?php\n\n// Tests\n"
    expected_output: "Hello, World!"
YAML;
});

test('action creates lesson with auto-generated slug', function () {
    $action = app(ImportLessonFromYaml::class);
    $result = $action->handle($this->admin, $this->validYaml, $this->course);

    expect($result->lesson)->toBeInstanceOf(Lesson::class)
        ->title->toBe('Introduction to PHP Variables')
        ->slug->toBe('introduction-to-php-variables')
        ->description->toBe('Learn the basics of PHP variables')
        ->course_id->toBe($this->course->id)
        ->published->toBeFalse()
        ->order->toBe(1);

    expect($result->steps)->toHaveCount(3);

    $steps = $result->steps;
    expect($steps[0]->title)->toBe('What are Variables?');
    expect($steps[0]->type->value)->toBe('reading');
    expect($steps[0]->order)->toBe(1);
    expect($steps[0]->published)->toBeTrue();

    expect($steps[1]->title)->toBe('Variable Types Quiz');
    expect($steps[1]->type->value)->toBe('quiz');
    expect($steps[1]->order)->toBe(2);

    $quizContent = json_decode($steps[1]->quiz_content, true);
    expect($quizContent)->toHaveCount(2);
    expect($quizContent[0]['type'])->toBe('single');
    expect($quizContent[0]['answer'])->toBe(0);
    expect($quizContent[1]['type'])->toBe('text');
    expect($quizContent[1]['answer'])->toBe('echo');

    expect($steps[2]->title)->toBe('Write Your First PHP Code');
    expect($steps[2]->type->value)->toBe('coding');
    expect($steps[2]->order)->toBe(3);

    $codingData = json_decode($steps[2]->coding_content, true);
    expect($codingData['prompt'])->toContain('Hello, World!');
    expect($codingData['expected_output'])->toBe('Hello, World!');
});

test('action auto-generates unique slug on collision', function () {
    // First import creates the lesson
    $action = app(ImportLessonFromYaml::class);
    $action->handle($this->admin, $this->validYaml, $this->course);

    // Second import with the same title must generate a different slug
    $result = $action->handle($this->admin, $this->validYaml, $this->course);

    expect($result->lesson->slug)->not->toBe('introduction-to-php-variables');
    expect($result->lesson->title)->toBe('Introduction to PHP Variables');
    expect(Lesson::where('course_id', $this->course->id)->count())->toBe(2);
});

test('action rejects malicious YAML with php object tag', function () {
    $action = app(ImportLessonFromYaml::class);

    $maliciousYaml = <<<'YAML'
lesson:
  title: "Malicious"
steps:
  - title: "Hack"
    type: reading
    content: "test"
YAML;

    // Replace content with an object tag — we test that the parser rejects it
    $maliciousYaml = str_replace('content: "test"', 'content: !php/object "O:1:\"A\":0:{}"', $maliciousYaml);

    expect(fn () => $action->handle($this->admin, $maliciousYaml, $this->course))
        ->toThrow(RuntimeException::class);
});

test('action validates required structure', function () {
    $action = app(ImportLessonFromYaml::class);

    $incompleteYaml = <<<'YAML'
lesson:
  title: "Incomplete"
YAML;

    expect(fn () => $action->handle($this->admin, $incompleteYaml, $this->course))
        ->toThrow(RuntimeException::class, 'steps');
});

test('action rejects unknown step type', function () {
    $action = app(ImportLessonFromYaml::class);

    $badYaml = <<<'YAML'
lesson:
  title: "Bad Type"
steps:
  - title: "Unknown"
    type: video
    content: "test"
YAML;

    expect(fn () => $action->handle($this->admin, $badYaml, $this->course))
        ->toThrow(RuntimeException::class);
});

test('action sanitizes XSS in reading content', function () {
    $action = app(ImportLessonFromYaml::class);

    $xssYaml = <<<'YAML'
lesson:
  title: "XSS Test"
steps:
  - title: "XSS Step"
    type: reading
    content: "<script>alert('xss')</script><iframe src='http://evil.com'></iframe><object data='evil'></object><embed src='evil.swf'><a href='javascript:alert(1)'>click</a>Hello"
YAML;

    $result = $action->handle($this->admin, $xssYaml, $this->course);

    expect($result->steps[0]->reading_content)->not->toContain('<script>');
    expect($result->steps[0]->reading_content)->not->toContain('<iframe');
    expect($result->steps[0]->reading_content)->not->toContain('<object');
    expect($result->steps[0]->reading_content)->not->toContain('<embed');
    expect($result->steps[0]->reading_content)->not->toContain('javascript:');
});

test('action respects max nesting depth in YAML', function () {
    $action = app(ImportLessonFromYaml::class);

    $deepYaml = str_repeat('  ', 30)."title: deep\n";
    $deepYaml = "lesson:\n  title: Deep\nsteps:\n  - title: Deep Step\n    type: reading\n".$deepYaml;
    // This should not be parseable at extreme depth, but we want to ensure content: at least exists
    $deepYaml = "lesson:\n  title: Deep\nsteps:\n  - title: Deep Step\n    type: reading\n    content: shallow\n";

    // Create a deeply nested YAML manually
    $yamlParts = ['lesson:', '  title: Deep', 'steps:', '  - title: Step', '    type: reading', '    content: ok'];
    for ($i = 0; $i < 30; $i++) {
        $yamlParts[] = str_repeat('  ', $i + 4).'nested: value';
    }
    $veryDeepYaml = implode("\n", $yamlParts);

    expect(fn () => $action->handle($this->admin, $veryDeepYaml, $this->course))
        ->toThrow(RuntimeException::class);
});

test('action creates lesson with course owner as owner', function () {
    $action = app(ImportLessonFromYaml::class);
    $result = $action->handle($this->admin, $this->validYaml, $this->course);

    expect($result->lesson->course->user_id)->toBe($this->admin->id);
});

test('livewire component uploads yaml and shows preview', function () {
    $this->actingAs($this->admin);

    $file = UploadedFile::fake()->createWithContent(
        'lesson.yaml',
        $this->validYaml
    );

    Livewire::test(AdminLessonImport::class, ['course' => $this->course])
        ->set('yamlFile', $file)
        ->assertSet('parsedLesson', fn ($lesson) => $lesson !== null && $lesson['title'] === 'Introduction to PHP Variables')
        ->assertSee('Introduction to PHP Variables')
        ->assertSee('What are Variables?')
        ->assertSee('Variable Types Quiz')
        ->assertSee('Write Your First PHP Code');
});

test('livewire component imports on confirm', function () {
    $this->actingAs($this->admin);

    $file = UploadedFile::fake()->createWithContent(
        'lesson.yaml',
        $this->validYaml
    );

    Livewire::test(AdminLessonImport::class, ['course' => $this->course])
        ->set('yamlFile', $file)
        ->call('import')
        ->assertRedirect(route('admin.lessons.index', $this->course))
        ->assertSessionHas('flash');

    expect(Lesson::where('course_id', $this->course->id)->get())->toHaveCount(1);
    expect(Step::whereIn('lesson_id', Lesson::where('course_id', $this->course->id)->pluck('id'))->get())->toHaveCount(3);
});

test('livewire component requires staff role', function () {
    $student = User::factory()->create(['role' => 'student']);
    $this->actingAs($student);

    Livewire::test(AdminLessonImport::class, ['course' => $this->course])
        ->assertForbidden();
});

test('lesson import artisan command works', function () {
    $this->actingAs($this->admin);

    $path = tempnam(sys_get_temp_dir(), 'yaml_').'.yaml';
    file_put_contents($path, $this->validYaml);

    $this->artisan('lesson:import', [
        'file' => $path,
        'course' => (string) $this->course->id,
        '--user' => (string) $this->admin->id,
    ])->assertExitCode(0);

    expect(Lesson::where('course_id', $this->course->id)->get())->toHaveCount(1);

    unlink($path);
});

test('instructor cannot import lessons into another instructors course', function () {
    $instructorA = User::factory()->create(['role' => 'instructor']);
    $courseA = Course::factory()->create(['user_id' => $instructorA->id]);
    $instructorB = User::factory()->create(['role' => 'instructor']);

    Livewire::actingAs($instructorB)
        ->test(AdminLessonImport::class, ['course' => $courseA])
        ->assertForbidden();
});
