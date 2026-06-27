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
}
