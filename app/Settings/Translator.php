<?php

namespace App\Settings;

class Translator extends \Illuminate\Translation\Translator
{

    public static function replace($line, $replace)
    {
        return app('translator')->makeReplacements($line, $replace);
    }

}
