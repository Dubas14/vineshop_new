<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->cookie('locale', config('app.locale'));
        app()->setLocale($locale);

        return $next($request);
    }
}
