# Senior Developer Code Review

**Project**: Laravel With Jiri — E-learning Platform  
**Stack**: Laravel 13 + Livewire 3 + Tailwind 3 + Pest 4 + PostgreSQL  
**Review Date**: July 2026  
**Overall Rating**: **7.5/10** — Above average, production-ready, significant duplication in quiz logic, some architectural friction points.

---

## What's Genuinely Good

### Architecture
- **Action/DTO pattern**: `SubmitQuizAnswer::handle()` returns `SubmitQuizAnswerResult` — clean separation of command and result.
- **Batch query methods** in `ProgressService` (`courseProgressBatch`, `lessonCompleteBatch`, `stepCompleteBatch`) directly prevent N+1 at the query level.
- **Transaction-safe reordering** (`ManagesOrdering`): three-step swap via sentinel value `-1` avoids unique constraint violations during reorder — clever and robust.
- **Domain exceptions** with localizable messages via `__()` — specific, catchable, consistent.

### Consistency & Tooling
- `declare(strict_types=1)` on every PHP file — rare and commendable.
- Larastan level 6 with 0 new errors; array shapes (`@return array{prompt: string, ...}`) in key methods.
- Every model has a factory; every Livewire component, action, and policy has tests.
- 460 tests pass reliably with `RefreshDatabase` — no flaky tests, no shared mutable state.
- `#[Scope]`, `#[Layout]`, `#[Computed]`, `#[Fillable]` PHP 8 attributes used consistently.

### Domain Modeling
- `Step::isAccessibleBy()` delegates to `Lesson::hasUserCompletedPreviousStep()` — clean responsibility chain.
- `HasOrder` trait with `scopeOrdered()` — minimal, reusable across three models.
- Three-step validation in `StepViewer::mount()`: context → enrollment → accessibility.

---

## Critical Issues

### 1. Two Parallel Quiz Systems — Logic Duplication

`app/Actions/SubmitQuizAnswer.php` (lesson quizzes) and `app/Livewire/TriviaQuiz.php` (trivia) implement nearly identical answer-checking algorithms for `single`, `multiple`, and `text` question types:

| Concern | `SubmitQuizAnswer` | `TriviaQuiz` |
|---------|-------------------|--------------|
| Text normalization | `strcasecmp(trim(...))` | `mb_strtolower(trim(...))` |
| Multiple answer comparison | `array_diff` both ways | `sort()` then `===` |
| Alternatives handling | iterates `$content['alternatives']` | iterates `$alternatives` param |
| Answer resolution | `correctAnswer()` with 3 fallback keys | direct `$question['answer']` access |

These will drift apart. The different normalization functions mean the two paths already disagree on case-insensitive matching for multibyte characters.

**Recommendation**: Extract into `App\Services\AnswerChecker` with methods `checkSingle()`, `checkMultiple()`, `checkText()`. Both callers use the same service.

### 2. Polymorphic JSON in a Single Column

`steps.content` stores three semantically different payloads:
- **Reading**: plain string (`"Some text content"`)
- **Quiz**: JSON array (`[{type:"single", ...}]`)
- **Coding**: JSON object (`{prompt:"...", ...}`)

Code must check `$step->type` before interpreting `$step->content`. `getContentAsArray()` uses `json_validate()` as a heuristic, but this returns `null` for valid reading content like `"true"` or `"42"` (valid JSON primitives).

**Recommendation**: Use dedicated JSON columns per type or a polymorphic `StepContent` interface.

### 3. Race Condition Handler Eats All QueryExceptions

```php
// app/Actions/SubmitQuizAnswer.php:36
catch (QueryException) {
    $existing = StepAnswer::where(...)->firstOrFail();
    return new SubmitQuizAnswerResult(...);
}
```

Catches *every* `QueryException` — deadlock, connection lost, column not found — and silently returns stale data.

**Recommendation**: Check `$e->getCode() === '23505'` (PostgreSQL) or `$e->errorInfo[1] === 19` (SQLite). Re-throw non-duplicate exceptions.

---

## Moderate Issues

### 4. ManagesOrdering Accepts a String Class — Type-Unsafe

```php
protected function deleteItem(int $id, string $modelClass): void
{
    $model = $modelClass::findOrFail($id);
```

Bypasses static analysis. Passing `Course::class` with a Step ID causes a runtime error.

**Recommendation**: Accept a `Model` instance or use `@template T of Model`.

### 5. EnsuresEnrollment Aborts with 404 Instead of 403

```php
if (! $enrolled) {
    abort(404);
}
```

A previously-enrolled user who lost access gets "not found" instead of "forbidden". Hinders debugging.

**Recommendation**: `abort(403)` or throw `NotEnrolledException`.

### 6. Inconsistent Livewire Action Patterns

- `Actions/SwitchLocale` — invokable class
- `Actions/Logout` — invokable class
- `QuizViewer::submit()` — component method
- `MarkStepComplete` — action class called via `(new ...)->handle()`

The boundary between action types is arbitrary.

**Recommendation**: Choose one convention — either all invokable or all `handle()`.

### 7. QuizViewer json_decode Without `true` Flag

```php
$this->answers[$entry->question_index] = $type === 'multiple'
    ? json_decode((string) $entry->answer)
    : $entry->answer;
```

`json_decode` defaults to objects for associative arrays. While numeric JSON arrays decode to arrays correctly, this is fragile.

**Recommendation**: Use `json_decode(..., true)` for consistent array output.

### 8. Course::enrolledUsers() Relationship Defined But Unused

Defined as a `BelongsToMany` but every enrollment check uses `CourseEnrollment::where(...)->exists()` directly. Dead code that could trigger N+1 if used naively.

### 9. updatedSearch() Hook in Trait Causes Collision Risk

`ManagesOrdering` defines `updatedSearch()`. If a component uses this trait AND defines its own `updatedSearch()`, PHP throws a fatal error.

---

## Minor Issues

### 10. php-wasm Dependency

`php-wasm` compiles PHP to WebAssembly for browser-side execution in coding steps. ~15MB download, significant security surface, version mismatch risk.

### 11. StaffMiddleware Duplicates Policy Logic

Both `StaffMiddleware` and `CoursePolicy::viewAny()` check `isAdmin() || isInstructor()` — two places to update if roles change.

### 12. Hardcoded English Strings in Tests

```php
->assertSee('Welcome')
->assertSee('Dashboard')
```

Breaks under non-English locales. Should use `__()` or locale-aware assertions.

### 13. No `composer run pint` Script

Developer experience nit — AGENTS.md references `vendor/bin/pint` directly.

### 14. Larastan at Level 6, Not Higher

Level 6 is good. Level 7 would catch more type inconsistencies (especially around `json_decode` returns).

---

## Priority Order for Improvements

| Priority | Issue | Effort | Risk |
|----------|-------|--------|------|
| P0 | Extract shared AnswerChecker service | Medium | Low |
| P0 | Fix unique constraint handling in SubmitQuizAnswer | Small | Low |
| P1 | Dedicated content columns on steps | Large | Medium |
| P1 | 403 instead of 404 for EnsuresEnrollment | Small | Low |
| P1 | Type-safe ManagesOrdering | Small | Low |
| P2 | Consistent action invocation pattern | Medium | Low |
| P2 | Remove dead Course::enrolledUsers() | Small | Low |
| P2 | Fix QuizViewer json_decode assoc flag | Small | Low |
| P3 | Raise Larastan to level 7 | Medium | Low |
| P3 | Convenience scripts + Pint command | Small | None |
| P3 | Document php-wasm | Small | None |
