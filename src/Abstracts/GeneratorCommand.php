<?php

namespace SnakeInk\Crude\Abstracts;

use Illuminate\Console\GeneratorCommand as LaravelGeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

abstract class GeneratorCommand extends LaravelGeneratorCommand
{
    protected $basePath;

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->basePath = app()['path'].'/';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name').$this->type);
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $dummyClassFirstUpper = str_replace($this->getNamespace($name).'\\', '', $name);

        $classFirstUpper = str_replace($this->type, '', $dummyClassFirstUpper);

        return str_replace(
            [
                'DummyClass',
                '{{Model}}',
                '{{ModelSpace}}',
                '{{ModelPlural}}',
                '{{ModelPluralSpace}}',
                '{{model}}',
                '{{snake_case_model}}',
                '{{kebab-case-model-plural}}',
                '{{space-case-model-plural}}',
                '{{space-case-model}}',
            ],
            [
                $dummyClassFirstUpper,
                $classFirstUpper,
                ucwords(str_replace('-', ' ', Str::kebab($classFirstUpper))),
                Str::pluralStudly($classFirstUpper),
                ucwords(str_replace('-', ' ', Str::kebab(Str::plural($classFirstUpper)))),
                Str::camel($classFirstUpper),
                Str::snake($classFirstUpper),
                Str::kebab(Str::plural($classFirstUpper)),
                str_replace('-', ' ', Str::kebab(Str::plural($classFirstUpper))),
                str_replace('-', ' ', Str::kebab($classFirstUpper)),
            ],
            $stub
        );
    }

    public function getStub()
    {
        return base_path('stubs/generic.stub');
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->basePath.str_replace('\\', '/', $name).'.php';
    }
}
