<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Tests\TestCase;

class CourseDetailTest extends TestCase
{
    public function test_guest_is_redirected_to_login_when_viewing_course(): void
    {
        $course = Course::factory()->create();

        $this->get("/courses/{$course->slug}")->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_course_lessons(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create(['title' => 'My Course']);
        $lessons = Lesson::factory()->count(3)->published()->create([
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}");

        $response->assertOk();
        $response->assertSee('My Course');
        foreach ($lessons as $lesson) {
            $response->assertSee($lesson->title);
        }
    }

    public function test_draft_course_is_not_visible(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['published' => false]);

        $this->actingAs($user)->get("/courses/{$course->slug}")->assertNotFound();
    }

    public function test_lessons_are_ordered_by_order_column(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $second = Lesson::factory()->published()->create(['course_id' => $course->id, 'title' => 'Second', 'order' => 2]);
        $first = Lesson::factory()->published()->create(['course_id' => $course->id, 'title' => 'First', 'order' => 1]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}");

        $response->assertSeeInOrder(['First', 'Second']);
    }
}
