<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * Імена cookie, які не потрібно шифрувати.
     *
     * @var array<int, string>
     */
    protected $except = [
        'locale', // ✅ НЕ шифрувати цю cookie — Laravel зможе її прочитати
    ];
}
