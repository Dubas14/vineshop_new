<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Читаємо з cookie, fallback на config/app.php
        $locale = $request->cookie('locale', config('app.locale'));
        App::setLocale($locale);

        return $next($request);
    }
}
