<?php

namespace Kovrigo\BusinessHours;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Illuminate\Support\Facades\File;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('business-hours', __DIR__.'/../dist/js/field.js');
            Nova::style('business-hours', __DIR__.'/../dist/css/field.css');
        });

        $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang/vendor/business-hours')], 'translations');

        if (method_exists('Nova', 'translations')) {
            // Load local translation files
            $localTranslationFiles = File::files(__DIR__ . '/../resources/lang');
            foreach ($localTranslationFiles as $file) {
                Nova::translations($file->getPathName());
            }

            // Load project translation files
            $projectTransFilesPath = resource_path('lang/vendor/business-hours');
            if (File::exists($projectTransFilesPath)) {
                $projectTranslationFiles = File::files($projectTransFilesPath);
                foreach ($projectTranslationFiles as $file) {
                    Nova::translations($file->getPathName());
                }
            }
        }

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
