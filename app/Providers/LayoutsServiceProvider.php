<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;

class LayoutsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Layouts
        Blade::component('app-layout', \App\View\Components\AppLayout::class);
    }
}
