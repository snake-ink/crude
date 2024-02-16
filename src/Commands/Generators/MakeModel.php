<?php

namespace SnakeInk\Crude\Commands\Generators;

use SnakeInk\Crude\Abstracts\GeneratorCommand;
use SnakeInk\Crude\Commands\Concerns\HasExtraGeneratorFunctionality;

class MakeModel extends GeneratorCommand
{
    use HasExtraGeneratorFunctionality;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sss:make-model
                            {name : Name of the entity}
                            {--a|all : Whether to create all other associated classes}
                            {--f|force :  Whether to overwrite classes that already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    public function handle()
    {
        parent::handle();

        if ($this->option('all')) {
            try {
                $this->call('sss:make-validator', $this->buildGeneratorCommandArguments());
                $this->call('sss:make-controller', $this->buildGeneratorCommandArguments());
                $this->call('sss:make-factory', $this->buildGeneratorCommandArguments());
                $this->call('sss:make-route-test', $this->buildGeneratorCommandArguments());
            } catch (\Throwable $exception) {
                $this->error('Something went wrong while calling a command.');
                $this->error('Have you installed all neccessary libraries to enable the "--all" option?');
                $this->error('Please check the documentation for more details.');
                $this->error('Exception message: '.$exception->getMessage());
            }
        }
    }

    public function getStub()
    {
        return dirname(__DIR__, 3).'/stubs/model.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Models';
    }
}
