<?php

declare(strict_types=1);

use App\Models\TriviaQuestion;
use Database\Seeders\TriviaQuestionSeeder;
use Symfony\Component\Yaml\Yaml;
use Tests\TestCase;

uses(TestCase::class);

function countYamlQuestions(string $directory): int
{
    $files = glob($directory.'/*.yaml');

    if ($files === false || $files === []) {
        return 0;
    }

    $total = 0;

    foreach ($files as $filepath) {
        $questions = Yaml::parseFile($filepath);

        if ($questions !== null) {
            $total += count($questions);
        }
    }

    return $total;
}

test('seeds all english trivia questions from yaml', function () {
    $this->seed(TriviaQuestionSeeder::class);

    $expected = countYamlQuestions(database_path('data/trivia'));
    $actual = TriviaQuestion::where('locale', 'en')->count();

    expect($actual)->toBe($expected);
});

test('seeds all czech trivia questions from yaml', function () {
    $this->seed(TriviaQuestionSeeder::class);

    $expected = countYamlQuestions(database_path('data/trivia/cs'));
    $actual = TriviaQuestion::where('locale', 'cs')->count();

    expect($actual)->toBe($expected);
});

test('seeds 18 unique topics per locale', function () {
    $this->seed(TriviaQuestionSeeder::class);

    $enTopics = TriviaQuestion::where('locale', 'en')->distinct('topic')->pluck('topic')->count();
    $csTopics = TriviaQuestion::where('locale', 'cs')->distinct('topic')->pluck('topic')->count();

    expect($enTopics)->toBe(18);
    expect($csTopics)->toBe(18);
});

test('replaces partial data after interrupted seed', function () {
    // Simulate a partial/interrupted seed: insert only 5 records for EN
    TriviaQuestion::insert([
        [
            'topic' => 'routing',
            'type' => 'single',
            'difficulty' => 'easy',
            'question' => 'Partial?',
            'answer' => 'yes',
            'explanation' => 'test',
            'locale' => 'en',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'topic' => 'routing',
            'type' => 'single',
            'difficulty' => 'easy',
            'question' => 'Partial 2?',
            'answer' => 'yes',
            'explanation' => 'test',
            'locale' => 'en',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'topic' => 'routing',
            'type' => 'single',
            'difficulty' => 'easy',
            'question' => 'Partial 3?',
            'answer' => 'yes',
            'explanation' => 'test',
            'locale' => 'en',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'topic' => 'routing',
            'type' => 'single',
            'difficulty' => 'easy',
            'question' => 'Partial 4?',
            'answer' => 'yes',
            'explanation' => 'test',
            'locale' => 'en',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'topic' => 'routing',
            'type' => 'single',
            'difficulty' => 'easy',
            'question' => 'Partial 5?',
            'answer' => 'yes',
            'explanation' => 'test',
            'locale' => 'en',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    // This should replace partial data with full set
    $this->seed(TriviaQuestionSeeder::class);

    $expected = countYamlQuestions(database_path('data/trivia'));
    $actual = TriviaQuestion::where('locale', 'en')->count();

    expect($actual)->toBe($expected);
});

test('re-seeding replaces existing data completely', function () {
    $this->seed(TriviaQuestionSeeder::class);
    $firstTotal = TriviaQuestion::count();

    $this->seed(TriviaQuestionSeeder::class);
    $secondTotal = TriviaQuestion::count();

    expect($secondTotal)->toBe($firstTotal);
});

test('is idempotent across runs', function () {
    $this->seed(TriviaQuestionSeeder::class);
    $firstCount = TriviaQuestion::count();

    $this->seed(TriviaQuestionSeeder::class);
    $secondCount = TriviaQuestion::count();

    expect($secondCount)->toBe($firstCount);
});
