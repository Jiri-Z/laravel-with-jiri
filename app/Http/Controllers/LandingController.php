<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        $courses = Course::query()
            ->withCount('lessons')
            ->published()
            ->ordered()
            ->get();

        return view('landing', ['courses' => $courses]);
    }
}
