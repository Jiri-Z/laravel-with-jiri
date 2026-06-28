<?php

namespace Tests\Feature;

use App\Actions\SubmitQuizAnswer;
use App\Livewire\CodingViewer;
use App\Livewire\QuizViewer;
use App\Livewire\StepViewer;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepAnswer;
use App\Models\StepCompletion;
use App\Models\User;
use Livewire\Livewire;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class StepViewerTest extends TestCase
{
    public function test_guest_is_redirected_to_login(): void
    {
        $step = Step::factory()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $this->get("/courses/{$step->lesson->course->slug}/lessons/{$step->lesson->slug}/steps/{$step->id}")
            ->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_reading_step(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'title' => 'My Reading Step',
        ]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");

        $response->assertOk();
        $response->assertSee('My Reading Step');
    }

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

    public function test_step_viewer_complete_rejects_invalid_context(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $otherStep = Step::factory()->reading()->create([
            'lesson_id' => Lesson::factory()->published()->create(['course_id' => $course->id]),
        ]);

        $this->actingAs($user);

        $component = new StepViewer;
        $component->course = $course;
        $component->lesson = $lesson;
        $component->step = $otherStep;

        try {
            $component->complete();

            $this->fail('Expected the step completion action to abort.');
        } catch (HttpException $e) {
            $this->assertSame(404, $e->getStatusCode());
        }

        $this->assertDatabaseCount('step_completions', 0);
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

    public function test_step_under_unpublished_course_returns_404(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['published' => false]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->create(['lesson_id' => $lesson->id]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}")
            ->assertNotFound();
    }

    public function test_step_under_unpublished_lesson_returns_404(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->create(['course_id' => $course->id, 'published' => false]);
        $step = Step::factory()->create(['lesson_id' => $lesson->id]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}")
            ->assertNotFound();
    }

    public function test_step_from_wrong_lesson_returns_404(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $otherLesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->create(['lesson_id' => $otherLesson->id]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}")
            ->assertNotFound();
    }

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
        $response->assertSee('Submit All Answers');
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

    public function test_quiz_viewer_submit_rejects_invalid_context(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        $this->actingAs($user);

        $component = new QuizViewer;
        $component->course = Course::factory()->create(['published' => false]);
        $component->lesson = $lesson;
        $component->step = $step;
        $component->answers = [0 => 1];

        try {
            $component->submit();

            $this->fail('Expected the quiz submit action to abort.');
        } catch (HttpException $e) {
            $this->assertSame(404, $e->getStatusCode());
        }

        $this->assertDatabaseCount('step_answers', 0);
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

    public function test_coding_step_shows_prompt(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->coding()->create(['lesson_id' => $lesson->id]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");

        $response->assertOk();
        $response->assertSee('Write a PHP function that returns the sum of two numbers.');
        $response->assertSee('Run Code');
    }

    public function test_coding_viewer_can_mark_complete(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->coding()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(CodingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSet('completed', false)
            ->call('markCodingComplete')
            ->assertSet('completed', true);

        $this->assertDatabaseHas('step_completions', [
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);
    }

    public function test_coding_viewer_mark_complete_rejects_invalid_context(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->coding()->create(['lesson_id' => $lesson->id]);
        $otherLesson = Lesson::factory()->published()->create(['course_id' => $course->id]);

        $this->actingAs($user);

        $component = new CodingViewer;
        $component->course = $course;
        $component->lesson = $otherLesson;
        $component->step = $step;

        try {
            $component->markCodingComplete();

            $this->fail('Expected the coding completion action to abort.');
        } catch (HttpException $e) {
            $this->assertSame(404, $e->getStatusCode());
        }

        $this->assertDatabaseCount('step_completions', 0);
    }

    public function test_coding_viewer_wont_mark_complete_twice(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->coding()->create(['lesson_id' => $lesson->id]);

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
            ->call('markCodingComplete');

        $this->assertDatabaseCount('step_completions', 1);
    }

    public function test_previously_completed_coding_step_shows_badge(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->coding()->create(['lesson_id' => $lesson->id]);

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

    public function test_nonexistent_step_id_returns_404(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/999999")
            ->assertNotFound();
    }

    public function test_step_from_wrong_course_lesson_returns_404(): void
    {
        $user = User::factory()->create();
        $courseA = Course::factory()->published()->create();
        $courseA->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $courseB = Course::factory()->published()->create();
        $courseB->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lessonA = Lesson::factory()->published()->create(['course_id' => $courseA->id]);
        $lessonB = Lesson::factory()->published()->create(['course_id' => $courseB->id]);
        $step = Step::factory()->create(['lesson_id' => $lessonB->id]);

        $this->actingAs($user)
            ->get("/courses/{$courseA->slug}/lessons/{$lessonA->slug}/steps/{$step->id}")
            ->assertNotFound();
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
        $response->assertSee('Submit All Answers');
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
}
