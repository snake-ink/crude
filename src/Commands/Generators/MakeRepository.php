<?php

namespace SnakeInk\Crude\Commands\Generators;

use SnakeInk\Crude\Abstracts\GeneratorCommand;

class MakeRepository extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sss:make-repository
                            {name : Name of the entity}
                            {--a|all : Whether to create all other associated classes}
                            {--f|force : Whether to overwrite classes that already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Repository class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    public function handle()
    {
        parent::handle();

        if ($this->option('all')) {
            try {
                $this->call('sss:make-model', $this->buildGeneratorCommandArguments());
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
        return dirname(__DIR__, 2).'/stubs/repository.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories';
    }
}
