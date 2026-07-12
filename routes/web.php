<?php

use App\Actions\EnrollInCourse;
use App\Actions\SwitchLocale;
use App\Http\Controllers\LandingController;
use App\Livewire\AdminCourseForm;
use App\Livewire\AdminCourseList;
use App\Livewire\AdminLessonForm;
use App\Livewire\AdminLessonImport;
use App\Livewire\AdminLessonList;
use App\Livewire\AdminStepForm;
use App\Livewire\AdminStepList;
use App\Livewire\CourseDetail;
use App\Livewire\CourseList;
use App\Livewire\Dashboard;
use App\Livewire\LessonDetail;
use App\Livewire\StepViewer;
use App\Livewire\TriviaQuiz;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/courses', CourseList::class)->name('courses.index');
    Route::get('/courses/{course:slug}', CourseDetail::class)->name('courses.show');
    Route::get('/courses/{course:slug}/lessons/{lesson:slug}', LessonDetail::class)->name('lessons.show');
    Route::get('/courses/{course:slug}/lessons/{lesson:slug}/steps/{step}', StepViewer::class)->name('steps.show');

    Route::get('/quiz', TriviaQuiz::class)->name('quiz');

    Route::post('/enroll/{course}', function (Course $course) {
        $action = app(EnrollInCourse::class);
        $action->handle(auth()->user(), $course);

        return redirect()->route('courses.show', $course);
    })->name('courses.enroll');
});

Route::middleware(['auth', 'verified', 'staff'])->prefix('admin')->group(function () {
    Route::get('/courses', AdminCourseList::class)->name('admin.courses.index');
    Route::get('/courses/create', AdminCourseForm::class)->name('admin.courses.create');
    Route::get('/courses/{course}/edit', AdminCourseForm::class)->name('admin.courses.edit');

    Route::get('/courses/{course}/lessons', AdminLessonList::class)->name('admin.lessons.index');
    Route::get('/courses/{course}/lessons/create', AdminLessonForm::class)->name('admin.lessons.create');
    Route::get('/courses/{course}/lessons/import', AdminLessonImport::class)->name('admin.lessons.import');
    Route::get('/courses/{course}/lessons/{lesson}/edit', AdminLessonForm::class)->name('admin.lessons.edit');

    Route::get('/courses/{course}/lessons/{lesson}/steps', AdminStepList::class)->name('admin.steps.index');
    Route::get('/courses/{course}/lessons/{lesson}/steps/create', AdminStepForm::class)->name('admin.steps.create');
    Route::get('/courses/{course}/lessons/{lesson}/steps/{step}/edit', AdminStepForm::class)->name('admin.steps.edit');
});

Route::get('/dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/locale', function (SwitchLocale $switcher) {
    $locale = request()->input('locale', 'en');
    $switcher->handle($locale);

    return redirect()->back();
})->name('locale.switch');

Route::view('/terms', 'legal.terms')->name('terms');
Route::view('/privacy', 'legal.privacy')->name('privacy');

require __DIR__.'/auth.php';
