<?php

declare(strict_types=1);

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    public function __invoke(): void
    {
        Auth::guard('web')->logout();

        $locale = session('locale');
        Session::invalidate();
        Session::regenerateToken();
        session(['locale' => $locale]);
    }
}
