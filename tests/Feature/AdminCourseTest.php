<?php

namespace Tests\Feature;

use App\Livewire\AdminCourseForm;
use App\Livewire\AdminCourseList;
use App\Models\Course;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class AdminCourseTest extends TestCase
{
    public function test_student_cannot_access_admin(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('/admin/courses')->assertForbidden();
    }

    public function test_instructor_can_view_course_list(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);

        $this->actingAs($user)->get('/admin/courses')->assertOk();
    }

    public function test_admin_can_view_course_list(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get('/admin/courses')->assertOk();
    }

    public function test_instructor_can_create_course(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);

        Livewire::actingAs($user)
            ->test(AdminCourseForm::class)
            ->set('title', 'New Course')
            ->set('slug', 'new-course')
            ->set('description', 'A test course')
            ->set('published', true)
            ->set('order', 1)
            ->call('save')
            ->assertRedirect('/admin/courses');

        $this->assertDatabaseHas('courses', [
            'title' => 'New Course',
            'slug' => 'new-course',
            'published' => true,
        ]);
    }

    public function test_instructor_can_edit_course(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create();

        Livewire::actingAs($user)
            ->test(AdminCourseForm::class, ['course' => $course])
            ->set('title', 'Updated Title')
            ->call('save')
            ->assertRedirect('/admin/courses');

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_instructor_cannot_delete_course(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create();

        Livewire::actingAs($user)
            ->test(AdminCourseList::class)
            ->call('delete', $course->id);

        $this->assertDatabaseHas('courses', ['id' => $course->id]);
    }

    public function test_admin_can_delete_course(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();

        Livewire::actingAs($user)
            ->test(AdminCourseList::class)
            ->call('delete', $course->id);

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_course_list_shows_published_and_draft(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        Course::factory()->published()->create(['title' => 'Pub Course']);
        Course::factory()->create(['title' => 'Draft Course']);

        $this->actingAs($user)->get('/admin/courses')
            ->assertOk()
            ->assertSee('Pub Course')
            ->assertSee('Draft Course');
    }
}
