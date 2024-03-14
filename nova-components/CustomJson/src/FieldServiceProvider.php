<?php

namespace Kovrigo\CustomJson;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../dist/fonts/vendor' => public_path('fonts/vendor'),
        ], 'public');

        Nova::serving(function (ServingNova $event) {
            Nova::script('custom-json', __DIR__.'/../dist/js/field.js');
            Nova::style('custom-json', __DIR__.'/../dist/css/field.css');
        });
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
