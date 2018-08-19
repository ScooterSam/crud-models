<?php

namespace ScooterSam\CrudModels\Tests;

use ScooterSam\CrudModels\Facades\CrudModels;
use ScooterSam\CrudModels\ServiceProvider;
use Orchestra\Testbench\TestCase;

class CrudModelsTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'crud-models' => CrudModels::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
