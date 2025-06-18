<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = session('locale');

        if (!$locale) {
            $cookieLocale = $request->cookie('locale');

            if (in_array($cookieLocale, ['uk', 'en'], true)) {
                $locale = $cookieLocale;
                session(['locale' => $locale]);
            }
        }

        if ($locale) {
            app()->setLocale($locale);
        }
        return $next($request);
    }
}
