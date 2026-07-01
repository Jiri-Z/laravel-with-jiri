<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class EnrollInCourse
{
    public function handle(User $user, Course $course): void
    {
        if (! $course->published) {
            abort(404);
        }

        try {
            CourseEnrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'enrolled_at' => now(),
            ]);
        } catch (QueryException $e) {
            if (! $this->isDuplicateEntryError($e)) {
                throw $e;
            }
        }
    }

    private function isDuplicateEntryError(QueryException $e): bool
    {
        $code = $e->getCode();
        $message = $e->getMessage();

        return match (DB::getDriverName()) {
            'sqlite' => $code === '23000' && str_contains($message, 'UNIQUE constraint'),
            'mysql' => $code === '23000' && str_contains($message, 'Duplicate entry'),
            'pgsql' => $code === '23505',
            default => false,
        };
    }
}
