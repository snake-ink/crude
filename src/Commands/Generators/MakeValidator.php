<?php

namespace SnakeInk\Crude\Commands\Generators;

use SnakeInk\Crude\Abstracts\GeneratorCommand;

class MakeValidator extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sss:make-validator
                            {name : Name of the model}
                            {--f|force : Whether to overwrite classes if they already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Validator class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Validator';

    public function getStub()
    {
        return dirname(__DIR__, 2).'/stubs/validator.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Validators';
    }
}
