<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;

class SwitchLocale
{
    public function handle(string $locale): void
    {
        if (! in_array($locale, ['en', 'cs'])) {
            throw ValidationException::withMessages([
                'locale' => 'The selected locale is invalid.',
            ]);
        }

        if (auth()->user()) {
            auth()->user()->update(['locale' => $locale]);
        }

        session(['locale' => $locale]);
        App::setLocale($locale);
    }
}
