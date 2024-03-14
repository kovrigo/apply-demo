<?php

namespace App\Settings\Resolved\Traits;

use Illuminate\Support\Arr;
use App\Settings\RequestContext;
use App\Settings\Scripting\Script;
use App\Settings\Resolved\FieldCollection;

trait HasScriptableFields
{

    protected $fields = null;
    protected $fieldsWithScript = null;

    public function getFields()
    {
        if (is_null($this->fields)) {
            $fields = $this->fieldsWithScript;
            $fieldCollection = Arr::get($fields, 'FieldCollection', []);
            // Run script that modifies FieldCollection
            $script = Arr::get($fields, 'script', null);
            if ($script) {
                $context = RequestContext::make()
                    ->set('fields', $fieldCollection)
                    ->forScripting();
                $fieldCollection = Script::run($script, $context);
                Arr::set($fields, 'FieldCollection', $fieldCollection);
            }
            $this->fields = $fields;
        }
        return new FieldCollection($this->fields);
    }

}