<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProgressServiceTest extends TestCase
{
    public function test_course_progress_with_unpublished_step(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'order' => 1,
            'published' => true,
        ]);
        $unpublished = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'order' => 2,
            'published' => false,
        ]);
        $unpublished->completions()->create(['user_id' => $user->id, 'completed_at' => now()]);

        $progress = (new ProgressService)->courseProgress($user, $course);

        expect($progress)->toBe(0.0);
    }

    public function test_course_progress_batch_with_unpublished_step(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'order' => 1,
            'published' => true,
        ]);
        $unpublished = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'order' => 2,
            'published' => false,
        ]);
        $unpublished->completions()->create(['user_id' => $user->id, 'completed_at' => now()]);

        $progress = (new ProgressService)->courseProgressBatch($user, new Collection([$course]));

        expect($progress[$course->id])->toBe(0.0);
    }

    public function test_lesson_complete_with_unpublished_step(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'order' => 1,
            'published' => true,
        ]);
        $unpublished = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'order' => 2,
            'published' => false,
        ]);
        $unpublished->completions()->create(['user_id' => $user->id, 'completed_at' => now()]);

        $complete = (new ProgressService)->lessonComplete($user, $lesson);

        // Only 1 published step, and it's not completed, so lesson is not complete
        expect($complete)->toBeFalse();
    }

    public function test_course_progress_batch(): void
    {
        $user = User::factory()->create();
        $course1 = Course::factory()->published()->create();
        $course1->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson1 = Lesson::factory()->published()->create(['course_id' => $course1->id]);
        $step1 = Step::factory()->reading()->create(['lesson_id' => $lesson1->id, 'order' => 1]);
        $step2 = Step::factory()->reading()->create(['lesson_id' => $lesson1->id, 'order' => 2]);
        $step1->completions()->create(['user_id' => $user->id, 'completed_at' => now()]);

        $course2 = Course::factory()->published()->create();
        $course2->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson2 = Lesson::factory()->published()->create(['course_id' => $course2->id]);
        $step3 = Step::factory()->reading()->create(['lesson_id' => $lesson2->id, 'order' => 1]);
        $step3->completions()->create(['user_id' => $user->id, 'completed_at' => now()]);

        $progress = (new ProgressService)->courseProgressBatch($user, new Collection([$course1, $course2]));

        expect($progress[$course1->id])->toBe(50.0);
        expect($progress[$course2->id])->toBe(100.0);
    }

    public function test_lesson_complete_batch(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson1 = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step1 = Step::factory()->reading()->create(['lesson_id' => $lesson1->id, 'order' => 1]);
        $step1->completions()->create(['user_id' => $user->id, 'completed_at' => now()]);

        $lesson2 = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step2 = Step::factory()->reading()->create(['lesson_id' => $lesson2->id, 'order' => 1]);

        $completed = (new ProgressService)->lessonCompleteBatch($user, new Collection([$lesson1, $lesson2]));

        expect($completed[$lesson1->id])->toBeTrue();
        expect($completed[$lesson2->id])->toBeFalse();
    }

    public function test_step_complete_batch(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step1 = Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $step2 = Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 2]);
        $step1->completions()->create(['user_id' => $user->id, 'completed_at' => now()]);

        $completed = (new ProgressService)->stepCompleteBatch($user, new Collection([$step1, $step2]));

        expect($completed[$step1->id])->toBeTrue();
        expect($completed[$step2->id])->toBeFalse();
    }
}
