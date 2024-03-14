<?php

namespace App\Settings\Scripting;

use Illuminate\Database\Eloquent\Model;

class Inspector extends \EloquentJs\ScriptGenerator\Model\Inspector
{

    /**
     * Get the scope methods for this model with 'scope' prefix removed.
     *
     * @param Model $instance
     * @return array
     */
    protected function findScopeMethods(Model $instance)
    {
        $scopes = parent::findScopeMethods($instance);

        // Also check for trait methods
        $traits = class_uses($instance);
        $traitScopes = collect($traits)->map(function ($trait) {
            return get_class_methods($trait);
        })
            ->flatten()
            ->filter(function ($method) {
                return substr($method, 0, 5) === 'scope' and ! in_array($method, $this->excludeScopes);
            })
            ->all();
            
        // Merge results
        $scopes = array_merge($scopes, $traitScopes);
        return $scopes;
    }

}
