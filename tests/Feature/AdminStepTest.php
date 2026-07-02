<?php

namespace Tests\Feature;

use App\Enums\StepType;
use App\Livewire\AdminStepForm;
use App\Livewire\AdminStepList;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class AdminStepTest extends TestCase
{
    public function test_instructor_can_view_step_list(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps")->assertOk();
    }

    public function test_instructor_can_create_step(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson])
            ->set('title', 'New Step')
            ->set('type', StepType::Reading->value)
            ->set('content', 'Step content here')
            ->call('save')
            ->assertRedirect("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps");

        $this->assertDatabaseHas('steps', [
            'lesson_id' => $lesson->id,
            'title' => 'New Step',
            'type' => StepType::Reading->value,
        ]);
    }

    public function test_instructor_can_edit_step(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create(['lesson_id' => $lesson->id]);

        $component = Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson, 'step' => $step]);
        $component
            ->set('title', 'Updated Step')
            ->set('type', $step->type->value)
            ->set('content', $step->content)
            ->call('save')
            ->assertRedirect("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps");

        $this->assertDatabaseHas('steps', [
            'id' => $step->id,
            'title' => 'Updated Step',
        ]);
    }

    public function test_instructor_cannot_delete_step(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $step = Step::factory()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(AdminStepList::class, ['course' => $course, 'lesson' => $lesson])
            ->call('delete', $step->id)
            ->assertForbidden();

        $this->assertDatabaseHas('steps', ['id' => $step->id]);
    }

    public function test_admin_can_delete_step(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $step = Step::factory()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(AdminStepList::class, ['course' => $course, 'lesson' => $lesson])
            ->call('delete', $step->id);

        $this->assertDatabaseMissing('steps', ['id' => $step->id]);
    }

    public function test_student_cannot_access_step_admin(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps")->assertForbidden();
    }

    public function test_move_up_swaps_order_with_previous_step(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $a = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $b = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        Livewire::actingAs($user)
            ->test(AdminStepList::class, ['course' => $course, 'lesson' => $lesson])
            ->call('moveUp', $b->id);

        $this->assertDatabaseHas('steps', ['id' => $a->id, 'order' => 2]);
        $this->assertDatabaseHas('steps', ['id' => $b->id, 'order' => 1]);
    }

    public function test_move_down_swaps_order_with_next_step(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $a = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $b = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        Livewire::actingAs($user)
            ->test(AdminStepList::class, ['course' => $course, 'lesson' => $lesson])
            ->call('moveDown', $a->id);

        $this->assertDatabaseHas('steps', ['id' => $a->id, 'order' => 2]);
        $this->assertDatabaseHas('steps', ['id' => $b->id, 'order' => 1]);
    }

    public function test_guest_is_redirected_from_admin_steps(): void
    {
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $this->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps")->assertRedirect('/login');
        $this->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps/create")->assertRedirect('/login');
        $step = Step::factory()->create(['lesson_id' => $lesson->id]);
        $this->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps/{$step->id}/edit")->assertRedirect('/login');
    }

    public function test_bad_lesson_course_parent_returns_404_on_step_form(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $courseA = Course::factory()->create();
        $courseB = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $courseA->id]);

        $this->actingAs($user)
            ->get("/admin/courses/{$courseB->id}/lessons/{$lesson->id}/steps/create")
            ->assertNotFound();
    }

    public function test_cannot_create_step_with_empty_title(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson])
            ->set('title', '')
            ->set('type', StepType::Reading->value)
            ->set('content', 'Some content')
            ->call('save')
            ->assertHasErrors('title');
    }

    public function test_instructor_can_create_all_step_types(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        foreach (StepType::cases() as $i => $type) {
            $test = Livewire::actingAs($user)
                ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson])
                ->set('title', "Step {$type->value}")
                ->set('type', $type->value);

            if ($type === StepType::Coding) {
                $test->set('prompt', 'Write code')
                    ->set('initialCode', "<?php\n")
                    ->set('testCode', "<?php\n")
                    ->set('expectedOutput', 'ok');
            } else {
                $content = match ($type) {
                    StepType::Reading => 'Reading text',
                    StepType::Quiz => '[{"type":"single","question":"Q?","options":["A","B"],"correct_answer":0}]',
                };
                $test->set('content', $content);
            }

            $test->call('save')
                ->assertRedirect("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps");

            $this->assertDatabaseHas('steps', [
                'lesson_id' => $lesson->id,
                'title' => "Step {$type->value}",
                'type' => $type->value,
            ]);
        }
    }

    public function test_move_up_on_first_step_does_nothing(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $a = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        Livewire::actingAs($user)
            ->test(AdminStepList::class, ['course' => $course, 'lesson' => $lesson])
            ->call('moveUp', $a->id);

        $this->assertDatabaseHas('steps', ['id' => $a->id, 'order' => 1]);
    }

    public function test_move_down_on_last_step_does_nothing(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $b = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        Livewire::actingAs($user)
            ->test(AdminStepList::class, ['course' => $course, 'lesson' => $lesson])
            ->call('moveDown', $b->id);

        $this->assertDatabaseHas('steps', ['id' => $b->id, 'order' => 2]);
    }

    public function test_move_up_rolls_back_when_swap_fails(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $a = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $b = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER abort_step_swap_update
            BEFORE UPDATE ON steps
            WHEN NEW."order" != -1 AND EXISTS(
                SELECT 1 FROM steps WHERE "order" = -1
            )
            BEGIN
                SELECT RAISE(ABORT, 'swap failed');
            END;
        SQL);

        try {
            Livewire::actingAs($user)
                ->test(AdminStepList::class, ['course' => $course, 'lesson' => $lesson])
                ->call('moveUp', $b->id);

            $this->fail('Expected the reorder action to fail.');
        } catch (\Throwable) {
            // The trigger intentionally aborts the reorder mid-swap.
        } finally {
            DB::unprepared('DROP TRIGGER IF EXISTS abort_step_swap_update');
        }

        $this->assertDatabaseHas('steps', ['id' => $a->id, 'order' => 1]);
        $this->assertDatabaseHas('steps', ['id' => $b->id, 'order' => 2]);
    }

    public function test_admin_step_list_empty_state(): void
    {
        $user = User::factory()->admin()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps")
            ->assertOk()
            ->assertSee('No steps yet.');
    }

    public function test_admin_step_list_renders_table_headers(): void
    {
        $user = User::factory()->admin()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lesson->id]);

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps")
            ->assertOk()
            ->assertSeeInOrder(['Order', 'Title', 'Type', 'Actions']);
    }

    public function test_search_filters_step_list(): void
    {
        $user = User::factory()->admin()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1, 'title' => 'Alpha Step']);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2, 'title' => 'Beta Step']);

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps?q=Alpha")
            ->assertOk()
            ->assertSee('Alpha Step')
            ->assertDontSee('Beta Step');
    }

    public function test_search_no_results_step_empty(): void
    {
        $user = User::factory()->admin()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1, 'title' => 'Alpha']);

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps?q=zzz_nonexistent")
            ->assertOk()
            ->assertSee('No steps found.')
            ->assertDontSee('Alpha');
    }

    public function test_step_pagination(): void
    {
        $user = User::factory()->admin()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        for ($i = 1; $i <= 12; $i++) {
            Step::factory()->create(['lesson_id' => $lesson->id, 'order' => $i, 'title' => sprintf('Step %02d', $i)]);
        }

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps?page=2")
            ->assertOk()
            ->assertSee('Step 11')
            ->assertSee('Step 12')
            ->assertDontSee('Step 01');
    }

    public function test_wire_loading_present_in_step_list(): void
    {
        $user = User::factory()->admin()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps")
            ->assertOk()
            ->assertSee('wire:loading');
    }

    public function test_order_is_auto_assigned_on_create(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        // Pre-existing step at order 5
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 5]);

        Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson])
            ->set('title', 'Auto-ordered Step')
            ->set('type', StepType::Reading->value)
            ->set('content', 'Content')
            ->call('save')
            ->assertRedirect("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps");

        $this->assertDatabaseHas('steps', [
            'lesson_id' => $lesson->id,
            'title' => 'Auto-ordered Step',
            'order' => 6,
        ]);
    }

    public function test_coding_step_form_has_separate_fields(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $component = Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson])
            ->set('type', StepType::Coding->value);

        $this->assertSame(StepType::Coding->value, $component->get('type'));
        $this->assertSame('', $component->instance()->prompt);
        $this->assertSame('', $component->instance()->initialCode);
        $this->assertSame('', $component->instance()->testCode);
        $this->assertSame('', $component->instance()->expectedOutput);
    }

    public function test_coding_step_serializes_fields_to_json_on_save(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson])
            ->set('title', 'Coding Step')
            ->set('type', StepType::Coding->value)
            ->set('prompt', 'Write PHP')
            ->set('initialCode', "<?php\necho 'hi';")
            ->set('testCode', "<?php\nassert(true);")
            ->set('expectedOutput', 'hi')
            ->call('save')
            ->assertRedirect("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps");

        $this->assertDatabaseHas('steps', [
            'lesson_id' => $lesson->id,
            'title' => 'Coding Step',
            'type' => StepType::Coding->value,
        ]);

        $step = Step::where('lesson_id', $lesson->id)->where('title', 'Coding Step')->first();
        $content = json_decode((string) $step->content, true);
        $this->assertSame('Write PHP', $content['prompt']);
        $this->assertSame("<?php\necho 'hi';", $content['initial_code']);
        $this->assertSame("<?php\nassert(true);", $content['test_code']);
        $this->assertSame('hi', $content['expected_output']);
    }

    public function test_coding_step_validation_requires_prompt(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson])
            ->set('title', 'Bad Coding Step')
            ->set('type', StepType::Coding->value)
            ->set('prompt', '')
            ->call('save')
            ->assertHasErrors('prompt');
    }

    public function test_editing_coding_step_populates_separate_fields(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $step = Step::factory()->coding()->create([
            'lesson_id' => $lesson->id,
            'title' => 'Edit Coding',
        ]);

        $component = Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson, 'step' => $step]);

        $this->assertSame(StepType::Coding->value, $component->get('type'));
        $this->assertSame('Write a PHP function that returns the sum of two numbers.', $component->get('prompt'));
        $this->assertStringContainsString('function add(', $component->get('initialCode'));
        $this->assertStringContainsString('echo add', $component->get('testCode'));
        $this->assertSame('5', $component->get('expectedOutput'));
    }

    public function test_reading_step_form_still_uses_content_property(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $component = Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson])
            ->set('type', StepType::Reading->value);

        Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson])
            ->set('title', 'Reading Step')
            ->set('type', StepType::Reading->value)
            ->set('content', 'Simple text')
            ->call('save')
            ->assertRedirect("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps");

        $this->assertDatabaseHas('steps', [
            'lesson_id' => $lesson->id,
            'title' => 'Reading Step',
            'type' => StepType::Reading->value,
            'content' => 'Simple text',
        ]);
    }

    public function test_bad_lesson_course_parent_returns_404_on_step_list(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $courseA = Course::factory()->create();
        $courseB = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $courseA->id]);

        $this->actingAs($user)
            ->get("/admin/courses/{$courseB->id}/lessons/{$lesson->id}/steps")
            ->assertNotFound();
    }

    public function test_step_url_tracking_with_ownership(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $instructor->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1, 'title' => 'Alpha Step']);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2, 'title' => 'Beta Step']);

        $this->actingAs($instructor)->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps?q=Alpha")
            ->assertOk()
            ->assertSee('Alpha Step')
            ->assertDontSee('Beta Step');
    }

    public function test_instructor_cannot_edit_other_instructors_step(): void
    {
        $instructorA = User::factory()->create(['role' => 'instructor']);
        $instructorB = User::factory()->create(['role' => 'instructor']);
        $courseB = Course::factory()->create(['user_id' => $instructorB->id]);
        $lessonB = Lesson::factory()->create(['course_id' => $courseB->id]);
        $stepB = Step::factory()->create(['lesson_id' => $lessonB->id]);

        Livewire::actingAs($instructorA)
            ->test(AdminStepForm::class, ['course' => $courseB, 'lesson' => $lessonB, 'step' => $stepB])
            ->assertForbidden();

        $this->actingAs($instructorA)
            ->get("/admin/courses/{$courseB->id}/lessons/{$lessonB->id}/steps/{$stepB->id}/edit")
            ->assertForbidden();
    }
}
