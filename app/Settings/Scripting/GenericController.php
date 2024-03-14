<?php

namespace App\Settings\Scripting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class GenericController extends \EloquentJs\Controllerless\GenericController
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Create a new GenericController instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model = null)
    {
        $this->model = $model;
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(Auth::user()->can('viewAny', get_class($this->model)), 403);
        return $this->model->eloquentJs()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Model
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->can('create', get_class($this->model)), 403);
        $modelClass = get_class($this->model);
        $model = new $modelClass();
        $model->fill($request->all());
        $model->save();
        return $model;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $resource = $this->model->eloquentJs()->findOrFail($id);
        abort_unless(Auth::user()->can('view', $resource), 403);
        return $resource;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $resource = $this->model->findOrFail($id);
        abort_unless(Auth::user()->can('update', $resource), 403);
        $resource->update($request->all());
        return $resource;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resource = $this->model->findOrFail($id);
        abort_unless(Auth::user()->can('delete', $resource), 403);
        return ['success' => $resource->delete()];
    }

}
