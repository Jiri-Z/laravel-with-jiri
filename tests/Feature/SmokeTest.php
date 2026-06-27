<?php

namespace Tests\Feature;

use App\Enums\StepType;
use App\Livewire\AdminCourseList;
use App\Models\Course;
use App\Models\Step;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    public function test_student_can_view_courses_and_coding_step(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->published()->create();
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
        $lesson = $course->lessons()->create([
            'title' => 'Quiz Lesson',
            'slug' => 'quiz-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Quiz Step',
            'type' => StepType::QuizSingle,
            'content' => json_encode([
                'question' => 'What is 2+2?',
                'options' => ['3', '4', '5'],
                'correct_answer' => 1,
            ]),
            'order' => 1,
        ]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");
        $response->assertOk();
        $response->assertSee('Quiz Step');
        $response->assertSee('What is 2+2?');
        $response->assertSee('Submit Answer');
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
}
