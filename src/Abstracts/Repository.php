<?php

namespace SnakeInk\Crude\Abstracts;

use Illuminate\Support\Str;
use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 */
abstract class Repository
{
    /**
     * The name of the repository's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model;

    /**
     * The default namespace where repositories reside.
     *
     * @var string
     */
    public static $namespace = 'App\\Repositories\\';

    /**
     * Get a new repository instance.
     *
     * @return static
     */
    public static function new()
    {
        return (new static);
    }

    /**
     * Get the application namespace for the application.
     *
     * @return string
     */
    protected static function appNamespace()
    {
        try {
            return Container::getInstance()
                ->make(Application::class)
                ->getNamespace();
        } catch (\Throwable) {
            return 'App\\';
        }
    }

    /**
     * Get the repository name for the given model name.
     *
     * @template TClass of \Illuminate\Database\Eloquent\Model
     *
     * @param  class-string<TClass>  $modelName
     * @return class-string<\SnakeInk\Crude\Abstracts\Repository<TClass>>
     */
    public static function resolveRepositoryName(string $modelName)
    {
        $resolver = function (string $modelName) {
            $appNamespace = static::appNamespace();

            $modelName = Str::startsWith($modelName, $appNamespace.'Models\\')
                ? Str::after($modelName, $appNamespace.'Models\\')
                : Str::after($modelName, $appNamespace);

            return static::$namespace.$modelName.'Repository';
        };

        return $resolver($modelName);
    }

    /**
     * Get a new repository instance for the given model name.
     *
     * @template TClass of \Illuminate\Database\Eloquent\Model
     *
     * @param  class-string<TClass>  $modelName
     * @return \SnakeInk\Crude\Abstracts\Repository<TClass>
     */
    public static function repositoryForModel(string $modelName)
    {
        $repository = static::resolveRepositoryName($modelName);

        return $repository::new();
    }
}
