<?php

namespace SnakeInk\Crude\Commands\Generators;

use SnakeInk\Crude\Abstracts\GeneratorCommand;

class MakePolicy extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sss:make-policy
                            {name : Name of the entity}
                            {--f|force : Whether to overwrite classes if they already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Policy class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Policy';

    public function getStub()
    {
        return dirname(__DIR__, 3).'/stubs/policy.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Policies';
    }
}
