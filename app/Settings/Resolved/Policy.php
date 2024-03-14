<?php

namespace App\Settings\Resolved;

use Illuminate\Support\Arr;
use App\Settings\RequestContext;
use App\Settings\Scripting\Script;
use App\Settings\Query\Interpreter;
use App\Settings\Query\Query;

class Policy
{

    protected $policy = null;

	public function __construct($policy)
    {
        $this->policy = $policy;
    }
 
    public function can($ability, $model = null, $relatedModel = null)
    {
        $permission = Arr::get($this->policy, $ability, false);
        if (is_array($permission)) {
            $query = Arr::get($permission, 'query', null);
            $relatedQuery = Arr::get($permission, 'relatedQuery', null);
            $script = Arr::get($permission, 'script', null);
            $permission = false;
            if ($script) {
                $context = RequestContext::make()
                    ->set('model', $model)
                    ->set('relatedModel', $relatedModel)
                    ->forScripting();
                $permission = Script::run($script, $context);
            } else {
                if ($query && $model) {
                    $modelClass = get_class($model);
                    $permission = Query::make($query)
                        ->applyTo($modelClass::query())
                        ->where('id', $model->id)
                        ->exists();
                }
                if ($relatedQuery && $relatedModel) {
                    $relatedModelClass = get_class($relatedModel);
                    $permission = $permission && Query::make($relatedQuery)
                        ->applyTo($relatedModelClass::query())
                        ->where('id', $relatedModel->id)
                        ->exists();
                }
            }
        }
        return $permission;
    }

}
