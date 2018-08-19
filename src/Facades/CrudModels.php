<?php

namespace ScooterSam\CrudModels\Facades;

use Illuminate\Support\Facades\Facade;

class CrudModels extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'crud-models';
    }
}
