<?php

use App\Actions\ImportCourseFromYaml;
use App\Actions\ImportLessonFromYaml;
use App\Models\Course;
use App\Models\TriviaQuestion;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Yaml\Yaml;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('lesson:import {file : Path to YAML file} {course : Course ID} {--user= : User ID (defaults to first admin)}', function () {
    $filePath = $this->argument('file');

    if (! file_exists($filePath) || ! is_readable($filePath)) {
        $this->error("File not found or not readable: {$filePath}");

        return 1;
    }

    $course = Course::find((int) $this->argument('course'));

    if ($course === null) {
        $this->error('Course not found.');

        return 1;
    }

    $userId = $this->option('user');

    if ($userId !== null) {
        $user = User::find((int) $userId);
    } else {
        $user = User::where('role', 'admin')->first();
    }

    if ($user === null) {
        $this->error('User not found.');

        return 1;
    }

    $yamlContent = file_get_contents($filePath);

    try {
        $action = app(ImportLessonFromYaml::class);
        $result = $action->handle($user, $yamlContent, $course);

        $this->info("Lesson \"{$result->lesson->title}\" imported successfully.");
        $this->line("  ID: {$result->lesson->id}");
        $this->line("  Slug: {$result->lesson->slug}");
        $this->line("  Steps: {$result->steps->count()}");
    } catch (Exception $e) {
        $this->error($e->getMessage());

        return 1;
    }

    return 0;
})->purpose('Import a lesson from a YAML file');

Artisan::command('course:import {file : Path to YAML file} {--user= : User ID (defaults to first admin)}', function () {
    $filePath = $this->argument('file');

    if (! file_exists($filePath) || ! is_readable($filePath)) {
        $this->error("File not found or not readable: {$filePath}");

        return 1;
    }

    $userId = $this->option('user');

    if ($userId !== null) {
        $user = User::find((int) $userId);
    } else {
        $user = User::where('role', 'admin')->first();
    }

    if ($user === null) {
        $this->error('User not found.');

        return 1;
    }

    $yamlContent = file_get_contents($filePath);

    try {
        $action = app(ImportCourseFromYaml::class);
        $result = $action->handle($user, $yamlContent);

        $this->info("Course \"{$result->course->title}\" imported successfully.");
        $this->line("  ID: {$result->course->id}");
        $this->line("  Slug: {$result->course->slug}");
        $this->line("  Lessons: {$result->lessons->count()}");
    } catch (Exception $e) {
        $this->error($e->getMessage());

        return 1;
    }

    return 0;
})->purpose('Import a course with lessons from a YAML file');

Artisan::command('trivia:prune', function () {
    $totalPruned = 0;

    /** @var array<string, string> */
    $locales = ['en' => database_path('data/trivia'), 'cs' => database_path('data/trivia/cs')];

    foreach ($locales as $locale => $directory) {
        if (! is_dir($directory)) {
            continue;
        }

        $files = glob($directory.'/*.yaml');

        if ($files === false || $files === []) {
            continue;
        }

        $validQuestions = [];

        foreach ($files as $filepath) {
            $questions = Yaml::parseFile($filepath);

            if ($questions === null) {
                continue;
            }

            foreach ($questions as $question) {
                $validQuestions[$question['question']] = true;
            }
        }

        $dbQuestions = TriviaQuestion::where('locale', $locale)
            ->pluck('id', 'question');

        $staleIds = [];

        foreach ($dbQuestions as $question => $id) {
            if (! isset($validQuestions[$question])) {
                $staleIds[] = $id;
            }
        }

        if ($staleIds !== []) {
            foreach (array_chunk($staleIds, 50) as $chunk) {
                TriviaQuestion::whereIn('id', $chunk)->delete();
            }

            $count = count($staleIds);
            $this->info("Pruned {$count} stale {$locale} question(s).");
            $totalPruned += $count;
        }
    }

    if ($totalPruned === 0) {
        $this->info('No stale questions found.');
    }

    $enCount = TriviaQuestion::where('locale', 'en')->count();
    $csCount = TriviaQuestion::where('locale', 'cs')->count();
    $this->line("EN: {$enCount}, CS: {$csCount}");
})->purpose('Remove trivia questions not in YAML source files');
