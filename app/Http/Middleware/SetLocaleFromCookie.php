<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocaleFromCookie
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->cookie('locale', config('app.locale'));

        if (! in_array($locale, config('app.locales', ['en', 'uk']), true)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
