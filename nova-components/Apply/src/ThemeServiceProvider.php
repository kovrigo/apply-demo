<?php

namespace Kovrigo\Apply;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::theme(asset('/kovrigo/apply/theme.css'));

        $this->publishes([
            __DIR__.'/../dist/css' => public_path('kovrigo/apply'),
            __DIR__.'/../dist/fonts' => public_path('kovrigo/apply/fonts'),
        ], 'public');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
