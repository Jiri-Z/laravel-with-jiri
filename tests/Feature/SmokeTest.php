<?php

namespace Tests\Feature;

use App\Enums\StepType;
use App\Livewire\AdminCourseList;
use App\Livewire\AdminLessonList;
use App\Livewire\AdminStepList;
use App\Livewire\QuizViewer;
use App\Livewire\StepViewer;
use App\Livewire\TriviaQuiz;
use App\Models\Course;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    public function test_student_can_view_courses_and_coding_step(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = $course->lessons()->create([
            'title' => 'Test Lesson',
            'slug' => 'test-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Test Coding',
            'type' => StepType::Coding,
            'content' => json_encode([
                'prompt' => 'Write PHP code',
                'initial_code' => "<?php\n",
                'test_code' => "<?php\necho 'ok';",
                'expected_output' => 'ok',
            ]),
            'order' => 1,
        ]);

        // Course list page
        $this->actingAs($user)->get('/courses')
            ->assertOk()
            ->assertSee($course->title);

        // Coding step page (verifies Alpine x-data loads)
        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");
        $response->assertOk();
        $response->assertSee('Write PHP code');
        $response->assertSee('x-data');
        $response->assertSee('codingViewer');
        $response->assertSee('Run Code');
        $response->assertSee('Check Answer');
    }

    public function test_admin_can_manage_courses_via_http(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get('/admin/courses')->assertOk();

        $this->actingAs($user)->get('/admin/courses/create')
            ->assertOk()
            ->assertSee('New Course')
            ->assertSee('Create Course');

        $course = Course::factory()->create();
        $this->actingAs($user)->get("/admin/courses/{$course->id}/edit")
            ->assertOk()
            ->assertSee('Edit Course');
    }

    public function test_instructor_can_manage_courses_via_http(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);

        $this->actingAs($user)->get('/admin/courses')->assertOk();
        $this->actingAs($user)->get('/admin/courses/create')->assertOk();
    }

    public function test_student_can_view_lesson_detail_page(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->published()->create(['title' => 'Smoke Course']);
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
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
        $response->assertSee('No steps available yet');
    }

    public function test_quiz_step_page_renders_question_and_options(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = $course->lessons()->create([
            'title' => 'Quiz Lesson',
            'slug' => 'quiz-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Quiz Step',
            'type' => StepType::Quiz,
            'content' => json_encode([
                ['type' => 'single', 'question' => 'What is 2+2?', 'options' => ['3', '4', '5'], 'correct_answer' => 1],
            ]),
            'order' => 1,
        ]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");
        $response->assertOk();
        $response->assertSee('Quiz Step');
        $response->assertSee('What is 2+2?');
        $response->assertSee('Submit All Answers');
    }

    public function test_admin_delete_course_via_livewire(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();

        Livewire::actingAs($user)
            ->test(AdminCourseList::class)
            ->call('delete', $course->id);

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_admin_lesson_list_via_livewire(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = $course->lessons()->create([
            'title' => 'Admin Lesson',
            'slug' => 'admin-lesson',
            'published' => true,
            'order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(AdminLessonList::class, ['course' => $course])
            ->assertOk()
            ->assertSee('Admin Lesson');
    }

    public function test_admin_step_list_via_livewire(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = $course->lessons()->create([
            'title' => 'Step List Lesson',
            'slug' => 'step-list-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Admin Step',
            'type' => StepType::Reading,
            'content' => 'Step content',
            'order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(AdminStepList::class, ['course' => $course, 'lesson' => $lesson])
            ->assertOk()
            ->assertSee('Admin Step');
    }

    public function test_reading_step_page_loads(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = $course->lessons()->create([
            'title' => 'Reading Lesson',
            'slug' => 'reading-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Reading Step',
            'type' => StepType::Reading,
            'content' => 'Some reading content here',
            'order' => 1,
        ]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");
        $response->assertOk();
        $response->assertSee('Some reading content here');
        $response->assertSee('Mark as Complete');
    }

    public function test_mark_reading_step_complete(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = $course->lessons()->create([
            'title' => 'Complete Lesson',
            'slug' => 'complete-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Complete Step',
            'type' => StepType::Reading,
            'content' => 'Content',
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
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = $course->lessons()->create([
            'title' => 'Quiz Submit Lesson',
            'slug' => 'quiz-submit-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Quiz Submit Step',
            'type' => StepType::Quiz,
            'content' => json_encode([
                ['type' => 'single', 'question' => 'Pick the right one', 'options' => ['Wrong', 'Right'], 'correct_answer' => 1],
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

    public function test_unauthorized_user_cannot_access_admin(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('/admin/courses')->assertForbidden();
    }

    public function test_guest_redirected_to_login(): void
    {
        $this->get('/courses')->assertRedirect('/login');
    }

    public function test_landing_and_legal_pages(): void
    {
        $this->get('/')->assertOk()->assertSee('Laravel With Jiri');
        $this->get('/terms')->assertOk()->assertSee('Terms of Service');
        $this->get('/privacy')->assertOk()->assertSee('Privacy Policy');
    }

    public function test_lesson_detail_shows_steps(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = $course->lessons()->create([
            'title' => 'Steps Lesson',
            'slug' => 'steps-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $lesson->steps()->create([
            'title' => 'Step One',
            'type' => StepType::Reading,
            'content' => 'Content',
            'order' => 1,
        ]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}");
        $response->assertOk();
        $response->assertSee('Step One');
        $response->assertDontSee('No steps available yet');
    }

    public function test_quiz_multi_question_step_page_loads(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = $course->lessons()->create([
            'title' => 'Multi Quiz Lesson',
            'slug' => 'multi-quiz-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $lesson->steps()->create([
            'title' => 'Multi Quiz Step',
            'type' => StepType::Quiz,
            'content' => json_encode([
                ['type' => 'single', 'question' => 'Q1', 'options' => ['A', 'B'], 'correct_answer' => 0],
                ['type' => 'text', 'question' => 'Q2', 'correct_answer' => 'ok'],
            ]),
            'order' => 1,
        ]);

        $response = $this->actingAs($user)->get(
            "/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$lesson->steps->first()->id}"
        );
        $response->assertOk();
        $response->assertSee('Submit All Answers');
    }

    public function test_quiz_multi_question_submit_via_livewire(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = $course->lessons()->create([
            'title' => 'Multi Submit Lesson',
            'slug' => 'multi-submit-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $lesson->steps()->create([
            'title' => 'Multi Submit Step',
            'type' => StepType::Quiz,
            'content' => json_encode([
                ['type' => 'single', 'question' => 'Q1', 'options' => ['A', 'B'], 'correct_answer' => 0],
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

    public function test_dashboard_page_loads_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create(['title' => 'Dash Course']);
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = $course->lessons()->create([
            'title' => 'Dash Lesson',
            'slug' => 'dash-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Dash Step',
            'type' => StepType::Reading,
            'content' => 'Content',
            'order' => 1,
        ]);

        StepCompletion::create([
            'user_id' => $user->id,
            'step_id' => $step->id,
            'completed_at' => now(),
        ]);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertSee('Welcome')
            ->assertSee('Dash Course')
            ->assertSee('Dashboard');
    }

    public function test_trivia_quiz_page_loads(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('/quiz')
            ->assertOk()
            ->assertSee('Laravel Trivia');

        Livewire::actingAs($user)
            ->test(TriviaQuiz::class)
            ->assertOk()
            ->assertSee('Select Topics');
    }

    public function test_dashboard_shows_trivia_card(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertSee('Laravel Trivia')
            ->assertSee('Test Your Laravel Knowledge')
            ->assertSee(route('quiz'));
    }
}
