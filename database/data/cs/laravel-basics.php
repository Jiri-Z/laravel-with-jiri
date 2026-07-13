<?php

declare(strict_types=1);

return [
    'title' => 'CS: Laravel Basics',
    'slug' => 'cs-laravel-basics',
    'description' => 'CS: Learn the fundamentals of Laravel from the ground up. Covers routing, controllers, Blade, Eloquent, authentication, and more.',
    'user_email' => 'instructor@example.com',
    'lessons' => [
        0 => [
            'title' => 'CS: What is Laravel?',
            'slug' => 'cs-what-is-laravel',
            'description' => 'CS: An introduction to the Laravel PHP framework, its philosophy, ecosystem, and architecture.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: What is Laravel?',
                    'content' => 'CS: Laravel is a free, open-source PHP web framework created by Taylor Otwell in 2011. It follows the Model-View-Controller (MVC) architectural pattern and aims to make common web development tasks such as routing, authentication, caching, and queue management more enjoyable and less painful. Laravel emphasizes elegant, expressive syntax and provides a robust set of tools out of the box. Its philosophy centers on developer happiness without sacrificing application quality. Laravel is built on top of Symfony components and leverages modern PHP features like traits, interfaces, and dependency injection. The framework has grown into a complete ecosystem with tools for every stage of development, from local environment setup to production deployment. Laravel is currently one of the most popular PHP frameworks, powering everything from small blogs to enterprise-level applications. Its extensive documentation, large community, and long-term support releases make it a reliable choice for projects of any scale.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: What is Laravel? Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Who created the Laravel PHP framework?',
                            'options' => [
                                0 => 'CS: Taylor Otwell',
                                1 => 'CS: Rasmus Lerdorf',
                                2 => 'CS: Fabien Potencier',
                                3 => 'CS: Jeffrey Way',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Taylor Otwell created Laravel and first released it in June 2011.',
                            'difficulty' => 'easy',
                            'topic' => 'laravel',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: The MVC Architecture Pattern',
                    'content' => 'CS: Laravel follows the Model-View-Controller (MVC) architectural pattern, which separates application logic into three interconnected components. The Model represents the data layer and business logic, typically interacting with the database via Eloquent ORM. The View is responsible for presenting data to the user, using Laravel\'s Blade templating engine to create dynamic HTML. The Controller handles incoming HTTP requests, processes user input, interacts with models, and returns responses. This separation of concerns makes code more organized, testable, and maintainable. Each component has a single responsibility: models manage data, views handle presentation, and controllers coordinate the flow. Laravel also supports alternative patterns like Action classes, Form Requests, and Service classes that complement MVC for more complex applications. The framework\'s routing layer maps URLs to controllers, allowing clean and RESTful URL structures. Laravel\'s implementation of MVC is flexible and doesn\'t force you into rigid structures, letting you organize your code in a way that best fits your application\'s needs.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: MVC Architecture Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which part of MVC is responsible for handling HTTP requests and returning responses?',
                            'options' => [
                                0 => 'CS: Model',
                                1 => 'CS: View',
                                2 => 'CS: Controller',
                                3 => 'CS: Router',
                            ],
                            'answer' => 2,
                            'explanation' => 'CS: Controllers handle incoming HTTP requests, process user input, interact with models, and return responses.',
                            'difficulty' => 'easy',
                            'topic' => 'laravel',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: The Laravel Ecosystem',
                    'content' => 'CS: Laravel is not just a framework — it is a complete ecosystem of tools that work together seamlessly. For local development, Laravel Homestead (a Vagrant box) and Laravel Sail (a Docker-based solution) provide consistent environments. Laravel Forge handles production server management, while Laravel Vapor offers serverless deployment on AWS Lambda. For frontend assets, Laravel Mix provides a clean API over Webpack, and Vite integration is available for modern JavaScript tooling. The ecosystem includes first-party packages like Laravel Horizon for Redis queue management, Laravel Telescope for debugging and profiling, Laravel Nova for admin panels, Laravel Cashier for subscription billing, Laravel Sanctum for API token authentication, and Laravel Socialite for OAuth provider integration. Laravel Jetstream and Laravel Breeze provide starter kits for authentication and team management with Livewire or Inertia.js. The Laravel community is one of the largest in the PHP world, with thousands of packages available via Composer, extensive documentation on Laravel.com, and learning resources like Laracasts. This rich ecosystem allows developers to rapidly build production-ready applications without reinventing common patterns.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Laravel Ecosystem Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are part of the Laravel ecosystem? (Select all that apply)',
                            'options' => [
                                0 => 'CS: Laravel Forge',
                                1 => 'CS: Laravel Nova',
                                2 => 'CS: Laravel React',
                                3 => 'CS: Laravel Horizon',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: Laravel Forge (server management), Nova (admin panels), and Horizon (queue monitoring) are all first-party Laravel tools. Laravel React is not an official Laravel package.',
                            'difficulty' => 'medium',
                            'topic' => 'laravel',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: System Requirements and Installation',
                    'content' => 'CS: Before installing Laravel, you need a development environment that meets its system requirements. Laravel 11 requires PHP 8.2 or higher with extensions including BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, and XML. You also need a database system such as MySQL 5.7+, PostgreSQL 9.6+, SQLite 3.8+, or SQL Server 2017+. For local development, Laravel Sail provides a Docker-based environment that includes all dependencies. Alternatively, you can use Composer to create a new Laravel project with the command "composer create-project laravel/laravel example-app". After installation, you configure your .env file with database credentials and other environment-specific settings. Laravel uses Composer for PHP dependency management and npm or Bun for frontend assets. The framework provides a built-in development server via "php artisan serve" for quick testing. For production, you typically set up a web server like Nginx or Apache with PHP-FPM, and configure queues, caching, and other services as needed. Laravel Sail is recommended for beginners as it eliminates environment inconsistencies and provides a reproducible development setup with MySQL, Redis, Mailpit, and Meilisearch out of the box.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Requirements and Installation Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following PHP extensions are required by Laravel? (Select all that apply)',
                            'options' => [
                                0 => 'CS: BCMath',
                                1 => 'CS: GD',
                                2 => 'CS: PDO',
                                3 => 'CS: Exif',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 2,
                            ],
                            'explanation' => 'CS: BCMath and PDO are required by Laravel. GD and Exif are optional extensions not listed in Laravel\'s core requirements.',
                            'difficulty' => 'medium',
                            'topic' => 'laravel',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Your First Laravel Application',
                    'content' => 'CS: Once Laravel is installed, creating your first application is straightforward. The default Laravel skeleton includes a welcome page, basic routing, and authentication scaffolding (if using Breeze or Jetstream). The main entry point for all HTTP requests is the public/index.php file, which bootstraps the framework and dispatches requests to the router. Routes are defined in the routes/web.php file for web interfaces and routes/api.php for API endpoints. A basic route returning a view looks like "Route::get(\'/\', function () { return view(\'welcome\'); })". Laravel\'s directory structure is organized by convention: app/ for core application code, config/ for configuration files, database/ for migrations and seeders, resources/ for views and assets, and routes/ for route definitions. The Artisan command-line tool provides hundreds of commands for common tasks like generating code, running migrations, and clearing caches. For example, "php artisan make:model Post" creates a new Eloquent model, migration, and factory with a single command. Laravel\'s Tinker REPL lets you interact with your application from the command line, making it easy to test relationships, queries, and services during development. The framework\'s convention-over-configuration approach means you can build a fully functional CRUD application with minimal boilerplate code, following Laravel\'s naming conventions for models, controllers, migrations, and routes.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: First Application Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which file is the entry point for all HTTP requests in a Laravel application?',
                            'options' => [
                                0 => 'CS: index.php',
                                1 => 'CS: app.php',
                                2 => 'CS: bootstrap.php',
                                3 => 'CS: artisan',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The public/index.php file is the entry point that bootstraps the framework and handles all incoming HTTP requests.',
                            'difficulty' => 'easy',
                            'topic' => 'laravel',
                        ],
                    ],
                ],
            ],
        ],
        1 => [
            'title' => 'CS: Routing Fundamentals',
            'slug' => 'cs-routing-fundamentals',
            'description' => 'CS: Learn how Laravel handles URL routing, route parameters, named routes, and route groups.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Basic Routing in Laravel',
                    'content' => 'CS: Routing in Laravel is how the framework maps incoming HTTP requests to specific application logic. All web routes are defined in the routes/web.php file, while API routes go in routes/api.php. The most basic route uses the Route facade to register a closure that handles a specific HTTP method and URI. For example, "Route::get(\'/greeting\', function () { return \'Hello World\'; })" responds to GET requests at /greeting. Laravel supports all standard HTTP verbs: get, post, put, patch, delete, and options. You can also use "Route::match([\'GET\', \'POST\'], \'/\', ...)" to respond to multiple verbs, or "Route::any(\'/\', ...)" to respond to all verbs. The framework automatically applies middleware to route groups, handles CSRF protection for POST requests, and provides clean error handling with customizable HTTP exception pages. Routes are evaluated in the order they are defined, so more specific routes should be placed before wildcard routes. Laravel\'s route service provider loads route files from the RoutesServiceProvider, which applies the web and api middleware groups respectively. The Route facade provides a fluent interface for chaining additional configuration like middleware, name, and prefix.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Basic Routing Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which file should you define web routes in?',
                            'options' => [
                                0 => 'CS: routes/web.php',
                                1 => 'CS: routes/api.php',
                                2 => 'CS: routes/channels.php',
                                3 => 'CS: routes/console.php',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Web routes are defined in routes/web.php, which is loaded by the RouteServiceProvider with the web middleware group.',
                            'difficulty' => 'easy',
                            'topic' => 'routing',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Route Parameters',
                    'content' => 'CS: Laravel allows you to capture segments of the URI as parameters that are passed to your route closure or controller. Required parameters are defined with curly braces: "Route::get(\'/user/{id}\', function (string $id) { ... })". Parameters are injected into the route callback in order, and the framework automatically resolves them from the URI. You can define multiple parameters: "Route::get(\'/posts/{post}/comments/{comment}\', ...)". Optional parameters are indicated with a question mark and require a default value: "Route::get(\'/user/{name?}\', function (?string $name = null) { ... })". You can constrain route parameters using the "where" method with regular expressions: "Route::get(\'/user/{name}\')->where(\'name\', \'[A-Za-z]+\')". Laravel provides global pattern constraints via "Route::pattern(\'id\', \'[0-9]+\')" in the AppServiceProvider. For more expressive validation, you can use "whereNumber", "whereAlpha", "whereAlphaNumeric", and "whereUlid" helper methods. Parameters whose values correspond to Eloquent models can leverage implicit or explicit route model binding, where the framework automatically fetches the model instance from the database. Route parameters are accessible from request instances and can be retrieved via "$request->route(\'parameterName\')" within controllers.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Route Parameters Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How do you define an optional route parameter in Laravel?',
                            'options' => [
                                0 => 'CS: Route::get(\'/user/{name?}\', ...)',
                                1 => 'CS: Route::get(\'/user/{name}*\', ...)',
                                2 => 'CS: Route::get(\'/user/{name:optional}\', ...)',
                                3 => 'CS: Route::get(\'/user/[name]\', ...)',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Optional parameters use a question mark suffix and the corresponding variable must have a default value.',
                            'difficulty' => 'easy',
                            'topic' => 'routing',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Named Routes and Route Groups',
                    'content' => 'CS: Named routes allow you to generate URLs or redirects without hardcoding the actual URI. You chain the "->name()" method after defining a route: "Route::get(\'/user/profile\', ...)->name(\'profile\')". Named routes are particularly useful because if the URI structure changes, all generated URLs automatically reflect the change. You generate URLs with "route(\'profile\')" or redirect with "redirect()->route(\'profile\')". For routes with parameters, pass an array: "route(\'posts.show\', [\'post\' => $post])". Route groups allow you to share attributes like middleware, prefix, and namespace across multiple routes without repeating them on each individual route. Use "Route::prefix(\'admin\')->group(function () { ... })" to prefix all URIs in the group. The "Route::middleware(\'auth\')->group(...)" applies middleware to all routes in the group. You can also use "Route::name(\'admin.\')->group(...)" to prefix all route names. Groups can be nested, with attributes inherited from parent groups. Laravel also supports subdomain routing with "Route::domain(\'{account}.example.com\')->group(...)" for multi-tenant applications. Route groups help keep your route files organized and reduce duplication, especially in larger applications with many routes sharing common characteristics.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Named Routes and Groups Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are valid uses of named routes and route groups in Laravel? (Select all that apply)',
                            'options' => [
                                0 => 'CS: Generating URLs with route(\'profile\')',
                                1 => 'CS: Grouping routes with Route::prefix(\'admin\')->group(...)',
                                2 => 'CS: Defining route constraints with Route::constraint(...)',
                                3 => 'CS: Applying middleware to multiple routes with Route::middleware(\'auth\')->group(...)',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: Named routes enable URL generation with route() helper. Prefix and middleware groups are valid. Route::constraint() is not a valid method.',
                            'difficulty' => 'medium',
                            'topic' => 'routing',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Route Model Binding',
                    'content' => 'CS: Route model binding provides a convenient way to automatically inject model instances into your routes. When you type-hint an Eloquent model in your route or controller method and the URI segment matches the model\'s primary key, Laravel automatically retrieves the corresponding model from the database. Implicit binding works out of the box: "Route::get(\'/posts/{post}\', function (Post $post) { return $post; })" automatically fetches the Post with the given ID. Laravel resolves the {post} parameter from the URI and queries the database. If the model is not found, a 404 response is automatically generated. You can customize the column used for binding by overriding the "getRouteKeyName" method on the model, or use explicit binding in the AppServiceProvider with "Route::bind(\'post\', function (string $value) { return Post::where(\'slug\', $value)->firstOrFail(); })". Laravel 11 introduced "Route::model(\'slug\', Post::class)" for easier explicit binding. Soft-deleted models are not automatically retrieved unless you chain "->withTrashed()" on the route definition. Route model binding is a powerful feature that reduces boilerplate code and keeps your controllers clean by eliminating repetitive database lookups. It also integrates seamlessly with authorization policies and form requests.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Route Model Binding Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What happens automatically when a route with implicit model binding cannot find the model?',
                            'options' => [
                                0 => 'CS: A 404 response is returned',
                                1 => 'CS: A 500 error is thrown',
                                2 => 'CS: Null is passed to the controller',
                                3 => 'CS: An exception is logged and execution continues',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Laravel automatically generates a 404 response when implicitly bound models are not found in the database.',
                            'difficulty' => 'medium',
                            'topic' => 'routing',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Fallback Routes and Rate Limiting',
                    'content' => 'CS: Fallback routes allow you to define what happens when no other route matches the incoming request. Use "Route::fallback(function () { ... })" to catch unmatched URIs, typically placed at the end of your route file to display a custom 404 page. This is useful for single-page applications where you need to catch all routes and serve your frontend entry point. Laravel also provides robust rate limiting capabilities through the "RateLimiter" facade and the "throttle" middleware. You define rate limiters in the AppServiceProvider using "RateLimiter::for(\'api\', function (Request $request) { return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()); })". Apply rate limiting to routes with "Route::middleware(\'throttle:api\')->group(...)". Rate limits can be configured per minute, per hour, or with custom periods, and you can define multiple limits with "Limit::perMinute(100)->then(Limit::perMinute(10)->by(...))". Laravel\'s built-in "throttle" middleware supports named limiters defined in the RateLimiter facade. HTTP 429 Too Many Requests responses are automatically returned when the limit is exceeded, with Retry-After headers. Rate limiting is essential for protecting your API from abuse and ensuring fair resource usage across all clients.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Fallback Routes and Rate Limiting Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following statements about fallback routes and rate limiting are true? (Select all that apply)',
                            'options' => [
                                0 => 'CS: Fallback routes should be defined at the bottom of your route file',
                                1 => 'CS: Rate limiting only works on API routes',
                                2 => 'CS: The throttle middleware can use named rate limiters',
                                3 => 'CS: RateLimiter::for() is used to define rate limiters in the AppServiceProvider',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 2,
                                2 => 3,
                            ],
                            'explanation' => 'CS: Fallback routes must be last, throttle works on any routes, named limiters are defined with RateLimiter::for(), and rate limiting works on web routes too.',
                            'difficulty' => 'medium',
                            'topic' => 'routing',
                        ],
                    ],
                ],
            ],
        ],
        2 => [
            'title' => 'CS: Controllers',
            'slug' => 'cs-controllers',
            'description' => 'CS: Understand Laravel controllers, how to organize request handling, and use dependency injection.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: What Are Controllers?',
                    'content' => 'CS: Controllers in Laravel group related HTTP request handling logic into a single class. Instead of defining all request handling logic as closures in route files, controllers organize that logic into methods. A basic controller extends the base Controller class provided by Laravel, which uses traits for middleware authorization and other convenience features. Create a controller with the Artisan command "php artisan make:controller UserController". Controllers are stored in the app/Http/Controllers directory. Route definitions point to controller methods using an array syntax: "Route::get(\'/users\', [UserController::class, \'index\'])". This maps GET /users to the index method of UserController. Controllers can return views, redirects, JSON responses, or any other response type that Laravel supports. They are automatically resolved through Laravel\'s service container, enabling dependency injection in the constructor or individual methods. Controllers should follow the Single Responsibility Principle, handling a related set of actions for a specific resource or feature. By moving logic from route closures to controllers, your code becomes more testable, reusable, and maintainable, especially as your application grows beyond a handful of routes.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Controllers Overview Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which Artisan command creates a new controller?',
                            'options' => [
                                0 => 'CS: php artisan make:controller',
                                1 => 'CS: php artisan create:controller',
                                2 => 'CS: php artisan generate:controller',
                                3 => 'CS: php artisan new:controller',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The \\"php artisan make:controller\\" command creates a new controller class in app/Http/Controllers.',
                            'difficulty' => 'easy',
                            'topic' => 'controllers',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Single Action and Invokable Controllers',
                    'content' => 'CS: When a controller only needs to handle a single action, you can use an invokable controller. Instead of defining multiple methods, the controller implements the __invoke magic method. Create one with "php artisan make:controller ShowProfile --invokable". Routes use the single class reference: "Route::get(\'/profile\', ShowProfile::class)". Invokable controllers are perfect for actions that don\'t belong to a typical RESTful resource — like a dashboard controller, a search handler, or an export function. They keep your route syntax clean and signal clearly that the controller has a single responsibility. You can still use dependency injection in the __invoke method parameters, and Laravel will automatically resolve dependencies from the service container. Invokable controllers can also implement middleware in their constructor, just like regular controllers. This pattern works well when combined with Laravel\'s automatic route model binding, form request injection, and response macros. Many developers prefer invokable controllers for simple, focused actions because they reduce cognitive overhead and reinforce the Single Responsibility Principle at the class level.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Invokable Controllers Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which method must an invokable controller implement?',
                            'options' => [
                                0 => 'CS: __invoke',
                                1 => 'CS: __call',
                                2 => 'CS: __callStatic',
                                3 => 'CS: __construct',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Invokable controllers implement the __invoke magic method and are registered as single-action controllers.',
                            'difficulty' => 'easy',
                            'topic' => 'controllers',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Resource Controllers',
                    'content' => 'CS: Resource controllers provide a convention-based way to handle all CRUD operations for a resource in a single controller. Create one with "php artisan make:controller PostController --resource", which generates methods for index, create, store, show, edit, update, and destroy. Register all routes with a single line: "Route::resource(\'posts\', PostController::class)". This generates routes following RESTful conventions, with the correct HTTP verbs and URI patterns. Laravel also supports "apiResource" for API-only routes (excluding create and edit, which return HTML forms). You can specify which methods are available with "->only([\'index\', \'show\'])" or exclude methods with "->except([\'create\', \'edit\'])". Nested resources use "Route::resource(\'posts.comments\', CommentController::class)", which generates routes like "/posts/{post}/comments/{comment}". Resource controllers enforce consistency across your application by following predictable naming patterns for methods and routes. The "resources" method also automatically names all routes following the pattern "{resource}.{action}" like "posts.index", "posts.store", "posts.show". This naming convention integrates perfectly with the route() helper and named route generation throughout your Blade views and controllers.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Resource Controllers Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which methods are generated by a resource controller? (Select all that apply)',
                            'options' => [
                                0 => 'CS: index',
                                1 => 'CS: show',
                                2 => 'CS: delete',
                                3 => 'CS: update',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: Resource controllers generate index, create, store, show, edit, update, and destroy. \\"delete\\" is not a standard resource controller method.',
                            'difficulty' => 'medium',
                            'topic' => 'controllers',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Dependency Injection in Controllers',
                    'content' => 'CS: Laravel\'s service container automatically resolves dependencies for controller methods. You can type-hint any class in a controller\'s constructor or method, and Laravel will inject the appropriate instance. Constructor injection is useful for services that every method needs: "public function __construct(protected UserService $service) {}". Method injection injects dependencies specific to a single action: "public function store(CreateUserRequest $request, UserService $service) {}". Laravel can also inject Request instances, which provide access to input data, uploaded files, session information, and more. The service container resolution supports automatic resolution of concrete classes, interfaces bound to implementations, and contextual binding for different scenarios. Controllers are resolved by the container each request, so constructor-injected dependencies are fresh instances. You can also use method injection on controller methods to automatically receive route parameters alongside dependencies — Laravel intelligently separates route parameters from services based on type hints. This dependency injection approach makes controllers highly testable because you can easily swap real services with mocks or fakes during testing. Laravel\'s container even caches resolved singletons, so expensive service instantiations happen only once per request lifecycle.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Dependency Injection Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How does Laravel distinguish between route parameters and injected dependencies in a controller method?',
                            'options' => [
                                0 => 'CS: By type hints',
                                1 => 'CS: By parameter name',
                                2 => 'CS: By the order of parameters',
                                3 => 'CS: By a special attribute',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Laravel uses PHP type hints to identify dependencies that need injection. Route parameters without type hints are treated as URI segments.',
                            'difficulty' => 'medium',
                            'topic' => 'controllers',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Controller Middleware and Form Requests',
                    'content' => 'CS: Middleware can be applied to controller actions within the controller\'s constructor using the "middleware" method: "$this->middleware(\'auth\')->only([\'create\', \'store\', \'edit\', \'update\', \'destroy\'])". This provides fine-grained control over which actions are protected. You can also apply middleware directly in routes with "Route::get(...)->middleware(\'auth\')". For more complex authorization, Laravel Form Requests provide a dedicated class for validating and authorizing incoming request data. Create one with "php artisan make:request StorePostRequest". Form requests extend "FormRequest" and contain authorization logic in the "authorize" method and validation rules in the "rules" method. When type-hinted in a controller method, the form request automatically validates the request before the controller method executes. Failed validation returns a redirect with errors for traditional form submissions, or JSON error responses for API requests. Form requests can also handle after-validation logic, customize error messages, and prepare input data before validation. They keep controllers clean by moving validation logic out of controller methods and into dedicated classes that follow the Single Responsibility Principle.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Middleware and Form Requests Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What method in a FormRequest class defines the authorization logic?',
                            'options' => [
                                0 => 'CS: authorize',
                                1 => 'CS: rules',
                                2 => 'CS: validate',
                                3 => 'CS: authorization',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The authorize method in a FormRequest class determines if the user is authorized to make the request.',
                            'difficulty' => 'medium',
                            'topic' => 'controllers',
                        ],
                    ],
                ],
            ],
        ],
        3 => [
            'title' => 'CS: Blade Templating',
            'slug' => 'cs-blade-templating',
            'description' => 'CS: Master Laravel\'s Blade templating engine, including directives, components, and layout inheritance.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Introduction to Blade',
                    'content' => 'CS: Blade is Laravel\'s powerful and lightweight templating engine. Unlike many PHP templating engines, Blade allows you to use plain PHP code directly in your templates. All Blade views are compiled into plain PHP code and cached until they are modified, meaning Blade adds zero overhead to your application. Blade files use the .blade.php extension and are stored in the resources/views directory. You return Blade views from routes or controllers using the "view()" helper: "return view(\'pages.home\')", where "pages.home" maps to resources/views/pages/home.blade.php. Blade provides a rich set of features including template inheritance, sections, components, directives, and a secure data display system that automatically escapes output to prevent XSS attacks. You can create loops using "@for", "@foreach", "@while", and include conditional logic with "@if", "@unless", "@empty", "@auth", and "@guest". Blade also supports the "dd()" and "dump()" debugging functions via "@dd" and "@dump". The engine is designed to be intuitive for designers and developers alike, with a clean syntax that reads naturally. Laravel\'s Blade system is extensible, allowing you to define custom directives using the "Blade::directive()" method in a service provider.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Blade Introduction Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What file extension do Blade view files use?',
                            'options' => [
                                0 => 'CS: .blade.php',
                                1 => 'CS: .blade',
                                2 => 'CS: .php',
                                3 => 'CS: .blade.html',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Blade view files use the .blade.php extension and are stored in the resources/views directory.',
                            'difficulty' => 'easy',
                            'topic' => 'blade',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Blade Directives and Control Structures',
                    'content' => 'CS: Blade directives provide clean syntax for common PHP control structures and templating tasks. The "@if", "@elseif", "@else", and "@endif" directives work exactly like PHP\'s if-else but with cleaner syntax. "@unless" executes the block when the condition is false, and "@auth"/"@endauth" shows content only to authenticated users. Blade\'s loop directives include "@for", "@endforeach", "@while", and "@foreach". Inside loops, you can use the "$loop" variable to get information about the current iteration: "$loop->first", "$loop->last", "$loop->iteration", "$loop->index", "$loop->count", and "$loop->remaining". The "@forelse" directive handles empty collections gracefully, executing the "@empty" section when the array has no items: "@forelse($users as $user) ... @empty ... @endforelse". Blade also provides "@each" for rendering partials for each item in a collection. The "@include" directive includes a sub-view with access to the parent view\'s variables, while "@includeWhen" and "@includeIf" conditionally include views. "@once" directives render their content only once in a loop, which is useful for pushing assets or scripts. These directives make views more readable and expressive compared to raw PHP blocks, especially when working with deeply nested template structures.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Blade Directives Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which Blade variable provides information about the current loop iteration?',
                            'options' => [
                                0 => 'CS: $loop',
                                1 => 'CS: $iteration',
                                2 => 'CS: $index',
                                3 => 'CS: $foreach',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The \\$loop variable is automatically available inside Blade loops and provides properties like iteration, first, last, count, and remaining.',
                            'difficulty' => 'easy',
                            'topic' => 'blade',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Template Inheritance and Layouts',
                    'content' => 'CS: Blade template inheritance allows you to define a master layout file that child views can extend and override. The master layout uses "@yield(\'section_name\')" to define areas where child content will be injected. For example, a master layout resources/views/layouts/app.blade.php might yield "title", "styles", "content", and "scripts" sections. Child views use "@extends(\'layouts.app\')" to inherit the layout, then "@section(\'content\') ... @endsection" to fill in the defined sections. The "@parent" directive within a child section will include the parent\'s content for that section, allowing you to append to rather than replace sections. This is particularly useful for stacking stylesheets or scripts: "@section(\'styles\') @parent <link rel=\'stylesheet\' href=\'/custom.css\'> @endsection". Template inheritance keeps your HTML structure DRY by avoiding duplication of the page shell across every view. You can create multiple levels of inheritance, with a base layout extended by sub-layouts that are themselves extended by specific page templates. The "@show" directive differs from "@endsection" by immediately displaying the section content, which is useful for default content in layouts. Blade\'s inheritance system is conceptually similar to class inheritance in object-oriented programming, providing a clean and familiar mental model.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Template Inheritance Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following Blade directives are used for template inheritance? (Select all that apply)',
                            'options' => [
                                0 => 'CS: @extends',
                                1 => 'CS: @include',
                                2 => 'CS: @section',
                                3 => 'CS: @yield',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 2,
                                2 => 3,
                            ],
                            'explanation' => 'CS: @extends specifies the parent layout, @section defines content sections, and @yield defines where content is displayed. @include is for including partials, not for inheritance.',
                            'difficulty' => 'medium',
                            'topic' => 'blade',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Blade Components and Slots',
                    'content' => 'CS: Blade components offer a more powerful and flexible alternative to template inheritance and @include partials. Class-based components are created with "php artisan make:component Alert" and consist of a PHP class and a Blade template. You render components with the "<x-alert/>" syntax. Components accept data via attributes: "<x-alert type=\'error\' :message=\'$error\'/>". The ":" prefix evaluates the attribute as a PHP expression, while attributes without ":" are treated as strings. Components can have slots using the HTML "slot" element: "<x-card> <x-slot:title>Card Title</x-slot:title> Card body content </x-card>". Default slots correspond to the content between the component tags. Anonymous components are stored in resources/views/components/ and only need a Blade file, no PHP class. For example, resources/views/components/alert.blade.php can be rendered as "<x-alert/>". Components support method execution, computed properties, and can be rendered conditionally. Laravel also supports dynamic components, inline components, and component namespacing for package development. Components promote reusability and enforce a consistent UI pattern across your application. The "@props" directive within anonymous components defines which attributes the component accepts, providing a clean API for component consumers.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Blade Components Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How do you pass a PHP expression as an attribute to a Blade component?',
                            'options' => [
                                0 => 'CS: :attribute="$variable"',
                                1 => 'CS: attribute="$variable"',
                                2 => 'CS: @attribute="$variable"',
                                3 => 'CS: {{ $variable }}',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Prefixing an attribute with \\":\\" tells Blade to evaluate the value as a PHP expression rather than a literal string.',
                            'difficulty' => 'medium',
                            'topic' => 'blade',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Displaying Data and the @class Directive',
                    'content' => 'CS: Blade provides safe data display using double curly braces: "{{ $variable }}". All output within these braces is automatically escaped by PHP\'s htmlspecialchars function to prevent XSS attacks. To display unescaped data (for trusted HTML), use "{!! $variable !!}", but be extremely careful as this bypasses all escaping. Blade\'s "@json" directive converts PHP variables to JSON directly in JavaScript contexts: "var data = @json($array);". The "@js" directive is similar but handles object serialization more elegantly for Livewire and Alpine.js. The "@class" directive conditionally applies CSS classes: "@class([\'active\' => $isActive, \'highlight\' => $isHighlighted])". This compiles to a space-separated string of only the classes whose conditions are true. It\'s far cleaner than inline ternary operators for building class lists. The "@style" directive works identically but for inline CSS styles. Blade also supports the "@checked" directive for keeping old input checked after validation errors: "<input type=\'checkbox\' @checked(old(\'remember\')) />", and similar directives "@selected", "@disabled", and "@readonly" for select options, disabled buttons, and readonly inputs respectively. These small helpers significantly reduce boilerplate in forms.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Data Display and @class Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How does Blade prevent XSS attacks when displaying data?',
                            'options' => [
                                0 => 'CS: Automatically escaping output with htmlspecialchars',
                                1 => 'CS: Sanitizing all input with strip_tags',
                                2 => 'CS: Using prepared statements',
                                3 => 'CS: Encoding data as JSON',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Blade\'s {{ }} syntax automatically escapes output using PHP\'s htmlspecialchars function to prevent XSS.',
                            'difficulty' => 'easy',
                            'topic' => 'blade',
                        ],
                    ],
                ],
            ],
        ],
        4 => [
            'title' => 'CS: Database and Migrations',
            'slug' => 'cs-database-migrations',
            'description' => 'CS: Learn about database migrations, schema building, seeding, and foreign key constraints in Laravel.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Database Migrations in Laravel',
                    'content' => 'CS: Migrations are like version control for your database, allowing you to define and share the application\'s database schema. Each migration describes a set of changes to the database schema — creating tables, adding columns, modifying indexes, or dropping tables. Laravel\'s Schema facade provides a database-agnostic way to build table definitions using PHP code. Migrations are stored in database/migrations and are executed in chronological order based on their filename timestamps. Create a migration with "php artisan make:migration create_posts_table". The up method defines what changes to apply, while the down method reverses them. Run migrations with "php artisan migrate", roll back the last batch with "php artisan migrate:rollback", or completely reset with "php artisan migrate:fresh". Laravel also provides "migrate:refresh" to roll back and migrate again, useful during development. Migration status can be checked with "php artisan migrate:status". Laravel tracks which migrations have run in a migrations table in your database. You can use "php artisan migrate:fresh --seed" to drop all tables, run all migrations, and seed the database — extremely useful during early development when the schema changes frequently.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Migrations Basics Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which Artisan command applies all pending migrations?',
                            'options' => [
                                0 => 'CS: php artisan migrate',
                                1 => 'CS: php artisan migrate:run',
                                2 => 'CS: php artisan migrate:up',
                                3 => 'CS: php artisan db:migrate',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: \\"php artisan migrate\\" runs all pending migrations defined in the database/migrations directory.',
                            'difficulty' => 'easy',
                            'topic' => 'migrations',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: The Schema Builder',
                    'content' => 'CS: The Schema facade provides methods for creating and modifying database tables. Use "Schema::create(\'table_name\', function (Blueprint $table) { ... })" to define a new table. The Blueprint object has methods for every column type Laravel supports: "$table->id()" for auto-incrementing big integer primary keys, "$table->string(\'title\', 100)" for VARCHAR columns, "$table->text(\'body\')" for TEXT, "$table->integer(\'votes\')" for INTEGER, "$table->boolean(\'is_published\')" for BOOLEAN, "$table->dateTime(\'published_at\')" for DATETIME, "$table->foreignId(\'user_id\')" for foreign key columns, and "$table->timestamps()" for created_at and updated_at. Laravel supports many more column types including "float", "decimal" for precise math, "enum" for constrained string values, "json" and "jsonb" for JSON columns, "uuid" and "ulid" for universal unique identifiers, "softDeletes" for nullable deleted_at columns used by Eloquent\'s soft delete feature, and "rememberToken" for the "remember_me" token. Column modifiers chain after the type method: "$table->string(\'email\')->unique()->nullable()". Common modifiers include "nullable()", "default($value)", "unsigned()", "comment(\'text\')", and "first()"/"after(\'column\')" for column ordering in MySQL.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Schema Builder Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which method creates an auto-incrementing big integer primary key column?',
                            'options' => [
                                0 => 'CS: $table->id()',
                                1 => 'CS: $table->bigIncrements(\'id\')',
                                2 => 'CS: $table->primary(\'id\')',
                                3 => 'CS: $table->autoIncrement(\'id\')',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The id() method is a shortcut for creating an auto-incrementing big integer primary key column named "id".',
                            'difficulty' => 'easy',
                            'topic' => 'migrations',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Running Migrations and Seeders',
                    'content' => 'CS: After defining migrations, you run them with "php artisan migrate". Laravel keeps track of each migration\'s batch number in the migrations table. If a migration fails, Laravel rolls back the entire batch. You can rollback migrations with "php artisan migrate:rollback" which undoes the last batch, or "php artisan migrate:rollback --step=3" to roll back three batches. "php artisan migrate:reset" rolls back all migrations. Seeders populate your database with test or default data using "php artisan make:seeder UserSeeder". Database seeders are stored in database/seeders and extend the Seeder class. The "run" method inserts data using Eloquent or the DB facade: "DB::table(\'users\')->insert([...])". The main DatabaseSeeder class calls individual seeders: "$this->call([UserSeeder::class, PostSeeder::class])". Run seeders with "php artisan db:seed" or combine with migrations: "php artisan migrate:fresh --seed". Laravel provides model factories that generate realistic test data automatically. Factories define default values using the "define" method and are called with "User::factory()->count(10)->create()". Factory states allow variations: "User::factory()->unverified()->create()". Seeder and factory combinations create comprehensive test datasets quickly.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Running Migrations and Seeders Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following Artisan commands are valid for managing migrations? (Select all that apply)',
                            'options' => [
                                0 => 'CS: migrate:fresh',
                                1 => 'CS: migrate:rollback',
                                2 => 'CS: migrate:revert',
                                3 => 'CS: migrate:reset',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: migrate:fresh drops all tables and re-runs all migrations. migrate:rollback undoes the last batch. migrate:reset rolls back all migrations. migrate:revert is not a valid command.',
                            'difficulty' => 'medium',
                            'topic' => 'migrations',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Foreign Keys and Indexes',
                    'content' => 'CS: Foreign key constraints ensure referential integrity between related tables. In migrations, use "$table->foreignId(\'user_id\')->constrained()->onDelete(\'cascade\')" to create a foreign key column and its constraint. The "constrained()" method automatically references the primary key of the related table based on Laravel\'s naming conventions (user_id references id on users table). To specify a custom table or column: "$table->foreignId(\'author_id\')->constrained(\'users\', \'id\')". Cascade actions define what happens when the parent record is deleted or updated: "onDelete(\'cascade\')" deletes related records, "onDelete(\'set null\')" sets the foreign key column to null, and "onDelete(\'restrict\')" prevents deletion if related records exist. Indexes improve query performance on columns used frequently in WHERE clauses or JOIN conditions. Add indexes with "$table->index(\'status\')", "$table->unique(\'email\')" for unique indexes, or composite indexes with "$table->index([\'status\', \'destination\'])". Laravel also supports full-text indexes with "$table->fullText(\'body\')" for MySQL and PostgreSQL, and spatial indexes with "$table->spatialIndex(\'coordinates\')". Indexes can be added or dropped in separate migrations after a table is created using "Schema::table(\'posts\', function (Blueprint $table) { $table->index(\'status\'); })" and dropped with "$table->dropIndex([\'posts_status_index\'])" following Laravel\'s index naming convention of "table_column_index".',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Foreign Keys and Indexes Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does the constrained() method do in a migration?',
                            'options' => [
                                0 => 'CS: Automatically references the related table\'s primary key',
                                1 => 'CS: Adds a NOT NULL constraint',
                                2 => 'CS: Makes the column unique',
                                3 => 'CS: Sets a default value',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The constrained() method automatically infers the related table and column name based on the foreign key column name.',
                            'difficulty' => 'medium',
                            'topic' => 'migrations',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Column Modifiers and Migration Best Practices',
                    'content' => 'CS: Column modifiers allow fine-tuning of column behavior. The "nullable()" modifier allows NULL values, "default(\'value\')" sets a default value, "unsigned()" makes integer columns unsigned (positive only), "comment(\'text\')" adds a database comment, "autoIncrement()" creates auto-incrementing columns, and "first()"/"after(\'column\')" control column position in MySQL. You can change columns after creation using "$table->string(\'title\', 250)->change()" in a new migration. Before modifying columns, install the doctrine/dbal package which Laravel uses to inspect existing column types. Best practices for migrations include: create one migration per logical change rather than combining unrelated changes, always define both up and down methods for reversibility, use meaningful migration names like "add_excerpt_to_posts_table", test both up and down methods, avoid modifying production migrations after deployment, use foreign keys for referential integrity but be aware of performance implications on large tables, add indexes early for columns used in WHERE and JOIN clauses, and use database-specific column types only when necessary (preferring portable types from the Schema builder). Laravel also supports "virtual" and "stored" generated columns for computed values in MySQL. Squash related changes during development with "php artisan migrate:fresh" rather than creating many rollback migrations.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Column Modifiers and Best Practices Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which package is required to modify existing columns using the change() method?',
                            'options' => [
                                0 => 'CS: doctrine/dbal',
                                1 => 'CS: laravel/legacy-db',
                                2 => 'CS: symfony/db-validator',
                                3 => 'CS: migrations/modifier',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: doctrine/dbal is required by Laravel to inspect existing column types and compare them with the new definition when using the change() method.',
                            'difficulty' => 'hard',
                            'topic' => 'migrations',
                        ],
                    ],
                ],
            ],
        ],
        5 => [
            'title' => 'CS: Eloquent ORM',
            'slug' => 'cs-eloquent-orm',
            'description' => 'CS: Dive into Laravel\'s Eloquent ORM for database interaction, including models, queries, and accessors.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: What is Eloquent ORM?',
                    'content' => 'CS: Eloquent is Laravel\'s Object-Relational Mapper (ORM) that provides an ActiveRecord implementation for working with your database. Each Eloquent model corresponds to a database table, and each model instance represents a row in that table. Eloquent makes database interactions intuitive by translating SQL queries into PHP method calls. Instead of writing "SELECT * FROM users WHERE active = 1", you write "User::where(\'active\', true)->get()". Eloquent handles all the SQL generation, parameter binding, and result hydration automatically. Models are typically stored in app/Models and created with "php artisan make:model Post". The ORM provides a rich set of features including relationship management, mutators and accessors, global scopes, event observers, API resource serialization, and query scopes. Eloquent\'s ActiveRecord pattern means that a single class handles both data retrieval and business logic, which keeps related code together. The ORM is also incredibly flexible: you can drop down to raw SQL when needed, use subqueries, write complex joins, and still get Eloquent collections back. Eloquent\'s query builder (which it extends) provides a fluent, chainable interface for building queries of any complexity while protecting against SQL injection through parameter binding.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Eloquent ORM Basics Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What design pattern does Eloquent ORM implement?',
                            'options' => [
                                0 => 'CS: ActiveRecord',
                                1 => 'CS: Data Mapper',
                                2 => 'CS: Repository',
                                3 => 'CS: Unit of Work',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Eloquent implements the ActiveRecord pattern where each model class corresponds to a database table and each instance represents a row.',
                            'difficulty' => 'easy',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Eloquent Conventions and Configuration',
                    'content' => 'CS: By convention, Eloquent assumes certain defaults to minimize configuration. A model\'s table name is the snake_case plural of the class name: "User" maps to "users", "PostTag" maps to "post_tags". Override with "protected $table = \'custom_table\'". The primary key column is assumed to be "id" with an integer type and auto-incrementing behavior. Override with "protected $primaryKey = \'uuid\'" and set "public $incrementing = false" for non-integer keys. Eloquent expects "created_at" and "updated_at" timestamp columns, which are automatically managed. Disable with "public $timestamps = false". The model also assumes a connection to the default database, overridable with "protected $connection = \'pgsql\'". The "fillable" property defines which attributes are mass-assignable, protecting against mass-assignment vulnerabilities: "protected $fillable = [\'title\', \'body\']". Alternatively, "protected $guarded = [\'is_admin\']" denies specific attributes. Eloquent uses "snake_case" attribute names for database columns and "camelCase" for relationship methods, following community conventions. The "appends" property adds computed attributes to JSON serialization: "protected $appends = [\'full_name\']". Understanding these conventions is essential because Eloquent relies heavily on naming patterns to provide its convention-over-configuration developer experience. When you follow the conventions, you write dramatically less configuration code.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Eloquent Conventions Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are Eloquent conventions? (Select all that apply)',
                            'options' => [
                                0 => 'CS: Table name is snake_case plural of class name',
                                1 => 'CS: Primary key column is named "id"',
                                2 => 'CS: Models use camelCase for database columns',
                                3 => 'CS: Timestamps are expected as created_at and updated_at',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: Eloquent expects snake_case for columns and table names. camelCase is used for relationship methods, not column names.',
                            'difficulty' => 'medium',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Retrieving Data with Eloquent',
                    'content' => 'CS: Eloquent provides multiple ways to retrieve data from the database. "User::all()" returns all records as a Collection. "User::find($id)" retrieves a single record by primary key or returns null. "User::findOrFail($id)" throws a ModelNotFoundException if not found, which Laravel converts to a 404 response. "User::where(\'active\', true)->get()" applies conditions, and chaining further constraints like "->where(\'age\', \'>\', 18)" is natural. "User::first()" returns the first matching record, "firstOrFail()" throws if none exist, and "firstOrCreate([\'email\' => $email], [\'name\' => $name])" finds or creates a record. "User::count()", "User::max(\'age\')", and "User::sum(\'votes\')" return aggregate values. Eloquent also supports "lazy loading" with cursor-based iteration for memory-efficient processing of large datasets: "foreach (User::lazy() as $user) { ... }". The "chunk" method processes results in batches: "User::chunk(100, function ($users) { ... })". For pagination, "User::paginate(15)" handles limit/offset automatically and returns a LengthAwarePaginator instance with methods for generating pagination links. Eloquent\'s query builder also supports "select", "join", "groupBy", "orderBy", "having", and "distinct" for more complex queries. The "toSql()" method is invaluable during development to see the generated SQL.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Retrieving Data Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does User::findOrFail($id) do when no record is found?',
                            'options' => [
                                0 => 'CS: Throws a ModelNotFoundException',
                                1 => 'CS: Returns null',
                                2 => 'CS: Returns an empty collection',
                                3 => 'CS: Logs a warning and returns null',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: findOrFail throws a ModelNotFoundException when the record is not found, which Laravel converts to a 404 HTTP response.',
                            'difficulty' => 'easy',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Inserting and Updating Records',
                    'content' => 'CS: Creating new records with Eloquent is straightforward. The simplest approach uses mass assignment: "User::create([\'name\' => \'John\', \'email\' => \'john@example.com\'])", but requires the model\'s "fillable" or "guarded" properties to be defined. Alternatively, instantiate a new model and set attributes individually: "$user = new User(); $user->name = \'John\'; $user->save();". The "save" method persists the model: for new instances it inserts, for existing instances it updates. Updating records: "$user = User::find(1); $user->name = \'Jane\'; $user->save();" or use "User::where(\'active\', true)->update([\'status\' => \'inactive\'])" for bulk updates. The "firstOrCreate" and "firstOrNew" methods combine find-and-create logic. "User::firstOrCreate([\'email\' => $email], [\'name\' => $name, \'active\' => true])" finds by email or creates with all attributes. "updateOrCreate" combines check and update: "User::updateOrCreate([\'email\' => $email], [\'name\' => \'Updated Name\'])", which finds a record matching the first array or creates a new one, then updates with the second array. Eloquent also fires model events during these operations: creating, created, updating, updated, saving, saved, deleting, deleted, restoring, and restored. These events let you hook into the lifecycle for logging, cache clearing, or sending notifications. Models can be refreshed from the database with "$user->refresh()" to get the latest data after a save or concurrent modification.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Inserting and Updating Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is required for mass assignment using User::create()?',
                            'options' => [
                                0 => 'CS: The $fillable or $guarded property must be defined',
                                1 => 'CS: The model must extend Authenticatable',
                                2 => 'CS: The table must have an auto-incrementing ID',
                                3 => 'CS: The attributes must cast to appropriate types',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Mass assignment protection requires defining either $fillable (whitelist) or $guarded (blacklist) to prevent unauthorized attribute changes.',
                            'difficulty' => 'medium',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Eloquent Collections, Accessors, and Serialization',
                    'content' => 'CS: When Eloquent retrieves multiple records, it returns an Eloquent Collection object, which extends Laravel\'s base Collection class with additional model-specific methods. Collections provide powerful data manipulation methods: "filter", "map", "reject", "sortBy", "groupBy", "pluck", "contains", and "each". Eloquent-specific methods include "modelKeys" (returns primary keys), "load" (lazy-loads relationships), "loadCount", "makeVisible", "makeHidden", and "fresh". Accessors allow you to compute custom attribute values on the fly. Define an accessor by adding a method following the pattern "get{AttributeName}Attribute": "public function getFullNameAttribute(): string { return $this->first_name.\' \'.$this->last_name; }". The attribute is then accessible as "$user->full_name". Accessors can also be added to the model\'s $appends array to include them in JSON serialization. Mutators (setters) follow the pattern "set{AttributeName}Attribute" and manipulate data before saving. Serialization controls how models are converted to arrays or JSON. The "$hidden" property hides sensitive attributes like passwords: "protected $hidden = [\'password\', \'remember_token\']". The "$casts" property casts attributes to native types: "protected $casts = [\'is_admin\' => \'boolean\', \'metadata\' => \'array\']". Laravel also supports custom casters for complex types.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Collections and Accessors Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is the method naming convention for an Eloquent accessor?',
                            'options' => [
                                0 => 'CS: get{AttributeName}Attribute',
                                1 => 'CS: access{AttributeName}',
                                2 => 'CS: getAttribute{Name}',
                                3 => 'CS: retrieve{AttributeName}',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Accessors follow the \\"get{AttributeName}Attribute\\" pattern, like \\"getNameAttribute\\" for the \\"name\\" attribute.',
                            'difficulty' => 'medium',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
            ],
        ],
        6 => [
            'title' => 'CS: Eloquent Relationships',
            'slug' => 'cs-eloquent-relationships',
            'description' => 'CS: Master Eloquent relationships: one-to-one, one-to-many, many-to-many, polymorphic, and eager loading.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: One-to-One and One-to-Many Relationships',
                    'content' => 'CS: Eloquent relationships define how your model\'s data connects to other models, expressed as methods returning relationship instances. A one-to-one relationship exists when a single model owns exactly one instance of another model. Define it with the "hasOne" method: "public function profile(): HasOne { return $this->hasOne(Profile::class); }". The inverse is "belongsTo": "public function user(): BelongsTo { return $this->belongsTo(User::class); }". One-to-many relationships define a parent model that has multiple child models: "public function posts(): HasMany { return $this->hasMany(Post::class); }". The inverse is "belongsTo" on the child. Laravel automatically determines foreign key names based on the parent model name (e.g., "user_id" on the posts table). You can override the foreign key: "return $this->hasMany(Post::class, \'author_id\')". Similarly, override the local key: "return $this->hasMany(Post::class, \'author_id\', \'local_key\')". Relationships are accessed as dynamic properties: "$user->posts" (returns a Collection) or "$user->posts()" (returns a query builder instance for further chaining). These relationships form the foundation for more complex associations and are the most commonly used relationship types in typical Laravel applications.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: One-to-One and One-to-Many Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which Eloquent method defines the inverse side of a one-to-many relationship?',
                            'options' => [
                                0 => 'CS: belongsTo',
                                1 => 'CS: hasOne',
                                2 => 'CS: hasMany',
                                3 => 'CS: belongsToMany',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The belongsTo method defines the inverse of both hasOne and hasMany relationships on the child model.',
                            'difficulty' => 'easy',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Many-to-Many Relationships',
                    'content' => 'CS: Many-to-many relationships are more complex because they require an intermediate pivot table. For example, a user can have many roles, and a role can belong to many users. The pivot table "role_user" contains "user_id" and "role_id" columns. Define the relationship with "belongsToMany": "public function roles(): BelongsToMany { return $this->belongsToMany(Role::class); }". The pivot table name is derived from the singular model names in alphabetical order separated by an underscore. Override the pivot table name as the second argument: "belongsToMany(Role::class, \'user_roles\')". You can also customize the pivot columns: the third argument is the foreign key on the pivot ("user_id"), and the fourth is the related key ("role_id"). Access the relationship as "$user->roles", which returns a Collection of Role models with an added pivot attribute: "$role->pivot". If the pivot table has additional columns, specify them in the relationship: "->withPivot(\'expires_at\', \'is_active\')". Use "withTimestamps()" if the pivot has timestamps. Attach and detach relationships: "$user->roles()->attach($roleId)", "$user->roles()->detach($roleId)", or "$user->roles()->sync([1, 2, 3])" which automatically detaches missing IDs and attaches new ones. "syncWithoutDetaching" prevents detaching existing relations. "toggle" toggles the attachment status of each given ID.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Many-to-Many Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which methods can be used to manage many-to-many pivot attachments? (Select all that apply)',
                            'options' => [
                                0 => 'CS: attach',
                                1 => 'CS: sync',
                                2 => 'CS: toggle',
                                3 => 'CS: push',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: attach, sync, and toggle are valid methods for managing many-to-many relationships. push is not a relationship management method.',
                            'difficulty' => 'medium',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Has-Many-Through and Polymorphic Relationships',
                    'content' => 'CS: The "has-many-through" relationship provides a convenient way to access distant relations through intermediate relations. For example, a Country model might have many Post models through a User model: "public function posts(): HasManyThrough { return $this->hasManyThrough(Post::class, User::class); }". This generates a query joining the users table to retrieve posts for all users in that country. The method signature accepts the target model, intermediate model, foreign key on intermediate (country_id), foreign key on target (user_id), and local key (id). Polymorphic relationships allow a model to belong to multiple other models on a single association. For example, a Comment model can belong to both Post and Video models. The comments table needs "commentable_id" (integer) and "commentable_type" (string) columns. Define the inverse: "public function commentable(): MorphTo { return $this->morphTo(); }". On the parent models: "public function comments(): MorphMany { return $this->morphMany(Comment::class, \'commentable\'); }". Polymorphic many-to-many relationships (morphToMany) use a pivot table with columns for both the parent and related model types, useful for features like tags that can be applied to multiple model types. The morphedByMany method defines the inverse polymorphic many-to-many relationship. Polymorphic relationships are extremely powerful for building flexible, reusable features like comments, tags, likes, and ratings that work across multiple entity types.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Has-Many-Through and Polymorphic Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which two columns are required in a polymorphic relationship\'s database table?',
                            'options' => [
                                0 => 'CS: commentable_id and commentable_type',
                                1 => 'CS: polymorphic_id and polymorphic_type',
                                2 => 'CS: owner_id and owner_type',
                                3 => 'CS: entity_id and entity_type',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Polymorphic relationships require a *_id column (integer) and a *_type column (string) to store the related model\'s ID and class name.',
                            'difficulty' => 'medium',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Eager Loading and the N+1 Problem',
                    'content' => 'CS: The N+1 query problem occurs when you retrieve a list of models and then access a relationship on each one individually. For example, "$users = User::all(); foreach ($users as $user) { echo $user->profile->bio; }" executes one query for users and then one query per user for their profile — N+1 queries total. Eager loading solves this by loading all related models in two queries using JOINs or WHERE IN clauses. Use "with": "$users = User::with(\'profile\')->get()". Load multiple relationships: "->with([\'profile\', \'posts\', \'roles\'])". Nested eager loading uses dot notation: "->with(\'posts.comments\')". Constrain eager loads: "->with([\'posts\' => fn ($query) => $query->where(\'published\', true)])". Lazy eager loading loads relationships after the parent model is already retrieved: "$users->load(\'profile\')" or conditional loading with "$users->loadMissing(\'profile\')" to avoid duplicate loads. The "withCount" method retrieves the count of related models without loading them: "$users = User::withCount(\'posts\')->get(); echo $user->posts_count". Similarly, "withSum", "withAvg", "withMax", and "withMin" aggregate related data. Eager loading is critical for application performance and should be a default consideration whenever you programmatically access relationships in loops. Laravel\'s "N+1 Query Detector" in Telescope or dedicated packages like "laravel-n+1" can help identify missing eager loads during development.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Eager Loading Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is the N+1 problem in Eloquent?',
                            'options' => [
                                0 => 'CS: Querying a relationship in a loop causing one query per item plus the initial query',
                                1 => 'CS: Using too many JOINs in a single query',
                                2 => 'CS: Creating more than N indexes on a table',
                                3 => 'CS: Paginating results with incorrect per-page values',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The N+1 problem occurs when lazy-loading a relationship inside a loop, generating one additional query per model instance.',
                            'difficulty' => 'medium',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Touching Parent Timestamps and Aggregating Relationships',
                    'content' => 'CS: When a child model is updated, you may want to update the parent model\'s "updated_at" timestamp to reflect the change. The "touches" property on the child model defines which relationships to automatically touch: "protected $touches = [\'post\']". Whenever a Comment is created, updated, or deleted, its parent Post\'s "updated_at" column is updated. This is useful for cache invalidation or knowing when any related content was last modified. Eloquent also provides powerful relationship querying methods. The "has" method filters models based on the existence of a relationship: "Post::has(\'comments\')->get()" returns posts with at least one comment. "whereHas" filters based on relationship conditions: "Post::whereHas(\'comments\', fn ($query) => $query->where(\'approved\', true))->get()". "orWhereHas" and "doesntHave" provide negation. Aggregating relationship counts uses "withCount": "$posts = Post::withCount([\'comments\', \'likes as approval_count\' => fn ($q) => $q->where(\'approved\', true)])->get()". This adds "comments_count" and "approval_count" to each Post model. The "loadCount" method provides the same for already retrieved models. These aggregate methods are far more efficient than loading all related records and counting them in PHP, especially for large datasets.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Touching and Aggregating Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does the $touches property on an Eloquent model do?',
                            'options' => [
                                0 => 'CS: Updates the parent model\'s updated_at when the child is updated',
                                1 => 'CS: Prevents mass assignment on related models',
                                2 => 'CS: Caches relationship queries automatically',
                                3 => 'CS: Creates a join table for many-to-many relationships',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The $touches property defines which parent relationships should have their updated_at timestamp updated when the child model is saved or deleted.',
                            'difficulty' => 'hard',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
            ],
        ],
        7 => [
            'title' => 'CS: CRUD Operations',
            'slug' => 'cs-crud-operations',
            'description' => 'CS: Build full CRUD functionality with Laravel: index, create, store, show, edit, update, and destroy.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: The CRUD Pattern in Laravel',
                    'content' => 'CS: CRUD — Create, Read, Update, Delete — is the fundamental pattern for data management in web applications. Laravel provides a natural structure for implementing CRUD operations using resource controllers, form requests for validation, Eloquent models for database interaction, and Blade views for presentation. The typical CRUD flow begins with an Index page listing all resources. A "Create" link leads to a form, which submits to a "Store" endpoint. Each resource has a "Show" page for viewing details, an "Edit" page with a pre-filled form that submits to an "Update" endpoint, and a "Delete" button that triggers the "Destroy" action. Laravel\'s "Route::resource()" generates all seven methods automatically, each with a clear responsibility. The controller methods correspond directly to HTTP verbs and URIs: GET /resources (index), GET /resources/create (create), POST /resources (store), GET /resources/{id} (show), GET /resources/{id}/edit (edit), PUT/PATCH /resources/{id} (update), DELETE /resources/{id} (destroy). This RESTful convention is intuitive for both developers and API consumers, and makes your application predictable and self-explanatory.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: CRUD Pattern Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which HTTP method is used for the "update" action in a Laravel resource controller?',
                            'options' => [
                                0 => 'CS: PUT',
                                1 => 'CS: POST',
                                2 => 'CS: DELETE',
                                3 => 'CS: GET',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The update action uses PUT (or PATCH) HTTP method to update an existing resource.',
                            'difficulty' => 'easy',
                            'topic' => 'crud',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Index and Show — Reading Resources',
                    'content' => 'CS: The "index" method retrieves and displays a list of all resources, typically with pagination, sorting, and search functionality. A typical index method queries the model and returns a view with the data: "public function index(): View { return view(\'posts.index\', [\'posts\' => Post::latest()->paginate(10)]); }". The corresponding Blade view uses "@foreach" or "@forelse" to iterate over the records, presenting them in a table or card layout. The index page includes links to show, edit, and delete individual records. The "show" method displays a single resource in detail, typically using route model binding to automatically retrieve the model: "public function show(Post $post): View { return view(\'posts.show\', compact(\'post\')); }". The show view presents all relevant fields of the resource, along with related data and navigation links back to the index. Eager-loading relationships in the show method prevents N+1 queries: "Post::with([\'user\', \'comments\'])->findOrFail($id)". Both index and show should implement authorization checks using policies or gates to ensure users can only view resources they\'re permitted to access. The responses should also handle empty states gracefully — both when there are no resources at all and when a specific resource is not found (though route model binding handles 404s automatically).',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Index and Show Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does route model binding automatically do in a show method?',
                            'options' => [
                                0 => 'CS: Fetches the model from the database by ID',
                                1 => 'CS: Validates the request data',
                                2 => 'CS: Authorizes the user',
                                3 => 'CS: Returns a JSON response',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Route model binding automatically queries the database for the model matching the route parameter and injects it into the controller method.',
                            'difficulty' => 'easy',
                            'topic' => 'crud',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Create and Store — Creating Resources',
                    'content' => 'CS: The "create" method returns a view with a form for creating a new resource. It instantiates an empty model for form binding: "public function create(): View { return view(\'posts.create\', [\'post\' => new Post()]); }". The Blade form uses Laravel\'s FORM method spoofing with "@csrf" and "@method(\'POST\')" directives. Pre-fill form fields with old input after validation failure using "old(\'title\')". The "store" method handles form submission, validates the input, creates the record, and redirects. Leverage Form Requests for validation: "public function store(StorePostRequest $request): RedirectResponse { Post::create($request->validated()); return redirect()->route(\'posts.index\')->with(\'success\', \'Post created successfully.\'); }". The Form Request validates input before the method executes, preventing invalid data from reaching your store logic. After successful creation, redirect to a relevant page — typically the index list or the newly created resource\'s show page — with a flash message. Handle failed validation by redirecting back to the create form with errors and old input, which Laravel\'s Blade "@error" directive can display. The store method should also authorize the action (can the user create this resource?), log the creation for auditing, and trigger any necessary post-creation jobs like cache clearing or notification dispatching.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Create and Store Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are best practices for a store method? (Select all that apply)',
                            'options' => [
                                0 => 'CS: Using Form Requests for validation',
                                1 => 'CS: Redirecting after successful creation',
                                2 => 'CS: Returning the form view',
                                3 => 'CS: Using a flash message for user feedback',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: Form requests validate input, redirecting is standard post-submit behavior, and flash messages provide user feedback. Returning the form view (re-rendering) is not the standard pattern.',
                            'difficulty' => 'medium',
                            'topic' => 'crud',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Edit and Update — Modifying Resources',
                    'content' => 'CS: The "edit" method displays a pre-filled form for modifying an existing resource. Like create, it returns a view but passes the existing model instance: "public function edit(Post $post): View { return view(\'posts.edit\', compact(\'post\')); }". The edit form looks nearly identical to the create form but uses the model\'s existing attribute values via model binding. The "update" method accepts the form submission, validates, updates, and redirects: "public function update(UpdatePostRequest $request, Post $post): RedirectResponse { $post->update($request->validated()); return redirect()->route(\'posts.show\', $post)->with(\'success\', \'Post updated.\'); }". Compare firstOrCreate vs update for mass assignment: update() requires the model to already exist. After updating, redirect to the resource\'s show page or back to the index. Authorization in update is critical — ensure via a Policy that the current user owns the resource or has appropriate permissions. The edit and update methods should also handle the case where the resource is no longer available (though implicit model binding handles 404). For optimistic concurrency, consider checking timestamps when multiple users might edit the same resource simultaneously.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Edit and Update Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which HTTP verb is typically used for the update action?',
                            'options' => [
                                0 => 'CS: PUT',
                                1 => 'CS: POST',
                                2 => 'CS: GET',
                                3 => 'CS: DELETE',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The update action uses PUT (or PATCH) HTTP method, with @method(\'PUT\') in the Blade form for HTML form spoofing.',
                            'difficulty' => 'easy',
                            'topic' => 'crud',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Destroy and Validation — Deleting Resources',
                    'content' => 'CS: The "destroy" method removes a resource from the database. Typically a simple action: "public function destroy(Post $post): RedirectResponse { $post->delete(); return redirect()->route(\'posts.index\')->with(\'success\', \'Post deleted.\'); }". The delete action is often triggered via a form that uses POST with @method(\'DELETE\'), displayed as a button in the index or show view. Add a confirmation dialog (JavaScript confirm() or a modal) before submission. Always authorize deletion — users should only delete resources they own or have moderator permissions for. Use soft deletes for recoverable data by adding the SoftDeletes trait to your model, which sets "deleted_at" instead of actually removing the row. Query soft-deleted models with "withTrashed()" and restore them with "$post->restore()". Validation is a cross-cutting concern across all CRUD operations. Store and update methods should validate that the data meets business rules: required fields, unique constraints, data formats, and length limits. Laravel Form Requests encapsulate validation and authorization in a single class. For simpler cases, use the validate() method directly in the controller: "$validated = $request->validate([\'title\' => \'required|max:255\'])". Display validation errors in Blade with "@error(\'title\')" directives. Consistent validation patterns across your CRUD operations prevent data integrity issues and provide a unified user experience.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Destroy and Validation Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are true about the destroy action? (Select all that apply)',
                            'options' => [
                                0 => 'CS: It typically uses a POST form with @method(\'DELETE\')',
                                1 => 'CS: Soft deletes set a deleted_at column instead of deleting the row',
                                2 => 'CS: Authorization is optional for delete operations',
                                3 => 'CS: Soft deleted models can be restored with restore()',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: DELETE forms use method spoofing, soft deletes set deleted_at, and they can be restored. Authorization is never optional for destructive actions.',
                            'difficulty' => 'medium',
                            'topic' => 'crud',
                        ],
                    ],
                ],
            ],
        ],
        8 => [
            'title' => 'CS: Forms and Validation',
            'slug' => 'cs-forms-validation',
            'description' => 'CS: Build forms in Blade, validate input, display errors, and create custom validation rules.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Building Forms in Blade',
                    'content' => 'CS: Laravel provides a clean approach for building forms in Blade. A basic form starts with "<form method=\'POST\' action=\'{{ route(\'posts.store\') }}\'>". Every POST form needs a CSRF token field: "@csrf". For PUT, PATCH, or DELETE methods, use method spoofing: "@method(\'PUT\')". Blade form inputs use "old()" to retain values after validation failure: "<input type=\'text\' name=\'title\' value=\'{{ old(\'title\') }}\'>". For edit forms, pre-fill with the model\'s value: "value=\'{{ old(\'title\', $post->title) }}\'". The "@error" directive displays validation errors per field: "@error(\'title\') <div class=\'text-red-500\'>{{ $message }}</div> @enderror". Checkboxes and selects have special handling: "@error(\'categories.*\')" for array inputs, and loosely check old input for checkboxes: "@checked(old(\'remember\') ?? false)". Livewire forms take a different approach with wire:model for real-time binding, but traditional Blade forms with POST/redirect are still the standard for many applications.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Blade Forms Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What Blade directive generates a CSRF token field in forms?',
                            'options' => [
                                0 => 'CS: @csrf',
                                1 => 'CS: @token',
                                2 => 'CS: @secure',
                                3 => 'CS: @form',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The @csrf directive generates a hidden input field containing a CSRF token to protect against cross-site request forgery.',
                            'difficulty' => 'easy',
                            'topic' => 'forms',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: The validate() Method and Validation Rules',
                    'content' => 'CS: Laravel\'s validate() method on the Request instance provides a straightforward way to validate incoming data. Inside a controller method: "$validated = $request->validate([\'title\' => \'required|string|max:255\', \'email\' => \'required|email|unique:users\', \'age\' => \'required|integer|min:18\'])". Validation rules can be passed as a pipe-delimited string or an array: "\'title\' => [\'required\', \'string\', \'max:255\']". Laravel provides dozens of built-in validation rules including "required", "string", "integer", "numeric", "email", "url", "ip", "date", "date_format:Y-m-d", "min:N", "max:N", "between:min,max", "unique:table,column,except,idColumn", "exists:table,column", "confirmed" (matches field_confirmation), "same:field", "different:field", "in:foo,bar", "not_in:foo,bar", "regex:/^[A-Z]+$/", "image" (valid image file), "mimes:jpg,png", "file", "array", "boolean", "json", "string", and "nullable". On validation failure, Laravel automatically redirects back with errors stored in the session and old input available via the old() helper. For AJAX requests, JSON with validation error messages is returned with a 422 status code. The validate() method is perfect for simple validation scenarios, while Form Requests are recommended for complex or reusable validation logic.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Validation Rules Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which validation rules ensure a field matches its confirmation field? (Select all that apply)',
                            'options' => [
                                0 => 'CS: confirmed',
                                1 => 'CS: same:field',
                                2 => 'CS: match:field',
                                3 => 'CS: verify',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                            ],
                            'explanation' => 'CS: The "confirmed" rule expects a matching field_confirmation field. The "same" rule explicitly specifies the field name to match against.',
                            'difficulty' => 'medium',
                            'topic' => 'validation',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Displaying Validation Errors',
                    'content' => 'CS: Laravel makes validation error display simple with Blade directives and helper functions. The "@error(\'field_name\')" directive checks if an error exists for a specific field: "@error(\'title\') <span class=\'text-red-600\'>{{ $message }}</span> @enderror". The "$errors" variable is automatically available in all Blade views and is an instance of Illuminate\\Support\\MessageBag. Use "$errors->any()" to check if any errors exist, and "$errors->all()" to retrieve all error messages. For displaying all errors at the top of a form: "@if($errors->any()) <div><ul> @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul></div> @endif". The "@error" directive accepts wildcard for array inputs: "@error(\'items.*\')". You can customize error messages by passing a custom messages array as the third argument to validate(): "validate($rules, $messages, $attributes)". The ":attribute" placeholder in messages is replaced with the human-readable field name. Customize attribute names with the "attributes" array: "validate($rules, [], [\'email\' => \'email address\'])", which produces "The email address field is required." Error messages are automatically translated via language files in resources/lang if using Laravel\'s localization features.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Error Display Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which variable is automatically available in all Blade templates for displaying validation errors?',
                            'options' => [
                                0 => 'CS: $errors',
                                1 => 'CS: $messages',
                                2 => 'CS: $validation',
                                3 => 'CS: $flash',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The $errors variable, an instance of MessageBag, is automatically shared with all Blade views by Laravel\'s error middleware.',
                            'difficulty' => 'easy',
                            'topic' => 'validation',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Custom Validation Rules and Form Requests',
                    'content' => 'CS: For complex or reusable validation logic, Laravel offers custom validation rules. Create one with "php artisan make:rule Uppercase". A custom rule class implements the ValidationRule interface with a "validate" method: "public function validate(string $attribute, mixed $value, Closure $fail): void { if (strtoupper($value) !== $value) { $fail(\'The :attribute must be uppercase.\'); } }". Use the rule in validation: "\'title\' => [\'required\', new Uppercase]". Laravel also supports rule objects with the "invokable" rule pattern: "Rule::in([\'admin\', \'user\'])" and "Rule::unique(\'users\')->ignore($userId)". For simple ad-hoc validation, use "Rule::unique()" or "Rule::exists()". Form Requests provide the ultimate encapsulation for validation. Created with "php artisan make:request StorePostRequest", they combine authorization, validation rules, custom messages, and attribute names in one class. The "authorize()" method checks permissions, "rules()" returns validation rules, "messages()" customizes messages, and "attributes()" customizes attribute names. Form Requests are type-hinted in controller methods: "public function store(StorePostRequest $request) { ... }". Validation is automatically performed before the controller method executes. Form Requests can also prepare input data via the "prepareForValidation()" method and perform additional validation after validation via "withValidator()" for conditional logic.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Custom Rules and Form Requests Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which method in a Form Request class defines the validation rules?',
                            'options' => [
                                0 => 'CS: rules',
                                1 => 'CS: validate',
                                2 => 'CS: validation',
                                3 => 'CS: constraints',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The rules() method returns an array of validation rules that will be applied to the incoming request.',
                            'difficulty' => 'easy',
                            'topic' => 'validation',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Validation Error Messages and Localization',
                    'content' => 'CS: Customizing validation error messages makes your application more user-friendly. Inline custom messages can be passed to validate(): "$request->validate([\'email\' => \'required\'], [\'email.required\' => \'We need your email address!\'])", using "field.rule" syntax. For Form Requests, return custom messages from the "messages()" method: "public function messages(): array { return [\'title.required\' => \'A post title is required.\']; }". Laravel also supports localized validation messages via language files. Published validation language files are in "resources/lang/{locale}/validation.php". Key patterns include ":attribute" (field name), ":value" (current value), ":min", ":max", ":size", ":other" (other field name), and ":values" (comma-separated values for "in" rules). Customize the "custom" array for field-specific messages: "\'custom\' => [\'email\' => [\'required\' => \'Don\\\'t forget your email!\']]". The "attributes" array provides human-readable field names: "\'attributes\' => [\'email\' => \'e-mail address\']". Laravel\'s validation extension supports fully localized error messages by publishing and modifying the validation language file. For dynamic error messages, use Closure-based rules with localization.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Error Messages Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is the ":attribute" placeholder replaced with in validation error messages?',
                            'options' => [
                                0 => 'CS: The human-readable field name',
                                1 => 'CS: The field\'s value',
                                2 => 'CS: The field\'s database column type',
                                3 => 'CS: The form input name attribute',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The :attribute placeholder is replaced with the human-readable field name, customizable via the "attributes" array in validation.',
                            'difficulty' => 'medium',
                            'topic' => 'validation',
                        ],
                    ],
                ],
            ],
        ],
        9 => [
            'title' => 'CS: Authentication',
            'slug' => 'cs-authentication',
            'description' => 'CS: Implement authentication in Laravel, including guards, policies, email verification, and password reset.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Laravel Authentication Overview',
                    'content' => 'CS: Laravel provides a complete authentication system out of the box. With Laravel Breeze or Laravel Jetstream, you can scaffold login, registration, password reset, and email verification in minutes. Laravel\'s authentication system is built on "guards" (which define how users are authenticated for each request) and "providers" (which define how users are retrieved from storage). The default guard is "web" which uses session storage, while "api" guards typically use tokens. Authentication configuration is in config/auth.php, where you define guards, providers, and password reset settings. Laravel Breeze provides minimal, simple authentication scaffolding with Tailwind CSS, supporting Livewire (with Volt) or Inertia.js stacks. It includes login, registration, password confirmation, email verification, and password reset views. Jetstream offers more features including team management and two-factor authentication. The underlying authentication logic is handled by Laravel\'s Auth facade and the Illuminate\\Auth\\AuthManager class. You can check if a user is authenticated with "Auth::check()", get the current user with "Auth::user()", and require authentication with the "auth" middleware.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Authentication Overview Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which file configures authentication guards and providers?',
                            'options' => [
                                0 => 'CS: config/auth.php',
                                1 => 'CS: config/app.php',
                                2 => 'CS: routes/auth.php',
                                3 => 'CS: config/services.php',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: config/auth.php defines authentication guards, user providers, and password reset configuration.',
                            'difficulty' => 'easy',
                            'topic' => 'authentication',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Authentication Guards and Protecting Routes',
                    'content' => 'CS: Guards define how users are authenticated for each request. The default "web" guard uses session-based authentication, storing the user ID in the session and persisting login across requests. The "api" guard typically uses token-based authentication with Sanctum, which issues API tokens. Sanctum provides SPA authentication (session-based for your own frontend) and token-based authentication (for mobile apps or third-party APIs). Configure Sanctum in config/sanctum.php. To protect routes, use the "auth" middleware: "Route::middleware(\'auth\')->group(function () { ... })". This redirects unauthenticated users to the login page. For finer control, use "auth:api" to specify which guard to use. Guest routes (for login/register pages) use middleware(\'guest\') to redirect authenticated users away. Laravel also supports multiple authentication guards simultaneously — useful for separate admin and user authentication systems. You can attempt authentication with "Auth::guard(\'admin\')->attempt([\'email\' => $email, \'password\' => $password])". The "attempt" method checks credentials and logs the user in. Logout is equally simple: "Auth::logout()" clears the session. The "logoutOtherDevices" method logs out sessions on other devices by re-encrypting the password with the current device\'s session.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Guards and Route Protection Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are valid uses of Laravel authentication guards? (Select all that apply)',
                            'options' => [
                                0 => 'CS: Protecting routes with middleware(\'auth\')',
                                1 => 'CS: Using multiple guards for admin and user areas',
                                2 => 'CS: Authenticating with Auth::attempt()',
                                3 => 'CS: Encrypting passwords with bcrypt() automatically',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: Route protection, multiple guards, and manual authentication are all guard features. bcrypt() is automatic for passwords but is not a guard feature itself.',
                            'difficulty' => 'medium',
                            'topic' => 'authentication',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Retrieving the Authenticated User',
                    'content' => 'CS: Once a user is authenticated, you can access the user instance through several methods. The most common is the Auth facade: "Auth::user()" returns the currently authenticated user instance or null. "Auth::id()" returns the user\'s primary key. "Auth::check()" returns true if a user is authenticated, "Auth::guest()" returns true for guests. Via the Request instance: "$request->user()" also returns the authenticated user. In controllers, you can type-hint the User model, though explicit Auth::user() is more common. In Blade views, use "@auth ... @endauth" for authenticated content and "@guest ... @endguest" for guest content. The "auth()->user()" helper function is a convenient shortcut. For API authentication with Sanctum, "$request->user()" returns the token-authenticated user. The authenticated user\'s abilities (token abilities) are checked with "$user->tokenCan(\'read\')". Laravel also provides "Auth::once()" for authenticating a single request without session persistence, useful for stateless API authentication. The "Auth::viaRemember()" method checks if the user was authenticated via the "remember me" cookie. Understanding how to access the authenticated user is fundamental for building personalized application features.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Retrieving the User Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which Auth method checks if a user is currently authenticated?',
                            'options' => [
                                0 => 'CS: Auth::check()',
                                1 => 'CS: Auth::user()',
                                2 => 'CS: Auth::id()',
                                3 => 'CS: Auth::guest()',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Auth::check() returns true when a user is authenticated and false otherwise. Auth::guest() is the inverse.',
                            'difficulty' => 'easy',
                            'topic' => 'authentication',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Email Verification and Password Reset',
                    'content' => 'CS: Laravel provides built-in email verification through the "MustVerifyEmail" interface. Implement it on your User model: "class User extends Authenticatable implements MustVerifyEmail". Routes for email verification are included in Breeze/Jetstream scaffolding. After registration, users receive an email with a verification link. The "verified" middleware protects routes that require verified accounts: "Route::middleware([\'auth\', \'verified\'])->group(...)". Verify the email manually: "$user->markEmailAsVerified()". Customize the verification email notification by overriding the "sendEmailVerificationNotification" method on the User model. Password reset functionality is also built in. Users request a reset link via a form, receive an email with a tokenized link, and create a new password. The process uses a password reset table to store tokens. Customize password reset with "Password::sendResetLink()" and "Password::reset()" methods. The password broker configuration in config/auth.php specifies which passwords table and user provider to use. Laravel validates the reset token and can enforce password complexity rules via validation. Both email verification and password reset rely on Laravel\'s notification system, which supports mail, database, SMS (Vonage), and Slack channels. Queueing these notifications is recommended for better response times.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Email Verification and Password Reset Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are true about email verification? (Select all that apply)',
                            'options' => [
                                0 => 'CS: The User model must implement MustVerifyEmail',
                                1 => 'CS: The "verified" middleware protects routes',
                                2 => 'CS: Verification emails are sent synchronously by default',
                                3 => 'CS: markEmailAsVerified() manually verifies an email',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: MustVerifyEmail interface, verified middleware, and markEmailAsVerified() are all correct. Emails are queueable but not synchronous by default behavior.',
                            'difficulty' => 'medium',
                            'topic' => 'authentication',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Authorization: Gates and Policies',
                    'content' => 'CS: Laravel provides two complementary ways to authorize user actions: Gates and Policies. Gates are Closure-based authorization checks, typically defined in the AppServiceProvider: "Gate::define(\'update-post\', fn (User $user, Post $post) => $user->id === $post->user_id)". Use gates with "Gate::allows(\'update-post\', $post)" or "Gate::denies(\'update-post\', $post)". Policies are class-based authorization organized around a particular model or resource. Create one with "php artisan make:policy PostPolicy". Policies contain methods like "viewAny", "view", "create", "update", "delete", "restore", and "forceDelete". Register policies in the AuthServiceProvider by mapping them to models: "protected $policies = [Post::class => PostPolicy::class]". Use policies in controllers with "authorize": "public function update(Request $request, Post $post) { $this->authorize(\'update\', $post); ... }". Blade directives "@can" and "@cannot" control view rendering: "@can(\'update\', $post) <a href=\'...\'>Edit</a> @endcan". For "create" actions, pass the class name: "$this->authorize(\'create\', Post::class)". Policies also filter model queries with query builders. Gates are best for actions not tied to a model, while Policies are the recommended approach for model-specific authorization. Both integrate seamlessly with Livewire components and form requests.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Authorization Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which Blade directive conditionally shows content based on authorization?',
                            'options' => [
                                0 => 'CS: @can',
                                1 => 'CS: @auth',
                                2 => 'CS: @if',
                                3 => 'CS: @allow',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: @can checks if the current user is authorized for a given action and model before rendering content.',
                            'difficulty' => 'medium',
                            'topic' => 'authentication',
                        ],
                    ],
                ],
            ],
        ],
        10 => [
            'title' => 'CS: Advanced Relationships',
            'slug' => 'cs-advanced-relationships',
            'description' => 'CS: Deep dive into advanced Eloquent relationship patterns, custom pivots, and polymorphic many-to-many.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Has-One-Through Relationships',
                    'content' => 'CS: The "has-one-through" relationship defines a one-to-one connection through another intermediate model. For example, a Mechanic model may have one Car model, and that Car may have one Owner model. The mechanic can access the owner through the car: "public function carOwner(): HasOneThrough { return $this->hasOneThrough(Owner::class, Car::class); }". Laravel resolves this by joining the cars table on mechanic_id, then the owners table on the car\'s foreign key. The method signature: "hasOneThrough(RelatedModel::class, IntermediateModel::class, ...)". The default behavior uses Eloquent\'s convention-based key guessing, but you can override any parameter if your schema uses non-standard naming. Has-one-through is useful for accessing distant relations in a "chain" of one-to-one relationships without adding direct foreign keys to the final model. It keeps your schema normalized while still allowing convenient access to related data. Performance is good because Laravel generates efficient join queries rather than lazy-loading the intermediate model first. This relationship type is less commonly used than has-many-through but is essential when you encounter deeply nested one-to-one chains in your data model.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Has-One-Through Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How many database tables are involved in a has-one-through relationship definition?',
                            'options' => [
                                0 => 'CS: Three',
                                1 => 'CS: Two',
                                2 => 'CS: Four',
                                3 => 'CS: One',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Has-one-through involves the parent table, an intermediate table, and the related (target) table — three tables total.',
                            'difficulty' => 'hard',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Working with Pivot Data',
                    'content' => 'CS: Many-to-many relationships store their association in a pivot table. By default, accessing a belongsToMany relationship gives you the related models, but you can also access pivot data. The "withPivot" method specifies extra columns to include from the pivot table: "return $this->belongsToMany(Role::class)->withPivot(\'expires_at\', \'is_active\')". These columns are accessible via the pivot attribute: "$user->roles->first()->pivot->expires_at". If your pivot table has timestamps, call "->withTimestamps()" on the relationship, making "created_at" and "updated_at" available. To filter by pivot column values, use "wherePivot": "$user->roles()->wherePivot(\'is_active\', true)->get()". Similarly, "wherePivotIn", "wherePivotNotIn", and "wherePivotBetween" provide additional filtering. The "pivot" attribute is an instance of Illuminate\\Database\\Eloquent\\Relations\\Pivot, which you can customize by creating a custom Pivot model class. Filtering and ordering pivot data is essential for features like "user roles with expiration dates", "product categories with sort order", or "students enrolled in courses with enrollment date". The pivot data is automatically managed when attaching models: "$user->roles()->attach($roleId, [\'expires_at\' => now()->addYear()])". Updating pivot data uses "updateExistingPivot": "$user->roles()->updateExistingPivot($roleId, [\'is_active\' => false])".',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Pivot Data Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which methods are used to work with pivot table data? (Select all that apply)',
                            'options' => [
                                0 => 'CS: withPivot',
                                1 => 'CS: wherePivot',
                                2 => 'CS: pivotData',
                                3 => 'CS: updateExistingPivot',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: withPivot defines extra columns, wherePivot filters by pivot columns, and updateExistingPivot modifies pivot records. pivotData is not a valid method.',
                            'difficulty' => 'medium',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Polymorphic Many-to-Many (MorphToMany)',
                    'content' => 'CS: Polymorphic many-to-many relationships allow a model to belong to multiple other model types through a single pivot table. For example, Tags can be applied to both Posts and Videos. The pivot table "taggables" contains columns: "tag_id", "taggable_id", and "taggable_type". Define the relationship on the Tag model: "public function posts(): MorphToMany { return $this->morphedByMany(Post::class, \'taggable\'); }" and "public function videos(): MorphToMany { return $this->morphedByMany(Video::class, \'taggable\'); }". On the Post model: "public function tags(): MorphToMany { return $this->morphToMany(Tag::class, \'taggable\'); }". The "morphedByMany" method defines the inverse on the parent polymorphic model. The third parameter is the "name" of the polymorphic fields (taggable_id + taggable_type), and the fourth parameter customizes the foreign key (tag_id). You can attach tags like a regular many-to-many: "$post->tags()->attach($tagId)". Polymorphic many-to-many relationships are powerful for building reusable features: tagging, categories, metadata, favorites, likes, and flags that work across multiple entity types with a single database table. The pivot table can have extra columns too: "->withPivot(\'weight\')". This pattern keeps your schema clean and avoids creating separate pivot tables for every combination of entities.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Polymorphic Many-to-Many Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which method defines the inverse side of a polymorphic many-to-many relationship?',
                            'options' => [
                                0 => 'CS: morphedByMany',
                                1 => 'CS: morphToMany',
                                2 => 'CS: morphMany',
                                3 => 'CS: morphTo',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: morphedByMany defines the inverse on the parent model (e.g., Post.tags), while morphToMany defines it on the shared model (e.g., Tag.posts).',
                            'difficulty' => 'hard',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Querying Relationship Existence and Default Models',
                    'content' => 'CS: Eloquent provides elegant methods for querying based on relationship existence, absence, and conditions. The simplest is "has": "Post::has(\'comments\')->get()" returns all posts with at least one comment. Specify a minimum count: "Post::has(\'comments\', \'>=\', 3)->get()". "orHas" works as expected. "whereHas" adds constraints to the relationship query: "Post::whereHas(\'comments\', fn ($q) => $q->where(\'approved\', true))->get()". "doesntHave" and "whereDoesntHave" provide the inverse, useful for finding posts without comments or users without any orders. The "orWhereHas" and "orDoesntHave" complete the boolean logic. These methods are all executed as subqueries in SQL, maintaining excellent performance. Default models allow relationships to return a default model instead of null: "public function author(): BelongsTo { return $this->belongsTo(User::class)->withDefault([\'name\' => \'Guest Author\']) }". Now "$post->author->name" never errors — it returns "Guest Author" when there is no author. This is incredibly useful for template code that assumes a related model exists. The default can also be a Closure for dynamic defaults: "->withDefault(fn ($user, $post) => $user->forceFill([\'name\' => \'Anonymous\']))". Default models remove the need for null-checking boilerplate in your Blade views and make templates much cleaner.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Querying Existence and Defaults Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which Eloquent method finds models that do NOT have a related model?',
                            'options' => [
                                0 => 'CS: doesntHave',
                                1 => 'CS: lacks',
                                2 => 'CS: without',
                                3 => 'CS: missing',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: doesntHave() filters models that have zero related records. It\'s the inverse of has().',
                            'difficulty' => 'medium',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Custom Pivot Models and Storing/Updating Relations',
                    'content' => 'CS: For advanced pivot data scenarios, you can create a custom Pivot model. Use "php artisan make:pivot RoleUser" to generate a class extending Illuminate\\Database\\Eloquent\\Relations\\Pivot. Define the custom pivot in the relationship: "return $this->belongsToMany(Role::class)->using(RoleUser::class)". Custom pivots can have their own accessors, mutators, casts, and events. They also support soft deleting, timestamps, and custom methods. This is helpful when pivot data is complex enough to warrant its own behavior. For storing and updating related models, Eloquent offers several approaches beyond simple "save()". The "save" method on a relationship automatically sets the foreign key: "$post = new Post([\'title\' => \'New Post\']); $user->posts()->save($post)". Use "saveMany" to persist multiple records. The "create" method combines instantiation and saving: "$user->posts()->create([\'title\' => \'New Post\'])", which is the most common approach. For many-to-many: "sync" (synchronizes the entire array, detaching missing IDs), "syncWithoutDetaching" (adds new IDs without removing existing ones), "toggle" (attaches if not present, detaches if present), "attach" (adds without checking), and "detach" (removes). Each supports pivot data: "$user->roles()->attach($roleIds, [\'expires_at\' => now()])". The "update" method works on relationship query builders: "$user->roles()->update([\'pivot_column\' => $value])". For belongs-to relationships, use "associate": "$post->user()->associate($user)" and "dissociate" to remove the relation.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Custom Pivots and Storing Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which method attaches related model IDs without detaching existing ones?',
                            'options' => [
                                0 => 'CS: syncWithoutDetaching',
                                1 => 'CS: attachWithoutDetach',
                                2 => 'CS: toggleWithoutRemoving',
                                3 => 'CS: syncFresh',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: syncWithoutDetaching adds new IDs to the pivot table while preserving existing relations — the opposite of sync which detaches missing IDs.',
                            'difficulty' => 'medium',
                            'topic' => 'eloquent',
                        ],
                    ],
                ],
            ],
        ],
        11 => [
            'title' => 'CS: Middleware',
            'slug' => 'cs-middleware',
            'description' => 'CS: Understand middleware in Laravel: creation, registration, parameters, and ordering.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: What is Middleware?',
                    'content' => 'CS: Middleware acts as a filtering layer between an incoming HTTP request and your application. It provides a convenient mechanism for inspecting and filtering HTTP requests entering your application. Common use cases include authentication (only allow logged-in users), logging (log all requests), CORS (add cross-origin headers), rate limiting (prevent abuse), and input sanitization (strip malicious content). Middleware runs before your controller or route handler executes, and can also run after the response is generated. Laravel includes several built-in middleware: "auth" for authentication, "guest" for guest-only routes, "throttle" for rate limiting, "verified" for email verification, "cache.headers" for HTTP cache headers, and "TrimStrings"/"ConvertEmptyStringsToNull" for input normalization. Middleware is executed in a pipeline — the request passes through each middleware in order before reaching the application, and the response flows back through the same pipeline. This pipeline architecture is inspired by Rack (Ruby) and is similar to how Reactor patterns work. Middleware can be applied globally (every request), to route groups, or to individual routes. Laravel\'s middleware system is one of the most flexible and well-designed aspects of the framework, enabling clean separation of cross-cutting concerns from business logic.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Middleware Overview Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: When does middleware execute relative to the controller?',
                            'options' => [
                                0 => 'CS: Before the controller, and optionally after',
                                1 => 'CS: Only before the controller',
                                2 => 'CS: Only after the controller',
                                3 => 'CS: In parallel with the controller',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Middleware runs before the controller (request filtering) and can optionally run after the response is generated (terminable middleware).',
                            'difficulty' => 'easy',
                            'topic' => 'middleware',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Creating and Registering Middleware',
                    'content' => 'CS: Create middleware with "php artisan make:middleware LogRequests". The generated class has a "handle" method that receives the request and a closure named "$next". Process the request: "public function handle(Request $request, Closure $next): Response { // before logic $response = $next($request); // after logic return $response; }". The "$next($request)" passes the request deeper into the application pipeline. Middleware must be registered before it can be used. Global middleware (runs on every request) is registered in bootstrap/app.php using the "->withMiddleware(function (Middleware $middleware) { $middleware->append(LogRequests::class); })" method, replacing the old Kernel class approach in Laravel 11. Route-specific middleware is registered with aliases in the same file: "$middleware->alias([\'log\' => LogRequests::class])". Apply aliased middleware to routes: "Route::get(\'/admin\', ...)->middleware(\'log\')". You can also register middleware groups: "$middleware->group(\'web\', [LogRequests::class])", which appends to the default web or api groups. Laravel 11\'s bootstrap/app.php approach is cleaner and more explicit than previous versions. For middleware that needs to run in a specific order relative to other middleware, you can manage priority settings to control the execution order.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Creating Middleware Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Where do you register global middleware in Laravel 11?',
                            'options' => [
                                0 => 'CS: In bootstrap/app.php using withMiddleware',
                                1 => 'CS: In config/middleware.php',
                                2 => 'CS: In app/Http/Kernel.php',
                                3 => 'CS: In routes/web.php',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Laravel 11 uses bootstrap/app.php with the withMiddleware() method to register global and aliased middleware.',
                            'difficulty' => 'medium',
                            'topic' => 'middleware',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Middleware Parameters',
                    'content' => 'CS: Middleware can accept additional parameters beyond the standard $request and $next. For example, a "role" middleware that checks the user\'s role receives the role name as a parameter: "public function handle(Request $request, Closure $next, string $role): Response { if (! $request->user()->hasRole($role)) { abort(403); } return $next($request); }". Parameters are passed when applying middleware to routes: "Route::get(\'/admin\', ...)->middleware(\'role:admin\')". Multiple parameters are separated by commas: "middleware(\'check:posts,create\')". The middleware method receives these parameters in order after $next. This parameterization makes middleware incredibly reusable — instead of creating "AdminMiddleware", "EditorMiddleware", and "ModeratorMiddleware", you create a single "RoleMiddleware" parameterized with the required role. Middleware parameters can be combined with route model binding to authorize based on both the user and the resource. For example, a "can" middleware wraps Laravel\'s authorization: "middleware(\'can:update,post\')" which calls the PostPolicy update method. Middleware parameters enable fine-grained access control without writing separate middleware classes for every permission scenario. They are also useful for feature flags, locale detection, and A/B testing contexts.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Middleware Parameters Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How do you pass a parameter to middleware in a route definition?',
                            'options' => [
                                0 => 'CS: middleware(\'role:admin\')',
                                1 => 'CS: middleware(\'role\', \'admin\')',
                                2 => 'CS: middleware([\'role\' => \'admin\'])',
                                3 => 'CS: middleware->param(\'role\', \'admin\')',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Middleware parameters follow the middleware alias with a colon separator: middleware(\'alias:param1,param2\').',
                            'difficulty' => 'easy',
                            'topic' => 'middleware',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Terminable Middleware and Middleware Ordering',
                    'content' => 'CS: Terminable middleware runs after the HTTP response has been sent to the browser. This is useful for slow operations that the user doesn\'t need to wait for, like logging, analytics, or cache warming. Implement the "terminate" method on your middleware: "public function terminate(Request $request, Response $response): void { // Log request or send analytics }". Laravel calls terminate after the response is sent to the client, freeing the server to handle the next request. However, terminate requires the middleware to be registered in the global middleware group — it doesn\'t work with route-specific middleware unless explicitly handled. Middleware ordering matters because each middleware can modify the request before passing it downstream. For example, "TrimStrings" should run before "ConvertEmptyStringsToNull" because TrimStrings cleans whitespace first. In Laravel 11, you can set priority in bootstrap/app.php. Lower numbers run earlier in the pipeline. The default priority includes important built-in middleware. If you have middleware that must run before framework middleware (like session handling), you can prepend it. Understanding middleware ordering prevents subtle bugs where early middleware changes the request in unexpected ways.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Terminable Middleware Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is the key characteristic of terminable middleware?',
                            'options' => [
                                0 => 'CS: It runs after the response is sent to the browser',
                                1 => 'CS: It can only be used on POST requests',
                                2 => 'CS: It runs before the controller',
                                3 => 'CS: It terminates the request immediately',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Terminable middleware runs after the HTTP response has been sent, making it ideal for post-response tasks like logging.',
                            'difficulty' => 'medium',
                            'topic' => 'middleware',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Trust Proxies and Middleware vs Route Groups',
                    'content' => 'CS: When Laravel runs behind a load balancer or reverse proxy (like Nginx, HAProxy, or AWS ELB), it needs to trust certain proxies to correctly generate URLs, detect HTTPS, and get the correct client IP. Laravel\'s "TrustProxies" middleware handles this. In Laravel 11, this is configured in bootstrap/app.php: "->withMiddleware(function (Middleware $middleware) { $middleware->trustProxies(at: \'*\', headers: Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO); }")". You can trust specific IPs or all proxies with "*". The headers constant specifies which forwarded headers to trust. Without this configuration, URL generation might produce HTTP instead of HTTPS, and the request IP would be the proxy\'s IP, not the client\'s. Middleware vs route groups: both organize multiple routes, but they serve different purposes. Route groups prefix URIs, apply middleware, and share namespaces. Middleware modifies request/response behavior. Use route groups for structural organization (admin prefix, API prefix) and middleware for cross-cutting concerns (auth, logging, CORS). Combine them: "Route::middleware(\'auth\')->prefix(\'admin\')->group(...)". This applies auth checks to all admin routes while prefixing their URIs. Understanding the distinction helps you choose the right tool for each organizational need.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Trust Proxies and Groups Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are true about trust proxies middleware? (Select all that apply)',
                            'options' => [
                                0 => 'CS: It correctly identifies the client IP behind a proxy',
                                1 => 'CS: It ensures HTTPS URL generation behind a load balancer',
                                2 => 'CS: It is only needed in production environments',
                                3 => 'CS: It can trust all proxies with "*"',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: Trust proxies correctly identifies client IP, generates HTTPS URLs when behind a proxy, and accepts "*" for all proxies. It is useful in any environment behind a proxy.',
                            'difficulty' => 'hard',
                            'topic' => 'middleware',
                        ],
                    ],
                ],
            ],
        ],
        12 => [
            'title' => 'CS: Artisan CLI',
            'slug' => 'cs-artisan-cli',
            'description' => 'CS: Explore the Artisan command-line tool, custom commands, and task scheduling.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: What is Artisan?',
                    'content' => 'CS: Artisan is Laravel\'s command-line interface, providing a vast array of helpful commands for development, maintenance, and deployment. Access it with "php artisan" which lists all available commands. Artisan commands are organized into categories: "make" for generating code, "migrate" for database management, "route" for route inspection, "cache" for cache management, "config" for configuration, "queue" for queue management, and "schedule" for task scheduling. Artisan is built on top of the Symfony Console component, which provides the command structure, input/output handling, and progress bars. You can run "php artisan list" to see all commands, "php artisan help [command]" for detailed help, and "php artisan [command] --option=value" to pass options. The Artisan CLI can be used in production for cache clearing, migrations, queue processing, and maintenance mode. It\'s also invaluable during development for generating boilerplate code, running tests, and interacting with the application via Tinker. Artisan commands return exit codes (0 for success, 1 for error), making them suitable for automated deployment scripts and CI/CD pipelines. The tool is extensible — you can write your own commands and register them in the console kernel. Many first-party packages add their own commands: "php artisan horizon", "php artisan telescope", and so on.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Artisan Overview Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which component does Laravel\'s Artisan CLI build upon?',
                            'options' => [
                                0 => 'CS: Symfony Console',
                                1 => 'CS: Symfony HttpKernel',
                                2 => 'CS: Symfony VarDumper',
                                3 => 'CS: Symfony Process',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Artisan is built on the Symfony Console component, which provides the framework for command definition, input parsing, and output handling.',
                            'difficulty' => 'easy',
                            'topic' => 'artisan',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Make Commands — Code Generation',
                    'content' => 'CS: The "make" commands are among the most frequently used Artisan commands, generating boilerplate code for various Laravel components. Key make commands include: "php artisan make:controller PostController" with options like "--resource", "--api", and "--invokable". "php artisan make:model Post" with "-m" for migration, "-c" for controller, "-f" for factory, "-s" for seeder, and "-a" for all. "php artisan make:migration create_posts_table" creates migration files. "php artisan make:policy PostPolicy" generates authorization policies. "php artisan make:request StorePostRequest" creates form request classes. "php artisan make:rule Uppercase" creates custom validation rules. "php artisan make:job ProcessVideo" creates queueable jobs. "php artisan make:event" and "php artisan make:listener" create event/listener pairs. "php artisan make:mail OrderShipped" creates mail classes. "php artisan make:notification InvoicePaid" creates notifications. "php artisan make:command SendEmails" creates custom Artisan commands. "php artisan make:factory PostFactory" generates model factories. "php artisan make:seeder PostSeeder" creates database seeders. "php artisan make:middleware LogRequests" creates middleware. "php artisan make:component Alert" creates Blade components. Each command generates a skeleton file with the correct namespace and class structure, saving significant development time.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Make Commands Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which flags can be combined with php artisan make:model? (Select all that apply)',
                            'options' => [
                                0 => 'CS: -m (migration)',
                                1 => 'CS: -c (controller)',
                                2 => 'CS: -f (factory)',
                                3 => 'CS: -t (test)',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: The -m, -c, and -f flags create a migration, controller, and factory alongside the model. The -t flag is not a valid make:model option.',
                            'difficulty' => 'easy',
                            'topic' => 'artisan',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Database and Route Artisan Commands',
                    'content' => 'CS: Artisan provides comprehensive commands for database management. The "php artisan migrate" family includes "migrate:fresh" (drop all tables and re-run all migrations), "migrate:rollback" (undo the last batch), "migrate:status" (view migration status), and "migrate:refresh" (rollback and restart). The "php artisan db:seed" command runs database seeders, with "--class" to specify a specific seeder. "php artisan db:wipe" drops all tables without running migrations. For route inspection, "php artisan route:list" displays all registered routes with their methods, URIs, names, and middleware. Filter routes with "--method=GET", "--name=users", "--path=api", "--except-vendor", or "--only-vendor". "php artisan route:cache" serializes your route definitions for faster loading in production. "php artisan route:clear" clears the route cache. "php artisan route:list --json" outputs routes as JSON for tooling integration. Additional useful commands include "php artisan config:cache" and "php artisan config:clear" for configuration caching, "php artisan make:migration" with "--table=existing_table" to generate a migration for modifying an existing table, and "php artisan schema:dump" to create a database schema dump file. The "php artisan db:show" and "php artisan db:table" commands provide database inspection capabilities directly from the command line.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Database and Route Commands Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which command displays all registered routes with their URIs and methods?',
                            'options' => [
                                0 => 'CS: php artisan route:list',
                                1 => 'CS: php artisan route:show',
                                2 => 'CS: php artisan route:display',
                                3 => 'CS: php artisan route:index',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: php artisan route:list displays all registered routes with columns for method, URI, name, action, and middleware.',
                            'difficulty' => 'easy',
                            'topic' => 'artisan',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Cache Commands and Custom Artisan Commands',
                    'content' => 'CS: Laravel\'s cache commands optimize application performance by caching various components. "php artisan config:cache" combines all configuration files into a single cached file, dramatically reducing the time spent loading config on each request. "php artisan route:cache" caches route registration. "php artisan view:cache" precompiles all Blade templates. "php artisan events:cache" caches event discovery. When deploying, run these commands sequentially to optimize the application. Clear individual caches with "config:clear", "route:clear", "view:clear", and "events:clear". "php artisan optimize" runs all cache commands at once, while "php artisan optimize:clear" clears all caches. For custom commands, create them with "php artisan make:command SendInvoices". The generated class defines the command signature (name and arguments) in the "$signature" property, and the command description in "$description". The "handle" method contains the command logic: "public function handle(): int { $this->info(\'Invoices sent!\'); return Command::SUCCESS; }". Use "$this->argument(\'name\')" for arguments, "$this->option(\'name\')" for options, and output methods like "info", "error", "warn", "line", "table", "ask", "confirm", "anticipate", "choice", and "withProgressBar" for interactive input and formatted output. Custom commands are registered automatically when they exist in the app/Console/Commands directory.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Custom Commands Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which property defines the command name and arguments in a custom Artisan command?',
                            'options' => [
                                0 => 'CS: $signature',
                                1 => 'CS: $name',
                                2 => 'CS: $command',
                                3 => 'CS: $definition',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The $signature property defines the command name, arguments, and options in a string format like "command:name {argument} {--option}".',
                            'difficulty' => 'medium',
                            'topic' => 'artisan',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Task Scheduling and Tinker',
                    'content' => 'CS: Laravel\'s task scheduler provides a fluent, expressive way to define scheduled tasks. Instead of creating a cron entry for every task, you define your schedule in the "schedule" method of the App\\Console\\Kernel class: "protected function schedule(Schedule $schedule): void { $schedule->command(\'emails:send\')->daily(); $schedule->job(ProcessPodcast::class)->everyMinute()->withoutOverlapping(); $schedule->exec(\'node /home/forge/script.js\')->weekly()->environments([\'production\']); }". The scheduler provides many frequency methods: "->cron(\'* * * * *\')", "->everyMinute()", "->everyFiveMinutes()", "->hourly()", "->daily()", "->dailyAt(\'13:00\')", "->twiceDaily(1, 13)", "->weekly()", "->monthly()", "->weekdays()", "->weekends()", "->sundays()", "->mondays()", and more. You can chain constraints: "->between(\'7:00\', \'22:00\')" or "->when(function () { return true; })". Run the scheduler with a single cron entry: "* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1". Tinker is an interactive REPL that lets you interact with your Laravel application: "php artisan tinker". Inside Tinker, you can run any PHP code in the context of your application: "User::count()", "Cache::put(\'key\', \'value\', 3600)", or "factory(User::class)->count(10)->create()". Tinker uses PsySH under the hood and provides a great way to test code, inspect models, and debug issues without writing test files or temporary routes. It\'s an essential tool for development and debugging.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Task Scheduling Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are valid scheduler frequency methods? (Select all that apply)',
                            'options' => [
                                0 => 'CS: daily()',
                                1 => 'CS: everyHour()',
                                2 => 'CS: everyFiveMinutes()',
                                3 => 'CS: weekly()',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 2,
                                2 => 3,
                            ],
                            'explanation' => 'CS: daily(), everyFiveMinutes(), and weekly() are valid scheduler methods. The correct method for hourly is hourly(), not everyHour().',
                            'difficulty' => 'medium',
                            'topic' => 'artisan',
                        ],
                    ],
                ],
            ],
        ],
        13 => [
            'title' => 'CS: Testing Basics',
            'slug' => 'cs-testing-basics',
            'description' => 'CS: Learn testing fundamentals in Laravel, including Pest, HTTP tests, database testing, and TDD.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Why Test Your Laravel Application?',
                    'content' => 'CS: Testing is an essential practice in software development that ensures your application works correctly and continues to work as it evolves. Laravel is built with testing in mind, providing a robust testing suite out of the box using PHPUnit with Pest as a first-class option. Testing catches bugs before they reach production, documents expected behavior, and gives developers confidence to refactor code without fear of breaking things. Laravel supports several types of tests: unit tests (test individual methods or classes), feature tests (test larger portions of the system, including HTTP requests and database interactions), browser tests (test JavaScript-heavy interfaces using Laravel Dusk), and Livewire tests (test Livewire component behavior). The "php artisan test" command runs all tests, with options for parallel execution, filtering, and coverage reports. Testing is not an afterthought in Laravel — the framework provides helper methods for simulating HTTP requests, working with databases, authenticating users, and asserting response content. Laravel\'s testing philosophy encourages writing tests that mirror how real users interact with your application, focusing on features rather than implementation details. This makes tests more resilient to refactoring and more valuable for long-term project maintenance.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Why Test Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which command runs all tests in a Laravel application?',
                            'options' => [
                                0 => 'CS: php artisan test',
                                1 => 'CS: php artisan test:run',
                                2 => 'CS: php artisan tests',
                                3 => 'CS: php artisan phpunit',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: \'php artisan test\' runs all tests defined in the tests/ directory.',
                            'difficulty' => 'easy',
                            'topic' => 'testing',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Types of Tests: Unit, Feature, and Browser',
                    'content' => 'CS: Laravel categorizes tests into different types, each serving a specific purpose. Unit tests focus on a small, isolated piece of code — typically a single method or class. They are fast and help verify that individual components work correctly in isolation. Feature tests test larger portions of the system, often simulating HTTP requests and verifying the full request-response cycle. Feature tests typically use Laravel\'s "actingAs" method to authenticate users, "get" and "post" to simulate HTTP requests, and assertions like "assertOk", "assertSee", and "assertRedirect" to verify responses. Database testing helpers include "assertDatabaseHas", "assertDatabaseMissing", and "assertSoftDeleted" to verify database state. Browser tests using Laravel Dusk drive a real browser to test JavaScript-heavy features, clicking buttons, filling forms, and asserting visible content. For Livewire applications, Livewire testing helpers provide methods like "call", "set", "assertSee", "assertDispatched", and "assertHasErrors" to test component behavior without a browser. The recommended approach in Laravel is to write mostly feature tests (which test real user workflows) with a smaller number of unit tests for complex business logic. This gives the best balance of confidence and test maintenance overhead.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Test Types Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are types of tests supported by Laravel? (Select all that apply)',
                            'options' => [
                                0 => 'CS: Feature tests',
                                1 => 'CS: Unit tests',
                                2 => 'CS: Integration tests',
                                3 => 'CS: Livewire component tests',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: Laravel supports feature tests, unit tests, and Livewire component tests. "Integration tests" is not a distinct Laravel test category (feature tests serve that role).',
                            'difficulty' => 'medium',
                            'topic' => 'testing',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: HTTP Tests in Laravel',
                    'content' => 'CS: Laravel provides a fluent API for testing HTTP requests and responses. The "get" method simulates a GET request: "$response = $this->get(\'/\')". The "post" method sends POST data: "$response = $this->post(\'/posts\', [\'title\' => \'New Post\'])"). Available HTTP methods include "get", "post", "put", "patch", "delete", and "options". You can set headers with "withHeaders", authenticate with "actingAs", and disable middleware with "withoutMiddleware". Response assertions include: "$response->assertOk()" (200 status), "$response->assertStatus(201)" (custom status), "$response->assertRedirect(\'/dashboard\')", "$response->assertSee(\'Welcome\')", "$response->assertDontSee(\'Error\')", "$response->assertSeeInOrder([\'First\', \'Second\'])", "$response->assertSessionHas(\'key\')", "$response->assertSessionHasErrors([\'email\'])", and "$response->assertJson([\'name\' => \'John\'])"). For JSON APIs, use "$this->getJson(\'/api/users\')" and "$this->postJson(\'/api/users\', [...])". JSON assertions include "assertJsonFragment", "assertJsonStructure", "assertJsonCount", and "assertJsonPath". Session and cookie assertions: "assertSessionHasNoErrors", "assertSessionMissing", "assertCookie", and "assertPlainCookie". These HTTP test helpers make it simple to write comprehensive tests that verify every aspect of your application\'s HTTP interface.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: HTTP Tests Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which assertion checks that the response has a 200 status code?',
                            'options' => [
                                0 => 'CS: assertOk',
                                1 => 'CS: assertStatus(200)',
                                2 => 'CS: assertSuccess',
                                3 => 'CS: assertSuccessful',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: assertOk() is the most readable assertion for checking a 200 status code. assertStatus(200) also works but is more verbose.',
                            'difficulty' => 'easy',
                            'topic' => 'testing',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Database Testing and Model Factories',
                    'content' => 'CS: Laravel provides powerful tools for testing database interactions. The "RefreshDatabase" trait automatically migrates the database before each test and rolls back changes after, ensuring test isolation without manually cleaning up. Alternatively, "DatabaseTransactions" wraps each test in a database transaction for faster execution. Model factories generate test data with sensible defaults: "User::factory()->create()" creates and persists a user, while "User::factory()->make()" returns an unsaved instance. Factory states define variations: "User::factory()->unverified()->create()". Factories can define relationships: "Post::factory()->for(User::factory())->create()", or use the "has" method: "User::factory()->hasPosts(3)->create()". Seeder integration: "$this->seed(DatabaseSeeder::class)" runs seeders during tests. Database assertions verify database state: "$this->assertDatabaseHas(\'users\', [\'email\' => \'john@example.com\'])" and "$this->assertDatabaseMissing(\'users\', [\'email\' => \'deleted@example.com\'])", "$this->assertSoftDeleted(\'users\', [\'id\' => 1])", and "$this->assertModelExists($user)". The "assertDatabaseCount" helper checks record counts: "$this->assertDatabaseCount(\'users\', 5)". These tools make database testing reliable, fast, and expressive. The RefreshDatabase trait combined with factories provides the foundation for comprehensive feature tests that verify real database state.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Database Testing Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are valid database assertions? (Select all that apply)',
                            'options' => [
                                0 => 'CS: assertDatabaseHas',
                                1 => 'CS: assertDatabaseMissing',
                                2 => 'CS: assertRecordExists',
                                3 => 'CS: assertSoftDeleted',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: assertDatabaseHas, assertDatabaseMissing, and assertSoftDeleted are valid assertions. assertRecordExists is not a Laravel assertion.',
                            'difficulty' => 'medium',
                            'topic' => 'testing',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Livewire Testing',
                    'content' => 'CS: Livewire provides a comprehensive testing API for testing component behavior without a browser. Use "Livewire::test(ComponentClass::class)" to instantiate a component for testing. Set properties with "->set(\'title\', \'Hello\')" and call actions with "->call(\'save\')". Assertions include "->assertSee(\'Hello\')" (check rendered output), "->assertSet(\'title\', \'Hello\')" (check property value), "->assertDispatched(\'post-created\')" (check dispatched events), "->assertHasErrors([\'title\' => \'required\'])" (check validation), "->assertNoErrors()" (no validation errors), "->assertRedirect(\'/posts\')" (check redirect), and "->assertMethodIsPublic(\'save\')". Test authenticated components with "->actingAs($user)". Test component method calls with parameters: "->call(\'deletePost\', $postId)". Test file uploads: "->set(\'photo\', UploadedFile::fake()->image(\'photo.jpg\'))". For Volt components, use "Livewire::test(\'posts.create\')" with the Volt component path. Livewire testing also supports "assertSet" for checking property values after actions, "assertPayloadSent" for Livewire-specific payload testing, and "assertEmitting" for event emission. You can test validation in real-time by setting properties and calling "validate": "->set(\'title\', \'\')->call(\'save\')->assertHasErrors([\'title\' => \'required\'])", Livewire testing is fast because it runs entirely in PHP without JavaScript, making it ideal for CI/CD pipelines and rapid feedback during development.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Livewire Testing Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which method instantiates a Livewire component for testing?',
                            'options' => [
                                0 => 'CS: Livewire::test()',
                                1 => 'CS: Livewire::render()',
                                2 => 'CS: Livewire::mount()',
                                3 => 'CS: Livewire::component()',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Livewire::test(Component::class) creates a testable Livewire component instance with methods like set(), call(), and assertSee().',
                            'difficulty' => 'medium',
                            'topic' => 'testing',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
