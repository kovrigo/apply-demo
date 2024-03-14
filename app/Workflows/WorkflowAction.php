<?php

namespace App\Workflows;

use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Collection;
use App\Settings\Resolved\Traits\HasScriptableFields;
use Kovrigo\ActionValidationError\ActionValidationError;
use Illuminate\Support\Arr;
use App\Settings\RequestContext;
use App\Settings\Scripting\Script;
use App\Settings\CustomizedAction;
use App\Settings\RequestScriptValidationRule;

class WorkflowAction extends CustomizedAction
{

	protected $transition;

	public function setTransition($transition)
	{
		$this->transition = $transition;
        $this->setName($transition->label)
        	->setKey($transition->key)
        	->setHandler($transition->operations->handler())
        	->setValidator($transition->operations->validator())
            ->setRedirect($transition->redirect)
        	->setFields($transition->operations->fields())
            // Workflow actions can be executed even if user doesn't have permissions to update the model
            // For nomal actions you have to call 'canRun' method on such actions            
            ->canRun(function () {
               return true;
            });
        return $this;
	}

    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function getFields()
    {
    	return $this->fields;
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
        // Add new state to the dialog fields
        $model = RequestContext::make()->serializedModel();
        $stateClass = $model->stateClass();
        $state = $stateClass::where('code', $this->transition->to)->first();
        $fields['new_state_id'] = $state->id;
        // Run operations
        $redirect = parent::handle($fields, $models);
    	// Change state
    	$model->state()->associate($state);
    	$model->save();
        if ($redirect) {
            return $redirect;
        }
    }

}
