<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        app()->register(\App\Providers\RouteServiceProvider::class);

        View::composer('partials.header', function ($view) {
            $view->with('categories', Category::whereNull('parent_id')->with('children')->get());
        });
    }
}
