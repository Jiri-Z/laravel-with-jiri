<?php

namespace Tests\Feature;

use App\Livewire\AdminLessonForm;
use App\Livewire\AdminLessonList;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\Feature\Concerns\AdminTestHelpers;
use Tests\TestCase;

class AdminLessonTest extends TestCase
{
    use AdminTestHelpers;

    public function test_instructor_can_view_lesson_list(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons")->assertOk();
    }

    public function test_instructor_can_create_lesson(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(AdminLessonForm::class, ['course' => $course])
            ->set('title', 'New Lesson')
            ->set('slug', 'new-lesson')
            ->set('published', true)
            ->set('order', 1)
            ->call('save')
            ->assertRedirect("/admin/courses/{$course->id}/lessons");

        $this->assertDatabaseHas('lessons', [
            'course_id' => $course->id,
            'title' => 'New Lesson',
            'slug' => 'new-lesson',
        ]);
    }

    public function test_instructor_can_edit_lesson(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        Livewire::actingAs($user)
            ->test(AdminLessonForm::class, ['course' => $course, 'lesson' => $lesson])
            ->set('title', 'Updated Lesson')
            ->call('save')
            ->assertRedirect("/admin/courses/{$course->id}/lessons");

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'title' => 'Updated Lesson',
        ]);
    }

    public function test_instructor_cannot_delete_lesson(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $user->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        Livewire::actingAs($user)
            ->test(AdminLessonList::class, ['course' => $course])
            ->call('delete', $lesson->id)
            ->assertForbidden();

        $this->assertDatabaseHas('lessons', ['id' => $lesson->id]);
    }

    public function test_admin_can_delete_lesson(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        Livewire::actingAs($this->admin())
            ->test(AdminLessonList::class, ['course' => $course])
            ->call('delete', $lesson->id);

        $this->assertDatabaseMissing('lessons', ['id' => $lesson->id]);
    }

    public function test_lesson_list_shows_all_lessons(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();
        Lesson::factory()->published()->create(['course_id' => $course->id, 'title' => 'Pub Lesson']);
        Lesson::factory()->create(['course_id' => $course->id, 'title' => 'Draft Lesson']);

        $this->get("/admin/courses/{$course->id}/lessons")
            ->assertOk()
            ->assertSee('Pub Lesson')
            ->assertSee('Draft Lesson');
    }

    public function test_move_up_swaps_order_with_previous_lesson(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();
        $a = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);
        $b = Lesson::factory()->create(['course_id' => $course->id, 'order' => 2]);

        Livewire::actingAs($this->admin())
            ->test(AdminLessonList::class, ['course' => $course])
            ->call('moveUp', $b->id);

        $this->assertDatabaseHas('lessons', ['id' => $a->id, 'order' => 2]);
        $this->assertDatabaseHas('lessons', ['id' => $b->id, 'order' => 1]);
    }

    public function test_move_down_swaps_order_with_next_lesson(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();
        $a = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);
        $b = Lesson::factory()->create(['course_id' => $course->id, 'order' => 2]);

        Livewire::actingAs($this->admin())
            ->test(AdminLessonList::class, ['course' => $course])
            ->call('moveDown', $a->id);

        $this->assertDatabaseHas('lessons', ['id' => $a->id, 'order' => 2]);
        $this->assertDatabaseHas('lessons', ['id' => $b->id, 'order' => 1]);
    }

    public function test_student_cannot_access_admin_lessons(): void
    {
        $this->asStudent();
        $course = Course::factory()->create();

        $this->get("/admin/courses/{$course->id}/lessons")->assertForbidden();
    }

    public function test_guest_is_redirected_from_admin_lessons(): void
    {
        $course = Course::factory()->create();

        $this->get("/admin/courses/{$course->id}/lessons")->assertRedirect('/login');
        $this->get("/admin/courses/{$course->id}/lessons/create")->assertRedirect('/login');
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $this->get("/admin/courses/{$course->id}/lessons/{$lesson->id}/edit")->assertRedirect('/login');
    }

    public function test_lesson_form_with_wrong_course_returns_404(): void
    {
        $this->asAdmin();
        $courseA = Course::factory()->create();
        $courseB = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $courseA->id]);

        $this->get("/admin/courses/{$courseB->id}/lessons/{$lesson->id}/edit")
            ->assertNotFound();
    }

    public function test_duplicate_lesson_slug_within_course_fails_validation(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();
        Lesson::factory()->create(['course_id' => $course->id, 'slug' => 'existing-slug']);

        Livewire::actingAs($this->admin())
            ->test(AdminLessonForm::class, ['course' => $course])
            ->set('title', 'Duplicate')
            ->set('slug', 'existing-slug')
            ->set('order', 1)
            ->call('save')
            ->assertHasErrors('slug');
    }

    public function test_move_up_on_first_lesson_does_nothing(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();
        $a = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);
        Lesson::factory()->create(['course_id' => $course->id, 'order' => 2]);

        Livewire::actingAs($this->admin())
            ->test(AdminLessonList::class, ['course' => $course])
            ->call('moveUp', $a->id);

        $this->assertDatabaseHas('lessons', ['id' => $a->id, 'order' => 1]);
    }

    public function test_move_down_on_last_lesson_does_nothing(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();
        Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);
        $b = Lesson::factory()->create(['course_id' => $course->id, 'order' => 2]);

        Livewire::actingAs($this->admin())
            ->test(AdminLessonList::class, ['course' => $course])
            ->call('moveDown', $b->id);

        $this->assertDatabaseHas('lessons', ['id' => $b->id, 'order' => 2]);
    }

    public function test_move_up_rolls_back_when_swap_fails(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $a = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);
        $b = Lesson::factory()->create(['course_id' => $course->id, 'order' => 2]);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER abort_lesson_swap_update
            BEFORE UPDATE ON lessons
            WHEN NEW."order" != -1 AND EXISTS(
                SELECT 1 FROM lessons WHERE "order" = -1
            )
            BEGIN
                SELECT RAISE(ABORT, 'swap failed');
            END;
        SQL);

        try {
            Livewire::actingAs($user)
                ->test(AdminLessonList::class, ['course' => $course])
                ->call('moveUp', $b->id);

            $this->fail('Expected the reorder action to fail.');
        } catch (\Throwable) {
            // The trigger intentionally aborts the reorder mid-swap.
        } finally {
            DB::unprepared('DROP TRIGGER IF EXISTS abort_lesson_swap_update');
        }

        $this->assertDatabaseHas('lessons', ['id' => $a->id, 'order' => 1]);
        $this->assertDatabaseHas('lessons', ['id' => $b->id, 'order' => 2]);
    }

    public function test_admin_lesson_list_empty_state(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();

        $this->get("/admin/courses/{$course->id}/lessons")
            ->assertOk()
            ->assertSee(__('admin.no_lessons_yet'));
    }

    public function test_admin_lesson_list_renders_table_headers(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();
        Lesson::factory()->create(['course_id' => $course->id]);

        $this->get("/admin/courses/{$course->id}/lessons")
            ->assertOk()
            ->assertSeeInOrder(['Order', 'Title', 'Status', 'Actions']);
    }

    public function test_search_filters_lesson_list(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();
        Lesson::factory()->create(['course_id' => $course->id, 'title' => 'Alpha Lesson']);
        Lesson::factory()->create(['course_id' => $course->id, 'title' => 'Beta Lesson']);

        $this->get("/admin/courses/{$course->id}/lessons?q=Alpha")
            ->assertOk()
            ->assertSee('Alpha Lesson')
            ->assertDontSee('Beta Lesson');
    }

    public function test_search_no_results_lesson_empty(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();
        Lesson::factory()->create(['course_id' => $course->id, 'title' => 'Alpha']);

        $this->get("/admin/courses/{$course->id}/lessons?q=zzz_nonexistent")
            ->assertOk()
            ->assertSee(__('admin.no_lessons_found'))
            ->assertDontSee('Alpha');
    }

    public function test_lesson_pagination(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();

        for ($i = 1; $i <= 12; $i++) {
            Lesson::factory()->create(['course_id' => $course->id, 'order' => $i, 'title' => sprintf('Lesson %02d', $i)]);
        }

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons?page=2")
            ->assertOk()
            ->assertSee('Lesson 11')
            ->assertSee('Lesson 12')
            ->assertDontSee('Lesson 01');
    }

    public function test_wire_loading_present_in_lesson_list(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();
        Lesson::factory()->create(['course_id' => $course->id]);

        $this->get("/admin/courses/{$course->id}/lessons")
            ->assertOk()
            ->assertSee('wire:loading');
    }

    public function test_lesson_url_tracking_with_ownership(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['user_id' => $instructor->id]);
        Lesson::factory()->create(['course_id' => $course->id, 'title' => 'Alpha Lesson']);
        Lesson::factory()->create(['course_id' => $course->id, 'title' => 'Beta Lesson']);

        $this->actingAs($instructor)->get("/admin/courses/{$course->id}/lessons?q=Alpha")
            ->assertOk()
            ->assertSee('Alpha Lesson')
            ->assertDontSee('Beta Lesson');
    }

    public function test_same_slug_allowed_in_different_course(): void
    {
        $this->asAdmin();
        $courseA = Course::factory()->create();
        $courseB = Course::factory()->create();
        Lesson::factory()->create(['course_id' => $courseA->id, 'slug' => 'shared-slug']);

        Livewire::actingAs($this->admin())
            ->test(AdminLessonForm::class, ['course' => $courseB])
            ->set('title', 'Shared Slug Lesson')
            ->set('slug', 'shared-slug')
            ->set('order', 1)
            ->call('save')
            ->assertRedirect("/admin/courses/{$courseB->id}/lessons");

        $this->assertDatabaseHas('lessons', [
            'course_id' => $courseB->id,
            'slug' => 'shared-slug',
        ]);
    }

    public function test_lesson_validates_required_fields(): void
    {
        $this->asAdmin();
        $course = Course::factory()->create();

        Livewire::actingAs($this->admin())
            ->test(AdminLessonForm::class, ['course' => $course])
            ->set('title', '')
            ->set('slug', '')
            ->call('save')
            ->assertHasErrors(['title', 'slug']);
    }

    public function test_instructor_cannot_edit_other_instructors_lesson(): void
    {
        $instructorA = User::factory()->create(['role' => 'instructor']);
        $instructorB = User::factory()->create(['role' => 'instructor']);
        $courseB = Course::factory()->create(['user_id' => $instructorB->id]);
        $lessonB = Lesson::factory()->create(['course_id' => $courseB->id]);

        Livewire::actingAs($instructorA)
            ->test(AdminLessonForm::class, ['course' => $courseB, 'lesson' => $lessonB])
            ->assertForbidden();

        $this->actingAs($instructorA)
            ->get("/admin/courses/{$courseB->id}/lessons/{$lessonB->id}/edit")
            ->assertForbidden();
    }
}
