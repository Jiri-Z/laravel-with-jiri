<?php

namespace Tests\Feature;

use App\Livewire\AdminCourseForm;
use App\Livewire\AdminLessonForm;
use App\Livewire\AdminStepForm;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class AdminValidationTest extends TestCase
{
    public function test_admin_course_form_has_validation_rules(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $component = Livewire::actingAs($user)->test(AdminCourseForm::class);

        $rules = $component->instance()->validationRules();

        expect($rules)->toHaveKeys(['title', 'slug', 'description', 'published', 'order']);
    }

    public function test_admin_course_form_update_rules_include_unique_ignoring_current_id(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();

        $component = Livewire::actingAs($user)
            ->test(AdminCourseForm::class, ['course' => $course]);

        $rules = $component->instance()->validationRules();

        expect($rules['slug'])->toContain(','.$course->id);
    }

    public function test_admin_lesson_form_has_validation_rules(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();

        $component = Livewire::actingAs($user)
            ->test(AdminLessonForm::class, ['course' => $course]);

        $rules = $component->instance()->validationRules();

        expect($rules)->toHaveKeys(['title', 'slug', 'description', 'published', 'order']);
    }

    public function test_admin_step_form_has_validation_rules(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $component = Livewire::actingAs($user)
            ->test(AdminStepForm::class, ['course' => $course, 'lesson' => $lesson]);

        $rules = $component->instance()->validationRules();

        expect($rules)->toHaveKeys(['title', 'type', 'content', 'order']);
    }
}
