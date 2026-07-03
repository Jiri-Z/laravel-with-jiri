<?php

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
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Sam Student',
                'role' => 'student',
                'password' => bcrypt('password'),
            ]
        );

        $course = Course::firstOrCreate(
            ['slug' => __('factories.seeder_course1_slug')],
            [
                'title' => __('factories.seeder_course1_title'),
                'description' => __('factories.seeder_course1_description'),
                'published' => true,
                'order' => 1,
                'user_id' => $instructor->id,
            ]
        );

        $lesson1 = Lesson::firstOrCreate(
            ['slug' => __('factories.seeder_lesson1_slug'), 'course_id' => $course->id],
            [
                'title' => __('factories.seeder_lesson1_title'),
                'description' => __('factories.seeder_lesson1_description'),
                'published' => true,
                'order' => 1,
            ]
        );

        Step::firstOrCreate(
            ['lesson_id' => $lesson1->id, 'order' => 1],
            [
                'title' => __('factories.seeder_step1_title'),
                'type' => StepType::Reading,
                'content' => __('factories.seeder_step1_content'),
            ]
        );

        Step::firstOrCreate(
            ['lesson_id' => $lesson1->id, 'order' => 2],
            [
                'title' => __('factories.seeder_step2_title'),
                'type' => StepType::Quiz,
                'content' => json_encode([
                    ['type' => 'single', 'question' => __('factories.seeder_quiz1_question'), 'options' => __('factories.seeder_quiz1_options'), 'answer' => 1, 'explanation' => 'MVC is the correct answer.', 'difficulty' => 'easy', 'topic' => 'laravel'],
                ]),
            ]
        );

        Step::firstOrCreate(
            ['lesson_id' => $lesson1->id, 'order' => 3],
            [
                'title' => __('factories.seeder_step4_title'),
                'type' => StepType::Quiz,
                'content' => json_encode([
                    ['type' => 'text', 'question' => __('factories.seeder_quiz4_question'), 'answer' => __('factories.seeder_quiz4_answer'), 'alternatives' => null, 'explanation' => 'Web routes are defined in routes/web.php.', 'difficulty' => 'easy', 'topic' => 'laravel'],
                ]),
            ]
        );

        Step::firstOrCreate(
            ['lesson_id' => $lesson1->id, 'order' => 4],
            [
                'title' => __('factories.seeder_step3_title'),
                'type' => StepType::Quiz,
                'content' => json_encode([
                    ['type' => 'single', 'question' => __('factories.seeder_quiz2_question'), 'options' => __('factories.seeder_quiz2_options'), 'answer' => 1, 'explanation' => 'Web routes are defined in routes/web.php.', 'difficulty' => 'easy', 'topic' => 'laravel'],
                    ['type' => 'text', 'question' => __('factories.seeder_quiz3_question'), 'answer' => __('factories.seeder_quiz3_answer'), 'alternatives' => ['blade', 'Blade templating engine'], 'explanation' => 'Blade is Laravels templating engine.', 'difficulty' => 'easy', 'topic' => 'laravel'],
                ]),
            ]
        );

        $lesson2 = Lesson::firstOrCreate(
            ['slug' => __('factories.seeder_lesson2_slug'), 'course_id' => $course->id],
            [
                'title' => __('factories.seeder_lesson2_title'),
                'description' => __('factories.seeder_lesson2_description'),
                'published' => true,
                'order' => 2,
            ]
        );

        Step::firstOrCreate(
            ['lesson_id' => $lesson2->id, 'order' => 1],
            [
                'title' => __('factories.seeder_step5_title'),
                'type' => StepType::Reading,
                'content' => __('factories.seeder_step5_content'),
            ]
        );

        Step::firstOrCreate(
            ['lesson_id' => $lesson2->id, 'order' => 2],
            [
                'title' => __('factories.seeder_step6_title'),
                'type' => StepType::Quiz,
                'content' => json_encode([
                    ['type' => 'multiple', 'question' => __('factories.seeder_quiz5_question'), 'options' => __('factories.seeder_quiz5_options'), 'answer' => [0, 1, 2, 3], 'explanation' => 'GET, POST, PUT, and DELETE are standard HTTP methods.', 'difficulty' => 'easy', 'topic' => 'laravel'],
                ]),
            ]
        );

        $course2 = Course::firstOrCreate(
            ['slug' => __('factories.seeder_course2_slug')],
            [
                'title' => __('factories.seeder_course2_title'),
                'description' => __('factories.seeder_course2_description'),
                'published' => true,
                'order' => 2,
                'user_id' => $instructor->id,
            ]
        );

        $lesson3 = Lesson::firstOrCreate(
            ['slug' => __('factories.seeder_lesson3_slug'), 'course_id' => $course2->id],
            [
                'title' => __('factories.seeder_lesson3_title'),
                'description' => __('factories.seeder_lesson3_description'),
                'published' => true,
                'order' => 1,
            ]
        );

        Step::firstOrCreate(
            ['lesson_id' => $lesson3->id, 'order' => 1],
            [
                'title' => __('factories.seeder_step7_title'),
                'type' => StepType::Reading,
                'content' => __('factories.seeder_step7_content'),
            ]
        );

        Step::firstOrCreate(
            ['lesson_id' => $lesson3->id, 'order' => 2],
            [
                'title' => __('factories.seeder_step8_title'),
                'type' => StepType::Coding,
                'content' => json_encode([
                    'prompt' => __('factories.seeder_coding1_prompt'),
                    'initial_code' => __('factories.seeder_coding1_initial_code'),
                    'test_code' => __('factories.seeder_coding1_test_code'),
                    'expected_output' => __('factories.seeder_coding1_expected_output'),
                ]),
            ]
        );
    }
}
