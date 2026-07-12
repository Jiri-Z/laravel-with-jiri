<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\StepType;
use App\Jobs\LogQuizAttempt;
use App\Livewire\QuizViewer;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->course = Course::factory()->published()->create();
    $this->course->enrollments()->create(['user_id' => $this->user->id, 'enrolled_at' => now()]);
    $this->lesson = Lesson::factory()->published()->create(['course_id' => $this->course->id]);
    $this->step = Step::factory()->create([
        'lesson_id' => $this->lesson->id,
        'type' => StepType::Quiz,
        'quiz_content' => json_encode([
            [
                'type' => 'single',
                'question' => 'What is 2+2?',
                'options' => ['3', '4', '5'],
                'answer' => 1,
                'explanation' => 'Basic math',
                'difficulty' => 'easy',
                'topic' => 'math',
            ],
            [
                'type' => 'single',
                'question' => 'What is the capital of France?',
                'options' => ['London', 'Paris', 'Berlin'],
                'answer' => 1,
                'explanation' => 'Geography',
                'difficulty' => 'easy',
                'topic' => 'geography',
            ],
        ]),
        'reading_content' => null,
    ]);
});

it('dispatches LogQuizAttempt after submitting all questions with correct data', function () {
    Queue::fake();

    Livewire::actingAs($this->user)
        ->test(QuizViewer::class, [
            'course' => $this->course,
            'lesson' => $this->lesson,
            'step' => $this->step,
        ])
        ->set('answers.0', 1)
        ->set('answers.1', 1)
        ->call('submit');

    Queue::assertPushed(function (LogQuizAttempt $job) {
        return $job->userId === $this->user->id
            && $job->stepId === $this->step->id
            && $job->score === 2
            && $job->total === 2;
    });
});
