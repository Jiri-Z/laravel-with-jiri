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

    public function test_move_up_swaps_order_with_previous_course(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $a = Course::factory()->create(['order' => 1, 'title' => 'Alpha']);
        $b = Course::factory()->create(['order' => 2, 'title' => 'Beta']);

        Livewire::actingAs($user)
            ->test(AdminCourseList::class)
            ->call('moveUp', $b->id);

        $this->assertDatabaseHas('courses', ['id' => $a->id, 'order' => 2]);
        $this->assertDatabaseHas('courses', ['id' => $b->id, 'order' => 1]);
    }

    public function test_move_down_swaps_order_with_next_course(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $a = Course::factory()->create(['order' => 1, 'title' => 'Alpha']);
        $b = Course::factory()->create(['order' => 2, 'title' => 'Beta']);

        Livewire::actingAs($user)
            ->test(AdminCourseList::class)
            ->call('moveDown', $a->id);

        $this->assertDatabaseHas('courses', ['id' => $a->id, 'order' => 2]);
        $this->assertDatabaseHas('courses', ['id' => $b->id, 'order' => 1]);
    }

    public function test_guest_is_redirected_from_admin_courses(): void
    {
        $this->get('/admin/courses')->assertRedirect('/login');
        $this->get('/admin/courses/create')->assertRedirect('/login');
        $course = Course::factory()->create();
        $this->get("/admin/courses/{$course->id}/edit")->assertRedirect('/login');
    }

    public function test_move_up_on_first_item_does_nothing(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $a = Course::factory()->create(['order' => 1, 'title' => 'First']);
        Course::factory()->create(['order' => 2, 'title' => 'Second']);

        Livewire::actingAs($user)
            ->test(AdminCourseList::class)
            ->call('moveUp', $a->id);

        $this->assertDatabaseHas('courses', ['id' => $a->id, 'order' => 1]);
    }

    public function test_move_down_on_last_item_does_nothing(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        Course::factory()->create(['order' => 1, 'title' => 'First']);
        $b = Course::factory()->create(['order' => 2, 'title' => 'Last']);

        Livewire::actingAs($user)
            ->test(AdminCourseList::class)
            ->call('moveDown', $b->id);

        $this->assertDatabaseHas('courses', ['id' => $b->id, 'order' => 2]);
    }

    public function test_instructor_can_access_create_and_edit_pages_via_http(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create();

        $this->actingAs($user)->get('/admin/courses/create')->assertOk();
        $this->actingAs($user)->get("/admin/courses/{$course->id}/edit")->assertOk();
    }

    public function test_duplicate_course_slug_fails_validation(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        Course::factory()->create(['slug' => 'existing-slug']);

        Livewire::actingAs($user)
            ->test(AdminCourseForm::class)
            ->set('title', 'Duplicate')
            ->set('slug', 'existing-slug')
            ->set('order', 1)
            ->call('save')
            ->assertHasErrors('slug');
    }
}
