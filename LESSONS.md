# Lessons Learned

Accumulated patterns and operational knowledge from developing this application.

---

## Critical Context

Key invariants and gotchas that apply across the codebase. Read this first when onboarding.

### Symfony YAML parser is safe by default

`Symfony\Component\Yaml\Yaml::parse()` does **not** support `!php/object` or any PHP-object tags without explicitly passing `Yaml::PARSE_CUSTOM_TAGS` as the second argument. No sandboxing needed for untrusted YAML — the parser throws a `ParseException` on object-tagged input by default. Still validate structure and depth separately (YAML bomb detection).

### Duster bundles all four code-style tools

`tightenco/duster` runs **TLint + PHP_CodeSniffer + PHP-CS-Fixer + Pint** in a single command. Use `./vendor/bin/duster fix` for auto-fixing and `./vendor/bin/duster lint` for reporting. The pre-commit pipeline is `Rector → Duster fix → Larastan` — Duster replaces standalone Pint invocations. `--dirty` flag limits to changed files.

### Queue driver is `database`

The `QUEUE_CONNECTION` is set to `database`. The `jobs`, `job_batches`, and `failed_jobs` tables exist. No Redis or other in-memory queue. Non-trivial side effects should be dispatched as jobs, not run synchronously.

### `unlocked_at` is a one-way ratchet

The `unlocked_at` column on `step_completions` is set once and **never cleared** — it's not reset on re-ordering or content changes. It represents the earliest moment the next step was unlocked by this user. Used in conjunction with `completed_at` to determine step accessibility.

### Idempotent import matches by title, not slug

`course:import` and `lesson:import` identify existing records by **title**, not slug. Re-running the same YAML produces no duplicates — the action finds the record by title and skips creation. Manual title changes between runs create new records.

### Slugs are auto-generated with collision resolution

All slugs are derived from titles via `Str::slug($title)`. Never required in YAML. If the slug collides with an existing record, a 5-character random hex string is appended; if that still collides, longer random hex strings are tried until unique. The collision check queries only the relevant model table.

### Conventional Commits for git history

All commits follow the [Conventional Commits](https://www.conventionalcommits.org/) format: `type(scope): description` (e.g., `feat: add REPL tab`, `fix(policies): correct instructor ownership check`). Types: `feat`, `fix`, `refactor`, `test`, `docs`, `chore`. Scopes are optional, lowercase.

### Strict TDD order

**Red → Green → Refactor** is non-negotiable for every code change:
1. Write a failing test before any implementation
2. Write the minimum implementation to pass
3. Clean up without adding features
4. Run tests after every step

### localStorage for Monaco editor state

Monaco editor content is persisted to `localStorage` on every change event (`editor.onDidChangeModelContent`). Default: `<?php\n\n`. Key: `repl-code`. No server-side persistence — the editor is client-side only. Applies to the REPL tab; coding-viewer steps may use their own keys.

### PHP runs in-browser only, never server-side

The REPL tab and coding-step viewer execute PHP **entirely in the browser** via `php-wasm` (WebAssembly). No code is sent to the server. The WASM binary is loaded from CDN (`https://cdn.jsdelivr.net/npm/php-wasm/PhpWeb.mjs`). Listen for `'ready'` event before running; capture output via `'output'` event (not `run()` return value).

### `firstOrCreate` over try-catch for idempotence

Prefer `Model::firstOrCreate(uniqueAttrs, extraAttrs)` over try-catch + `QueryException` for duplicate-safe inserts. No driver-specific SQLSTATE matching, no fragile string parsing. Used in `CourseEnrollment`, `StepCompletion`, `StepAnswer` creation.

---

## Ordered swap (unique constraint safe)

When swapping two rows that share a unique constraint on `[parent_id, order]`, a direct assignment swap violates the constraint. Use a 3-step temp value approach:

```php
$previous->update(['order' => -1]);
$current->update(['order' => $previousOrder]);
$previous->update(['order' => $currentOrder]);
```

Used in `moveUp()`/`moveDown()` on all three admin list components.

---

## Progress lookup map

To display per-item progress in Livewire list views, inject `ProgressService` via method DI and compute a lookup map (e.g. `courseId → float`, `lessonId → bool`, `stepId → bool`) in `mount()` or `render()`:

```php
public function mount(ProgressService $progress): void
{
    $this->progressData = $courses->mapWithKeys(fn ($c) => [$c->id => $progress->courseProgress(auth()->user(), $c)]);
}
```

---

## PHPStan `missingType.iterableValue` on validation arrays

Laravel's `validationRules()` returns `array`. PHPStan level 6 requires `@return array<string, string>` on methods that return associative string→string arrays. Add the PHPDoc annotation above the method signature.

---

## Larastan invocation on this environment

`vendor/bin/phpstan analyse -c phpstan.neon --no-progress` can exit silently on PHP 8.3 here. Use the PHAR with `--debug` when running manually:

```bash
php -d memory_limit=-1 vendor/phpstan/phpstan/phpstan.phar analyse -c phpstan.neon --debug
```

---

## Race-safe writes via unique constraint

Instead of check-then-act (`exists()` then `create()`), wrap the `create()` in a try-catch for `QueryException`. The DB unique constraint provides atomicity — only one INSERT wins, the other gracefully returns the existing state:

```php
try {
    StepCompletion::create([...]);
    return true;
} catch (QueryException) {
    return false; // another request won the race
}
```

Used in `MarkStepComplete` and `SubmitQuizAnswer`.

---

## Auto-assign order on create

Instead of requiring manual order input (which risks duplicate unique-key violations), auto-assign on `mount()`:

```php
$this->order = ($this->lesson->steps()->max('order') ?? -1) + 1;
```

Reordering is handled exclusively via `moveUp()`/`moveDown()` on the list page.

---

## Batch progress lookup

To eliminate N+1 from per-item progress calls, add batch methods to `ProgressService` that compute progress for all items in 2 queries total:

```php
$progress->courseProgressBatch($user, $courses);  // [courseId => float]
$progress->lessonCompleteBatch($user, $lessons);  // [lessonId => bool]
```

---

## Min search length guard

Every `scopeSearch` should bail early for terms under 2 characters to prevent full table scans on single-character `LIKE` queries:

```php
if (strlen($term) < 2) {
    return $query;
}
```

---

## Stale autoloader cache after file changes

After `git stash pop` or modifying PHP files outside of normal editing, the full test suite (`php artisan test`) can hang silently (no output, exit 1). This is caused by the optimized Composer autoloader caching a stale class map. Fix: run `composer dump-autoload` or `git stash && git stash pop` to force a fresh class resolution. A single-file test (`--filter=SingleTest`) still works; the hang only manifests when loading the full test suite.

---

## Enrollment guard via shared trait

For Livewire components that require enrollment, create a `Concerns/EnsuresEnrollment` trait with a single `ensureEnrolled(Course $course): void` method that checks `CourseEnrollment::where(...)->exists()` and calls `abort(404)` if not enrolled. Apply with `use EnsuresEnrollment` in the component class. Used in `CourseDetail`, `LessonDetail`, `StepViewer`.

---

## Enroll from list page, not detail page

Since course detail 404s for unenrolled users, the Enroll button lives on the course list page (`CourseList`). Enrollment happens via a dedicated POST route (`/enroll/{course}`) using a closure controller, not a Livewire action:

```php
Route::post('/enroll/{course}', function (Course $course) {
    app(EnrollInCourse::class)->handle(auth()->user(), $course);
    return redirect()->route('courses.show', $course);
})->name('courses.enroll');
```

---

## Route closure type-hints need a `use` import

When using implicit route model binding inside a route closure (`function (Course $course)`), the `Course` class must be imported at the top of `routes/web.php` with `use App\Models\Course;`. Without it, PHP's reflection cannot resolve the type-hint and throws `ReflectionException: Class "Course" does not exist`.

---

## Admin stat cards via direct queries

For a simple admin overview (total users, courses, lessons), query aggregate counts directly in the Livewire component's `render()` method — no service class needed:

```php
public function render(): View
{
    return view('livewire.admin-dashboard', [
        'totalUsers' => User::count(),
        'totalCourses' => Course::count(),
    ]);
}
```

---

## Role toggle via Alpine dropdown

For inline user role management, use an Alpine.js dropdown per table row with `wire:click` handlers for each role option. Prevent self-demotion with `auth()->user()->is($user)`:

```php
<button wire:click="updateRole({{ $user->id }}, 'admin')">Admin</button>
```

---

## Conditional admin nav links

Show admin nav items via simple boolean checks in the navigation Blade view, not a Gate/policy:

```blade
@if (auth()->user()?->isAdmin())
    <a href="{{ route('admin.dashboard') }}">Admin</a>
@endif
```

---

## Monaco via Alpine.js conditional load

Load Monaco editor from CDN only when the step type is "coding". Use Alpine.js `x-init` on a `<div>` after the type dropdown changes to initialize the editor. Sync content back to a hidden input or Livewire property before form submission. Example:

```blade
<div x-data="{ showCoding: $wire.type === 'coding' }" x-init="$watch('$wire.type', v => showCoding = v === 'coding')">
    <div x-show="showCoding" x-init="editor = monaco.editor.create($el, { value: $wire.initialCode })"></div>
</div>
```

---

## Separate coding fields → JSON on save

Coding step fields (`prompt`, `initial_code`, `test_code`, `expected_output`) are separate Livewire properties, not a single JSON string. Serialize to JSON only in the save action:

```php
public function save(): void
{
    $this->form->content = json_encode([
        'prompt' => $this->prompt,
        'initial_code' => $this->initialCode,
        'test_code' => $this->testCode,
        'expected_output' => $this->expectedOutput,
    ]);
    $this->form->save();
}
```

---

## Conditional validation rules with `@return` PHPDoc

When adding conditional validation based on step type, use `match()` and annotate the return type for PHPStan level 6:

```php
/** @return array<string, string> */
public function validationRules(): array
{
    return match ($this->type) {
        StepType::Coding => [
            'prompt' => 'required|string',
            'initialCode' => 'nullable|string',
            'testCode' => 'nullable|string',
            'expectedOutput' => 'nullable|string',
        ],
        default => ['content' => 'required|string'],
    };
}
```

---

## Monaco AMD loader is a singleton

When loading Monaco editor from CDN for multiple editors on the same page, a single `import()` + `require.config()` + `require()` call must create all editors. Two independent `x-init` blocks each calling `import('loader.js')` and `require.config()` fail silently on the second call because AMD `require` is a singleton. Consolidate into one `x-init` using `x-ref` targets:

```blade
<div x-data="{}" x-init="
    import('https://cdn.jsdelivr.net/npm/monaco-editor@0.55.1/min/vs/loader.js').then(() => {
        require.config({ paths: { vs: '...' } });
        require(['vs/editor/editor.main'], (monaco) => {
            monaco.editor.create($refs.editorOne, { ... });
            monaco.editor.create($refs.editorTwo, { ... });
        });
    });
">
    <div x-ref="editorOne" style="..."></div>
    <div x-ref="editorTwo" style="..."></div>
</div>
```

---

## Testing `abort()` in Livewire components

`Livewire::test()` wraps the component and catches Symfony `HttpException` internally. To test that a component's `mount()` or action `abort()`s with a specific status code, instantiate the component directly and call methods manually:

```php
$component = new MyComponent;
$component->course = $course;
$component->step = $step;

try {
    $component->mount($course, $lesson, $step);
    $this->fail('Expected abort.');
} catch (HttpException $e) {
    $this->assertSame(404, $e->getStatusCode());
}
```

---

## NOT NULL constraint with `$fillable` removal

When removing a column from `$fillable`, the column's database constraints still apply. If the column is NOT NULL with no default, Eloquent's mass-assignment filters the field out, triggering a constraint violation on `create()`. Solutions:

- Set the attribute directly on the model instance before saving (trusted code path)
- Use `afterMaking()` hook in factories (fires before `save()`, unlike `afterCreating()` which fires after)

```php
// Direct attribute assignment (trusted action code)
$answer = new StepAnswer;
$answer->is_correct = $isCorrect;
$answer->save();

// Factory afterMaking hook (test data)
public function configure(): static
{
    return $this->afterMaking(function (StepAnswer $answer) {
        if (! isset($answer->is_correct)) {
            $answer->is_correct = fake()->boolean();
        }
    });
}
```

---

## Test ordering with SQLite in-memory database

Tests that pass in isolation may fail when run as part of the full test suite due to SQLite in-memory database state leakage between tests. This is especially likely when tests from different files interact through shared database state. Use `--filter=` to confirm isolation before debugging ordering-dependent failures.

---

## Tool timeouts on this environment

The sandbox environment has constrained resources. Always use generous timeouts:
- `php artisan test --compact`: 300 000 ms (300s) minimum
- `phpstan analyse`: 600 000 ms (600s) minimum
- `vendor/bin/rector process`: Rector's internal parallel processes time out at 120s. Run on specific files (`vendor/bin/rector process --dry-run --no-progress-bar --memory-limit=-1 path/to/file.php`) rather than the whole codebase to avoid its `ParallelProcess` timeout.

---

## Factory defaults must be self-consistent

When a factory's default definition randomises a property (e.g. `type`) that changes validation rules, the other default values must satisfy ALL possible outcomes. `StepFactory` randomised `type` across `Reading`, `Quiz`, `Coding` but always used `fake()->paragraphs()` as `content`. When `Coding` was selected, `getContentAsArray()` returned `null` (paragraphs aren't JSON), leaving `prompt` empty, which failed coding validation. Fix: default `type` must always be `Reading` since `content` is plain text, or generate type-appropriate content.

---

## Randomised factory defaults cause flaky tests

When a test relies on a factory's default value without specifying a state, and that default is randomised, the test becomes non-deterministic. `test_instructor_can_edit_step` called `Step::factory()->create(...)` and later used `$step->type->value` for validation. With `Coding` selected (~33%), validation required `prompt` which the test never set. The fix is twofold: (1) make the factory default deterministic for the content type, and (2) use an explicit state (`->reading()`) in the test for clarity and guaranteed determinism.

---

## Parallel testing with SQLite in-memory has diminishing returns

On a 2-core environment with SQLite `:memory:`, parallel testing (`--parallel`) shows limited speedup because each worker process runs migrations independently. Measured results for 371 tests:

| Mode | Time | vs Sequential |
|------|------|---------------|
| Sequential (`php artisan test`) | 233s | baseline |
| `--parallel` (2 processes, default) | 189s | 19% faster |
| **`--parallel --processes=1`** (WrapperRunner) | **167s** | **28% faster** |
| `--parallel --processes=4` | 246s | 6% slower |

The `--parallel --processes=1` setting is the optimal choice — it uses ParaTest's WrapperRunner (reuses PHP processes between test files without needing multiple workers) while avoiding per-worker migration overhead. Run with:

```bash
php artisan test --parallel --processes=1
```

Do NOT add `<testsuites parallel="true">` to `phpunit.xml` — the `--parallel` flag on `php artisan test` handles this correctly, and the XML attribute conflicts with ParaTest's process management.

---

## `php-wasm` initialization: use events, not `_runtime` polling

The `php-wasm` library's `PhpWeb` class (v0.1.0+) does NOT have a `_runtime` property or any polling-friendly readiness indicator. Polling `this.php._runtime` in a `setTimeout` loop **never resolves** — `phpReady` stays `false` and the UI stays stuck on "Loading...".

Instead:
1. Create `new PhpWeb()` — the constructor starts downloading the WASM binary asynchronously. It's safe to call `run()` immediately; calls are queued internally until the binary is ready.
2. Listen for the `'ready'` event to enable UI buttons:
   ```js
   php.addEventListener('ready', () => { this.phpReady = true; });
   ```
3. Capture output via the `'output'` event, not from `run()`'s return value. `run()` returns an exit code (number), not the output text. Register:
   ```js
   php.addEventListener('output', (event) => { this.lastOutput += event.detail; });
   php.addEventListener('error',  (event) => { this.lastOutput += `[PHP Error]: ${event.detail}`; });
   ```
4. The import URL `https://cdn.jsdelivr.net/npm/php-wasm/PhpWeb.mjs` (without `@0.1.0` version pin) resolves to the latest published version.

---

## Delegate single-entity methods to batch methods

When a service has both single-entity and batch methods with duplicated SQL logic, make the single-entity method wrap its input in a `Collection` and delegate to the batch method:

```php
public function courseProgress(User $user, Course $course): float
{
    $results = $this->courseProgressBatch($user, new Collection([$course]));
    return $results[$course->id] ?? 0.0;
}
```

This eliminates duplicated SQL while keeping the single-entity convenience API. Applied to `ProgressService::courseProgress()` and `lessonComplete()`.

---

## Shared ownership check via User model method

When multiple policies repeat the same `$x->user_id === $user->id` pattern, add an `ownsCourse()` method to the `User` model:

```php
public function ownsCourse(Course $course): bool
{
    return $this->id === $course->user_id;
}
```

Then policies read naturally: `$user->isAdmin() || ($user->isInstructor() && $user->ownsCourse($step->lesson->course))`. Eliminates the 3-level `$step->lesson->course->user_id` chain and makes ownership semantics explicit.

---

## FirstOrCreate over try-catch for idempotent creation

Instead of catching `QueryException` and checking SQLSTATE to detect duplicate entries, use Eloquent's `firstOrCreate()` which checks for an existing record first:

```php
CourseEnrollment::firstOrCreate(
    ['user_id' => $user->id, 'course_id' => $course->id],
    ['enrolled_at' => now()],
);
```

No driver-specific logic, no fragile string matching on error messages, no try-catch. Also works for truly idempotent operations where a duplicate should be silently ignored.

---

## Single SQL query beats nested PHP loops

When searching for "the first incomplete step across all enrolled courses", a triple-nested PHP loop doing `$completedIds->contains($step->id)` can be replaced with a single query using JOINs and `whereDoesntHave`:

```php
return Step::query()
    ->where('steps.published', true)
    ->whereHas('lesson', fn ($q) => $q->where('published', true)->whereIn('course_id', $courseIds))
    ->whereDoesntHave('completions', fn ($q) => $q->where('user_id', auth()->id()))
    ->join('lessons', 'steps.lesson_id', '=', 'lessons.id')
    ->join('courses', 'lessons.course_id', '=', 'courses.id')
    ->orderBy('courses.order')->orderBy('lessons.order')->orderBy('steps.order')
    ->select('steps.*')
    ->first();
```

This eliminates N+1 plucks and scales to thousands of steps regardless of how many courses the user is enrolled in.

---

## Middleware naming must match its semantics

`AdminMiddleware` that admits instructors too is misleading. Rename to reflect who it actually allows. The route URL prefix (`/admin`) and the middleware class name are independent — the URL stays as `/admin` for admin panel conventions, the middleware is called `StaffMiddleware`.

```php
// bootstrap/app.php
'staff' => StaffMiddleware::class,

// routes/web.php
Route::middleware(['auth', 'verified', 'staff'])->prefix('admin')->group(function () { ... });
```

---

## Extract locking logic to the owning model

When step-accessibility logic is duplicated between `Step::isAccessibleBy()` and `LessonDetail`, extract it as a method on the `Lesson` model:

```php
public function hasUserCompletedPreviousStep(User $user, Step $step): bool
{
    // single query for previous step completion
}
```

`Step::isAccessibleBy()` then becomes a one-line delegate: `return $this->lesson->hasUserCompletedPreviousStep($user, $this);`. Single source of truth, no duplication.

---

## CDN-served assets don't belong in package.json

If Monaco editor is loaded entirely from CDN (`import()` + `require.config()` pointing to jsdelivr), the `monaco-editor` npm package is dead weight in `node_modules`. Remove it from `dependencies` to keep the lockfile lean. Only keep npm packages that are actually imported by your JS build (Vite-processed imports). CDN assets are managed independently — just keep their version strings in sync manually.

---

## Larastan level 9: `mixed` → string conversion

PHPStan level 9 (via `larastan/larastan` on PHP 8.3) does **not** accept `(string) mixed`, `strval(mixed)`, or `sprintf('%s', mixed)`:

| Expression | Level 9 status |
|------------|----------------|
| `(string) $mixed` | ❌ `cast.string` |
| `strval($mixed)` | ❌ `argument.type` (expects `bool\|float\|GMP\|int\|resource\|string\|null`) |
| `sprintf('%s', $mixed)` | ❌ `argument.type` (expects `bool\|float\|int\|string\|null`) |
| `is_string($mixed) ? $mixed : ''` | ✅ narrows `mixed` → `string` safely |

Use **narrowing guards** instead of casts:

```php
// ❌ (string) mixed — denied at level 9
return (string) ($data['prompt'] ?? '');

// ✅ is_string narrows the type
$val = $data['prompt'] ?? '';
return is_string($val) ? $val : '';
```

---

## Larastan level 9: `(int) mixed` from DB aggregates

Eloquent aggregate methods like `max('order')` and `pluck('total', 'course_id')` return `mixed` at level 9. Guard with `is_numeric()` before casting:

```php
// ❌ (int) mixed — denied
$total = (int) ($totalSteps[$course->id] ?? 0);

// ✅ is_numeric guard
$raw = $totalSteps[$course->id] ?? null;
$total = is_numeric($raw) ? (int) $raw : 0;
```

---

## Larastan level 9: `mixed` offset access in foreach

PHPStan loses element type information when iterating `array<mixed>`. A `foreach ($decoded as $q)` where `$decoded` is an `array` (narrowed from `is_array()`) gives `$q` as `mixed`. Accessing `$q['type']` then triggers `offsetAccess.nonOffsetAccessible`. Fix: add `is_array($q)` guard inside the loop:

```php
foreach ($decoded as $q) {
    if (! is_array($q)) {
        continue;
    }
    // $q is now array — offset access OK
    $type = is_string($q['type'] ?? null) ? $q['type'] : 'single';
}
```

---

## Larastan level 9: `array<mixed>` doesn't match shaped arrays

PHPStan level 9 distinguishes `array<mixed>` from `list<array{key: string}>`. A `json_decode(..., true)` result narrowed via `is_array()` becomes `array<mixed>`, which does **not** satisfy a `list<array{key: string, ...}>` property type. Fix: rebuild each element explicitly, casting each field:

```php
// ❌ array<mixed> ≠ list<array{type: string, options: list<string>, ...}>
$this->questions = is_array($decoded) ? $decoded : [];

// ✅ rebuild with explicit per-field type narrowing
$this->questions = [];
foreach ($decoded as $q) {
    if (! is_array($q)) { continue; }
    $this->questions[] = [
        'type' => is_string($q['type'] ?? null) ? $q['type'] : 'single',
        'options' => array_map(fn ($o): string => is_string($o) ? $o : '', $q['options'] ?? []),
        // ...
    ];
}
```

---

## Larastan 2.2.2: `--no-progress-bar` suppresses all output

PHPStan 2.2.2 has a bug where the `--no-progress-bar` flag causes **all** output (stdout + stderr) to be suppressed when the analysis finds errors (exit code 1). The JSON error payload is written to stderr (not stdout) and is lost when progress-bar suppression closes/flushes stderr prematurely.

**Workaround**: omit `--no-progress-bar`. The progress bar output on stderr is harmless when capturing JSON from stdout:

```bash
# ❌ suppresses ALL output (bug)
php vendor/bin/phpstan analyse -l 9 app/ --no-progress-bar

# ✅ works correctly
php vendor/bin/phpstan analyse -l 9 app/
```

---

## Split large test files by component concern

A 1312-line test file with tests for three different Livewire components (`StepViewer`, `QuizViewer`, `CodingViewer`) is harder to navigate and parallelize. Split into `StepViewerAccessTest.php`, `StepViewerReadingTest.php`, `StepViewerQuizTest.php`, `StepViewerCodingTest.php`. Each file:
- Has focused imports (only what its tests need)
- Maps to a single component or concern
- Is independently filterable via `--filter=StepViewerQuizTest`
