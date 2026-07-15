<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StepType;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::firstOrCreate(
            ['email' => 'instructor@example.com'],
            [
                'name' => 'Jane Instructor',
                'role' => 'instructor',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'role' => 'admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Sam Student',
                'role' => 'student',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $locale = app()->getLocale();
        $dataPath = database_path("data/{$locale}");

        $courseFiles = glob("{$dataPath}/*.php");

        if ($courseFiles === false || $courseFiles === []) {
            return;
        }

        $order = 1;

        foreach ($courseFiles as $file) {
            $courseData = require $file;

            if (! is_array($courseData)) {
                continue;
            }

            $course = Course::firstOrCreate(
                ['slug' => $courseData['slug']],
                [
                    'title' => $courseData['title'],
                    'description' => $courseData['description'],
                    'published' => true,
                    'order' => $order,
                    'user_id' => $instructor->id,
                ]
            );

            $order++;

            $lessonOrder = 1;

            foreach ($courseData['lessons'] as $lessonData) {
                $lesson = Lesson::firstOrCreate(
                    ['slug' => $lessonData['slug'], 'course_id' => $course->id],
                    [
                        'title' => $lessonData['title'],
                        'description' => $lessonData['description'],
                        'published' => true,
                        'order' => $lessonOrder,
                    ]
                );

                $stepOrder = 1;

                foreach ($lessonData['steps'] as $stepData) {
                    $stepAttributes = [
                        'title' => $stepData['title'],
                        'published' => true,
                        'order' => $stepOrder,
                    ];

                    if ($stepData['type'] === 'reading') {
                        $stepAttributes['type'] = StepType::Reading;
                        $stepAttributes['reading_content'] = $stepData['content'];
                    } elseif ($stepData['type'] === 'quiz') {
                        $stepAttributes['type'] = StepType::Quiz;
                        $stepAttributes['quiz_content'] = json_encode($stepData['quiz_content']);
                    }

                    Step::firstOrCreate(
                        ['lesson_id' => $lesson->id, 'order' => $stepOrder],
                        $stepAttributes
                    );

                    $stepOrder++;
                }

                $lessonOrder++;
            }
        }
    }
}
