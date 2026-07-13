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
    'quiz_single_explanation' => '2+2 equals 4, making option 2 (4) the correct choice.',

    // StepFactory quizMultiple
    'quiz_multiple_question' => 'Which are programming languages?',
    'quiz_multiple_options' => ['Python', 'HTML', 'CSS', 'JavaScript'],
    'quiz_multiple_explanation' => 'Python and JavaScript are programming languages; HTML and CSS are markup/styling languages.',

    // StepFactory quizText
    'quiz_text_question' => 'What is the capital of France?',
    'quiz_text_answer' => 'Paris',
    'quiz_text_explanation' => 'Paris has been the capital of France since the 10th century.',

    // StepFactory coding
    'coding_prompt' => 'Write a PHP function that returns the sum of two numbers.',
    'coding_initial_code' => "<?php\n\nfunction add(\$a, \$b) {\n    // Your code here\n}\n",
    'coding_test_code' => "<?php\necho add(2, 3);",
    'coding_expected_output' => '5',
];
