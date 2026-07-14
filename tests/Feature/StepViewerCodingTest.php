<?php

namespace Tests\Feature;

use App\Livewire\CodingViewer;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Livewire\Livewire;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class StepViewerCodingTest extends TestCase
{
    public function test_coding_step_shows_prompt(): void
    {
        [$user, $course, $lesson, $step] = $this->enrolledUserWithStep('coding');

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");

        $response->assertOk();
        $response->assertSee('Write a PHP function that returns the sum of two numbers.');
        $response->assertSee(__('steps.coding_run'));
    }

    public function test_coding_viewer_can_mark_complete(): void
    {
        [$user, $course, $lesson, $step] = $this->enrolledUserWithStep('coding');

        Livewire::actingAs($user)
            ->test(CodingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSet('completed', false)
            ->call('markCodingComplete', output: '5')
            ->assertSet('completed', true);

        $this->assertDatabaseHas('step_completions', [
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);
    }

    public function test_coding_viewer_wont_mark_complete_twice(): void
    {
        [$user, $course, $lesson, $step] = $this->enrolledUserWithStep('coding');

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        Livewire::actingAs($user)
            ->test(CodingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSet('completed', true)
            ->call('markCodingComplete', output: '5');

        $this->assertDatabaseCount('step_completions', 1);
    }

    public function test_coding_viewer_requires_correct_output(): void
    {
        [$user, $course, $lesson, $step] = $this->enrolledUserWithStep('coding');

        Livewire::actingAs($user)
            ->test(CodingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSet('completed', false)
            ->call('markCodingComplete', output: 'wrong_output')
            ->assertSet('completed', false);

        $this->assertDatabaseMissing('step_completions', [
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);
    }

    public function test_previously_completed_coding_step_shows_badge(): void
    {
        [$user, $course, $lesson, $step] = $this->enrolledUserWithStep('coding');

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        Livewire::actingAs($user)
            ->test(CodingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSet('completed', true);
    }

    public function test_coding_viewer_blocks_inaccessible_step(): void
    {
        [$user, $course, $lesson] = $this->enrolledUser();
        Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $secondStep = Step::factory()->coding()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        $this->actingAs($user);

        $component = new CodingViewer;
        $component->course = $course;
        $component->lesson = $lesson;
        $component->step = $secondStep;

        try {
            $component->mount($course, $lesson, $secondStep);

            $this->fail('Expected CodingViewer mount to abort for inaccessible step.');
        } catch (HttpException $e) {
            $this->assertSame(404, $e->getStatusCode());
        }
    }

    public function test_coding_viewer_redirects_unenrolled_user(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->coding()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(CodingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertRedirect(route('courses.index'));
    }
}
