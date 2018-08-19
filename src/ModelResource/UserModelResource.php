<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 19/08/18
 * Time: 05:17
 */

namespace ScooterSam\CrudModels\ModelResource;


use App\User;
use Illuminate\Validation\Rule;

class UserModelResource extends ModelResource
{
    protected $model = User::class;

    protected $title = 'User';

    protected $fields = ['id', 'name', 'email'];

    protected $searchableFields = ['name', 'email'];

    protected $fillableFields = ['name', 'email'];

    protected $middleware = [];

    protected $mappers = [
        'id'    => '#',
        'name'  => 'Name',
        'email' => 'Email',
    ];

    /**
     * Validations array that will be used when creating a resource
     *
     * @return array
     */
    public function onCreateValidations(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    /**
     * Validations that will be used when updating a resource
     *
     * @return array
     */
    public function onUpdateValidations(): array
    {
        return [
            'name'  => 'string|max:255',
            'email' => ['string', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->id())],
        ];
    }
}