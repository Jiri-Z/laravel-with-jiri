<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Collection;

class ImportCourseFromYamlResult
{
    /**
     * @param  Collection<int, Lesson>  $lessons
     * @param  array<int, string>  $errors
     */
    public function __construct(
        public readonly Course $course,
        public readonly Collection $lessons,
        public readonly array $errors = [],
    ) {}
}
