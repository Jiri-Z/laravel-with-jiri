<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\CourseList;

Route::view('/', 'welcome');

Route::get('/courses', CourseList::class)
    ->middleware(['auth', 'verified'])
    ->name('courses.index');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
