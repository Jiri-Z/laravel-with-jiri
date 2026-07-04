<?php

namespace Tests\Feature;

use App\Livewire\StepViewer;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class StepViewerReadingTest extends TestCase
{
    public function test_user_can_complete_a_reading_step(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->call('complete')
            ->assertSet('completed', true);

        $this->assertDatabaseHas('step_completions', [
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);
    }

    public function test_user_cannot_complete_same_step_twice(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create(['lesson_id' => $lesson->id]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->call('complete')
            ->assertSet('completed', true);

        $this->assertDatabaseCount('step_completions', 1);
    }

    public function test_completed_step_shows_badge_not_button(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create(['lesson_id' => $lesson->id]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");

        $response->assertOk();
        $response->assertSee('Completed');
        $response->assertDontSee('Mark as Complete');
    }

    public function test_rapid_completion_does_not_duplicate(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->call('complete')
            ->assertSet('completed', true)
            ->call('complete')
            ->assertSet('completed', true);

        $this->assertDatabaseCount('step_completions', 1);
    }

    public function test_first_step_is_always_accessible(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}")
            ->assertOk();
    }

    public function test_cannot_access_second_step_without_completing_first(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $secondStep = Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$secondStep->id}")
            ->assertRedirect(route('lessons.show', [$course->slug, $lesson->slug]));
    }

    public function test_second_step_accessible_after_completing_first(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $firstStep = Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $secondStep = Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $firstStep->id,
        ]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$secondStep->id}")
            ->assertOk();
    }

    public function test_three_step_progression_chain(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step1 = Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $step2 = Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 2]);
        $step3 = Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 3]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step1->id,
        ]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step3->id}")
            ->assertRedirect(route('lessons.show', [$course->slug, $lesson->slug]));

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step2->id,
        ]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step3->id}")
            ->assertOk();
    }

    public function test_five_step_progression_chain(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $steps = [];
        for ($i = 1; $i <= 5; $i++) {
            $steps[$i] = Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => $i]);
        }

        for ($i = 1; $i <= 3; $i++) {
            StepCompletion::factory()->create([
                'user_id' => $user->id,
                'step_id' => $steps[$i]->id,
            ]);
        }

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$steps[5]->id}")
            ->assertRedirect(route('lessons.show', [$course->slug, $lesson->slug]));

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $steps[4]->id,
        ]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$steps[5]->id}")
            ->assertOk();
    }

    public function test_step_viewer_complete_checks_accessibility(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $secondStep = Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        $this->actingAs($user);

        $component = new StepViewer;
        $component->course = $course;
        $component->lesson = $lesson;
        $component->step = $secondStep;
        $component->completed = false;

        $component->complete();

        expect($component->completed)->toBeFalse();
    }

    public function test_reading_step_renders_markdown_bold(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'content' => 'This is **bold** text.',
        ]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSeeHtml('<strong>bold</strong>');
    }

    public function test_reading_step_renders_markdown_inline_code(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'content' => 'Use the `User::find()` method.',
        ]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSeeHtml('<code>User::find()</code>');
    }

    public function test_reading_step_renders_markdown_code_block(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'content' => "```php\necho 'hello';\n```",
        ]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSeeHtml('<code class="language-php">');
    }

    public function test_reading_step_renders_markdown_list(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'content' => "- Item 1\n- Item 2\n- Item 3",
        ]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSeeHtml('<li>Item 1</li>')
            ->assertSeeHtml('<li>Item 2</li>')
            ->assertSeeHtml('<li>Item 3</li>');
    }

    public function test_reading_step_escapes_raw_html(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'content' => '<script>alert(1)</script>',
        ]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertDontSeeHtml('<script>alert(1)</script>');
    }
}
