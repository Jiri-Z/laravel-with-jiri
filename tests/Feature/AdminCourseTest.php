<?php

namespace Tests\Feature;

use App\Livewire\AdminCourseForm;
use App\Livewire\AdminCourseList;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\Feature\Concerns\AdminTestHelpers;
use Tests\TestCase;
use Throwable;

class AdminCourseTest extends TestCase
{
    use AdminTestHelpers;

    public function test_student_cannot_access_admin(): void
    {
        $this->asStudent();
        $this->get('/admin/courses')->assertForbidden();
    }

    public function test_instructor_can_view_course_list(): void
    {
        $this->asInstructor()->get('/admin/courses')->assertOk();
    }

    public function test_admin_can_view_course_list(): void
    {
        $this->asAdmin()->get('/admin/courses')->assertOk();
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
        $course = Course::factory()->create(['user_id' => $user->id]);

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
        $course = Course::factory()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(AdminCourseList::class)
            ->call('delete', $course->id)
            ->assertForbidden();

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
        $this->asAdmin();
        Course::factory()->published()->create(['title' => 'Pub Course']);
        Course::factory()->create(['title' => 'Draft Course']);

        $this->get('/admin/courses')
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

    public function test_move_up_rolls_back_when_swap_fails(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $a = Course::factory()->create(['order' => 1, 'title' => 'Alpha']);
        $b = Course::factory()->create(['order' => 2, 'title' => 'Beta']);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER abort_course_swap_update
            BEFORE UPDATE ON courses
            WHEN NEW."order" != -1 AND EXISTS(
                SELECT 1 FROM courses WHERE "order" = -1
            )
            BEGIN
                SELECT RAISE(ABORT, 'swap failed');
            END;
        SQL);

        try {
            Livewire::actingAs($user)
                ->test(AdminCourseList::class)
                ->call('moveUp', $b->id);

            $this->fail('Expected the reorder action to fail.');
        } catch (Throwable) {
            // The trigger intentionally aborts the reorder mid-swap.
        } finally {
            DB::unprepared('DROP TRIGGER IF EXISTS abort_course_swap_update');
        }

        $this->assertDatabaseHas('courses', ['id' => $a->id, 'order' => 1]);
        $this->assertDatabaseHas('courses', ['id' => $b->id, 'order' => 2]);
    }

    public function test_instructor_can_access_create_and_edit_pages_via_http(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get('/admin/courses/create')->assertOk();
        $this->actingAs($user)->get("/admin/courses/{$course->id}/edit")->assertOk();
    }

    public function test_duplicate_course_slug_fails_validation(): void
    {
        $this->asAdmin();
        Course::factory()->create(['slug' => 'existing-slug']);

        Livewire::actingAs($this->admin())
            ->test(AdminCourseForm::class)
            ->set('title', 'Duplicate')
            ->set('slug', 'existing-slug')
            ->set('order', 1)
            ->call('save')
            ->assertHasErrors('slug');
    }

    public function test_admin_course_list_empty_state(): void
    {
        $this->asAdmin()->get('/admin/courses')
            ->assertOk()
            ->assertSee(__('admin.no_courses_yet'));
    }

    public function test_admin_course_list_renders_table_headers(): void
    {
        $this->asAdmin();
        Course::factory()->create();

        $this->get('/admin/courses')
            ->assertOk()
            ->assertSeeInOrder([__('admin.th_order'), __('admin.th_title'), __('admin.th_status'), __('admin.th_actions')]);
    }

    public function test_search_filters_course_list(): void
    {
        $this->asAdmin();
        Course::factory()->create(['title' => 'Alpha Course']);
        Course::factory()->create(['title' => 'Beta Course']);

        $this->get('/admin/courses?q=Alpha')
            ->assertOk()
            ->assertSee('Alpha Course')
            ->assertDontSee('Beta Course');
    }

    public function test_search_with_single_multi_byte_character_returns_all_results(): void
    {
        $this->asAdmin();
        Course::factory()->create(['title' => 'Alpha Course']);
        Course::factory()->create(['title' => 'Beta Course']);

        $this->get('/admin/courses?q=ñ')
            ->assertOk()
            ->assertSee('Alpha Course')
            ->assertSee('Beta Course');
    }

    public function test_search_no_results_shows_no_courses_found(): void
    {
        $this->asAdmin();
        Course::factory()->create(['title' => 'Alpha']);

        $this->get('/admin/courses?q=zzz_nonexistent')
            ->assertOk()
            ->assertSee(__('admin.no_courses_found'))
            ->assertDontSee('Alpha');
    }

    public function test_pagination_shows_second_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        for ($i = 1; $i <= 12; $i++) {
            Course::factory()->create(['order' => $i, 'title' => sprintf('Course %02d', $i)]);
        }

        $this->actingAs($user)->get('/admin/courses?page=2')
            ->assertOk()
            ->assertSee('Course 11')
            ->assertSee('Course 12')
            ->assertDontSee('Course 01');
    }

    public function test_wire_loading_present_in_course_list(): void
    {
        $this->asAdmin();
        Course::factory()->create();

        $this->get('/admin/courses')
            ->assertOk()
            ->assertSee('wire:loading');
    }

    public function test_search_attribute_is_url_tracked(): void
    {
        $this->asAdmin();
        Course::factory()->create(['title' => 'Alpha', 'slug' => 'alpha']);
        Course::factory()->create(['title' => 'Beta', 'slug' => 'beta']);

        $this->get('/admin/courses?q=Alpha')
            ->assertOk()
            ->assertSee('Alpha')
            ->assertDontSee('Beta');
    }

    public function test_search_combined_with_ownership(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Course::factory()->create(['title' => 'My Course', 'user_id' => $instructor->id]);
        Course::factory()->create(['title' => 'Other Course']);

        $this->actingAs($instructor)->get('/admin/courses?q=Course')
            ->assertOk()
            ->assertSee('My Course')
            ->assertDontSee('Other Course');
    }

    public function test_search_resets_to_page_one(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        for ($i = 1; $i <= 12; $i++) {
            Course::factory()->create(['order' => $i, 'title' => sprintf('Course %02d', $i)]);
        }

        $component = Livewire::actingAs($user)->test(AdminCourseList::class);

        $component->set('paginators.page', 2);
        $component->assertSee('Course 11');

        $component->set('search', 'Course 01');
        $component->assertSee('Course 01');
        $component->assertDontSee('Course 11');
    }

    public function test_edit_keeps_slug_unchanged(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id, 'slug' => 'original-slug']);

        Livewire::actingAs($user)
            ->test(AdminCourseForm::class, ['course' => $course])
            ->set('title', 'Updated Title')
            ->call('save')
            ->assertRedirect('/admin/courses');

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'slug' => 'original-slug',
            'title' => 'Updated Title',
        ]);
    }

    public function test_validates_required_fields(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);

        Livewire::actingAs($user)
            ->test(AdminCourseForm::class)
            ->set('title', '')
            ->set('slug', '')
            ->call('save')
            ->assertHasErrors(['title', 'slug']);
    }

    public function test_null_description_stored_when_empty(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);

        Livewire::actingAs($user)
            ->test(AdminCourseForm::class)
            ->set('title', 'Minimal Course')
            ->set('slug', 'minimal-course')
            ->set('description', '')
            ->set('order', 1)
            ->call('save');

        $this->assertDatabaseHas('courses', [
            'title' => 'Minimal Course',
            'description' => null,
        ]);
    }

    public function test_created_course_gets_current_locale(): void
    {
        App::setLocale('cs');
        $user = User::factory()->create(['role' => 'instructor']);

        Livewire::actingAs($user)
            ->test(AdminCourseForm::class)
            ->set('title', 'Czech Course')
            ->set('slug', 'czech-course')
            ->set('order', 1)
            ->call('save')
            ->assertRedirect('/admin/courses');

        $this->assertDatabaseHas('courses', [
            'title' => 'Czech Course',
            'locale' => 'cs',
        ]);
    }

    public function test_instructor_cannot_edit_other_instructors_course(): void
    {
        $instructorA = User::factory()->create(['role' => 'instructor']);
        $instructorB = User::factory()->create(['role' => 'instructor']);
        $courseB = Course::factory()->create(['user_id' => $instructorB->id]);

        Livewire::actingAs($instructorA)
            ->test(AdminCourseForm::class, ['course' => $courseB])
            ->assertForbidden();

        $this->actingAs($instructorA)
            ->get("/admin/courses/{$courseB->id}/edit")
            ->assertForbidden();
    }
}
