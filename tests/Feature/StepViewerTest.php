<?php

namespace Tests\Feature;

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
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");

        $response->assertOk();
        $response->assertSee('What is 2+2?');
        $response->assertSee('4');
    }

    public function test_user_can_submit_correct_quiz_single_answer(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, ['step' => $step])
            ->set('selectedAnswer', 1)
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
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, ['step' => $step])
            ->set('selectedAnswer', 0)
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
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizMultiple()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, ['step' => $step])
            ->set('selectedAnswers', [0, 3])
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
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizText()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, ['step' => $step])
            ->set('textAnswer', 'Paris')
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
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        StepAnswer::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
            'answer' => '0',
            'is_correct' => false,
        ]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, ['step' => $step])
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);

        $this->assertDatabaseCount('step_answers', 1);
    }

    public function test_quiz_text_answer_is_case_insensitive(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizText()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, ['step' => $step])
            ->set('textAnswer', 'paris')
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', true);
    }

    public function test_coding_step_shows_prompt(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
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
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->coding()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(CodingViewer::class, ['step' => $step])
            ->assertSet('completed', false)
            ->call('markCodingComplete')
            ->assertSet('completed', true);

        $this->assertDatabaseHas('step_completions', [
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);
    }

    public function test_coding_viewer_wont_mark_complete_twice(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->coding()->create(['lesson_id' => $lesson->id]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        Livewire::actingAs($user)
            ->test(CodingViewer::class, ['step' => $step])
            ->assertSet('completed', true)
            ->call('markCodingComplete');

        $this->assertDatabaseCount('step_completions', 1);
    }

    public function test_previously_completed_coding_step_shows_badge(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->coding()->create(['lesson_id' => $lesson->id]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        Livewire::actingAs($user)
            ->test(CodingViewer::class, ['step' => $step])
            ->assertSet('completed', true);
    }

    public function test_completed_step_shows_badge_not_button(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
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
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizMultiple()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, ['step' => $step])
            ->set('selectedAnswers', [1, 2])
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);
    }

    public function test_quiz_multiple_with_partial_selection(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizMultiple()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, ['step' => $step])
            ->set('selectedAnswers', [0])
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);
    }

    public function test_quiz_text_with_wrong_answer(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizText()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, ['step' => $step])
            ->set('textAnswer', 'London')
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);
    }

    public function test_nonexistent_step_id_returns_404(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/999999")
            ->assertNotFound();
    }

    public function test_step_from_wrong_course_lesson_returns_404(): void
    {
        $user = User::factory()->create();
        $courseA = Course::factory()->published()->create();
        $courseB = Course::factory()->published()->create();
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
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->quizSingle()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(QuizViewer::class, ['step' => $step])
            ->set('selectedAnswer', null)
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('isCorrect', false);
    }
}
