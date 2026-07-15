<?php

namespace Tests;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /** @return array{0: User, 1: Course, 2: Lesson} */
    protected function enrolledUser(): array
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);

        return [$user, $course, $lesson];
    }

    /** @return array{0: User, 1: Course, 2: Lesson, 3: Step} */
    protected function enrolledUserWithStep(string $type = 'reading'): array
    {
        [$user, $course, $lesson] = $this->enrolledUser();

        $step = match ($type) {
            'reading' => Step::factory()->reading()->create(['lesson_id' => $lesson->id]),
            default => Step::factory()->create(['lesson_id' => $lesson->id, 'type' => $type]),
        };

        return [$user, $course, $lesson, $step];
    }

    protected function instructor(): User
    {
        return User::factory()->create(['role' => 'instructor']);
    }

    protected function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }
}
