<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\SubmitQuizAnswer;
use App\Enums\StepType;
use App\Livewire\Concerns\EnsuresEnrollment;
use App\Livewire\Concerns\ValidatesStepContext;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepAnswer;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuizViewer extends Component
{
    use EnsuresEnrollment;
    use ValidatesStepContext;

    public Course $course;

    public Lesson $lesson;

    public Step $step;

    public bool $submitted = false;

    public bool $isCorrect = false;

    /** @var array<int, int|string|array<int, int|string>|null> */
    public array $answers = [];

    public function mount(Course $course, Lesson $lesson, Step $step): void
    {
        $user = auth()->user();
        abort_unless($user !== null, 403);

        abort_unless($step->type === StepType::Quiz, 404);
        abort_unless($step->getContentAsArray() !== null, 404);
        abort_unless($step->isAccessibleBy($user), 404);

        $this->ensureEnrolled($course);
        $this->ensureContextIsValid($course, $lesson, $step);

        $this->course = $course;
        $this->lesson = $lesson;
        $this->step = $step;

        $existing = StepAnswer::where('user_id', $user->id)
            ->where('step_id', $this->step->id)
            ->get()
            ->keyBy('question_index');

        if ($existing->isNotEmpty()) {
            $this->submitted = true;

            $allCorrect = true;
            foreach ($existing as $entry) {
                if (! $entry->is_correct) {
                    $allCorrect = false;
                }
            }
            $this->isCorrect = $allCorrect;

            $questions = $this->step->getContentAsArray();

            if ($questions !== null) {
                foreach ($existing as $entry) {
                    $raw = $questions[$entry->question_index] ?? null;
                    $question = is_array($raw) ? $raw : [];
                    $type = is_string($question['type'] ?? null) ? $question['type'] : 'single';

                    if ($type === 'multiple') {
                        $decoded = json_decode((string) $entry->answer, true);

                        if (is_array($decoded)) {
                            $values = [];
                            foreach ($decoded as $v) {
                                if (is_int($v) || is_string($v)) {
                                    $values[] = $v;
                                }
                            }
                            $this->answers[$entry->question_index] = $values;
                        } else {
                            $this->answers[$entry->question_index] = [];
                        }
                    } else {
                        $answer = $entry->answer;
                        $this->answers[$entry->question_index] = is_int($answer) || is_string($answer) ? $answer : null;
                    }
                }
            }
        }
    }

    public function submit(): void
    {
        if ($this->submitted) {
            return;
        }

        $user = auth()->user();
        if ($user === null) { return; }

        $questions = $this->step->getContentAsArray();

        if ($questions === null) {
            abort(404);
        }

        $allCorrect = true;

        foreach ($questions as $index => $question) {
            $answer = $this->answers[$index] ?? null;

            $result = (new SubmitQuizAnswer)->handle(
                $user,
                $this->step,
                $answer,
                questionIndex: (int) $index,
            );

            if (! $result->isCorrect) {
                $allCorrect = false;
            }
        }

        $this->submitted = true;
        $this->isCorrect = $allCorrect;
    }

    public function render(): View
    {
        return view('livewire.quiz-viewer');
    }
}
