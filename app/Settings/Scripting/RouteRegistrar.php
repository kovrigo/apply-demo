<?php

namespace App\Settings\Scripting;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class RouteRegistrar extends \EloquentJs\Controllerless\RouteRegistrar
{

    /**
     * Add the resourceful routes for the given uri and resource.
     *
     * @param string $uri
     * @param string $resource
     * @param array $options
     */
    public function addRoute($uri, $resource, array $options = [])
    {
        // The $router->resource() method doesn't allow custom route attributes
        // in the $options array. So, while the group() may look redundant here,
        // we need it to attach the relevant resource classname to each of the
        // individual restful routes being defined.
        // This is so when we come to resolve the controller, we
        // can easily tell what type of resource we need, i.e. which model.
        $this->router->group(
            $this->groupOptions($resource, $options),
            function($router) use ($uri, $options) {
                $router->resource(
                    $uri,
                    $this->controller,
                    $this->routeOptions($options)
                );
            }
        );
    }

}
