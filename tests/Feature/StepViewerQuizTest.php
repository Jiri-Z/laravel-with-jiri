<?php

namespace Tests\Feature;

use App\Actions\SubmitQuizAnswer;
use App\Livewire\QuizViewer;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepAnswer;
use App\Models\User;
use Livewire\Livewire;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class StepViewerQuizTest extends TestCase
{
    public function test_quiz_single_step_shows_question_and_options(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");

        $response->assertOk();
        $response->assertSee('What is 2+2?');
        $response->assertSee('4');
        $response->assertSee(__('steps.quiz_submit'));
    }

    public function test_user_can_submit_correct_quiz_single_answer(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

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

        $this->assertDatabaseHas('step_answers', [
            'user_id' => $user->id,
            'step_id' => $step->id,
            'is_correct' => true,
        ]);
    }

    public function test_user_can_submit_incorrect_quiz_single_answer(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 0)
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);

        $this->assertDatabaseHas('step_answers', [
            'user_id' => $user->id,
            'step_id' => $step->id,
            'is_correct' => false,
        ]);
    }

    public function test_user_can_submit_correct_quiz_multiple_answer(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizMultiple()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', [0, 3])
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', true);

        $this->assertDatabaseHas('step_answers', [
            'user_id' => $user->id,
            'step_id' => $step->id,
            'is_correct' => true,
        ]);
    }

    public function test_user_can_submit_correct_quiz_text_answer(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizText()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 'Paris')
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', true);

        $this->assertDatabaseHas('step_answers', [
            'user_id' => $user->id,
            'step_id' => $step->id,
            'is_correct' => true,
        ]);
    }

    public function test_user_cannot_resubmit_quiz(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        StepAnswer::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
            'answer' => '0',
            'is_correct' => false,
        ]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);

        $this->assertDatabaseCount('step_answers', 1);
    }

    public function test_quiz_text_answer_is_case_insensitive(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizText()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 'paris')
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', true);
    }

    public function test_quiz_multiple_with_wrong_answer(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizMultiple()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', [1, 2])
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);
    }

    public function test_quiz_multiple_with_partial_selection(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizMultiple()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', [0])
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);
    }

    public function test_quiz_text_with_wrong_answer(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizText()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 'London')
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);
    }

    public function test_empty_text_answer_is_incorrect(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', null)
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);
    }

    public function test_rapid_quiz_submit_does_not_duplicate(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 1)
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', true)
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', true);

        $this->assertDatabaseCount('step_answers', 1);
    }

    public function test_quiz_type_step_shows_multiple_questions(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quiz()->create(['lesson_id' => $lesson->id]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");

        $response->assertOk();
        $response->assertSee('What is 2+2?');
        $response->assertSee('What is the capital of France?');
        $response->assertSee('Which are programming languages?');
        $response->assertSee(__('steps.quiz_submit'));
    }

    public function test_quiz_type_submit_all_correct_answers(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quiz()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 1)
            ->set('answers.1', 'Paris')
            ->set('answers.2', [0, 3])
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', true);

        $this->assertDatabaseCount('step_answers', 3);
    }

    public function test_quiz_type_submit_partially_correct(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quiz()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 0)
            ->set('answers.1', 'Paris')
            ->set('answers.2', [0, 3])
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);
    }

    public function test_quiz_type_submit_all_incorrect(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quiz()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 0)
            ->set('answers.1', 'London')
            ->set('answers.2', [1, 2])
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);
    }

    public function test_user_can_restart_quiz_and_resubmit(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 0)
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false)
            ->call('restart')
            ->assertSet('submitted', false)
            ->assertSet('isCorrect', false)
            ->assertSet('answers.0', null)
            ->set('answers.0', 1)
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', true);

        $this->assertDatabaseCount('step_answers', 1);
    }

    public function test_restart_deletes_existing_answers(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 0)
            ->call('submit')
            ->assertSet('submitted', true);

        $this->assertDatabaseCount('step_answers', 1);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->call('restart')
            ->assertSet('submitted', false);

        $this->assertDatabaseCount('step_answers', 0);
    }

    public function test_restart_is_guarded_when_not_submitted(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->call('restart')
            ->assertSet('submitted', false)
            ->assertSet('isCorrect', false);
    }

    public function test_quiz_type_previously_submitted_shows_submitted(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quiz()->create(['lesson_id' => $lesson->id]);

        (new SubmitQuizAnswer)->handle($user, $step, 0, questionIndex: 0);
        (new SubmitQuizAnswer)->handle($user, $step, 'Paris', questionIndex: 1);
        (new SubmitQuizAnswer)->handle($user, $step, [0, 3], questionIndex: 2);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSet('submitted', true);
    }

    public function test_quiz_type_rapid_submit_does_not_duplicate(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quiz()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 1)
            ->set('answers.1', 'Paris')
            ->set('answers.2', [0, 3])
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', true)
            ->call('submit')
            ->assertSet('submitted', true);

        $this->assertDatabaseCount('step_answers', 3);
    }

    public function test_quiz_viewer_aborts_404_for_non_quiz_step(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create(['lesson_id' => $lesson->id]);

        $this->actingAs($user);

        $component = new QuizViewer;
        $component->course = $course;
        $component->lesson = $lesson;
        $component->step = $step;

        try {
            $component->mount($course, $lesson, $step);

            $this->fail('Expected QuizViewer mount to abort for non-quiz step.');
        } catch (HttpException $e) {
            $this->assertSame(404, $e->getStatusCode());
        }
    }

    public function test_quiz_viewer_blocks_inaccessible_step(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        Step::factory()->quiz()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $secondStep = Step::factory()->quiz()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        $this->actingAs($user);

        $component = new QuizViewer;
        $component->course = $course;
        $component->lesson = $lesson;
        $component->step = $secondStep;

        try {
            $component->mount($course, $lesson, $secondStep);

            $this->fail('Expected QuizViewer mount to abort for inaccessible step.');
        } catch (HttpException $e) {
            $this->assertSame(404, $e->getStatusCode());
        }
    }

    public function test_quiz_viewer_redirects_unenrolled_user(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertRedirect(route('courses.index'));
    }

    public function test_quiz_viewer_restores_answers_on_remount(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quiz()->create(['lesson_id' => $lesson->id]);

        $this->actingAs($user);

        $first = Livewire::test(QuizViewer::class, [
            'course' => $course,
            'lesson' => $lesson,
            'step' => $step,
        ])
            ->set('answers.0', 1)
            ->set('answers.1', 'Paris')
            ->set('answers.2', [0, 3])
            ->call('submit')
            ->assertSet('submitted', true);

        $remount = Livewire::test(QuizViewer::class, [
            'course' => $course,
            'lesson' => $lesson,
            'step' => $step,
        ]);

        $remount->assertSet('submitted', true);
        $remount->assertSet('answers.0', '1');
        $remount->assertSet('answers.1', 'Paris');
        $remount->assertSet('answers.2', [0, 3]);
    }

    public function test_quiz_single_selected_option_has_highlight_class(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        $component = Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ]);

        // Before selection, no highlight class
        $html = $component->html();
        $this->assertStringNotContainsString('border-indigo-500', $html);

        // Select option index 1 ("4")
        $component->set('answers.0', 1);

        $html = $component->html();
        $this->assertStringContainsString('border-indigo-500', $html);
        // Exactly one option should be highlighted
        $this->assertSame(1, substr_count((string) $html, 'border-indigo-500'));
    }

    public function test_quiz_multiple_selected_options_have_highlight_class(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizMultiple()->create(['lesson_id' => $lesson->id]);

        $component = Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ]);

        // After submitting via the quick submit method, select options 0 and 2
        $component->set('answers.0', [0, 2]);

        $html = $component->html();
        // Options 0 (Python) and 2 (CSS) should be highlighted
        $this->assertSame(2, substr_count((string) $html, 'border-indigo-500'));
    }

    public function test_quiz_no_highlight_after_submission(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        $component = Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', 1)
            ->call('submit');

        $html = $component->html();
        // After submission, the highlight class should not appear
        $this->assertStringNotContainsString('border-indigo-500', $html);
    }

    public function test_multiple_choice_initialized_as_empty_array(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizMultiple()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSet('answers.0', []);
    }

    public function test_multiple_choice_accumulates_selections(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizMultiple()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSet('answers.0', [])
            ->set('answers.0', [0])
            ->assertSet('answers.0', [0])
            ->set('answers.0', [0, 2])
            ->assertSet('answers.0', [0, 2]);
    }

    public function test_multiple_choice_restores_array_on_remount(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizMultiple()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->set('answers.0', [0, 2])
            ->call('submit')
            ->assertSet('submitted', true);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSet('submitted', true)
            ->assertSet('answers.0', [0, 2]);
    }
}
