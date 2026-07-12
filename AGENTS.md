# Laravel With Jiri — AGENTS.md

E-learning platform. Laravel 13 + Livewire + Tailwind + Pest, scaffolded with Laravel Boost.

## MANDATORY SESSION WORKFLOW

**Every session** follows this exact sequence — do not skip, shorten, or reorder steps:

1. **`application-info`** — run at session start, record versions below
2. **`database-schema`** — inspect ALL tables relevant to the task *before* writing migrations, models, queries, or admin CRUD
3. **`search-docs`** — run *before every code change* on the relevant package(s). `packages` filter is required. Broad, topic-based queries (e.g. `["Livewire full-page components", "route model binding", "nested component parameters"]`)
4. **TDD cycle — Red-Green-Refactor.** This is strict and non-negotiable:
   - **Red** — write a failing test that describes the desired behaviour *before* any implementation code. Run it to confirm it fails (error dot or failure output). If no test changes, no code changes.
   - **Green** — write the *minimum* implementation code to make the test pass. Do not add extra features, refactor, or beautify during this step.
   - **Refactor** — clean up the implementation: rename variables, extract methods, remove duplication, add type hints. The test must still pass after every refactoring step. Run the test suite to confirm.
5. **Run affected tests** after every code change (Green and Refactor each complete with a test run)
6. **Rector** → **Duster fix** → **Larastan** before commit (run in that order to avoid fix conflicts)
7. **Manual verification** — start dev server + Vite, navigate to the feature in a real browser, verify the runtime behaviour. "I started the server" is NOT a smoke test.

## Smoke test requirements

A smoke test is not complete until ALL of:
- Dev server is reachable via `curl http://127.0.0.1:PORT` or browser navigation
- For frontend features (Monaco, WASM, Alpine): the page loads without console errors, the JS runtime initialises, and the expected interaction flow completes (click button → see output → wire call succeeds)
- For CRUD features: create, edit, delete are exercised via the actual HTTP routes, not just Livewire test assertions
- Record any errors found and fix them before moving on

**Login note**: Breeze's login form uses Livewire `wire:submit`, so it sends no plain `<input name="_token">` in the HTML. Curl-based manual login won't work via standard POST. Instead, use one of:
  - Run `SmokeTest.php` via Pest (it does real HTTP with `$this->actingAs()`)
  - Open in a real browser for manual testing
  - `npm run build` first (Vite must be built or running)

## Stack
- **Framework:** Laravel (latest), scaffolded with Laravel Boost
- **Frontend:** Livewire + Tailwind CSS + Alpine.js
- **Code quality:** Laravel Pint + Duster (TLint, PHP_CodeSniffer, PHP-CS-Fixer)
- **Testing:** Pest (every model, policy, Livewire component, significant service)
- **Database:** PostgreSQL
- **Auth:** Laravel Boost built-in auth + roles (`admin`, `instructor`, `student` via string/enum column)

## Domain
- **Course** → **Lesson** → **Step** (all with `published` boolean + `order` int)
- Step types: `reading`, `quiz_single`, `quiz_multiple`, `quiz_text`, `coding`
- **StepCompletion**: `user_id`, `step_id`, `completed_at` (unique pair)
- **StepAnswer**: `user_id`, `step_id`, `answer`, `is_correct` (unique per pair) — quiz submissions
- Lesson complete = all steps done; Course progress = completed / total steps

## Architecture
- Thin controllers; business logic in actions or service classes
- Livewire full-page components for views, nested for interactive pieces
- Livewire inline validation (`$this->validate()` / `#[Validate]` / `validationRules()`) for user input; policies for authorization
- Factories for all test data; `sequence()` for steps with unique order constraints
- Eager-load to avoid N+1
- Queue non-trivial side effects as jobs
- Coding steps run PHP code in a WASM sandbox (php-wasm) — no network, no filesystem, no system calls. Safe for student-submitted code execution within the browser.

## Lessons learned

See [LESSONS.md](./LESSONS.md) for patterns and operational knowledge accumulated during development.

## Out of scope
Payments, video, comments, email notifications, API endpoints.

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.3
- laravel/framework (LARAVEL) - v13
- laravel/prompts (PROMPTS) - v0
- livewire/livewire (LIVEWIRE) - v3
- livewire/volt (VOLT) - v1
- laravel/boost (BOOST) - v2
- laravel/breeze (BREEZE) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- tightenco/duster (DUSTER) - v3
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- tailwindcss (TAILWINDCSS) - v3

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Tool Timeouts (IMPORTANT)

The sandbox environment has constrained resources. Always use generous bash tool timeouts:

| Tool | Minimum timeout | Notes |
|------|----------------|-------|
| `php artisan test --compact` | 300 000 ms | Full suite can take 120s+ |
| `phpstan analyse` | 600 000 ms | Larastan can take 300s+ |
| `vendor/bin/rector process` | 600 000 ms | Internal parallel processes may time out. If so, run on specific files only: `vendor/bin/rector process --dry-run --no-progress-bar --memory-limit=-1 path/to/file.php` |
| `vendor/bin/duster lint` | 300 000 ms | Runs TLint + PHP_CodeSniffer + PHP-CS-Fixer + Pint. Slower than Pint alone |
| `vendor/bin/duster fix` | 300 000 ms | Auto-fixes all fixable issues across bundled tools |
| `vendor/bin/pint` | 60 000 ms | Usually fast, but run after other tools |

**Rector caveat**: Rector's `ParallelProcess` times out at 120s internally on this environment. If the whole-codebase run fails, run it per-file on changed files instead.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## MCP Tools (invocation)

Boost exposes 9 tools via the MCP protocol. These are NOT native shell commands — invoke them by piping a JSON-RPC request to `php artisan boost:mcp`:

```bash
echo '{"jsonrpc":"2.0","id":1,"method":"tools/call","params":{"name":"<tool>","arguments":{...}}}' | php artisan boost:mcp
```

At the start of every session, run `application-info` to get precise package versions, then `search-docs` before every code change. Available tools:

| Tool | When to use | Example arguments |
|------|-------------|-------------------|
| `application-info` | **Every session start** — get PHP/Laravel version + all package versions | `{}` |
| `search-docs` | **Before every code change** — search version-specific docs for any Laravel-ecosystem package | `{"queries":["Livewire components","form validation"],"packages":["livewire/livewire"]}` |
| `database-schema` | Before writing migrations or models — inspect table structure | `{"summary":true}` or `{"filter":"courses","include_column_details":true}` |
| `database-query` | Read-only SQL queries instead of tinker | `{"query":"SELECT * FROM courses WHERE published = 1"}` |
| `database-connections` | List configured database connections | `{}` |
| `browser-logs` | Debug frontend/JS issues | `{"entries":10}` |
| `get-absolute-url` | Resolve routes to absolute URLs before sharing | `{"route":"courses.index"}` or `{"path":"/courses"}` |
| `read-log-entries` | Read backend application logs | `{"entries":20}` |
| `last-error` | Get the last backend exception details | `{}` |

**Hard rule**: do NOT skip `search-docs` before writing any Laravel/Livewire/Pest code. Use `application-info` first to confirm package versions, then `search-docs` with relevant `packages` filter before the first code change of any session.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== tests rules ===

# Test Enforcement

- **Strict TDD is mandatory**: Red → Green → Refactor in that order. Never write implementation before a failing test. Never refactor before all tests pass.
- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== livewire/core rules ===

# Livewire

- Livewire allow to build dynamic, reactive interfaces in PHP without writing JavaScript.
- You can use Alpine.js for client-side interactions instead of JavaScript frameworks.
- Keep state server-side so the UI reflects it. Validate and authorize in actions as you would in HTTP requests.

=== volt/core rules ===

# Livewire Volt

- Single-file Livewire components: PHP logic and Blade templates in one file.
- Always check existing Volt components to determine functional vs class-based style.
- IMPORTANT: Always use `search-docs` tool for version-specific Volt documentation and updated code examples.
- IMPORTANT: Activate `volt-development` every time you're working with a Volt or single-file component-related task.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.
- Shortcut: `composer run pint`

=== duster/core rules ===

# Duster (Tighten Code Quality)

Duster bundles TLint, PHP_CodeSniffer, PHP-CS-Fixer, and Pint into a single command.

## Commands

- `./vendor/bin/duster lint` — reports all style issues without modifying files
- `./vendor/bin/duster fix` — automatically fixes fixable issues across all four tools
- `./vendor/bin/duster lint --dirty` — only checks files with uncommitted changes
- `./vendor/bin/duster fix --dirty` — only fixes files with uncommitted changes

## Workflow

Run Duster **after Rector** and **before Larastan** in the pre-commit pipeline to avoid fix conflicts. `duster fix` handles everything that PHP-CS-Fixer, TLint, PHP_CodeSniffer, and Pint can fix in a single pass.

## Configuration

If you need Duster-specific configuration, add a `duster.json` to the project root:

```json
{
    "scripts": {
        "lint": {
            "phpstan": ["./vendor/bin/phpstan", "analyse"]
        }
    }
}
```

This also lets you add custom scripts (like PHPStan) to the `duster lint` or `duster fix` pipeline.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- The `{name}` argument should not include the test suite directory. Use `php artisan make:test --pest SomeFeatureTest` instead of `php artisan make:test --pest Feature/SomeFeatureTest`.
- Run tests: `php artisan test --parallel --processes=1` (fastest — uses WrapperRunner, ~28% faster than sequential). Filter: `php artisan test --parallel --processes=1 --filter=testName`.
- Do NOT add `parallel="true"` to `phpunit.xml` testsuites — the `--parallel` flag handles it.
- Do NOT delete tests without approval.

</laravel-boost-guidelines>
