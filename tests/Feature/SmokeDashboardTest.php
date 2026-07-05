<?php

namespace Tests\Feature;

use App\Livewire\TriviaQuiz;
use App\Models\Course;
use App\Models\StepCompletion;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Livewire\Livewire;
use Tests\TestCase;

class SmokeDashboardTest extends TestCase
{
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
            'type' => 'reading',
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
            ->assertSee('Dashboard')
            ->assertSee('Laravel Trivia')
            ->assertSee('from-amber-50');
    }

    public function test_dashboard_shows_trivia_card(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertSee('Laravel Trivia')
            ->assertSee('Test Your Knowledge')
            ->assertSee(route('quiz'));
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

    public function test_czech_locale_shows_placeholder_content(): void
    {
        App::setLocale('cs');
        $user = User::factory()->create(['role' => 'student', 'locale' => 'cs']);
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        App::setLocale('en');

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk();
        $response->assertSee('CS:'); // Czech factory placeholder prefix
    }
}
