<?php

namespace App\Settings;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use App\Settings\Resolved\Traits\HasScriptableFields;
use Kovrigo\ActionValidationError\ActionValidationError;
use Illuminate\Support\Arr;
use App\Settings\RequestContext;
use App\Settings\Scripting\Script;
use App\Settings\Translator;

class CustomizedAction extends Action
{
    use InteractsWithQueue, Queueable, HasScriptableFields;

    public $key = null;
    public $handler = null;
    public $validator = null;
    public $redirect = null;

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function setFields($fields)
    {
        $this->fieldsWithScript = $fields;
        return $this;
    }

    public function setHandler($handler)
    {
        $this->handler = $handler;
        return $this;
    }

    public function setValidator($validator)
    {
        $this->validator = $validator;
        return $this;
    }

    public function setRedirect($redirect = null)
    {
        if ($redirect) {
            $context = RequestContext::make();
            $this->redirect = '/apply' . Translator::replace($redirect, [
                'resource' => $context->resourceKey(),
                'id' => $context->resourceId(),
            ]);
        }
        return $this;
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($this->handler) {
            $context = RequestContext::make()
                ->set('dialog', $fields)
                ->set('models', $models)
                ->forScripting();
            $redirect = Script::run($this->handler, $context);
            if ($redirect) {
                return Action::redirect($redirect);        
            }
        }
        return Action::redirect($this->redirect);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $fields = $this->getFields()->all();
        $validator = (new ActionValidationError)
            ->rules([new RequestScriptValidationRule($this->validator)]);
        array_push($fields, $validator);
        return $fields;
    }
    
    /**
     * Get the URI key for the action.
     *
     * @return string
     */
    public function uriKey()
    {
        return $this->key;
    }

    public function canAlwaysRun()
    {
        $this->canRun(function () {
           return true;
        });
        return $this;
    }

}
