<?php

namespace App\Settings\Scripting;

use App\Settings\Scripting\GenericController;
use App\Settings\Scripting\RouteRegistrar;
use EloquentJs\Query\Events\EloquentJsWasCalled;
use EloquentJs\Query\Guard\Policy\Builder;
use EloquentJs\Query\Guard\Policy\Factory;
use EloquentJs\Query\Interpreter;
use EloquentJs\Query\Listeners\ApplyQueryToBuilder;
use EloquentJs\Query\Listeners\CheckQueryIsAuthorized;
use EloquentJs\Query\Query;
use EloquentJs\ScriptGenerator\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class EloquentJsServiceProvider extends \EloquentJs\EloquentJsServiceProvider
{

    /**
     * Set up the generic resource routes + controller.
     *
     * @param Router $router
     * @return void
     */
    protected function enableGenericResourceRouting(Router $router)
    {
        $app = $this->app;

        $app->bind('eloquentjs.router', RouteRegistrar::class);

        $app->when(RouteRegistrar::class)
            ->needs('$controller')
            ->give(GenericController::class);

        $app->when(GenericController::class)
            ->needs(Model::class)
            ->give(function($app) {
                if ($resource = $app['eloquentjs.router']->getCurrentResource()) {
                    return $app->make($resource);
                }
            });

        $router->macro('eloquent', function($uri, $resource, $options = []) use ($app) {
            $app['eloquentjs.router']->addRoute($uri, $resource, $options);
        });
    }

}
