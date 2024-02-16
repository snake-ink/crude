<?php

namespace SnakeInk\Crude\Commands\Generators;

use SnakeInk\Crude\Abstracts\GeneratorCommand;

class MakeController extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sss:make-controller
                            {name : Name of the entity}
                            {--f|force : Whether to overwrite classes if they already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    public function getStub()
    {
        return dirname(__DIR__, 2).'/stubs/controller.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }
}
