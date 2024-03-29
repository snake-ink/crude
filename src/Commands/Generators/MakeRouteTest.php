<?php

namespace SnakeInk\Crude\Commands\Generators;

use Illuminate\Filesystem\Filesystem;
use SnakeInk\Crude\Abstracts\GeneratorCommand;

class MakeRouteTest extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sss:make-route-test
                            {name : Name of the entity}
                            {--f|force : Whether to overwrite classes if they already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Test class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Test';

    protected $basePath;

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->basePath = app()->basePath().'/tests/';
    }

    public function getStub()
    {
        return dirname(__DIR__, 3).'/stubs/route-test.stub';
    }

    protected function getRootNamespace()
    {
        return 'Tests';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Route';
    }
}
