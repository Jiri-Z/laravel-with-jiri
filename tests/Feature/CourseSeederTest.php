<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\StepType;
use App\Models\Course;
use App\Models\User;
use Database\Seeders\CourseSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

uses(TestCase::class);

test('seeds quiz type step', function () {
    $this->seed(CourseSeeder::class);

    $this->assertDatabaseHas('steps', [
        'type' => StepType::Quiz->value,
    ]);
});

test('seeds coding type step', function () {
    $tempFile = database_path('data/en/test-coding-course.php');

    try {
        file_put_contents($tempFile, '<?php
            return [
                "title" => "Test Coding Course",
                "slug" => "test-coding-course",
                "description" => "Test",
                "user_email" => "instructor@example.com",
                "lessons" => [[
                    "title" => "Lesson 1",
                    "slug" => "lesson-1",
                    "description" => "Test lesson",
                    "steps" => [[
                        "title" => "Coding Step",
                        "type" => "coding",
                        "content" => json_encode(["prompt" => "Write a loop", "initial_code" => "<?php", "test_code" => "", "expected_output" => ""]),
                    ]],
                ]],
            ];');

        $this->seed(CourseSeeder::class);

        $this->assertDatabaseHas('steps', [
            'type' => StepType::Coding->value,
        ]);
    } finally {
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }
});

test('seeds english and czech courses via database seeder', function () {
    $this->seed(DatabaseSeeder::class);

    $enCount = Course::where('slug', 'like', 'cs-%')->count();
    $csCount = Course::where('slug', 'not like', 'cs-%')->count();

    expect(Course::count())->toBe(4); // 2 en + 2 cs
    expect($enCount)->toBe(2);
    expect($csCount)->toBe(2);
});

test('course seeder is idempotent for users', function () {
    $this->seed(CourseSeeder::class);
    $userCount = User::count();

    $this->seed(CourseSeeder::class);

    expect(User::count())->toBe($userCount);
});

test('course seeder creates content for active locale', function () {
    App::setLocale('cs');
    $this->seed(CourseSeeder::class);
    App::setLocale('en');

    $course = Course::where('slug', 'cs-laravel-basics')->first();
    expect($course)->not->toBeNull();
    expect($course->title)->toStartWith('CS:');
});

test('course seeder treats each locale run independently', function () {
    App::setLocale('cs');
    $this->seed(CourseSeeder::class);
    App::setLocale('en');

    // Run again in English
    $this->seed(CourseSeeder::class);

    // Should have both locale versions
    expect(Course::where('slug', 'laravel-basics')->exists())->toBeTrue();
    expect(Course::where('slug', 'cs-laravel-basics')->exists())->toBeTrue();
});
