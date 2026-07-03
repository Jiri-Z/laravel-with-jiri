<?php

declare(strict_types=1);

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\App;

class SwitchLocale
{
    public function __invoke(string $locale): void
    {
        if (! in_array($locale, ['en', 'cs'])) {
            return;
        }

        if (auth()->user()) {
            auth()->user()->update(['locale' => $locale]);
        }

        session(['locale' => $locale]);
        App::setLocale($locale);
    }
}
