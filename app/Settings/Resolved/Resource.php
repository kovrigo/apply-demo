<?php

namespace App\Settings\Resolved;

use Illuminate\Support\Arr;
use App\Settings\RequestContext;
use App\Settings\Scripting\Script;
use App\Settings\Query\Interpreter;
use App\Settings\Query\Query;
use App\Settings\Resolved\Traits\HasScriptableFields;

class Resource
{
    use HasScriptableFields;

    protected $resource = null;
    
    // Collections
    protected $filters = null;
    protected $lenses = null;
    protected $actions = null;
    protected $cards = null;

    // Policy
    protected $policy = null;

    // Redirects
    public $redirectAfterCreate;
    public $redirectAfterUpdate;
    public $redirectAfterDelete;

    // Labels
    public $label;
    public $singularLabel;

    // Events
    public $events = null;

    // Supported events
    const SUPPORTED_EVENTS = ['retrieved', 'creating', 'created', 'updating', 'updated', 'saving', 'saved', 'deleting', 'deleted', 'restoring', 'restored'];

	public function __construct($resource = [])
    {
        $this->resource = $resource;
        $this->fieldsWithScript = Arr::get($this->resource, 'fields', null);
        $this->redirectAfterCreate = Arr::get($this->resource, 'redirectAfter.create', null);
        $this->redirectAfterUpdate = Arr::get($this->resource, 'redirectAfter.update', null);
        $this->redirectAfterDelete = Arr::get($this->resource, 'redirectAfter.delete', null);
        $this->label = Arr::get($this->resource, 'label', null);
        $this->singularLabel = Arr::get($this->resource, 'singularLabel', null);
    }
 
    public function registerEvents($modelClass)
    {
        collect(static::SUPPORTED_EVENTS)->each(function ($event) use ($modelClass) {
            $modelClass::$event(function ($model) use ($event) {
                $script = Arr::get($this->resource, "events.$event", null);
                if ($script) {
                    $context = RequestContext::make()
                        ->set('model', $model)
                        ->forScripting();
                    $newModel = Script::run($script, $context);
                    if (is_array($newModel)) {
                        $model->fill($newModel);                        
                    }
                }
            });
        });
    }

    public function __get($key)
    {
        switch ($key) {
            case "fields":
                return $this->getFields();
                break;
            case "filters":
                return $this->getNovaCollection('filters');
                break;
            case "lenses":
                return $this->getNovaCollection('lenses');
                break;
            case "actions":
                return $this->getNovaCollection('actions');
                break;
            case "cards":
                return $this->getNovaCollection('cards');
                break;                
            case "policy":
                return $this->getPolicy();
                break;                
        }
    }

    public function getIndexQuery($query)
    {
        $indexQuery = Arr::get($this->resource, 'indexQuery', null);
        return Query::make($indexQuery)->applyTo($query);
    }

    public function getPolicy()
    {
        if (is_null($this->policy)) {
            $policy = Arr::get($this->resource, 'policy', null);
            $this->policy = new Policy($policy);
        }
        return $this->policy;
    }

    public function getNovaCollection($name)
    {
        if (is_null($this->$name)) {
            $values = Arr::get($this->resource, $name, []);
            $this->$name = new NovaCollection($values);
        }
        return $this->$name;
    }

}
