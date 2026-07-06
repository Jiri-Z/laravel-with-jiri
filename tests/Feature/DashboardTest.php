<?php

namespace Tests\Feature;

use App\Livewire\Dashboard;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_guest_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_shows_welcome_message(): void
    {
        $user = User::factory()->create(['name' => 'Alice']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee(__('dashboard.welcome', ['name' => 'Alice']));
    }

    public function test_shows_course_progress(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create(['title' => 'Progress Course']);
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step1 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step1->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk();
        $response->assertSee('Progress Course');
        $response->assertSee('50%');
    }

    public function test_shows_recent_completions(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create(['title' => 'RC Course']);
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id, 'title' => 'RC Lesson']);
        $step = Step::factory()->create(['lesson_id' => $lesson->id, 'title' => 'RC Step', 'order' => 1]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk();
        $response->assertSee(__('dashboard.recent_activity'));
        $response->assertSee('RC Step');
    }

    public function test_shows_resume_step(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create(['title' => 'Resume Course']);
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step1 = Step::factory()->create(['lesson_id' => $lesson->id, 'title' => 'Done Step', 'order' => 1]);
        $step2 = Step::factory()->create(['lesson_id' => $lesson->id, 'title' => 'Next Step', 'order' => 2]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step1->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk();
        $response->assertSee(__('dashboard.continue_learning'));
        $response->assertSee('Next Step');
    }

    public function test_works_with_no_courses(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk();
    }

    public function test_livewire_component_renders(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertOk();
    }

    public function test_dashboard_does_not_have_n_plus_one_queries(): void
    {
        $user = User::factory()->create();

        $courses = Course::factory()->count(3)->published()->create();
        foreach ($courses as $course) {
            $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
            $lessons = Lesson::factory()->count(2)->published()->create(['course_id' => $course->id]);
            foreach ($lessons as $lesson) {
                Step::factory()->count(2)->create(['lesson_id' => $lesson->id]);
            }
        }

        // Complete all steps in the first 2 courses to force looping
        foreach ($courses->take(2) as $course) {
            foreach ($course->lessons as $lesson) {
                foreach ($lesson->steps as $step) {
                    StepCompletion::factory()->create([
                        'user_id' => $user->id,
                        'step_id' => $step->id,
                    ]);
                }
            }
        }

        DB::enableQueryLog();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk();

        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertLessThan(15, count($queries));
    }
}
