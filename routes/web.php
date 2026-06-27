<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\CourseDetail;
use App\Livewire\CourseList;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/courses', CourseList::class)->name('courses.index');
    Route::get('/courses/{course:slug}', CourseDetail::class)->name('courses.show');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
