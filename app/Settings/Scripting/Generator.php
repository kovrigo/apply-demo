<?php

namespace App\Settings\Scripting;

use Spatie\Ssr\Engines\Node;
use Illuminate\Support\Facades\File;
use EloquentJs\ScriptGenerator\Model\Metadata;

class Generator extends \EloquentJs\ScriptGenerator\Generator
{

    protected $externalBuild = false;

    public function buildExternal($models)
    {
        $this->externalBuild = true;
        return $this->build($models);
    }

    public function buildInternal($models)
    {
        $this->externalBuild = false;
        return $this->build($models);
    }

    /**
     * Get prefix for our javascript build.
     *
     * @return string
     */
    protected function prefix()
    {
        $name = $this->externalBuild ? 'Ext' : 'Int';
        return file_get_contents(base_path() . '/app/Settings/Scripting/Stubs/' . $name . '.js') . "\n\n";
    }

    /**
     * Get suffix for our javascript build.
     *
     * @return string
     */
    protected function suffix()
    {
        return "";
    }

    protected function models(array $models)
    {
        return implode('', array_map([$this, 'model'], $models));
    }

    protected function model(Metadata $model)
    {   
        $path = base_path() . '/app/Settings/Scripting/Stubs/' . $model->name . '.stub';
        $globalObject = $this->externalBuild ? 'window.' . $model->name : 'var ' . $model->name;
        if (file_exists($path)) {
            $stub = file_get_contents($path);
            $scopes = collect($model->scopes)->map(function ($scope) {
                return '"' . $scope . '"';
            })->implode(', ');
            $dates = collect($model->dates)->map(function ($date) {
                return '"' . $date . '"';
            })->implode(', ');            
            $content = str_replace('<globalObject>', $globalObject, $stub);
            $content = str_replace('<endpoint>', $model->endpoint, $content);
            $content = str_replace('<scopes>', $scopes, $content);
            $content = str_replace('<dates>', $dates, $content);
            return $content;
        } else {
            $config = json_encode(array_filter([
                'endpoint'  => $model->endpoint,
                'dates'     => $model->dates,
                'scopes'    => $model->scopes,
                'relations' => $model->relations,
            ]), JSON_UNESCAPED_SLASHES);
            return "Eloquent('{$model->name}', {$config}); $globalObject = Eloquent.{$model->name};\n";
        }
    }

}
