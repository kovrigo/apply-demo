<?php

namespace Kovrigo\Apply;

class Env
{

    public static function sideBarStyle()
    {
        $styles = [
            'local' => 'border-left: 10px solid green',
            'staging' => 'border-left: 10px solid orange',
            'production' => '',
        ];
        return $styles[config('app.env')];
    }
}
