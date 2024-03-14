<?php

namespace App\Settings;

use Illuminate\Database\Eloquent\SoftDeletes;
use EloquentJs\Model\EloquentJsQueries;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes, EloquentJsQueries;

    public function __construct()
    {
        parent::__construct();
        // TODO: Without it laravel doesn't boot model traits when updating/creating
        parent::boot();        
    }

    protected static function boot()
    {   
        parent::boot(); 
        $resourceClass = class_basename(get_called_class());
        $settings = apply()->settings;
        if ($settings) {
        	optional($settings->resources->$resourceClass)->registerEvents(get_called_class());
        }
    }

    public static $resourceTitleAttribute = "name";

    public function getModelTitleAttribute()
    {
        return $this->resource_title ?: $this->{static::$resourceTitleAttribute};
    }

    public function saveWithoutEvents(array $options = [])
    {
        return static::withoutEvents(function() use ($options) {
            return $this->save($options);
        });
    }

}
