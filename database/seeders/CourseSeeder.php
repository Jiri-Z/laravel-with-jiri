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
        $instructor = User::factory()->create([
            'name' => 'Jane Instructor',
            'email' => 'instructor@example.com',
            'role' => 'instructor',
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $student = User::factory()->create([
            'name' => 'Sam Student',
            'email' => 'student@example.com',
            'role' => 'student',
        ]);

        $course = Course::factory()->create([
            'title' => 'Laravel Basics',
            'slug' => 'laravel-basics',
            'description' => 'Learn the fundamentals of Laravel, from routing and controllers to Eloquent and Blade.',
            'published' => true,
            'order' => 1,
        ]);

        $lesson1 = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Introduction to Laravel',
            'slug' => 'introduction-to-laravel',
            'description' => 'What is Laravel and why use it?',
            'published' => true,
            'order' => 1,
        ]);

        Step::factory()->create([
            'lesson_id' => $lesson1->id,
            'title' => 'What is Laravel?',
            'type' => StepType::Reading,
            'content' => "Laravel is a PHP web application framework with expressive, elegant syntax.\n\nIt provides a robust set of tools for building modern web applications, including:\n- Routing\n- Middleware\n- Eloquent ORM\n- Blade templating\n- Queues and jobs\n\nLaravel follows the MVC (Model-View-Controller) architectural pattern.",
            'order' => 1,
        ]);

        Step::factory()->create([
            'lesson_id' => $lesson1->id,
            'title' => 'Quick Check',
            'type' => StepType::QuizSingle,
            'content' => json_encode([
                'question' => 'Which architectural pattern does Laravel follow?',
                'options' => ['MVP', 'MVC', 'MVVM', 'REST'],
                'correct_answer' => 1,
            ]),
            'order' => 2,
        ]);

        Step::factory()->create([
            'lesson_id' => $lesson1->id,
            'title' => 'What is a framework?',
            'type' => StepType::QuizText,
            'content' => json_encode([
                'question' => 'What directory are web routes defined in?',
                'correct_answer' => 'routes/web.php',
            ]),
            'order' => 3,
        ]);

        $lesson2 = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Routing & Controllers',
            'slug' => 'routing-and-controllers',
            'description' => 'How Laravel handles HTTP requests.',
            'published' => true,
            'order' => 2,
        ]);

        Step::factory()->create([
            'lesson_id' => $lesson2->id,
            'title' => 'Defining Routes',
            'type' => StepType::Reading,
            'content' => "Routes are defined in `routes/web.php` for web routes and `routes/api.php` for API routes.\n\nBasic route syntax:\n\n```php\nRoute::get('/hello', function () {\n    return 'Hello World';\n});\n```\n\nRoutes can be grouped, named, and restricted by middleware.",
            'order' => 1,
        ]);

        Step::factory()->create([
            'lesson_id' => $lesson2->id,
            'title' => 'Route Types Quiz',
            'type' => StepType::QuizMultiple,
            'content' => json_encode([
                'question' => 'Which HTTP methods can Laravel routes handle?',
                'options' => ['GET', 'POST', 'PUT', 'DELETE', 'FETCH'],
                'correct_answers' => [0, 1, 2, 3],
            ]),
            'order' => 2,
        ]);

        $course2 = Course::factory()->create([
            'title' => 'Eloquent ORM',
            'slug' => 'eloquent-orm',
            'description' => 'Master Laravel\'s beautiful ORM for database interactions.',
            'published' => true,
            'order' => 2,
        ]);

        $lesson3 = Lesson::factory()->create([
            'course_id' => $course2->id,
            'title' => 'Models & Migrations',
            'slug' => 'models-and-migrations',
            'description' => 'Defining your database schema and models.',
            'published' => true,
            'order' => 1,
        ]);

        Step::factory()->create([
            'lesson_id' => $lesson3->id,
            'title' => 'Creating a Migration',
            'type' => StepType::Reading,
            'content' => "Migrations are like version control for your database. They allow you to define and share the database schema.\n\nCreate a migration:\n```bash\nphp artisan make:migration create_posts_table\n```\n\nMigrations define table columns and indexes using Laravel's schema builder.",
            'order' => 1,
        ]);

        Step::factory()->create([
            'lesson_id' => $lesson3->id,
            'title' => 'Write Your First Migration',
            'type' => StepType::Coding,
            'content' => json_encode([
                'prompt' => "Write a PHP function that returns the string 'Migration created!'.",
                'initial_code' => "<?php\n\nfunction createMigration() {\n    // Return the correct string\n}\n",
                'test_code' => "<?php\necho createMigration();",
                'expected_output' => 'Migration created!',
            ]),
            'order' => 2,
        ]);
    }
}
