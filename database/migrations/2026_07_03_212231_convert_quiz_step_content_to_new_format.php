<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $steps = DB::table('steps')->where('type', 'quiz')->get();

        foreach ($steps as $step) {
            $content = json_decode((string) $step->content, true);

            if ($content === null || ! is_array($content)) {
                continue;
            }

            $converted = array_map(function (array $question): array {
                $question['answer'] ??= $question['correct_answer']
                ?? $question['correct_answers']
                ?? null;

                unset($question['correct_answer'], $question['correct_answers']);

                $question['explanation'] ??= '';
                $question['difficulty'] ??= 'easy';
                $question['topic'] ??= 'general';

                if ($question['type'] === 'text' && ! array_key_exists('alternatives', $question)) {
                    $question['alternatives'] = null;
                }

                return $question;
            }, $content);

            DB::table('steps')
                ->where('id', $step->id)
                ->update(['content' => json_encode($converted)]);
        }
    }

    public function down(): void
    {
        $steps = DB::table('steps')->where('type', 'quiz')->get();

        foreach ($steps as $step) {
            $content = json_decode((string) $step->content, true);

            if ($content === null || ! is_array($content)) {
                continue;
            }

            $converted = array_map(function (array $question): array {
                $answer = $question['answer'] ?? null;

                if ($question['type'] === 'multiple' && is_array($answer)) {
                    $question['correct_answers'] = $answer;
                } else {
                    $question['correct_answer'] = $answer;
                }

                unset(
                    $question['answer'],
                    $question['explanation'],
                    $question['difficulty'],
                    $question['topic'],
                    $question['alternatives'],
                );

                return $question;
            }, $content);

            DB::table('steps')
                ->where('id', $step->id)
                ->update(['content' => json_encode($converted)]);
        }
    }
};
