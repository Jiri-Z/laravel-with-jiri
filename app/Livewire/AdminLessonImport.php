<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\ImportLessonFromYaml;
use App\Models\Course;
use App\Models\Lesson;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\Yaml\Yaml;
use Throwable;

#[Layout('layouts.app')]
class AdminLessonImport extends Component
{
    use WithFileUploads;

    public Course $course;

    #[Validate(['yamlFile' => 'required|file|mimes:yaml,yml,txt|max:51200'])]
    public ?UploadedFile $yamlFile = null;

    public ?string $error = null;

    /** @var ?array{title: string, description?: ?string} */
    public ?array $parsedLesson = null;

    /** @var ?array<int, array<string, mixed>> */
    public ?array $parsedSteps = null;

    public bool $importing = false;

    public function mount(Course $course): void
    {
        $this->authorize('create', Lesson::class);
        $this->course = $course;
    }

    public function updatedYamlFile(): void
    {
        $this->error = null;
        $this->parsedLesson = null;
        $this->parsedSteps = null;

        $this->validate();

        if ($this->yamlFile === null) {
            return;
        }

        try {
            $content = $this->yamlFile->get();

            if (! is_string($content)) {
                $this->error = 'Failed to read uploaded file.';

                return;
            }

            $data = Yaml::parse($content, Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE);

            if (! is_array($data)) {
                $this->error = 'YAML must contain a top-level mapping.';

                return;
            }

            if (! isset($data['lesson']) || ! is_array($data['lesson'])) {
                $this->error = 'YAML must contain a "lesson" section.';

                return;
            }

            if (! isset($data['steps']) || ! is_array($data['steps'])) {
                $this->error = 'YAML must contain a "steps" section.';

                return;
            }

            /** @var array{title: string, description?: ?string} $lesson */
            $lesson = $data['lesson'];
            $this->parsedLesson = $lesson;

            /** @var array<int, array<string, mixed>> $steps */
            $steps = $data['steps'];
            $this->parsedSteps = $steps;
        } catch (Throwable $e) {
            $this->error = 'Failed to parse YAML: ' . $e->getMessage();
        }
    }

    public function import(): void
    {
        $this->authorize('create', Lesson::class);

        if ($this->parsedLesson === null || $this->parsedSteps === null) {
            $this->error = 'No YAML file has been parsed.';

            return;
        }

        if ($this->yamlFile === null) {
            $this->error = 'No YAML file uploaded.';

            return;
        }

        $this->importing = true;

        try {
            $content = $this->yamlFile->get();

            if (! is_string($content)) {
                $this->error = 'Failed to read uploaded file.';
                $this->importing = false;

                return;
            }

            $user = auth()->user();

            if ($user === null) {
                $this->error = 'You must be logged in.';
                $this->importing = false;

                return;
            }

            $action = app(ImportLessonFromYaml::class);
            $result = $action->handle($user, $content, $this->course);

            session()->flash('flash', [
                'type' => 'success',
                'message' => "Lesson \"{$result->lesson->title}\" imported with {$result->steps->count()} step(s).",
            ]);

            $this->redirect(route('admin.lessons.index', $this->course), navigate: true);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->importing = false;
        }
    }

    public function removeFile(): void
    {
        $this->yamlFile = null;
        $this->parsedLesson = null;
        $this->parsedSteps = null;
        $this->error = null;
    }

    public function render(): View
    {
        return view('livewire.admin-lesson-import');
    }
}
