<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TriviaQuestion;
use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;

class TriviaQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $files = glob(database_path('data/trivia/*.yaml'));

        if ($files === false || $files === []) {
            return;
        }

        TriviaQuestion::truncate();

        $records = [];

        foreach ($files as $filepath) {
            $questions = Yaml::parseFile($filepath);

            if ($questions === null) {
                continue;
            }

            foreach ($questions as $question) {
                $records[] = [
                    'topic' => $question['topic'],
                    'type' => $question['type'],
                    'difficulty' => $question['difficulty'],
                    'question' => $question['question'],
                    'options' => isset($question['options']) ? json_encode($question['options']) : null,
                    'answer' => $question['type'] === 'multiple'
                        ? json_encode($question['answers'])
                        : $question['answer'],
                    'alternatives' => isset($question['alternatives']) ? json_encode($question['alternatives']) : null,
                    'explanation' => $question['explanation'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        TriviaQuestion::insert($records);
    }
}
