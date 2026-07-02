<?php

namespace Database\Factories;

use App\Enums\StepType;
use App\Models\Lesson;
use App\Models\Step;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Step>
 */
class StepFactory extends Factory
{
    protected $model = Step::class;

    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::factory(),
            'title' => fake()->sentence(3),
            'type' => StepType::Reading,
            'content' => 'Laravel is a PHP web application framework known for its expressive syntax and robust tooling. It follows the Model-View-Controller (MVC) architectural pattern, which helps developers organize their code into reusable components. The framework provides a rich ecosystem of tools including Eloquent ORM for database interactions, Blade for templating, and Artisan for command-line tasks.

One of Laravel\'s key features is its service container, which manages class dependencies and performs dependency injection automatically. This makes it easy to swap implementations and keep your code testable. The framework also includes a powerful query builder, migration system for version-controlled database schemas, and built-in support for queues, caching, and event broadcasting.

Security is built into Laravel from the ground up, with features like CSRF protection, password hashing, and encrypted cookies. The framework also includes a built-in authorization system through policies and gates, making it straightforward to control access to your application resources.',
            'order' => fake()->numberBetween(1, 1000),
            'published' => true,
        ];
    }

    public function reading(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Reading,
            'content' => 'The Laravel framework provides an elegant syntax for building web applications. At its core, Laravel uses a service container for dependency management, allowing you to bind interfaces to concrete implementations. This enables loose coupling between components and makes your application more maintainable.

Routing in Laravel is both simple and powerful. You can define routes in the `routes/web.php` file for browser-based requests and `routes/api.php` for API endpoints. Routes can be grouped by middleware, prefixed by URL segments, and named for convenient URL generation throughout your application.

Controllers organize your request handling logic into classes. Instead of placing all your logic in route closures, you can create controller classes that group related HTTP actions together. Laravel\'s resource controllers provide a convenient way to implement RESTful endpoints with a single route definition.

Blade is Laravel\'s powerful templating engine that compiles templates into cached PHP code for optimal performance. It offers template inheritance through layouts and sections, reusable components, and directives for common PHP control structures. Blade templates are intuitive and allow you to write clean, readable view files without sacrificing functionality.

Eloquent ORM makes database interactions feel natural. Each database table has a corresponding Model that allows you to query data relationships using PHP methods rather than writing raw SQL. The ORM supports relationships like hasOne, hasMany, belongsTo, and many-to-many through belongsToMany, making it easy to work with complex data structures.',
        ]);
    }

    public function quizSingle(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'content' => json_encode([
                [
                    'type' => 'single',
                    'question' => 'What is 2+2?',
                    'options' => ['3', '4', '5', '6'],
                    'correct_answer' => 1,
                ],
            ]),
        ]);
    }

    public function quizMultiple(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'content' => json_encode([
                [
                    'type' => 'multiple',
                    'question' => 'Which are programming languages?',
                    'options' => ['Python', 'HTML', 'CSS', 'JavaScript'],
                    'correct_answers' => [0, 3],
                ],
            ]),
        ]);
    }

    public function quizText(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'content' => json_encode([
                [
                    'type' => 'text',
                    'question' => 'What is the capital of France?',
                    'correct_answer' => 'Paris',
                ],
            ]),
        ]);
    }

    public function quiz(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'content' => json_encode([
                [
                    'type' => 'single',
                    'question' => 'What is 2+2?',
                    'options' => ['3', '4', '5', '6'],
                    'correct_answer' => 1,
                ],
                [
                    'type' => 'text',
                    'question' => 'What is the capital of France?',
                    'correct_answer' => 'Paris',
                ],
                [
                    'type' => 'multiple',
                    'question' => 'Which are programming languages?',
                    'options' => ['Python', 'HTML', 'CSS', 'JavaScript'],
                    'correct_answers' => [0, 3],
                ],
            ]),
        ]);
    }

    public function coding(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Coding,
            'content' => json_encode([
                'prompt' => 'Write a PHP function that returns the sum of two numbers.',
                'initial_code' => "<?php\n\nfunction add(\$a, \$b) {\n    // Your code here\n}\n",
                'test_code' => "<?php\necho add(2, 3);",
                'expected_output' => '5',
            ]),
        ]);
    }
}
