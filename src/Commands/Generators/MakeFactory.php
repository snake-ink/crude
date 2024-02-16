<?php

namespace SnakeInk\Crude\Commands\Generators;

use Illuminate\Filesystem\Filesystem;
use SnakeInk\Crude\Abstracts\GeneratorCommand;

class MakeFactory extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sss:make-factory
                            {name : Name of the entity}
                            {--f|force : Whether to overwrite classes if they already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Factory class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Factory';

    protected $basePath;

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->basePath = app()->basePath().'/database/factories/';
    }

    public function getStub()
    {
        return dirname(__DIR__, 2).'/stubs/factory.stub';
    }

    protected function getRootNamespace()
    {
        return 'Database\Factories';
    }
}
