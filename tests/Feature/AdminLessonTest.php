<?php

namespace Tests\Feature;

use App\Livewire\AdminLessonForm;
use App\Livewire\AdminLessonList;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class AdminLessonTest extends TestCase
{
    public function test_instructor_can_view_lesson_list(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create();

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons")->assertOk();
    }

    public function test_instructor_can_create_lesson(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create();

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
        $course = Course::factory()->create();
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
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        Livewire::actingAs($user)
            ->test(AdminLessonList::class, ['course' => $course])
            ->call('delete', $lesson->id);

        $this->assertDatabaseHas('lessons', ['id' => $lesson->id]);
    }

    public function test_admin_can_delete_lesson(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        Livewire::actingAs($user)
            ->test(AdminLessonList::class, ['course' => $course])
            ->call('delete', $lesson->id);

        $this->assertDatabaseMissing('lessons', ['id' => $lesson->id]);
    }

    public function test_lesson_list_shows_all_lessons(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        Lesson::factory()->published()->create(['course_id' => $course->id, 'title' => 'Pub Lesson']);
        Lesson::factory()->create(['course_id' => $course->id, 'title' => 'Draft Lesson']);

        $this->actingAs($user)->get("/admin/courses/{$course->id}/lessons")
            ->assertOk()
            ->assertSee('Pub Lesson')
            ->assertSee('Draft Lesson');
    }
}
