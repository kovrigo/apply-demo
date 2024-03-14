<?php

namespace App\Workflows;

use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\ResourceDetailRequest;
use App\Settings\RequestContext;
use App\Workflows\WorkflowAction;
use App\Settings\CustomizedResource;
use Kovrigo\WorkflowActions\WorkflowActions;

class WorkflowableResource extends CustomizedResource
{

    /**
     * Get the actions available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        $actions = parent::actions($request);
        $model = RequestContext::make()->serializedModel();
        if ($model && $model->state) {
            $workflow = $model->workflow->getSettings();
            $transitions = $workflow->transitionsFrom($model->state->code);
            $workflowActions = collect($transitions)->map(function ($transition) {
                $action = (new WorkflowAction)->setTransition($transition);
                return $action;
            });
            $actions = collect($actions)->concat($workflowActions)->all();
        }      
        return $actions;
    }

}
