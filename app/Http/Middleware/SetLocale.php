<?php

namespace App\Http\Middleware;

use Closure;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->cookie('locale', config('app.locale'));
        app()->setLocale($locale);
        return $next($request);
    }
}
