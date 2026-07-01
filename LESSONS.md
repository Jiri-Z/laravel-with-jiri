# Lessons Learned

Accumulated patterns and operational knowledge from developing this application.

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
    import('https://cdn.jsdelivr.net/npm/monaco-editor@0.52.2/min/vs/loader.js').then(() => {
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
