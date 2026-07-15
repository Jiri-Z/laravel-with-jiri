<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\StepType;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class ImportLessonFromYaml
{
    private const int MAX_YAML_NESTING = 20;

    private const int MAX_FILE_SIZE = 50 * 1024 * 1024;

    public function handle(User $user, string $yamlContent, Course $course): ImportLessonFromYamlResult
    {
        if (strlen($yamlContent) > self::MAX_FILE_SIZE) {
            throw new RuntimeException('YAML content exceeds maximum allowed file size.');
        }

        $data = $this->parseYaml($yamlContent);

        $this->validateStructure($data);

        /** @var array{lesson: array{title: string, description?: ?string}, steps: array<int, array<string, mixed>>} $data */
        return DB::transaction(function () use ($course, $data): ImportLessonFromYamlResult {
            $lesson = $this->createLesson($course, $data['lesson']);

            /** @var array<int, array<string, mixed>> $stepsData */
            $stepsData = $data['steps'];

            /** @var array<int, Step> $steps */
            $steps = [];

            foreach ($stepsData as $index => $stepData) {
                $steps[] = $this->createStep($lesson, $stepData, $index + 1);
            }

            /** @var Collection<int, Step> $stepCollection */
            $stepCollection = new Collection($steps);

            return new ImportLessonFromYamlResult($lesson, $stepCollection);
        });
    }

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

    /** @param array<string, mixed> $data */
    private function validateStructure(array $data): void
    {
        $validator = Validator::make($data, [
            'lesson' => 'required|array',
            'lesson.title' => 'required|string|max:255',
            'lesson.description' => 'nullable|string|max:65535',
            'steps' => 'required|array|min:1',
            'steps.*.title' => 'required|string|max:255',
            'steps.*.type' => 'required|string|in:reading,quiz',
            'steps.*.content' => 'required_if:steps.*.type,reading|string',
            'steps.*.questions' => 'required_if:steps.*.type,quiz|array|min:1',
            'steps.*.questions.*.type' => 'required|in:single,multiple,text',
            'steps.*.questions.*.question' => 'required|string',
            'steps.*.questions.*.options' => 'prohibited_if:steps.*.questions.*.type,text|array|min:2',
            'steps.*.questions.*.answer' => 'required',
            'steps.*.questions.*.explanation' => 'nullable|string',
            'steps.*.questions.*.difficulty' => 'nullable|in:easy,medium,hard',
            'steps.*.questions.*.topic' => 'nullable|string',
            'steps.*.questions.*.alternatives' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();

            throw new RuntimeException('YAML structure validation failed: '.implode('; ', $messages));
        }
    }

    /** @param array{title: string, description?: ?string} $lessonData */
    private function createLesson(Course $course, array $lessonData): Lesson
    {
        $slug = $this->generateUniqueLessonSlug($course, $lessonData['title']);

        $maxOrder = Lesson::where('course_id', $course->id)->max('order');
        $nextOrder = is_int($maxOrder) || is_float($maxOrder) ? (int) $maxOrder + 1 : 1;

        return Lesson::create([
            'course_id' => $course->id,
            'title' => $lessonData['title'],
            'slug' => $slug,
            'description' => $lessonData['description'] ?? null,
            'published' => false,
            'order' => $nextOrder,
        ]);
    }

    /** @param array<string, mixed> $stepData */
    private function createStep(Lesson $lesson, array $stepData, int $order): Step
    {
        $typeValue = $stepData['type'];

        if (! is_string($typeValue)) {
            throw new RuntimeException('Step type must be a string.');
        }

        /** @var string $typeValue */
        $type = StepType::from($typeValue);

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
        $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content);
        $content = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', (string) $content);
        $content = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', '', (string) $content);
        $content = preg_replace('/<embed\b[^>]*>/i', '', (string) $content);
        $content = preg_replace('/javascript\s*:/i', '', is_string($content) ? $content : '');
        $content = preg_replace('/\bon\w+\s*=\s*"[^"]*"/i', '', is_string($content) ? $content : '');
        $content = preg_replace("/\bon\w+\s*=\s*'[^']*'/i", '', is_string($content) ? $content : '');

        return trim(is_string($content) ? $content : '');
    }
}
