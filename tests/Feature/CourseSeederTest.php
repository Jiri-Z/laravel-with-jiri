<?php

namespace Tests\Feature;

use App\Enums\StepType;
use Database\Seeders\CourseSeeder;
use Tests\TestCase;

class CourseSeederTest extends TestCase
{
    public function test_seed_includes_quiz_type_step(): void
    {
        $this->seed(CourseSeeder::class);

        $this->assertDatabaseHas('steps', [
            'type' => StepType::Quiz->value,
        ]);
    }
}
