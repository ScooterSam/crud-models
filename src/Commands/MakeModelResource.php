<?php

namespace ScooterSam\CrudModels\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeModelResource extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:model-resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model resource';


    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\ModelResources';
    }


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../Stubs/resource.stub';
    }
}
