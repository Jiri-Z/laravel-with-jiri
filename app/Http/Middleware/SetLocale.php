<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $locale = null;

        if ($user !== null && is_string($user->locale) && $user->locale !== '') {
            $locale = $user->locale;
        }

        if ($locale === null && $request->hasSession()) {
            $sessionLocale = $request->session()->get('locale');

            if (is_string($sessionLocale) && $sessionLocale !== '') {
                $locale = $sessionLocale;
            }
        }

        if ($locale === null) {
            $defaultLocale = config('app.locale');
            $locale = is_string($defaultLocale) ? $defaultLocale : 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
