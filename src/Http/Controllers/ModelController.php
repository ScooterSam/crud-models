<?php

namespace ScooterSam\CrudModels\Http\Controllers;

use Illuminate\Routing\Controller;
use ScooterSam\CrudModels\ModelResource\ModelResource;

class ModelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (!app()->runningInConsole()) {
            if (request()->route()->hasParameter('alias')) {
                $parameter  = request()->route()->parameter('alias');
                $middleware = $this->getResource($parameter)->getMiddleware();

                $this->middleware($middleware);
            }
        }
    }

    public function getResource($alias): ModelResource
    {
        return app(config('crud-models.models.' . $alias));
    }

    /**
     * List & paginate all model data on this resource
     *
     * @param $alias
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list($alias)
    {
        $this->middleware('auth');

        $resource = $this->getResource($alias);
        $builder  = $resource->builder();

        $req = $builder
            ->where(function ($builder) use ($resource) {

                //Only search if we have searchable fields set ofcourse...
                if ($resource->getSearchableFields() !== null && request()->has('query')) {
                    $builder->where(function ($builder) use ($resource) {
                        $query  = request('query');
                        $fields = $resource->getSearchableFields();

                        for ($i = 0; $i < count($fields); $i++) {
                            $field = $fields[$i];

                            if ($i === 0) {
                                $builder->where($field, 'LIKE', "%{$query}%");
                            } else {
                                $builder->orWhere($field, 'LIKE', "%{$query}%");
                            }
                        }

                        return $builder;
                    });
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(
                config('crud-models.per-page./list'),
                $resource->getFields()
            )
            ->toArray();

        $req['meta'] = [
            'fields' => $resource->getMappers(),
        ];

        return response()->json($req);
    }

    /**
     * Return a singular resource
     *
     * @param $alias
     *
     * @return \Illuminate\Http\JsonResponse|mixed|\Symfony\Component\HttpFoundation\ParameterBag
     */
    public function resource($alias, $id)
    {
        $resource = $this->getResource($alias);
        $builder  = $resource->builder();

        $request = $builder->where('id', $id)->first($resource->getFields());

        if (!$request->first()) {
            return request()->json([
                'message' => request()->has('page') ? 'No more ' . $resource->getTitle() . ' to return.' : 'No ' . $resource->getTitle() . ' to return.',
            ], 404);
        }

        return response()->json($request);
    }

    /**
     * Create a new entry for our model
     *
     * @param $alias
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create($alias)
    {
        $resource = $this->getResource($alias);
        $builder  = $resource->builder();

        //Get required columns for model
        $columns = $resource->getColumnsForModel();

        $values = [];

        foreach ($columns as $key => $column) {

            //We shouldn't be able to set these parameters
            if ($column->name === 'id') {
                continue;
            }
            if ($column->name === 'created_at') {
                continue;
            }
            if ($column->name === 'updated_at') {
                continue;
            }

            //Check if we have this column in the request
            if (!request()->has($column->name)) {
                //If we dont, lets check if its a nullable type
                if ($column->nullable === true) {
                    $values[$key] = null;
                    continue;
                }

                //There is nothing else we can do but skip this item
                continue;
            }

            //Looks like we passed the only check for now
            $values[$key] = request($key);

            //If we're doing confirmation validation, we need that too
            if (request()->has($key . '_confirmation')) {
                $values[$key . '_confirmation'] = request($key . '_confirmation');
            }
        }

        //Check if we have onCreateValidations to run and run them if we do
        if ($resource->hasValidation()) {
            if ($response = $resource->validate($values)) {
                if ($response !== true) {
                    return response()->json(['errors' => $response->errors()->toArray()], 500);
                }
            }

            //Eh... one last loop, lets map and strip out any validation extras
            foreach ($values as $key => $value) {
                //This should do it...
                if (isset($values[$key . '_confirmation'])) {
                    unset($values[$key . '_confirmation']);
                }
            }

        }

        //Finally lets actually create the table entry
        $model = $builder->create($values);

        if ($model) {
            return response()->json($model->only($resource->getFields()));
        }

        return response()->json(['message' => 'Failed to create.'], 500);
    }

    /**
     * Update our model
     *
     * @param $alias
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($alias, $id)
    {
        $resource = $this->getResource($alias);
        $builder  = $resource->builder();

        $model = $builder->where('id', $id)->first();

        if ($model == null) {
            return response()->json(['message' => 'This resource does not exist.'], 404);
        }

        //Get required columns for model
        $columns = $resource->getColumnsForModel();

        $values = [];

        foreach ($columns as $key => $column) {

            //We shouldn't be able to set these parameters
            if ($column->name === 'id') {
                continue;
            }

            //Check if we have this column in the request
            if (!request()->has($column->name)) {
                continue;
            }

            //Looks like we passed the only check for now
            $values[$key] = request($key);

            //If we're doing confirmation validation, we need that too
            if (request()->has($key . '_confirmation')) {
                $values[$key . '_confirmation'] = request($key . '_confirmation');
            }
        }

        //Check if we have onCreateValidations to run and run them if we do
        if ($resource->hasValidation('update')) {
            if ($response = $resource->validate($values, 'update')) {
                if ($response !== true) {
                    return response()->json(['errors' => $response->errors()->toArray()], 500);
                }
            }

            //Eh... one last loop, lets map and strip out any validation extras
            foreach ($values as $key => $value) {
                //This should do it...
                if (isset($values[$key . '_confirmation'])) {
                    unset($values[$key . '_confirmation']);
                }
            }

        }

        //Finally lets actually create the table entry
        $builder->update($values);

        $model = $builder->where('id', $id)->first($resource->getFields());

        if ($model) {
            return response()->json($model);
        }

        return response()->json(['message' => 'Failed to update.'], 500);
    }

    /**
     * Handle deleting of model dataww
     *
     * @param $alias
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($alias, $id)
    {
        $alias = $this->getResource($alias);

        $alias->builder()
            ->where('id', $id)
            ->delete();

        return response()->json(['message' => 'Successfully deleted.'], 200);


    }
}
