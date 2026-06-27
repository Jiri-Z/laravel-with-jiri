<?php

use App\Livewire\CourseDetail;
use App\Livewire\CourseList;
use App\Livewire\LessonDetail;
use App\Livewire\StepViewer;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/courses', CourseList::class)->name('courses.index');
    Route::get('/courses/{course:slug}', CourseDetail::class)->name('courses.show');
    Route::get('/courses/{course:slug}/lessons/{lesson:slug}', LessonDetail::class)->name('lessons.show');
    Route::get('/courses/{course:slug}/lessons/{lesson:slug}/steps/{step}', StepViewer::class)->name('steps.show');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
