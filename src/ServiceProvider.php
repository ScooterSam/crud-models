<?php

namespace ScooterSam\CrudModels;

use ScooterSam\CrudModels\Commands\MakeModelResource;
use ScooterSam\CrudModels\ModelResource\ModelResource;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/crud-models.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('crud-models.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        //Load Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModelResource::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'crud-models'
        );

        $this->app->bind('crud-models', function () {
            return new CrudModels();
        });
    }

}
