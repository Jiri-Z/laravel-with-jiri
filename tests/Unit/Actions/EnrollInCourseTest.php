<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\EnrollInCourse;
use App\Exceptions\CourseNotPublishedException;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EnrollInCourseTest extends TestCase
{
    /**
     * @test
     */
    public function enrolls_user_in_published_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();

        (new EnrollInCourse)->handle($user, $course);

        $this->assertDatabaseHas('course_enrollments', [
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    }

    /**
     * @test
     */
    public function duplicate_enrollment_is_idempotent(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        (new EnrollInCourse)->handle($user, $course);

        $this->assertDatabaseCount('course_enrollments', 1);
    }

    /**
     * @test
     */
    public function unpublished_course_throws_exception(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['published' => false]);

        $this->expectException(CourseNotPublishedException::class);

        (new EnrollInCourse)->handle($user, $course);
    }

    /**
     * @test
     */
    public function non_duplicate_query_exception_is_not_swallowed(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();

        DB::unprepared('CREATE TRIGGER fail_enroll_insert BEFORE INSERT ON course_enrollments
            WHEN NEW.user_id IS NOT NULL
            BEGIN
                SELECT RAISE(FAIL, \'simulated non-duplicate error\');
            END;');

        try {
            $this->expectException(QueryException::class);

            (new EnrollInCourse)->handle($user, $course);
        } finally {
            DB::unprepared('DROP TRIGGER IF EXISTS fail_enroll_insert');
        }
    }
}
