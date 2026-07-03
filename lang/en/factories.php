<?php

return [
    // CourseFactory
    'course_description' => 'A beginner-friendly course covering the fundamentals of the Laravel PHP framework, from routing and controllers to Eloquent ORM and Blade templating.',

    // LessonFactory
    'lesson_description' => 'Learn the essential concepts and hands-on skills needed to build modern web applications with Laravel.',

    // StepFactory definition (Reading default)
    'step_reading_default' => "Laravel is a PHP web application framework known for its expressive syntax and robust tooling. It follows the Model-View-Controller (MVC) architectural pattern, which helps developers organize their code into reusable components. The framework provides a rich ecosystem of tools including Eloquent ORM for database interactions, Blade for templating, and Artisan for command-line tasks.\n\nOne of Laravel's key features is its service container, which manages class dependencies and performs dependency injection automatically. This makes it easy to swap implementations and keep your code testable. The framework also includes a powerful query builder, migration system for version-controlled database schemas, and built-in support for queues, caching, and event broadcasting.\n\nSecurity is built into Laravel from the ground up, with features like CSRF protection, password hashing, and encrypted cookies. The framework also includes a built-in authorization system through policies and gates, making it straightforward to control access to your application resources.",

    'step_reading_content' => "The Laravel framework provides an elegant syntax for building web applications. At its core, Laravel uses a service container for dependency management, allowing you to bind interfaces to concrete implementations. This enables loose coupling between components and makes your application more maintainable.\n\nRouting in Laravel is both simple and powerful. You can define routes in the `routes/web.php` file for browser-based requests and `routes/api.php` for API endpoints. Routes can be grouped by middleware, prefixed by URL segments, and named for convenient URL generation throughout your application.\n\nControllers organize your request handling logic into classes. Instead of placing all your logic in route closures, you can create controller classes that group related HTTP actions together. Laravel's resource controllers provide a convenient way to implement RESTful endpoints with a single route definition.\n\nBlade is Laravel's powerful templating engine that compiles templates into cached PHP code for optimal performance. It offers template inheritance through layouts and sections, reusable components, and directives for common PHP control structures. Blade templates are intuitive and allow you to write clean, readable view files without sacrificing functionality.\n\nEloquent ORM makes database interactions feel natural. Each database table has a corresponding Model that allows you to query data relationships using PHP methods rather than writing raw SQL. The ORM supports relationships like hasOne, hasMany, belongsTo, and many-to-many through belongsToMany, making it easy to work with complex data structures.",

    // StepFactory quizSingle
    'quiz_single_question' => 'What is 2+2?',
    'quiz_single_options' => ['3', '4', '5', '6'],

    // StepFactory quizMultiple
    'quiz_multiple_question' => 'Which are programming languages?',
    'quiz_multiple_options' => ['Python', 'HTML', 'CSS', 'JavaScript'],

    // StepFactory quizText
    'quiz_text_question' => 'What is the capital of France?',
    'quiz_text_answer' => 'Paris',

    // StepFactory coding
    'coding_prompt' => 'Write a PHP function that returns the sum of two numbers.',
    'coding_initial_code' => "<?php\n\nfunction add(\$a, \$b) {\n    // Your code here\n}\n",
    'coding_test_code' => "<?php\necho add(2, 3);",
    'coding_expected_output' => '5',

    // CourseSeeder - Course 1
    'seeder_course1_title' => 'Laravel Basics',
    'seeder_course1_slug' => 'laravel-basics',
    'seeder_course1_description' => 'Learn the fundamentals of Laravel, from routing and controllers to Eloquent and Blade.',

    // CourseSeeder - Course 2
    'seeder_course2_title' => 'Eloquent ORM',
    'seeder_course2_slug' => 'eloquent-orm',
    'seeder_course2_description' => "Master Laravel's beautiful ORM for database interactions.",

    // CourseSeeder - Lesson 1
    'seeder_lesson1_title' => 'Introduction to Laravel',
    'seeder_lesson1_slug' => 'introduction-to-laravel',
    'seeder_lesson1_description' => 'What is Laravel and why use it?',

    // CourseSeeder - Lesson 2
    'seeder_lesson2_title' => 'Routing & Controllers',
    'seeder_lesson2_slug' => 'routing-and-controllers',
    'seeder_lesson2_description' => 'How Laravel handles HTTP requests.',

    // CourseSeeder - Lesson 3
    'seeder_lesson3_title' => 'Models & Migrations',
    'seeder_lesson3_slug' => 'models-and-migrations',
    'seeder_lesson3_description' => 'Defining your database schema and models.',

    // CourseSeeder - Steps for Lesson 1
    'seeder_step1_title' => 'What is Laravel?',
    'seeder_step1_content' => "Laravel is a PHP web application framework with expressive, elegant syntax.\n\nIt provides a robust set of tools for building modern web applications, including:\n- Routing\n- Middleware\n- Eloquent ORM\n- Blade templating\n- Queues and jobs\n\nLaravel follows the MVC (Model-View-Controller) architectural pattern.",

    // Quiz in Lesson 1 - Quick Check
    'seeder_step2_title' => 'Quick Check',
    'seeder_quiz1_question' => 'Which architectural pattern does Laravel follow?',
    'seeder_quiz1_options' => ['MVP', 'MVC', 'MVVM', 'REST'],

    // Quiz in Lesson 1 - Quick Knowledge Check
    'seeder_step3_title' => 'Quick Knowledge Check',
    'seeder_quiz2_question' => 'What directory are web routes defined in?',
    'seeder_quiz2_options' => ['routes/api.php', 'routes/web.php', 'config/app.php', 'app/Http/Controllers'],
    'seeder_quiz3_question' => "What is the name of Laravel's templating engine?",
    'seeder_quiz3_answer' => 'Blade',

    // Quiz in Lesson 1 - What is a framework?
    'seeder_step4_title' => 'What is a framework?',
    'seeder_quiz4_question' => 'What directory are web routes defined in?',
    'seeder_quiz4_answer' => 'routes/web.php',

    // CourseSeeder - Steps for Lesson 2
    'seeder_step5_title' => 'Defining Routes',
    'seeder_step5_content' => "Routes are defined in `routes/web.php` for web routes and `routes/api.php` for API routes.\n\nBasic route syntax:\n\n```php\nRoute::get('/hello', function () {\n    return 'Hello World';\n});\n```\n\nRoutes can be grouped, named, and restricted by middleware.",

    'seeder_step6_title' => 'Route Types Quiz',
    'seeder_quiz5_question' => 'Which HTTP methods can Laravel routes handle?',
    'seeder_quiz5_options' => ['GET', 'POST', 'PUT', 'DELETE', 'FETCH'],

    // CourseSeeder - Steps for Lesson 3
    'seeder_step7_title' => 'Creating a Migration',
    'seeder_step7_content' => "Migrations are like version control for your database. They allow you to define and share the database schema.\n\nCreate a migration:\n```bash\nphp artisan make:migration create_posts_table\n```\n\nMigrations define table columns and indexes using Laravel's schema builder.",

    'seeder_step8_title' => 'Write Your First Migration',
    'seeder_coding1_prompt' => "Write a PHP function that returns the string 'Migration created!'.",
    'seeder_coding1_initial_code' => "<?php\n\nfunction createMigration() {\n    // Return the correct string\n}\n",
    'seeder_coding1_test_code' => "<?php\necho createMigration();",
    'seeder_coding1_expected_output' => 'Migration created!',
];
