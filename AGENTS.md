# Laravel With Jiri — AGENTS.md

Greenfield e-learning platform. Nothing scaffolded yet — start from scratch.

## Stack
- **Framework:** Laravel (latest), scaffolded with Laravel Boost
- **Frontend:** Livewire + Tailwind CSS + Alpine.js
- **Testing:** Pest (every model, policy, Livewire component, significant service)
- **Database:** PostgreSQL
- **Auth:** Laravel Boost built-in auth + roles (`admin`, `instructor`, `student` via string/enum column)

## Domain (from `plan.txt`)
- **Course** → **Lesson** → **Step** (all with `published` boolean + `order` int)
- Step types: `reading`, `quiz_single`, `quiz_multiple`, `quiz_text`, `coding`
- **StepCompletion**: `user_id`, `step_id`, `completed_at` (unique pair)
- Lesson complete = all steps done; Course progress = completed / total steps

## Architecture
- Thin controllers; business logic in actions or service classes
- Livewire full-page components for views, nested for interactive pieces
- Form requests for validation, policies for authorization
- Factories for all test data
- Eager-load to avoid N+1
- Queue non-trivial side effects as jobs

## Build order (suggested)
1. Laravel Boost scaffold → auth, roles, base layout
2. Migrations + models: Course, Lesson, Step, StepCompletion
3. Factories + seeders
4. Pest tests for models + relationships
5. Student course/lesson/step views (reading first)
6. Step completion + progress (Livewire)
7. Quiz step types
8. Coding step type (Monaco)
9. Admin/instructor CRUD
10. Role-based access (policies + middleware)
11. Ordering / reordering
12. Polish: progress bars, empty/loading/error states
13. Full Pest test pass

## Out of scope
Payments, video, comments, email notifications, API endpoints.
