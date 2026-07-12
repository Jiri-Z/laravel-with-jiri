<?php

use App\Models\TriviaQuestion;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Yaml\Yaml;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

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
