<?php

namespace Tests\Feature;

use App\Enums\StepType;
use App\Models\Course;
use App\Models\Step;
use App\Models\User;
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

        // List
        $this->actingAs($user)->get('/admin/courses')->assertOk();

        // Create via form
        $this->actingAs($user)->get('/admin/courses/create')
            ->assertOk()
            ->assertSee('New Course')
            ->assertSee('Create Course');

        // Edit
        $course = Course::factory()->create();
        $this->actingAs($user)->get("/admin/courses/{$course->id}/edit")
            ->assertOk()
            ->assertSee('Edit Course');
    }
}
