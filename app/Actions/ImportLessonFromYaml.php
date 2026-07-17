<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Concerns\ImportYamlConcern;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class ImportLessonFromYaml
{
    use ImportYamlConcern;

    public function handle(User $user, string $yamlContent, Course $course): ImportLessonFromYamlResult
    {
        Gate::forUser($user)->authorize('create', Lesson::class);

        if ($user->isInstructor() && $course->user_id !== $user->id) {
            throw new AuthorizationException('You can only import lessons into your own courses.');
        }

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
        $nextOrder = is_numeric($maxOrder) ? (int) $maxOrder + 1 : 1;

        return Lesson::create([
            'course_id' => $course->id,
            'title' => $lessonData['title'],
            'slug' => $slug,
            'description' => $lessonData['description'] ?? null,
            'published' => false,
            'order' => $nextOrder,
        ]);
    }
}
