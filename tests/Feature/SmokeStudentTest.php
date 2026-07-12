<?php

namespace Tests\Feature;

use App\Enums\StepType;
use App\Livewire\QuizViewer;
use App\Livewire\StepViewer;
use App\Models\Course;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class SmokeStudentTest extends TestCase
{
    private function createStudentUser(): User
    {
        return User::factory()->create(['role' => 'student']);
    }

    private function createEnrolledCourse(User $user, array $overrides = []): Course
    {
        $course = Course::factory()->published()->create($overrides);
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        return $course;
    }

    public function test_student_can_view_courses_and_coding_step(): void
    {
        $user = $this->createStudentUser();
        $course = $this->createEnrolledCourse($user);

        $lesson = $course->lessons()->create([
            'title' => 'Test Lesson',
            'slug' => 'test-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Test Coding',
            'type' => StepType::Coding,
            'coding_content' => json_encode([
                'prompt' => 'Write PHP code',
                'initial_code' => "<?php\n",
                'test_code' => "<?php\necho 'ok';",
                'expected_output' => 'ok',
            ]),
            'order' => 1,
        ]);

        $this->actingAs($user)->get('/courses')
            ->assertOk()
            ->assertSee($course->title);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");
        $response->assertOk();
        $response->assertSee('Write PHP code');
        $response->assertSee('x-data');
        $response->assertSee('codingViewer');
        $response->assertSee(__('steps.coding_run'));
        $response->assertSee(__('steps.coding_check'));
    }

    public function test_student_can_view_lesson_detail_page(): void
    {
        $user = $this->createStudentUser();
        $course = $this->createEnrolledCourse($user, ['title' => 'Smoke Course']);
        $lesson = $course->lessons()->create([
            'title' => 'Smoke Lesson',
            'slug' => 'smoke-lesson',
            'published' => true,
            'order' => 1,
        ]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}");
        $response->assertOk();
        $response->assertSee('Smoke Lesson');
        $response->assertSee(__('lessons.no_steps'));
    }

    public function test_quiz_step_page_renders_question_and_options(): void
    {
        $user = $this->createStudentUser();
        $course = $this->createEnrolledCourse($user);

        $lesson = $course->lessons()->create([
            'title' => 'Quiz Lesson',
            'slug' => 'quiz-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Quiz Step',
            'type' => StepType::Quiz,
            'quiz_content' => json_encode([
                ['type' => 'single', 'question' => 'What is 2+2?', 'options' => ['3', '4', '5'], 'answer' => 1, 'explanation' => '', 'difficulty' => 'easy', 'topic' => 'math'],
            ]),
            'order' => 1,
        ]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");
        $response->assertOk();
        $response->assertSee('Quiz Step');
        $response->assertSee('What is 2+2?');
        $response->assertSee(__('steps.quiz_submit'));
    }

    public function test_reading_step_page_loads(): void
    {
        $user = $this->createStudentUser();
        $course = $this->createEnrolledCourse($user);

        $lesson = $course->lessons()->create([
            'title' => 'Reading Lesson',
            'slug' => 'reading-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Reading Step',
            'type' => StepType::Reading,
            'reading_content' => 'Some reading content here',
            'order' => 1,
        ]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");
        $response->assertOk();
        $response->assertSee('Some reading content here');
        $response->assertSee(__('steps.mark_complete'));
    }

    public function test_mark_reading_step_complete(): void
    {
        $user = $this->createStudentUser();
        $course = $this->createEnrolledCourse($user);

        $lesson = $course->lessons()->create([
            'title' => 'Complete Lesson',
            'slug' => 'complete-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Complete Step',
            'type' => StepType::Reading,
            'reading_content' => 'Content',
            'order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSet('completed', false)
            ->call('complete')
            ->assertSet('completed', true);
    }

    public function test_quiz_single_submit_via_livewire(): void
    {
        $user = $this->createStudentUser();
        $course = $this->createEnrolledCourse($user);

        $lesson = $course->lessons()->create([
            'title' => 'Quiz Submit Lesson',
            'slug' => 'quiz-submit-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Quiz Submit Step',
            'type' => StepType::Quiz,
            'quiz_content' => json_encode([
                ['type' => 'single', 'question' => 'Pick the right one', 'options' => ['Wrong', 'Right'], 'answer' => 1, 'explanation' => '', 'difficulty' => 'easy', 'topic' => 'general'],
            ]),
            'order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 1)
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', true);
    }

    public function test_lesson_detail_shows_steps(): void
    {
        $user = $this->createStudentUser();
        $course = $this->createEnrolledCourse($user);

        $lesson = $course->lessons()->create([
            'title' => 'Steps Lesson',
            'slug' => 'steps-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $lesson->steps()->create([
            'title' => 'Step One',
            'type' => StepType::Reading,
            'reading_content' => 'Content',
            'order' => 1,
        ]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}");
        $response->assertOk();
        $response->assertSee('Step One');
        $response->assertDontSee(__('lessons.no_steps'));
    }

    public function test_quiz_multi_question_step_page_loads(): void
    {
        $user = $this->createStudentUser();
        $course = $this->createEnrolledCourse($user);

        $lesson = $course->lessons()->create([
            'title' => 'Multi Quiz Lesson',
            'slug' => 'multi-quiz-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $lesson->steps()->create([
            'title' => 'Multi Quiz Step',
            'type' => StepType::Quiz,
            'quiz_content' => json_encode([
                ['type' => 'single', 'question' => 'Q1', 'options' => ['A', 'B'], 'answer' => 0, 'explanation' => '', 'difficulty' => 'easy', 'topic' => 'general'],
                ['type' => 'text', 'question' => 'Q2', 'answer' => 'ok', 'alternatives' => null, 'explanation' => '', 'difficulty' => 'easy', 'topic' => 'general'],
            ]),
            'order' => 1,
        ]);

        $response = $this->actingAs($user)->get(
            "/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$lesson->steps->first()->id}"
        );
        $response->assertOk();
        $response->assertSee(__('steps.quiz_submit'));
    }

    public function test_quiz_multi_question_submit_via_livewire(): void
    {
        $user = $this->createStudentUser();
        $course = $this->createEnrolledCourse($user);

        $lesson = $course->lessons()->create([
            'title' => 'Multi Submit Lesson',
            'slug' => 'multi-submit-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $lesson->steps()->create([
            'title' => 'Multi Submit Step',
            'type' => StepType::Quiz,
            'quiz_content' => json_encode([
                ['type' => 'single', 'question' => 'Q1', 'options' => ['A', 'B'], 'answer' => 0, 'explanation' => '', 'difficulty' => 'easy', 'topic' => 'general'],
            ]),
            'order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $lesson->steps->first(),
            ])
            ->set('answers.0', 0)
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', true);
    }
}
