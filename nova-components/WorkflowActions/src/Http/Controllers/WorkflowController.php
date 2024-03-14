<?php

namespace Kovrigo\WorkflowActions\Http\Controllers;

use Laravel\Nova\Resource;
use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Settings\RequestContext;
use Carbon\Carbon;
use App\Workflows\WorkflowAction;

class WorkflowController extends Controller
{

    /**
     * List the actions for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(NovaRequest $request)
    {
        $model = RequestContext::make()->serializedModel();
        $log = $model->logs()->latest()->first();
        $state = $log ? $log->state->name : $model->state->name;
        $user = $log ? $log->user : $model->user;
        $userName = $user->last_name . ' ' . $user->first_name;
        $date = $log ? $log->created_at : $model->created_at;
        $dateFormatted = (new Carbon($date))->format('d.m.Y H:i');
        $notes = $log ? $log->notes : null;
        $actions = $request->newResource()->resolveActions($request)->filter->authorizedToSee($request);
        $actions = collect($actions)->filter(function ($action) {
            return $action instanceof WorkflowAction;
        })->values();
        return response()->json([
            'actions' => $actions,
            'pivotActions' => [
                'name' => $request->pivotName(),
                'actions' => $request->newResource()->availablePivotActions($request),
            ],
            'log' => [
                'state' => $state,
                'user' => $userName,
                'date' => $dateFormatted,
                'notes' => $notes,
            ],
        ]);
    }

}