<?php

declare(strict_types=1);

namespace App\Actions\Concerns;

use App\Enums\StepType;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Services\SanitizeHtmlService;
use Exception;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

trait ImportYamlConcern
{
    private const int MAX_YAML_NESTING = 20;

    private const int MAX_FILE_SIZE = 50 * 1024 * 1024;

    /** @return array<string, mixed> */
    private function parseYaml(string $yamlContent): array
    {
        $nestingLevel = $this->getMaxNestingLevel($yamlContent);

        if ($nestingLevel > self::MAX_YAML_NESTING) {
            throw new RuntimeException('YAML nesting depth exceeds maximum allowed ('.self::MAX_YAML_NESTING.').');
        }

        try {
            $data = Yaml::parse($yamlContent, Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE);
        } catch (Exception $e) {
            throw new RuntimeException('Failed to parse YAML: '.$e->getMessage(), 0, $e);
        }

        if (! is_array($data)) {
            throw new RuntimeException('YAML must contain a top-level mapping.');
        }

        /** @var array<string, mixed> */
        return $data;
    }

    private function getMaxNestingLevel(string $yaml): int
    {
        $lines = explode("\n", $yaml);
        $maxLevel = 0;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '' || $trimmed[0] === '#') {
                continue;
            }

            $indent = 0;
            $len = strlen($line);

            while ($indent < $len && ($line[$indent] === ' ' || $line[$indent] === "\t")) {
                $indent++;
            }

            $level = (int) floor($indent / 2);
            $maxLevel = max($maxLevel, $level);
        }

        return $maxLevel;
    }

    /** @param array<string, mixed> $stepData */
    private function createStep(Lesson $lesson, array $stepData, int $order): Step
    {
        $typeValue = $stepData['type'];

        if (! is_string($typeValue)) {
            throw new RuntimeException('Step type must be a string.');
        }

        $existingStep = Step::where('lesson_id', $lesson->id)
            ->where('title', $stepData['title'] ?? '')
            ->first();

        if ($existingStep !== null) {
            return $existingStep;
        }

        $type = StepType::tryFrom($typeValue);

        if ($type === null) {
            throw new RuntimeException("Unknown step type: {$typeValue}");
        }

        $attributes = [
            'lesson_id' => $lesson->id,
            'title' => $stepData['title'] ?? '',
            'type' => $type,
            'order' => $order,
            'published' => true,
        ];

        $rawContent = $stepData['content'] ?? '';
        $rawQuestions = $stepData['questions'] ?? [];

        match ($type) {
            StepType::Reading => $attributes['reading_content'] = $this->sanitizeContent(
                is_string($rawContent) ? $rawContent : ''
            ),
            StepType::Quiz => $attributes['quiz_content'] = json_encode(
                $this->buildQuizQuestions(is_array($rawQuestions) ? $rawQuestions : [])
            ),
        };

        return Step::create($attributes);
    }

    private function generateUniqueLessonSlug(Course $course, string $title): string
    {
        $slug = Str::slug($title);

        if ($slug === '') {
            $slug = 'lesson';
        }

        $courseId = $course->id;

        if (! Lesson::where('course_id', $courseId)->where('slug', $slug)->exists()) {
            return $slug;
        }

        $hash = substr(md5($title.microtime()), 0, 5);
        $candidate = "{$slug}-{$hash}";

        if (! Lesson::where('course_id', $courseId)->where('slug', $candidate)->exists()) {
            return $candidate;
        }

        do {
            $candidate = "{$slug}-".bin2hex(random_bytes(4));
        } while (Lesson::where('course_id', $courseId)->where('slug', $candidate)->exists());

        return $candidate;
    }

    /**
     * @param  array<mixed>  $questions
     * @return array<int, array<string, mixed>>
     */
    private function buildQuizQuestions(array $questions): array
    {
        $result = [];

        foreach ($questions as $question) {
            if (! is_array($question)) {
                continue;
            }

            /** @var array<string, mixed> $question */
            $entry = [
                'type' => $question['type'] ?? 'single',
                'question' => $question['question'] ?? '',
                'answer' => $question['answer'] ?? '',
                'explanation' => $question['explanation'] ?? '',
                'difficulty' => $question['difficulty'] ?? 'easy',
                'topic' => $question['topic'] ?? 'general',
            ];

            if (($question['type'] ?? 'text') !== 'text') {
                $entry['options'] = $question['options'] ?? [];
            }

            if (isset($question['type']) && $question['type'] === 'text' && isset($question['alternatives'])) {
                $entry['alternatives'] = $question['alternatives'];
            }

            $result[] = $entry;
        }

        return $result;
    }

    private function sanitizeContent(string $content): string
    {
        return app(SanitizeHtmlService::class)->clean($content);
    }
}
