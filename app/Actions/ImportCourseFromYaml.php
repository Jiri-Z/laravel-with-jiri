<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\Concerns\ImportYamlConcern;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RuntimeException;

class ImportCourseFromYaml
{
    use ImportYamlConcern;

    public function handle(User $user, string $yamlContent): ImportCourseFromYamlResult
    {
        Gate::forUser($user)->authorize('create', Course::class);

        if (strlen($yamlContent) > self::MAX_FILE_SIZE) {
            throw new RuntimeException('YAML content exceeds maximum allowed file size.');
        }

        $data = $this->parseYaml($yamlContent);

        $this->validateStructure($data);

        /** @var array{course: array{title: string, description?: ?string}, lessons: array<int, array<string, mixed>>} $data */
        return DB::transaction(function () use ($user, $data): ImportCourseFromYamlResult {
            $course = $this->findOrCreateCourse($user, $data['course']);

            /** @var array<int, array<string, mixed>> $lessonsData */
            $lessonsData = $data['lessons'];

            /** @var array<int, Lesson> $lessons */
            $lessons = [];

            foreach ($lessonsData as $index => $lessonData) {
                $lessons[] = $this->createLessonWithSteps($course, $lessonData, $index + 1);
            }

            /** @var Collection<int, Lesson> $lessonCollection */
            $lessonCollection = new Collection($lessons);

            return new ImportCourseFromYamlResult($course, $lessonCollection);
        });
    }

    /** @param array<string, mixed> $data */
    private function validateStructure(array $data): void
    {
        $validator = Validator::make($data, [
            'course' => 'required|array',
            'course.title' => 'required|string|max:255',
            'course.description' => 'nullable|string|max:65535',
            'lessons' => 'required|array|min:1',
            'lessons.*.title' => 'required|string|max:255',
            'lessons.*.description' => 'nullable|string|max:65535',
            'lessons.*.steps' => 'required|array|min:1',
            'lessons.*.steps.*.title' => 'required|string|max:255',
            'lessons.*.steps.*.type' => 'required|string|in:reading,quiz',
            'lessons.*.steps.*.content' => 'required_if:lessons.*.steps.*.type,reading|string',
            'lessons.*.steps.*.questions' => 'required_if:lessons.*.steps.*.type,quiz|array|min:1',
            'lessons.*.steps.*.questions.*.type' => 'required|in:single,multiple,text',
            'lessons.*.steps.*.questions.*.question' => 'required|string',
            'lessons.*.steps.*.questions.*.options' => 'prohibited_if:lessons.*.steps.*.questions.*.type,text|array|min:2',
            'lessons.*.steps.*.questions.*.answer' => 'required',
            'lessons.*.steps.*.questions.*.explanation' => 'nullable|string',
            'lessons.*.steps.*.questions.*.difficulty' => 'nullable|in:easy,medium,hard',
            'lessons.*.steps.*.questions.*.topic' => 'nullable|string',
            'lessons.*.steps.*.questions.*.alternatives' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();

            throw new RuntimeException('YAML structure validation failed: '.implode('; ', $messages));
        }
    }

    /** @param array{title: string, description?: ?string} $courseData */
    private function findOrCreateCourse(User $user, array $courseData): Course
    {
        $course = Course::where('title', $courseData['title'])->first();

        if ($course !== null) {
            return $course;
        }

        $slug = $this->generateUniqueCourseSlug($courseData['title']);

        $maxOrder = Course::max('order');
        $nextOrder = is_numeric($maxOrder) ? (int) $maxOrder + 1 : 1;

        $locale = is_string($user->locale) ? $user->locale : 'en';

        return Course::create([
            'title' => $courseData['title'],
            'slug' => $slug,
            'description' => $courseData['description'] ?? null,
            'published' => false,
            'order' => $nextOrder,
            'locale' => $locale,
            'user_id' => $user->id,
        ]);
    }

    private function generateUniqueCourseSlug(string $title): string
    {
        $slug = Str::slug($title);

        if ($slug === '') {
            $slug = 'course';
        }

        if (! Course::where('slug', $slug)->exists()) {
            return $slug;
        }

        $hash = substr(md5($title.microtime()), 0, 5);
        $candidate = "{$slug}-{$hash}";

        if (! Course::where('slug', $candidate)->exists()) {
            return $candidate;
        }

        do {
            $candidate = "{$slug}-".bin2hex(random_bytes(4));
        } while (Course::where('slug', $candidate)->exists());

        return $candidate;
    }

    /** @param array<string, mixed> $lessonData */
    private function createLessonWithSteps(Course $course, array $lessonData, int $order): Lesson
    {
        $titleValue = $lessonData['title'];

        if (! is_string($titleValue)) {
            throw new RuntimeException('Lesson title must be a string.');
        }

        $existingLesson = Lesson::where('course_id', $course->id)
            ->where('title', $titleValue)
            ->first();

        if ($existingLesson !== null) {
            return $existingLesson;
        }

        $slug = $this->generateUniqueLessonSlug($course, $titleValue);

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => $titleValue,
            'slug' => $slug,
            'description' => (isset($lessonData['description']) && is_string($lessonData['description'])) ? $lessonData['description'] : null,
            'published' => false,
            'order' => $order,
        ]);

        $stepsData = $lessonData['steps'];

        if (! is_array($stepsData)) {
            throw new RuntimeException('Steps must be an array.');
        }

        /** @var array<int, array<string, mixed>> $stepsData */
        foreach ($stepsData as $index => $stepData) {
            $this->createStep($lesson, $stepData, $index + 1);
        }

        $lesson->load('steps');

        return $lesson;
    }
}
