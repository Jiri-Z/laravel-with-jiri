<?php

namespace Tests\Feature;

use App\Enums\StepType;
use App\Livewire\AdminCourseList;
use App\Livewire\AdminLessonList;
use App\Livewire\AdminStepList;
use App\Models\Course;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class SmokeAdminTest extends TestCase
{
    public function test_admin_can_manage_courses_via_http(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get('/admin/courses')->assertOk();

        $this->actingAs($user)->get('/admin/courses/create')
            ->assertOk()
            ->assertSee('New Course')
            ->assertSee('Create Course');

        $course = Course::factory()->create();
        $this->actingAs($user)->get("/admin/courses/{$course->id}/edit")
            ->assertOk()
            ->assertSee('Edit Course');
    }

    public function test_instructor_can_manage_courses_via_http(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);

        $this->actingAs($user)->get('/admin/courses')->assertOk();
        $this->actingAs($user)->get('/admin/courses/create')->assertOk();
    }

    public function test_admin_delete_course_via_livewire(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();

        Livewire::actingAs($user)
            ->test(AdminCourseList::class)
            ->call('delete', $course->id);

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_admin_lesson_list_via_livewire(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = $course->lessons()->create([
            'title' => 'Admin Lesson',
            'slug' => 'admin-lesson',
            'published' => true,
            'order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(AdminLessonList::class, ['course' => $course])
            ->assertOk()
            ->assertSee('Admin Lesson');
    }

    public function test_admin_step_list_via_livewire(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = $course->lessons()->create([
            'title' => 'Step List Lesson',
            'slug' => 'step-list-lesson',
            'published' => true,
            'order' => 1,
        ]);
        $step = $lesson->steps()->create([
            'title' => 'Admin Step',
            'type' => StepType::Reading,
            'reading_content' => 'Step content',
            'order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(AdminStepList::class, ['course' => $course, 'lesson' => $lesson])
            ->assertOk()
            ->assertSee('Admin Step');
    }

    public function test_unauthorized_user_cannot_access_admin(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('/admin/courses')->assertForbidden();
    }
}
