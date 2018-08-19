<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 19/08/18
 * Time: 05:09
 */

namespace ScooterSam\CrudModels\ModelResource;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function is_array;

abstract class ModelResource
{
    /**
     * This is basically what you wish to call every ModelResource publicly.
     * For example, Users.php model represents a users, and we typically call this "Users" that is our title.
     *
     * @var string
     */
    protected $title = "ModelResource";

    /**
     * The model we will use for all database communication
     *
     * @var Model
     */
    protected $model;

    /**
     * Which fields do we want to show on the frontend?
     *
     * @var $fields
     */
    protected $fields = ['id'];

    /**
     * Which fields are able to be filled, when creating or updating a model
     *
     * If you do not wish to use this feature, set the value to null
     *
     * Pass an array of fields which can be filled.
     *
     * @var $fields
     */
    protected $fillableFields = null;

    /**
     * How we want to title the fields, when we do display them on the frontend
     *
     * @var $mappers
     */
    protected $mappers = ['id' => 'ID'];

    /**
     * Fields that are able to be searched with mysql
     *
     * @var $searchableFields
     *
     * @return null|array
     */
    protected $searchableFields = null;

    /**
     * Assign middleware to this crud model endpoint
     *
     * @var $searchableFields
     *
     * @return null|array
     */
    protected $middleware = [];

    /**
     * Validations you'd like to run when creating a new table entry on this model
     *
     * @var $onCreateValidations
     *
     * @return null|array
     */
    protected $onCreateValidations = null;

    /**
     * Validations you'd like to run when updating this model
     *
     * @var $onCreateValidations
     *
     * @return null|array
     */
    protected $onUpdateValidations = null;

    /**
     * Get an instance of the query builder, allows you to query the resource
     *
     * @return Builder
     */
    public function builder(): Builder
    {
        return $this->model::query();
    }

    /**
     * "New up" a model to access some of its default properties/methods
     *
     * @return Model
     */
    public function modelInstance(): Model
    {
        return (new $this->model());
    }

    /**
     * Get all columns for the model(table)
     *
     * @return mixed
     */
    public function getColumnsForModel()
    {
        $tableName = $this->modelInstance()->getTable();

        $columns = DB::connection()->getDoctrineSchemaManager()
            ->listTableColumns($tableName);

        $columnsModified = collect($columns)->map(function ($value) {
            $newVal           = $value;
            $newVal->nullable = ($value->getNotnull() === false);
            $newVal->name     = $value->getName();

            return $newVal;
        })->toArray();

        return $columnsModified;
    }

    /**
     * Check if we use validation on this model resource
     *
     * @param string $method | "create" or "update". Default: "create"
     *
     * @return bool
     */
    public function hasValidation($method = 'create'): bool
    {
        $validations = ($method === 'create' ? $this->{'onCreateValidations'}() : $this->{'onUpdateValidations'}());

        if ($validations === null) {
            return false;
        }

        if ($validations === []) {
            return false;
        }

        if (!is_array($validations)) {
            return false;
        }

        if (count($validations) === 0) {
            return false;
        }

        return true;
    }

    /**
     * Run the onCreateValidations, if we have any that is
     *
     * @param        $parameters
     *
     * @param string $method | "create" or "update". Default: "create"
     *
     * @return bool|\Illuminate\Contracts\Validation\Validator
     */
    public function validate($parameters, $method = 'create')
    {
        $validations = ($method === 'create' ? $this->{'onCreateValidations'}() : $this->{'onUpdateValidations'}());

        if ($validations === null) {
            return true;
        }
        if (is_array($validations) && count($validations) === 0) {
            return true;
        }

        $validator = Validator::make($parameters, $validations);

        if ($validator->fails()) {
            return $validator;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return mixed
     */
    public function getMappers()
    {
        return $this->mappers;
    }

    /**
     * @return mixed
     */
    public function getSearchableFields()
    {
        return $this->searchableFields;
    }

    /**
     * @return mixed
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Validations array that will be used when creating a resource
     *
     * @return array
     */
    public abstract function onCreateValidations(): array;

    /**
     * Validations that will be used when updating a resource
     *
     * @return array
     */
    public abstract function onUpdateValidations(): array;
}