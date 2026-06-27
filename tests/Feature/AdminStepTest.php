<?php

namespace Tests\Feature;

use App\Enums\StepType;
use App\Livewire\AdminStepForm;
use App\Livewire\AdminStepList;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class AdminStepTest extends TestCase
{
    public function test_instructor_can_view_step_list(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps")->assertOk();
    }

    public function test_instructor_can_create_step(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson])
            ->set('title', 'New Step')
            ->set('type', StepType::Reading->value)
            ->set('content', 'Step content here')
            ->set('order', 1)
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
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $step = Step::factory()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson, 'step' => $step])
            ->set('title', 'Updated Step')
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
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $step = Step::factory()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(AdminStepList::class, ['course' => $course, 'lesson' => $lesson])
            ->call('delete', $step->id);

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
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson])
            ->set('title', '')
            ->set('type', StepType::Reading->value)
            ->set('content', 'Some content')
            ->set('order', 1)
            ->call('save')
            ->assertHasErrors('title');
    }

    public function test_instructor_can_create_all_step_types(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        foreach (StepType::cases() as $i => $type) {
            $content = match ($type) {
                StepType::Reading => 'Reading text',
                StepType::QuizSingle => '{"question":"Q?","options":["A","B"],"correct_answer":0}',
                StepType::QuizMultiple => '{"question":"Q?","options":["A","B","C","D"],"correct_answers":[0,3]}',
                StepType::QuizText => '{"question":"Q?","correct_answer":"Paris"}',
                StepType::Coding => '{"prompt":"Write code","initial_code":"<?php","test_code":"<?php","expected_output":"ok"}',
            };

            Livewire::actingAs($user)
                ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson])
                ->set('title', "Step {$type->value}")
                ->set('type', $type->value)
                ->set('content', $content)
                ->set('order', $i + 1)
                ->call('save')
                ->assertRedirect("/admin/courses/{$course->id}/lessons/{$lesson->id}/steps");

            $this->assertDatabaseHas('steps', [
                'lesson_id' => $lesson->id,
                'title' => "Step {$type->value}",
                'type' => $type->value,
                'order' => $i + 1,
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
}
