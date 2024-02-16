<?php

namespace SnakeInk\Crude\Commands\Generators;

use SnakeInk\Crude\Abstracts\GeneratorCommand;

class MakeService extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sss:make-service
                            {name : Name of the entity}
                            {--a|all : Whether to create all other associated classes}
                            {--f|force : Whether to overwrite classes that already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    public function handle()
    {
        parent::handle();

        if ($this->option('all')) {
            try {
                $this->call('sss:make-model', $this->buildGeneratorCommandArguments());
                $this->call('sss:make-repository', $this->buildGeneratorCommandArguments());
                $this->call('sss:make-validator', $this->buildGeneratorCommandArguments());
                $this->call('sss:make-policy', $this->buildGeneratorCommandArguments());
            } catch (\Throwable $exception) {
                $this->error('Something went wrong while calling a command.');
                $this->error('Have you installed and configured all neccessary libraries to enable the "--all" option?');
                $this->error('Please check the documentation for more details.');
                $this->error('Exception message: '.$exception->getMessage());
            }
        }
    }

    public function getStub()
    {
        return dirname(__DIR__, 3).'/stubs/service.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Services';
    }
}
