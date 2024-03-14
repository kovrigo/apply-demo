<?php

namespace App\Settings\Scripting;

use Spatie\Ssr\Engines\Node;

class Script
{

    public static function run($script, $context)
    {
        chdir(base_path());
        $engine = new Node(config('app.node_path'), base_path());
        //$engine = new Node('node', sys_get_temp_dir());
        $renderer = new Renderer($engine);
        $res = $renderer
            ->entry(resource_path() . '/js/eloquent/' . config('app.env') . '.js')
            ->script($script)
            ->context($context)
            ->debug()
            ->render();
        return $res;
    }

}
