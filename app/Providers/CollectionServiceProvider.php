<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;

class CollectionServiceProvider extends ServiceProvider
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
        // Register recursiveMap collection macro (as usual "map" macro, but iterates over nested collections)
        Collection::macro('recursiveMap', function ($callback) {
            return $this->map(function ($value) use ($callback) {
                $res = $value;
                if (is_array($value) || is_object($value)) {
                    $res = collect($value)->recursiveMap($callback);
                }
                $res = call_user_func($callback, $res);
                return $res;
            });
        });
        // Register recursiveAll collection macro (as usual "all" macro, but iterates over nested collections)
        Collection::macro('recursiveAll', function () {
            return $this->map(function ($value) {
                $res = $value;
                if ($value instanceof Collection) {
                    $res = $value->recursiveAll()->all();
                } elseif (is_array($value)) {
                    $res = collect($value)->recursiveAll()->all();
                }
                return $res;
            });
        });
    }
}
