<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportLangToJs extends Command
{
    protected $signature = 'lang:export-js';
    protected $description = 'Export Laravel translations to JS file for Vue';

    public function handle()
    {
        $locales = config('app.locales', ['en', 'uk']);
        $output = [];

        foreach ($locales as $locale) {
            $path = resource_path("lang/{$locale}/messages.php");
            if (file_exists($path)) {
                $arr = include $path;
                $output[$locale] = $arr;
            }
        }

        // Формат для експорту — JS-обʼєкт
        $js = 'export default ' . json_encode($output, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . ';';

        // Шлях для Vue (створити каталог, якщо треба)
        $outPath = resource_path('js/lang/messages.js');
        file_put_contents($outPath, $js);

        $this->info('messages.js згенеровано!');
    }
}
