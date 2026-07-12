<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Lesson;
use App\Models\Step;
use Illuminate\Database\Eloquent\Collection;

class ImportLessonFromYamlResult
{
    /**
     * @param  Collection<int, Step>  $steps
     * @param  array<int, string>  $errors
     */
    public function __construct(
        public readonly Lesson $lesson,
        public readonly Collection $steps,
        public readonly array $errors = [],
    ) {}
}
